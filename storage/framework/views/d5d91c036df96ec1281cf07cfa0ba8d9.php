<?php $__env->startSection('title', 'Meu Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">Bem-vindo, <?php echo e(auth()->user()->name); ?>!</h1>
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
                <h2 class="mb-0"><?php echo e(number_format($frequenciaMedia, 1)); ?>%</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-trophy"></i> Desempenho Médio
                </h5>
                <h2 class="mb-0"><?php echo e(number_format($desempenhoMedio, 1)); ?></h2>
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
                    <span class="badge <?php echo e($evasaoScore >= 0.7 ? 'badge-evasao' : 'badge-baixo-risco'); ?>">
                        <?php echo e(number_format($evasaoScore * 100, 1)); ?>%
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
                <?php if($ultimasFrequencias->count() > 0): ?>
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
                                <?php $__currentLoopData = $ultimasFrequencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $freq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($freq->data->format('d/m/Y')); ?></td>
                                    <td><?php echo e($freq->disciplina->nome); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($freq->presente ? 'success' : 'danger'); ?>">
                                            <?php echo e($freq->presente ? 'Presente' : 'Falta'); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhuma frequência registrada.</p>
                <?php endif; ?>
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
                <?php if($ultimosDesempenhos->count() > 0): ?>
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
                                <?php $__currentLoopData = $ultimosDesempenhos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desempenho): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($desempenho->data_avaliacao->format('d/m/Y')); ?></td>
                                    <td><?php echo e($desempenho->disciplina->nome); ?></td>
                                    <td>
                                        <strong><?php echo e(number_format($desempenho->nota, 1)); ?></strong>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhum desempenho registrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Notificações Não Lidas -->
<?php if($notificacoesNaoLidas->count() > 0): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Notificações Recentes</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php $__currentLoopData = $notificacoesNaoLidas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notificacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item">
                        <strong><?php echo e($notificacao->titulo); ?></strong>
                        <p class="mb-0"><?php echo e($notificacao->mensagem); ?></p>
                        <small class="text-muted"><?php echo e($notificacao->created_at->diffForHumans()); ?></small>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ryanfigueredo/Dev/plataforma-educacional/resources/views/dashboard/aluno.blade.php ENDPATH**/ ?>