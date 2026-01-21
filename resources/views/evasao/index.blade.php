@extends('layouts.app')

@section('title', 'Alertas de Evasão')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">Alertas de Evasão</h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Alertas</h5>
                <div>
                    <a href="{{ route('api.evasao.analisar-todos') }}" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('analisar-todos-form').submit();">
                        <i class="bi bi-arrow-repeat"></i> Analisar Todos
                    </a>
                    <form id="analisar-todos-form" action="{{ route('api.evasao.analisar-todos') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($alertas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Matrícula</th>
                                    <th>Score</th>
                                    <th>Motivos</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alertas as $alerta)
                                <tr>
                                    <td>{{ $alerta->aluno->user->name }}</td>
                                    <td>{{ $alerta->aluno->matricula }}</td>
                                    <td>
                                        <span class="badge {{ $alerta->score_evasao >= 0.7 ? 'badge-evasao' : 'badge-baixo-risco' }}">
                                            {{ number_format($alerta->score_evasao * 100, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        @if($alerta->motivos)
                                            <ul class="mb-0">
                                                @foreach($alerta->motivos as $motivo)
                                                    <li>{{ $motivo }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>{{ $alerta->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $alerta->status === 'pendente' ? 'warning' : 'success' }}">
                                            {{ ucfirst($alerta->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('evasao.show', $alerta->id) }}" class="btn btn-sm btn-primary">
                                            Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $alertas->links() }}
                    </div>
                @else
                    <p class="text-muted">Nenhum alerta encontrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
