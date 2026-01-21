@extends('layouts.app')

@section('title', 'Detalhes do Alerta de Evasão')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">Detalhes do Alerta de Evasão</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações do Aluno</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nome:</dt>
                    <dd class="col-sm-9">{{ $alerta->aluno->user->name }}</dd>
                    
                    <dt class="col-sm-3">Matrícula:</dt>
                    <dd class="col-sm-9">{{ $alerta->aluno->matricula }}</dd>
                    
                    <dt class="col-sm-3">Turma:</dt>
                    <dd class="col-sm-9">{{ $alerta->aluno->turma->nome ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-3">Curso:</dt>
                    <dd class="col-sm-9">{{ $alerta->aluno->curso->nome ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-3">Score de Evasão:</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $alerta->score_evasao >= 0.7 ? 'badge-evasao' : 'badge-baixo-risco' }}" style="font-size: 1.2rem;">
                            {{ number_format($alerta->score_evasao * 100, 1) }}%
                        </span>
                    </dd>
                    
                    <dt class="col-sm-3">Frequência Média (30 dias):</dt>
                    <dd class="col-sm-9">{{ number_format($frequenciaMedia, 1) }}%</dd>
                    
                    <dt class="col-sm-3">Desempenho Médio:</dt>
                    <dd class="col-sm-9">{{ number_format($desempenhoMedio, 1) }}</dd>
                </dl>
                
                @if($alerta->motivos)
                    <h6>Motivos do Alerta:</h6>
                    <ul>
                        @foreach($alerta->motivos as $motivo)
                            <li>{{ $motivo }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        
        @if($alerta->acoes_tomadas)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Ações Tomadas</h5>
                </div>
                <div class="card-body">
                    @foreach($alerta->acoes_tomadas as $acao)
                        <div class="mb-3 p-3 bg-light rounded">
                            <strong>{{ $acao['acao'] }}</strong>
                            @if(isset($acao['observacoes']))
                                <p class="mb-0 mt-2">{{ $acao['observacoes'] }}</p>
                            @endif
                            <small class="text-muted">{{ \Carbon\Carbon::parse($acao['registrado_em'])->format('d/m/Y H:i') }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Registrar Ação</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('evasao.acao', $alerta->id) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="acao" class="form-label">Ação Tomada</label>
                        <input type="text" class="form-control" id="acao" name="acao" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Registrar Ação</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
