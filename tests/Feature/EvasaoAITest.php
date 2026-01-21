<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\User;
use App\Models\Frequencia;
use App\Models\Desempenho;
use App\Models\Disciplina;
use App\Services\EvasaoAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvasaoAITest extends TestCase
{
    use RefreshDatabase;

    protected EvasaoAIService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EvasaoAIService();
    }

    public function test_analisa_risco_evasao_aluno_com_baixa_frequencia(): void
    {
        $user = User::factory()->create();
        $user->assignRole('aluno');
        
        $aluno = Aluno::factory()->create(['user_id' => $user->id]);
        $disciplina = Disciplina::factory()->create();
        
        // Criar frequências baixas (30% de presença)
        for ($i = 0; $i < 10; $i++) {
            Frequencia::factory()->create([
                'aluno_id' => $aluno->id,
                'disciplina_id' => $disciplina->id,
                'data' => now()->subDays($i),
                'presente' => $i % 3 === 0, // Apenas 1/3 presente
            ]);
        }
        
        $score = $this->service->analisarRiscoEvasao($aluno);
        
        $this->assertGreaterThan(0.5, $score);
        $this->assertLessThanOrEqual(1.0, $score);
    }

    public function test_analisa_risco_evasao_aluno_com_bom_desempenho(): void
    {
        $user = User::factory()->create();
        $user->assignRole('aluno');
        
        $aluno = Aluno::factory()->create(['user_id' => $user->id]);
        $disciplina = Disciplina::factory()->create();
        
        // Criar desempenhos altos
        for ($i = 0; $i < 5; $i++) {
            Desempenho::factory()->create([
                'aluno_id' => $aluno->id,
                'disciplina_id' => $disciplina->id,
                'nota' => 9.0,
                'data_avaliacao' => now()->subDays($i * 7),
            ]);
        }
        
        $score = $this->service->analisarRiscoEvasao($aluno);
        
        $this->assertLessThan(0.5, $score);
    }

    public function test_gera_alerta_quando_score_ultrapassa_threshold(): void
    {
        $user = User::factory()->create();
        $user->assignRole('aluno');
        
        $aluno = Aluno::factory()->create(['user_id' => $user->id]);
        $disciplina = Disciplina::factory()->create();
        
        // Criar situação de alto risco
        for ($i = 0; $i < 10; $i++) {
            Frequencia::factory()->create([
                'aluno_id' => $aluno->id,
                'disciplina_id' => $disciplina->id,
                'data' => now()->subDays($i),
                'presente' => false, // Nenhuma presença
            ]);
            
            Desempenho::factory()->create([
                'aluno_id' => $aluno->id,
                'disciplina_id' => $disciplina->id,
                'nota' => 3.0, // Notas baixas
                'data_avaliacao' => now()->subDays($i * 7),
            ]);
        }
        
        $score = $this->service->analisarRiscoEvasao($aluno);
        
        $this->assertGreaterThanOrEqual(config('ai.evasao_threshold', 0.7), $score);
        
        $this->assertDatabaseHas('alertas_evasao', [
            'aluno_id' => $aluno->id,
            'status' => 'pendente',
        ]);
    }

    public function test_nao_gera_alerta_duplicado_em_24h(): void
    {
        $user = User::factory()->create();
        $user->assignRole('aluno');
        
        $aluno = Aluno::factory()->create(['user_id' => $user->id]);
        
        // Primeira análise
        $this->service->analisarRiscoEvasao($aluno);
        
        $alertasAntes = \App\Models\AlertaEvasao::where('aluno_id', $aluno->id)->count();
        
        // Segunda análise imediatamente
        $this->service->analisarRiscoEvasao($aluno);
        
        $alertasDepois = \App\Models\AlertaEvasao::where('aluno_id', $aluno->id)->count();
        
        $this->assertEquals($alertasAntes, $alertasDepois);
    }
}
