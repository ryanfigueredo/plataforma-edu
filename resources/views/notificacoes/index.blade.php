@extends('layouts.app')

@section('title', 'Notificações')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Notificações</h1>
            <button class="btn btn-sm btn-primary" onclick="marcarTodasComoLidas()">
                Marcar Todas como Lidas
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($notificacoes->count() > 0)
                    <ul class="list-group">
                        @foreach($notificacoes as $notificacao)
                        <li class="list-group-item {{ $notificacao->lida ? '' : 'bg-light' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        @if(!$notificacao->lida)
                                            <span class="badge bg-primary me-2">Nova</span>
                                        @endif
                                        {{ $notificacao->titulo }}
                                    </h6>
                                    <p class="mb-1">{{ $notificacao->mensagem }}</p>
                                    <small class="text-muted">{{ $notificacao->created_at->diffForHumans() }}</small>
                                </div>
                                @if(!$notificacao->lida)
                                    <button class="btn btn-sm btn-outline-primary" onclick="marcarComoLida({{ $notificacao->id }})">
                                        Marcar como Lida
                                    </button>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    
                    <div class="mt-3">
                        {{ $notificacoes->links() }}
                    </div>
                @else
                    <p class="text-muted">Nenhuma notificação encontrada.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function marcarComoLida(id) {
    fetch(`/notificacoes/${id}/ler`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function marcarTodasComoLidas() {
    fetch('/notificacoes/ler-todas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection
