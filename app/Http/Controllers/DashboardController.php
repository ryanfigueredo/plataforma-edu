<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AlertaEvasao;
use App\Models\User;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard principal - acesso por perfil
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isAdministrador() || $user->isDiretor()) {
            return $this->dashboardAdministrativo($request);
        } elseif ($user->isSupervisor()) {
            return $this->dashboardSupervisor($request);
        } elseif ($user->isAluno()) {
            return $this->dashboardAluno($request);
        }
        
        return redirect()->route('login')->with('error', 'Perfil não reconhecido.');
    }

    /**
     * Dashboard administrativo completo
     */
    public function dashboardAdministrativo(Request $request)
    {
        $totalAlunos = Aluno::where('status', 'ativo')->count();
        $totalTurmas = Turma::where('ativo', true)->count();
        $totalUsuarios = User::where('ativo', true)->count();
        
        // Alunos em risco
        $alunosEmRisco = Aluno::where('status', 'ativo')
            ->where('evasao_score', '>=', config('ai.evasao_threshold', 0.7))
            ->count();
        
        // Taxa de retenção (últimos 6 meses)
        $alunosIngressos = Aluno::where('data_ingresso', '>=', now()->subMonths(6))
            ->where('status', 'ativo')
            ->count();
        
        $alunosEvasao = Aluno::where('data_ingresso', '>=', now()->subMonths(6))
            ->where('status', 'evadido')
            ->count();
        
        $taxaRetencao = $alunosIngressos > 0 
            ? (($alunosIngressos - $alunosEvasao) / $alunosIngressos) * 100 
            : 100;
        
        // Alertas pendentes
        $alertasPendentes = AlertaEvasao::where('status', 'pendente')
            ->count();
        
        // Gráfico de evasão por mês (últimos 6 meses)
        $evasaoPorMes = Aluno::select(
                DB::raw("TO_CHAR(updated_at, 'YYYY-MM') as mes"),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', 'evadido')
            ->where('updated_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        
        // Gráfico de frequência média por turma
        $frequenciaPorTurma = Turma::withCount(['alunos as alunos_ativos' => function ($query) {
                $query->where('status', 'ativo');
            }])
            ->where('ativo', true)
            ->get()
            ->map(function ($turma) {
                $alunos = $turma->alunos()->where('status', 'ativo')->get();
                $frequenciaMedia = $alunos->avg(function ($aluno) {
                    return $aluno->calcularFrequenciaMedia(30);
                });
                
                return [
                    'turma' => $turma->nome,
                    'frequencia_media' => round($frequenciaMedia ?? 0, 2),
                ];
            });
        
        // Últimos alertas
        $ultimosAlertas = AlertaEvasao::with(['aluno.user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard.administrativo', compact(
            'totalAlunos',
            'totalTurmas',
            'totalUsuarios',
            'alunosEmRisco',
            'taxaRetencao',
            'alertasPendentes',
            'evasaoPorMes',
            'frequenciaPorTurma',
            'ultimosAlertas'
        ));
    }

    /**
     * Dashboard do supervisor
     */
    public function dashboardSupervisor(Request $request)
    {
        $user = $request->user();
        
        $turmas = Turma::where('supervisor_id', $user->id)
            ->where('ativo', true)
            ->withCount(['alunos as alunos_ativos' => function ($query) {
                $query->where('status', 'ativo');
            }])
            ->get();
        
        $totalAlunos = Aluno::whereIn('turma_id', $turmas->pluck('id'))
            ->where('status', 'ativo')
            ->count();
        
        $alunosEmRisco = Aluno::whereIn('turma_id', $turmas->pluck('id'))
            ->where('status', 'ativo')
            ->where('evasao_score', '>=', config('ai.evasao_threshold', 0.7))
            ->count();
        
        $alertasPendentes = AlertaEvasao::whereHas('aluno', function ($query) use ($turmas) {
                $query->whereIn('turma_id', $turmas->pluck('id'));
            })
            ->where('status', 'pendente')
            ->count();
        
        return view('dashboard.supervisor', compact(
            'turmas',
            'totalAlunos',
            'alunosEmRisco',
            'alertasPendentes'
        ));
    }

    /**
     * Dashboard do aluno
     */
    public function dashboardAluno(Request $request)
    {
        $user = $request->user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return redirect()->route('login')->with('error', 'Perfil de aluno não encontrado.');
        }
        
        $frequenciaMedia = $aluno->calcularFrequenciaMedia(30);
        $desempenhoMedio = $aluno->calcularDesempenhoMedio();
        $evasaoScore = $aluno->evasao_score ?? 0;
        
        $ultimasFrequencias = $aluno->frequencias()
            ->with('disciplina')
            ->orderBy('data', 'desc')
            ->limit(10)
            ->get();
        
        $ultimosDesempenhos = $aluno->desempenhos()
            ->with('disciplina')
            ->orderBy('data_avaliacao', 'desc')
            ->limit(10)
            ->get();
        
        $notificacoesNaoLidas = $user->notificacoesNaoLidas()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.aluno', compact(
            'aluno',
            'frequenciaMedia',
            'desempenhoMedio',
            'evasaoScore',
            'ultimasFrequencias',
            'ultimosDesempenhos',
            'notificacoesNaoLidas'
        ));
    }

    /**
     * API para dados do dashboard (AJAX)
     */
    public function dadosDashboard(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isAdministrador() && !$user->isDiretor()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        
        $totalAlunos = Aluno::where('status', 'ativo')->count();
        $alunosEmRisco = Aluno::where('status', 'ativo')
            ->where('evasao_score', '>=', config('ai.evasao_threshold', 0.7))
            ->count();
        
        return response()->json([
            'total_alunos' => $totalAlunos,
            'alunos_em_risco' => $alunosEmRisco,
            'taxa_risco' => $totalAlunos > 0 ? ($alunosEmRisco / $totalAlunos) * 100 : 0,
        ]);
    }
}
