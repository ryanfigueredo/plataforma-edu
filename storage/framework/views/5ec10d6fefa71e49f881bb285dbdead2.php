<?php $__env->startSection('title', 'Dashboard Supervisor'); ?>

<?php $__env->startSection('content'); ?>
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
                <h2 class="mb-0"><?php echo e(number_format($totalAlunos)); ?></h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i> Alunos em Risco
                </h5>
                <h2 class="mb-0 text-danger"><?php echo e(number_format($alunosEmRisco)); ?></h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-bell"></i> Alertas Pendentes
                </h5>
                <h2 class="mb-0 text-warning"><?php echo e(number_format($alertasPendentes)); ?></h2>
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
                <?php if($turmas->count() > 0): ?>
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
                                <?php $__currentLoopData = $turmas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $turma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($turma->nome); ?></td>
                                    <td><?php echo e($turma->curso->nome); ?></td>
                                    <td><?php echo e($turma->periodo); ?></td>
                                    <td><?php echo e($turma->alunos_ativos); ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhuma turma atribuída.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ryanfigueredo/Dev/plataforma-educacional/resources/views/dashboard/supervisor.blade.php ENDPATH**/ ?>