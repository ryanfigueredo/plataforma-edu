<?php $__env->startSection('title', 'Alertas de Evasão'); ?>

<?php $__env->startSection('content'); ?>
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
                    <a href="<?php echo e(route('api.evasao.analisar-todos')); ?>" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('analisar-todos-form').submit();">
                        <i class="bi bi-arrow-repeat"></i> Analisar Todos
                    </a>
                    <form id="analisar-todos-form" action="<?php echo e(route('api.evasao.analisar-todos')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <?php if($alertas->count() > 0): ?>
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
                                <?php $__currentLoopData = $alertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alerta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($alerta->aluno->user->name); ?></td>
                                    <td><?php echo e($alerta->aluno->matricula); ?></td>
                                    <td>
                                        <span class="badge <?php echo e($alerta->score_evasao >= 0.7 ? 'badge-evasao' : 'badge-baixo-risco'); ?>">
                                            <?php echo e(number_format($alerta->score_evasao * 100, 1)); ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($alerta->motivos): ?>
                                            <ul class="mb-0">
                                                <?php $__currentLoopData = $alerta->motivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($motivo); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($alerta->created_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($alerta->status === 'pendente' ? 'warning' : 'success'); ?>">
                                            <?php echo e(ucfirst($alerta->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('evasao.show', $alerta->id)); ?>" class="btn btn-sm btn-primary">
                                            Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <?php echo e($alertas->links()); ?>

                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhum alerta encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ryanfigueredo/Dev/plataforma-educacional/resources/views/evasao/index.blade.php ENDPATH**/ ?>