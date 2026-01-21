<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AlertaEvasao;
use App\Services\EvasaoAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EvasaoAIController extends Controller
{
    protected EvasaoAIService $evasaoService;

    public function __construct(EvasaoAIService $evasaoService)
    {
        $this->evasaoService = $evasaoService;
    }

    /**
     * Lista alertas de evasão
     */
    public function index(Request $request)
    {
        $query = AlertaEvasao::with(['aluno.user', 'visualizadoPor']);
        
        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('turma_id')) {
            $query->whereHas('aluno', function ($q) use ($request) {
                $q->where('turma_id', $request->turma_id);
            });
        }
        
        // Supervisores só veem suas turmas
        if ($request->user()->isSupervisor()) {
            $turmasIds = \App\Models\Turma::where('supervisor_id', $request->user()->id)
                ->pluck('id');
            
            $query->whereHas('aluno', function ($q) use ($turmasIds) {
                $q->whereIn('turma_id', $turmasIds);
            });
        }
        
        $alertas = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('evasao.index', compact('alertas'));
    }

    /**
     * Exibe detalhes de um alerta
     */
    public function show(int $id, Request $request)
    {
        $alerta = AlertaEvasao::with(['aluno.user', 'aluno.turma', 'aluno.curso'])
            ->findOrFail($id);
        
        // Verificar permissão
        if ($request->user()->isSupervisor()) {
            $turmaId = \App\Models\Turma::where('supervisor_id', $request->user()->id)
                ->pluck('id');
            
            if (!in_array($alerta->aluno->turma_id, $turmaId->toArray())) {
                abort(403);
            }
        }
        
        // Marcar como visualizado se for diretor ou admin
        if ($request->user()->isDiretor() || $request->user()->isAdministrador()) {
            if ($alerta->status === 'pendente') {
                $alerta->marcarComoVisualizado($request->user()->id);
                $alerta->update(['status' => 'visualizado']);
            }
        }
        
        // Dados do aluno para análise
        $frequenciaMedia = $alerta->aluno->calcularFrequenciaMedia(30);
        $desempenhoMedio = $alerta->aluno->calcularDesempenhoMedio();
        
        return view('evasao.show', compact('alerta', 'frequenciaMedia', 'desempenhoMedio'));
    }

    /**
     * Analisa risco de evasão de um aluno específico
     */
    public function analisarAluno(int $alunoId, Request $request)
    {
        Gate::authorize('analisar-evasao');
        
        $aluno = Aluno::findOrFail($alunoId);
        $score = $this->evasaoService->analisarRiscoEvasao($aluno);
        
        return response()->json([
            'success' => true,
            'aluno_id' => $aluno->id,
            'score' => $score,
            'em_risco' => $score >= config('ai.evasao_threshold', 0.7),
        ]);
    }

    /**
     * Analisa todos os alunos (job em background)
     */
    public function analisarTodos(Request $request)
    {
        Gate::authorize('analisar-evasao');
        
        $this->evasaoService->analisarTodosAlunos();
        
        return response()->json([
            'success' => true,
            'message' => 'Análise de evasão iniciada para todos os alunos.',
        ]);
    }

    /**
     * Analisa alunos de uma turma
     */
    public function analisarTurma(int $turmaId, Request $request)
    {
        Gate::authorize('analisar-evasao');
        
        $this->evasaoService->analisarTurma($turmaId);
        
        return response()->json([
            'success' => true,
            'message' => 'Análise de evasão iniciada para a turma.',
        ]);
    }

    /**
     * Registra ação tomada em um alerta
     */
    public function registrarAcao(int $id, Request $request)
    {
        $alerta = AlertaEvasao::findOrFail($id);
        
        Gate::authorize('gerenciar-alertas');
        
        $request->validate([
            'acao' => 'required|string',
            'observacoes' => 'nullable|string',
        ]);
        
        $acoes = $alerta->acoes_tomadas ?? [];
        $acoes[] = [
            'acao' => $request->acao,
            'observacoes' => $request->observacoes,
            'registrado_por' => $request->user()->id,
            'registrado_em' => now()->toDateTimeString(),
        ];
        
        $alerta->update([
            'acoes_tomadas' => $acoes,
            'status' => 'acao_tomada',
        ]);
        
        return redirect()->route('evasao.show', $id)
            ->with('success', 'Ação registrada com sucesso.');
    }
}
