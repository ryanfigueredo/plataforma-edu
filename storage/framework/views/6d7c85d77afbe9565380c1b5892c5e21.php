<?php $__env->startSection('title', 'Detalhes do Alerta de Evasão'); ?>

<?php $__env->startSection('content'); ?>
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
                    <dd class="col-sm-9"><?php echo e($alerta->aluno->user->name); ?></dd>
                    
                    <dt class="col-sm-3">Matrícula:</dt>
                    <dd class="col-sm-9"><?php echo e($alerta->aluno->matricula); ?></dd>
                    
                    <dt class="col-sm-3">Turma:</dt>
                    <dd class="col-sm-9"><?php echo e($alerta->aluno->turma->nome ?? 'N/A'); ?></dd>
                    
                    <dt class="col-sm-3">Curso:</dt>
                    <dd class="col-sm-9"><?php echo e($alerta->aluno->curso->nome ?? 'N/A'); ?></dd>
                    
                    <dt class="col-sm-3">Score de Evasão:</dt>
                    <dd class="col-sm-9">
                        <span class="badge <?php echo e($alerta->score_evasao >= 0.7 ? 'badge-evasao' : 'badge-baixo-risco'); ?>" style="font-size: 1.2rem;">
                            <?php echo e(number_format($alerta->score_evasao * 100, 1)); ?>%
                        </span>
                    </dd>
                    
                    <dt class="col-sm-3">Frequência Média (30 dias):</dt>
                    <dd class="col-sm-9"><?php echo e(number_format($frequenciaMedia, 1)); ?>%</dd>
                    
                    <dt class="col-sm-3">Desempenho Médio:</dt>
                    <dd class="col-sm-9"><?php echo e(number_format($desempenhoMedio, 1)); ?></dd>
                </dl>
                
                <?php if($alerta->motivos): ?>
                    <h6>Motivos do Alerta:</h6>
                    <ul>
                        <?php $__currentLoopData = $alerta->motivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($motivo); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if($alerta->acoes_tomadas): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Ações Tomadas</h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $alerta->acoes_tomadas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3 p-3 bg-light rounded">
                            <strong><?php echo e($acao['acao']); ?></strong>
                            <?php if(isset($acao['observacoes'])): ?>
                                <p class="mb-0 mt-2"><?php echo e($acao['observacoes']); ?></p>
                            <?php endif; ?>
                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($acao['registrado_em'])->format('d/m/Y H:i')); ?></small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Registrar Ação</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('evasao.acao', $alerta->id)); ?>">
                    <?php echo csrf_field(); ?>
                    
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ryanfigueredo/Dev/plataforma-educacional/resources/views/evasao/show.blade.php ENDPATH**/ ?>