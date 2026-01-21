<?php

namespace App\Http\Controllers;

use App\Services\LgpdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LgpdController extends Controller
{
    protected LgpdService $lgpdService;

    public function __construct(LgpdService $lgpdService)
    {
        $this->lgpdService = $lgpdService;
    }

    /**
     * Exibe termos de consentimento
     */
    public function consentimento()
    {
        $user = Auth::user();
        
        if ($user->lgpd_consentimento) {
            return redirect()->route('dashboard');
        }
        
        return view('lgpd.consentimento');
    }

    /**
     * Processa consentimento
     */
    public function processarConsentimento(Request $request)
    {
        $request->validate([
            'consentimento' => 'required|accepted',
        ]);
        
        $this->lgpdService->registrarConsentimento(
            Auth::id(),
            true
        );
        
        return redirect()->route('dashboard')
            ->with('success', 'Consentimento registrado com sucesso.');
    }

    /**
     * Exporta dados do usuário
     */
    public function exportarDados(Request $request)
    {
        $dados = $this->lgpdService->exportarDadosUsuario(Auth::id());
        
        return response()->json($dados, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="meus-dados-' . date('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Solicita exclusão de dados
     */
    public function solicitarExclusao(Request $request)
    {
        $request->validate([
            'confirmacao' => 'required|accepted',
        ]);
        
        $sucesso = $this->lgpdService->processarExclusaoDados(Auth::id());
        
        if ($sucesso) {
            Auth::logout();
            return redirect()->route('login')
                ->with('success', 'Seus dados foram anonimizados conforme solicitado.');
        }
        
        return back()->with('error', 'Erro ao processar exclusão de dados.');
    }

    /**
     * Visualiza log de auditoria do usuário
     */
    public function auditoria(Request $request)
    {
        $auditorias = \App\Models\LgpdAuditoria::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('lgpd.auditoria', compact('auditorias'));
    }
}
