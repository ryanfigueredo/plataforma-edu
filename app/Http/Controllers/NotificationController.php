<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Lista notificações do usuário
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $notificacoes = $user->notificacoes()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('notificacoes.index', compact('notificacoes'));
    }

    /**
     * Marca notificação como lida
     */
    public function marcarComoLida(int $id, Request $request)
    {
        $this->notificationService->marcarComoLida($id, Auth::id());
        
        return response()->json(['success' => true]);
    }

    /**
     * Marca todas como lidas
     */
    public function marcarTodasComoLidas(Request $request)
    {
        $count = $this->notificationService->marcarTodasComoLidas(Auth::id());
        
        return response()->json([
            'success' => true,
            'marcadas' => $count,
        ]);
    }

    /**
     * API para notificações não lidas (AJAX)
     */
    public function naoLidas(Request $request)
    {
        $notificacoes = $this->notificationService->obterNaoLidas(
            Auth::id(),
            $request->get('limit', 10)
        );
        
        return response()->json($notificacoes);
    }
}
