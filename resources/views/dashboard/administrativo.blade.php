@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4" role="heading" aria-level="1">Dashboard Administrativo</h1>
    </div>
</div>

<div class="row">
    <!-- Cards de Métricas -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-people"></i> Total de Alunos
                </h5>
                <h2 class="mb-0">{{ number_format($totalAlunos) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i> Alunos em Risco
                </h5>
                <h2 class="mb-0 text-danger">{{ number_format($alunosEmRisco) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-graph-up"></i> Taxa de Retenção
                </h5>
                <h2 class="mb-0">{{ number_format($taxaRetencao, 1) }}%</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-bell"></i> Alertas Pendentes
                </h5>
                <h2 class="mb-0 text-warning">{{ number_format($alertasPendentes) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Últimos Alertas -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Últimos Alertas de Evasão</h5>
            </div>
            <div class="card-body">
                @if($ultimosAlertas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" role="table" aria-label="Tabela de alertas de evasão">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Score</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosAlertas as $alerta)
                                <tr>
                                    <td>{{ $alerta->aluno->user->name }}</td>
                                    <td>
                                        <span class="badge {{ $alerta->score_evasao >= 0.7 ? 'badge-evasao' : 'badge-baixo-risco' }}">
                                            {{ number_format($alerta->score_evasao * 100, 1) }}%
                                        </span>
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
                @else
                    <p class="text-muted">Nenhum alerta encontrado.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Estatísticas Rápidas -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estatísticas</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <strong>Total de Turmas:</strong> {{ $totalTurmas }}
                    </li>
                    <li class="mb-3">
                        <strong>Total de Usuários:</strong> {{ $totalUsuarios }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
