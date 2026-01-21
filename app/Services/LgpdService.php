<?php

namespace App\Services;

use App\Models\LgpdAuditoria;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class LgpdService
{
    /**
     * Criptografa dados sensíveis
     */
    public function criptografar(string $dado): string
    {
        try {
            return Crypt::encryptString($dado);
        } catch (\Exception $e) {
            Log::error('Erro ao criptografar dado', [
                'erro' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Descriptografa dados sensíveis
     */
    public function descriptografar(string $dadoCriptografado): string
    {
        try {
            return Crypt::decryptString($dadoCriptografado);
        } catch (\Exception $e) {
            Log::error('Erro ao descriptografar dado', [
                'erro' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Registra ação de auditoria LGPD
     */
    public function registrarAuditoria(
        int $userId,
        string $acao,
        string $tipoDado,
        ?array $dadosAnteriores = null,
        ?array $dadosNovos = null
    ): void {
        LgpdAuditoria::registrar(
            $userId,
            $acao,
            $tipoDado,
            $dadosAnteriores,
            $dadosNovos
        );
    }

    /**
     * Processa solicitação de exclusão de dados (direito ao esquecimento)
     */
    public function processarExclusaoDados(int $userId): bool
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            
            // Registrar auditoria antes de excluir
            $this->registrarAuditoria(
                $userId,
                'exclusao_dados',
                'dados_pessoais',
                $user->toArray(),
                null
            );
            
            // Anonimizar dados ao invés de excluir completamente
            $user->update([
                'name' => 'Usuário Excluído',
                'email' => 'excluido_' . $user->id . '@excluido.local',
                'cpf' => null,
                'telefone' => null,
                'data_nascimento' => null,
                'ativo' => false,
            ]);
            
            // Excluir dados relacionados se necessário
            if ($user->aluno) {
                $user->aluno->update([
                    'status' => 'excluido',
                ]);
            }
            
            Log::info('Dados do usuário anonimizados conforme LGPD', [
                'user_id' => $userId,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao processar exclusão de dados', [
                'user_id' => $userId,
                'erro' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Exporta dados do usuário (portabilidade)
     */
    public function exportarDadosUsuario(int $userId): array
    {
        $user = \App\Models\User::findOrFail($userId);
        
        $dados = [
            'usuario' => [
                'nome' => $user->name,
                'email' => $user->email,
                'data_nascimento' => $user->data_nascimento?->format('Y-m-d'),
                'telefone' => $user->telefone,
            ],
        ];
        
        if ($user->aluno) {
            $dados['aluno'] = [
                'matricula' => $user->aluno->matricula,
                'data_ingresso' => $user->aluno->data_ingresso->format('Y-m-d'),
                'status' => $user->aluno->status,
            ];
            
            $dados['frequencias'] = $user->aluno->frequencias()
                ->select('data', 'presente', 'justificativa')
                ->get()
                ->toArray();
            
            $dados['desempenhos'] = $user->aluno->desempenhos()
                ->select('tipo_avaliacao', 'nota', 'data_avaliacao')
                ->get()
                ->toArray();
        }
        
        // Registrar auditoria
        $this->registrarAuditoria(
            $userId,
            'exportacao_dados',
            'dados_pessoais',
            null,
            ['exportado_em' => now()->toDateTimeString()]
        );
        
        return $dados;
    }

    /**
     * Valida consentimento LGPD
     */
    public function validarConsentimento(int $userId): bool
    {
        $user = \App\Models\User::findOrFail($userId);
        return $user->lgpd_consentimento === true;
    }

    /**
     * Registra consentimento LGPD
     */
    public function registrarConsentimento(int $userId, bool $consentimento): void
    {
        $user = \App\Models\User::findOrFail($userId);
        
        $user->update([
            'lgpd_consentimento' => $consentimento,
            'lgpd_consentimento_data' => now(),
        ]);
        
        $this->registrarAuditoria(
            $userId,
            'consentimento_lgpd',
            'consentimento',
            ['anterior' => $user->lgpd_consentimento],
            ['novo' => $consentimento]
        );
    }
}
