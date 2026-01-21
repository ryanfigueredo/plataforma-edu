@extends('layouts.app')

@section('title', 'Auditoria LGPD')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">Auditoria LGPD</h1>
        <p class="text-muted">Registro de todas as ações realizadas com seus dados pessoais.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($auditorias->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Ação</th>
                                    <th>Tipo de Dado</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditorias as $auditoria)
                                <tr>
                                    <td>{{ $auditoria->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $auditoria->acao }}</td>
                                    <td>{{ $auditoria->tipo_dado }}</td>
                                    <td>{{ $auditoria->ip_address }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $auditorias->links() }}
                    </div>
                @else
                    <p class="text-muted">Nenhum registro de auditoria encontrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
