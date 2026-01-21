<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\AlertaEvasao;
use App\Jobs\AnalisarEvasaoJob;
use Illuminate\Support\Facades\Log;

class EvasaoAIService
{
    protected float $threshold;

    public function __construct()
    {
        $this->threshold = (float) config('ai.evasao_threshold', 0.7);
    }

    /**
     * Analisa o risco de evasão de um aluno
     * 
     * @param Aluno $aluno
     * @return float Score de risco (0-1)
     */
    public function analisarRiscoEvasao(Aluno $aluno): float
    {
        $frequenciaMedia = $aluno->calcularFrequenciaMedia(30);
        $desempenhoMedio = $aluno->calcularDesempenhoMedio();
        
        // Fatores de análise
        $fatorFrequencia = $this->calcularFatorFrequencia($frequenciaMedia);
        $fatorDesempenho = $this->calcularFatorDesempenho($desempenhoMedio);
        $fatorTempo = $this->calcularFatorTempo($aluno);
        $fatorHistorico = $this->calcularFatorHistorico($aluno);
        
        // Pesos para cada fator
        $pesoFrequencia = 0.35;
        $pesoDesempenho = 0.30;
        $pesoTempo = 0.20;
        $pesoHistorico = 0.15;
        
        // Cálculo do score final
        $score = (
            ($fatorFrequencia * $pesoFrequencia) +
            ($fatorDesempenho * $pesoDesempenho) +
            ($fatorTempo * $pesoTempo) +
            ($fatorHistorico * $pesoHistorico)
        );
        
        // Normalizar entre 0 e 1
        $score = max(0, min(1, $score));
        
        // Atualizar score no aluno
        $aluno->update([
            'evasao_score' => $score,
            'ultima_analise_evasao' => now(),
        ]);
        
        // Verificar se precisa gerar alerta
        if ($score >= $this->threshold) {
            $this->gerarAlerta($aluno, $score, [
                'frequencia' => $frequenciaMedia,
                'desempenho' => $desempenhoMedio,
                'fator_frequencia' => $fatorFrequencia,
                'fator_desempenho' => $fatorDesempenho,
                'fator_tempo' => $fatorTempo,
                'fator_historico' => $fatorHistorico,
            ]);
        }
        
        return $score;
    }

    /**
     * Calcula fator de risco baseado na frequência
     */
    protected function calcularFatorFrequencia(float $frequenciaMedia): float
    {
        if ($frequenciaMedia >= 90) {
            return 0.1; // Baixo risco
        } elseif ($frequenciaMedia >= 75) {
            return 0.3; // Risco moderado
        } elseif ($frequenciaMedia >= 60) {
            return 0.6; // Risco médio-alto
        } else {
            return 0.9; // Alto risco
        }
    }

    /**
     * Calcula fator de risco baseado no desempenho
     */
    protected function calcularFatorDesempenho(float $desempenhoMedio): float
    {
        if ($desempenhoMedio >= 8.0) {
            return 0.1; // Baixo risco
        } elseif ($desempenhoMedio >= 6.0) {
            return 0.4; // Risco moderado
        } elseif ($desempenhoMedio >= 4.0) {
            return 0.7; // Risco médio-alto
        } else {
            return 0.95; // Alto risco
        }
    }

    /**
     * Calcula fator de risco baseado no tempo desde ingresso
     */
    protected function calcularFatorTempo(Aluno $aluno): float
    {
        $diasDesdeIngresso = now()->diffInDays($aluno->data_ingresso);
        
        // Alunos muito novos (menos de 30 dias) têm risco maior
        if ($diasDesdeIngresso < 30) {
            return 0.5;
        }
        
        // Alunos com mais de 6 meses têm risco menor
        if ($diasDesdeIngresso > 180) {
            return 0.2;
        }
        
        return 0.4;
    }

    /**
     * Calcula fator de risco baseado no histórico de alertas
     */
    protected function calcularFatorHistorico(Aluno $aluno): float
    {
        $alertasRecentes = $aluno->alertasEvasao()
            ->where('created_at', '>=', now()->subDays(90))
            ->count();
        
        if ($alertasRecentes === 0) {
            return 0.2;
        } elseif ($alertasRecentes === 1) {
            return 0.5;
        } elseif ($alertasRecentes <= 3) {
            return 0.7;
        } else {
            return 0.9;
        }
    }

    /**
     * Gera alerta de evasão para a diretoria
     */
    protected function gerarAlerta(Aluno $aluno, float $score, array $detalhes): void
    {
        // Verificar se já existe alerta recente (últimas 24h)
        $alertaRecente = $aluno->alertasEvasao()
            ->where('created_at', '>=', now()->subDay())
            ->where('status', 'pendente')
            ->first();
        
        if ($alertaRecente) {
            return; // Não criar alerta duplicado
        }
        
        $motivos = [];
        
        if ($detalhes['frequencia'] < 75) {
            $motivos[] = 'Frequência abaixo do esperado (' . number_format($detalhes['frequencia'], 1) . '%)';
        }
        
        if ($detalhes['desempenho'] < 6.0) {
            $motivos[] = 'Desempenho acadêmico abaixo da média (' . number_format($detalhes['desempenho'], 1) . ')';
        }
        
        if ($detalhes['fator_historico'] > 0.6) {
            $motivos[] = 'Histórico de alertas anteriores';
        }
        
        $alerta = AlertaEvasao::create([
            'aluno_id' => $aluno->id,
            'score_evasao' => $score,
            'motivos' => $motivos,
            'status' => 'pendente',
        ]);
        
        // Notificar diretoria
        $this->notificarDiretoria($aluno, $alerta);
        
        Log::info('Alerta de evasão gerado', [
            'aluno_id' => $aluno->id,
            'score' => $score,
            'motivos' => $motivos,
        ]);
    }

    /**
     * Notifica a diretoria sobre novo alerta
     */
    protected function notificarDiretoria(Aluno $aluno, AlertaEvasao $alerta): void
    {
        $diretores = \App\Models\User::role('diretor')
            ->where('ativo', true)
            ->get();
        
        foreach ($diretores as $diretor) {
            \App\Models\Notificacao::create([
                'user_id' => $diretor->id,
                'titulo' => 'Alerta de Evasão - ' . $aluno->user->name,
                'mensagem' => "Aluno identificado com risco de evasão (Score: " . number_format($alerta->score_evasao * 100, 1) . "%)",
                'tipo' => 'evasao',
                'lida' => false,
                'notificavel_type' => AlertaEvasao::class,
                'notificavel_id' => $alerta->id,
            ]);
        }
    }

    /**
     * Analisa todos os alunos em lote
     */
    public function analisarTodosAlunos(): void
    {
        $alunos = Aluno::where('status', 'ativo')
            ->get();
        
        foreach ($alunos as $aluno) {
            AnalisarEvasaoJob::dispatch($aluno);
        }
    }

    /**
     * Analisa alunos de uma turma específica
     */
    public function analisarTurma(int $turmaId): void
    {
        $alunos = Aluno::where('turma_id', $turmaId)
            ->where('status', 'ativo')
            ->get();
        
        foreach ($alunos as $aluno) {
            $this->analisarRiscoEvasao($aluno);
        }
    }
}
