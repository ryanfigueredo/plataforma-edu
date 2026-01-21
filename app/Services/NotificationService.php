<?php

namespace App\Services;

use App\Models\Notificacao;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Envia notificação para um usuário
     */
    public function enviar(
        int $userId,
        string $titulo,
        string $mensagem,
        string $tipo = 'info',
        ?string $notificavelType = null,
        ?int $notificavelId = null
    ): Notificacao {
        return Notificacao::create([
            'user_id' => $userId,
            'titulo' => $titulo,
            'mensagem' => $mensagem,
            'tipo' => $tipo,
            'lida' => false,
            'notificavel_type' => $notificavelType,
            'notificavel_id' => $notificavelId,
        ]);
    }

    /**
     * Envia notificação para múltiplos usuários
     */
    public function enviarMultiplos(
        array $userIds,
        string $titulo,
        string $mensagem,
        string $tipo = 'info'
    ): int {
        $count = 0;
        
        foreach ($userIds as $userId) {
            $this->enviar($userId, $titulo, $mensagem, $tipo);
            $count++;
        }
        
        return $count;
    }

    /**
     * Envia notificação para todos os usuários de um perfil
     */
    public function enviarParaPerfil(
        string $role,
        string $titulo,
        string $mensagem,
        string $tipo = 'info'
    ): int {
        $users = User::role($role)
            ->where('ativo', true)
            ->pluck('id')
            ->toArray();
        
        return $this->enviarMultiplos($users, $titulo, $mensagem, $tipo);
    }

    /**
     * Envia notificação para todos os alunos de uma turma
     */
    public function enviarParaTurma(
        int $turmaId,
        string $titulo,
        string $mensagem,
        string $tipo = 'info'
    ): int {
        $alunos = \App\Models\Aluno::where('turma_id', $turmaId)
            ->where('status', 'ativo')
            ->with('user')
            ->get();
        
        $count = 0;
        foreach ($alunos as $aluno) {
            $this->enviar($aluno->user_id, $titulo, $mensagem, $tipo);
            $count++;
        }
        
        return $count;
    }

    /**
     * Marca notificação como lida
     */
    public function marcarComoLida(int $notificacaoId, int $userId): bool
    {
        $notificacao = Notificacao::where('id', $notificacaoId)
            ->where('user_id', $userId)
            ->first();
        
        if ($notificacao) {
            $notificacao->marcarComoLida();
            return true;
        }
        
        return false;
    }

    /**
     * Marca todas as notificações do usuário como lidas
     */
    public function marcarTodasComoLidas(int $userId): int
    {
        return Notificacao::where('user_id', $userId)
            ->where('lida', false)
            ->update([
                'lida' => true,
                'data_leitura' => now(),
            ]);
    }

    /**
     * Obtém notificações não lidas do usuário
     */
    public function obterNaoLidas(int $userId, int $limit = 10)
    {
        return Notificacao::where('user_id', $userId)
            ->where('lida', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
