<?php $__env->startSection('title', 'Auditoria LGPD'); ?>

<?php $__env->startSection('content'); ?>
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
                <?php if($auditorias->count() > 0): ?>
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
                                <?php $__currentLoopData = $auditorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($auditoria->created_at->format('d/m/Y H:i:s')); ?></td>
                                    <td><?php echo e($auditoria->acao); ?></td>
                                    <td><?php echo e($auditoria->tipo_dado); ?></td>
                                    <td><?php echo e($auditoria->ip_address); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <?php echo e($auditorias->links()); ?>

                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhum registro de auditoria encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ryanfigueredo/Dev/plataforma-educacional/resources/views/lgpd/auditoria.blade.php ENDPATH**/ ?>