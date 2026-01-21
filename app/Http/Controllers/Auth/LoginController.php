<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Exibe formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Verificar se usuário está ativo
            if (!$user->ativo) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Sua conta está inativa. Entre em contato com o administrador.',
                ]);
            }
            
            // Verificar consentimento LGPD
            if (!$user->lgpd_consentimento) {
                return redirect()->route('lgpd.consentimento');
            }
            
            // Registrar auditoria (com tratamento de erro)
            try {
                \App\Models\LgpdAuditoria::registrar(
                    $user->id,
                    'login',
                    'acesso_sistema',
                    null,
                    ['ip' => $request->ip()]
                );
            } catch (\Exception $e) {
                // Log do erro mas não impede o login
                \Log::warning('Erro ao registrar auditoria LGPD: ' . $e->getMessage());
            }
            
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['As credenciais fornecidas estão incorretas.'],
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Models\LgpdAuditoria::registrar(
                Auth::id(),
                'logout',
                'acesso_sistema',
                null,
                ['ip' => $request->ip()]
            );
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
