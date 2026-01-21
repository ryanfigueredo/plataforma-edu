@extends('layouts.app')

@section('title', 'Dashboard Supervisor')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">Dashboard Supervisor</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-people"></i> Total de Alunos
                </h5>
                <h2 class="mb-0">{{ number_format($totalAlunos) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i> Alunos em Risco
                </h5>
                <h2 class="mb-0 text-danger">{{ number_format($alunosEmRisco) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
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
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Minhas Turmas</h5>
            </div>
            <div class="card-body">
                @if($turmas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Turma</th>
                                    <th>Curso</th>
                                    <th>Período</th>
                                    <th>Alunos Ativos</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($turmas as $turma)
                                <tr>
                                    <td>{{ $turma->nome }}</td>
                                    <td>{{ $turma->curso->nome }}</td>
                                    <td>{{ $turma->periodo }}</td>
                                    <td>{{ $turma->alunos_ativos }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Nenhuma turma atribuída.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
