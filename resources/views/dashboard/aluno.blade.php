@extends('layouts.app')

@section('title', 'Meu Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">Bem-vindo, {{ auth()->user()->name }}!</h1>
    </div>
</div>

<div class="row">
    <!-- Métricas do Aluno -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-calendar-check"></i> Frequência Média
                </h5>
                <h2 class="mb-0">{{ number_format($frequenciaMedia, 1) }}%</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-trophy"></i> Desempenho Médio
                </h5>
                <h2 class="mb-0">{{ number_format($desempenhoMedio, 1) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-graph-up"></i> Score de Evasão
                </h5>
                <h2 class="mb-0">
                    <span class="badge {{ $evasaoScore >= 0.7 ? 'badge-evasao' : 'badge-baixo-risco' }}">
                        {{ number_format($evasaoScore * 100, 1) }}%
                    </span>
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Últimas Frequências -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Últimas Frequências</h5>
            </div>
            <div class="card-body">
                @if($ultimasFrequencias->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Disciplina</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasFrequencias as $freq)
                                <tr>
                                    <td>{{ $freq->data->format('d/m/Y') }}</td>
                                    <td>{{ $freq->disciplina->nome }}</td>
                                    <td>
                                        <span class="badge bg-{{ $freq->presente ? 'success' : 'danger' }}">
                                            {{ $freq->presente ? 'Presente' : 'Falta' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Nenhuma frequência registrada.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Últimos Desempenhos -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Últimos Desempenhos</h5>
            </div>
            <div class="card-body">
                @if($ultimosDesempenhos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Disciplina</th>
                                    <th>Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosDesempenhos as $desempenho)
                                <tr>
                                    <td>{{ $desempenho->data_avaliacao->format('d/m/Y') }}</td>
                                    <td>{{ $desempenho->disciplina->nome }}</td>
                                    <td>
                                        <strong>{{ number_format($desempenho->nota, 1) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Nenhum desempenho registrado.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Notificações Não Lidas -->
@if($notificacoesNaoLidas->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Notificações Recentes</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($notificacoesNaoLidas as $notificacao)
                    <li class="list-group-item">
                        <strong>{{ $notificacao->titulo }}</strong>
                        <p class="mb-0">{{ $notificacao->mensagem }}</p>
                        <small class="text-muted">{{ $notificacao->created_at->diffForHumans() }}</small>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
