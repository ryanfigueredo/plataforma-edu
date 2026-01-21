<?php $__env->startSection('title', 'Notificações'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Notificações</h1>
            <button class="btn btn-sm btn-primary" onclick="marcarTodasComoLidas()">
                Marcar Todas como Lidas
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if($notificacoes->count() > 0): ?>
                    <ul class="list-group">
                        <?php $__currentLoopData = $notificacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notificacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item <?php echo e($notificacao->lida ? '' : 'bg-light'); ?>">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <?php if(!$notificacao->lida): ?>
                                            <span class="badge bg-primary me-2">Nova</span>
                                        <?php endif; ?>
                                        <?php echo e($notificacao->titulo); ?>

                                    </h6>
                                    <p class="mb-1"><?php echo e($notificacao->mensagem); ?></p>
                                    <small class="text-muted"><?php echo e($notificacao->created_at->diffForHumans()); ?></small>
                                </div>
                                <?php if(!$notificacao->lida): ?>
                                    <button class="btn btn-sm btn-outline-primary" onclick="marcarComoLida(<?php echo e($notificacao->id); ?>)">
                                        Marcar como Lida
                                    </button>
                                <?php endif; ?>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    
                    <div class="mt-3">
                        <?php echo e($notificacoes->links()); ?>

                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhuma notificação encontrada.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function marcarComoLida(id) {
    fetch(`/notificacoes/${id}/ler`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function marcarTodasComoLidas() {
    fetch('/notificacoes/ler-todas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ryanfigueredo/Dev/plataforma-educacional/resources/views/notificacoes/index.blade.php ENDPATH**/ ?>