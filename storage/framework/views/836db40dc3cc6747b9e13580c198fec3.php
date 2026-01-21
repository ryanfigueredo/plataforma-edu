<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Plataforma Educacional'); ?></title>
    
    <!-- Acessibilidade WCAG 2.1 AA -->
    <meta name="description" content="Sistema de gestão acadêmica com IA para prevenção de evasão escolar">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8fafc;
        }
        
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand, .nav-link {
            color: white !important;
        }
        
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
        }
        
        .badge-evasao {
            background-color: var(--danger-color);
            color: white;
        }
        
        .badge-baixo-risco {
            background-color: var(--success-color);
            color: white;
        }
        
        /* Acessibilidade - Alto contraste */
        @media (prefers-contrast: high) {
            .card {
                border: 2px solid #000;
            }
        }
        
        /* Skip to main content para leitores de tela */
        .skip-link {
            position: absolute;
            left: -9999px;
            z-index: 999;
        }
        
        .skip-link:focus {
            left: 6px;
            top: 7px;
            background: #000;
            color: #fff;
            padding: 8px;
            text-decoration: none;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <a href="#main-content" class="skip-link">Pular para conteúdo principal</a>
    
    <nav class="navbar navbar-expand-lg navbar-dark" role="navigation" aria-label="Navegação principal">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>" aria-label="Plataforma Educacional - Página inicial">
                <i class="bi bi-mortarboard-fill"></i> Plataforma Educacional
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if(auth()->guard()->check()): ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('dashboard')); ?>" aria-current="page">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('analisar-evasao')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('evasao.index')); ?>">
                            <i class="bi bi-exclamation-triangle"></i> Alertas de Evasão
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('notificacoes.index')); ?>">
                            <i class="bi bi-bell"></i> Notificações
                            <?php if(auth()->user()->notificacoesNaoLidas()->count() > 0): ?>
                                <span class="badge bg-danger"><?php echo e(auth()->user()->notificacoesNaoLidas()->count()); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?php echo e(auth()->user()->name); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo e(route('lgpd.auditoria')); ?>">Auditoria LGPD</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('lgpd.exportar')); ?>">Exportar Meus Dados</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">Sair</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <main id="main-content" role="main">
        <div class="container-fluid mt-4">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
    
    <footer class="mt-5 py-4 bg-light text-center" role="contentinfo">
        <div class="container">
            <p class="text-muted mb-0">&copy; <?php echo e(date('Y')); ?> Plataforma Educacional. Todos os direitos reservados.</p>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CSRF Token para requisições AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/ryanfigueredo/Dev/plataforma-educacional/resources/views/layouts/app.blade.php ENDPATH**/ ?>