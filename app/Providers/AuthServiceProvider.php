<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\EvasaoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        Gate::define('analisar-evasao', [EvasaoPolicy::class, 'analisarEvasao']);
        Gate::define('gerenciar-alertas', [EvasaoPolicy::class, 'gerenciarAlertas']);
    }
}
