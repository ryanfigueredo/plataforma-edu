<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LgpdAuditoria;

class LgpdAudit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Registrar auditoria apenas para ações importantes
        if (auth()->check() && $this->deveAuditar($request)) {
            try {
                LgpdAuditoria::registrar(
                    auth()->id(),
                    $request->method() . ' ' . $request->path(),
                    'acesso_sistema',
                    null,
                    [
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]
                );
            } catch (\Exception $e) {
                // Log do erro mas não impede a requisição
                \Log::warning('Erro ao registrar auditoria LGPD: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Verifica se a requisição deve ser auditada
     */
    protected function deveAuditar(Request $request): bool
    {
        // Não auditar requisições GET simples
        if ($request->isMethod('GET') && !$request->is('api/*')) {
            return false;
        }

        // Auditar apenas rotas importantes
        $rotasImportantes = [
            'dashboard',
            'evasao',
            'usuarios',
            'lgpd',
        ];

        foreach ($rotasImportantes as $rota) {
            if ($request->is($rota . '*')) {
                return true;
            }
        }

        return false;
    }
}
