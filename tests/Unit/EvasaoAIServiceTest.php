<?php

namespace Tests\Unit;

use App\Models\Aluno;
use App\Services\EvasaoAIService;
use Tests\TestCase;

class EvasaoAIServiceTest extends TestCase
{
    protected EvasaoAIService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EvasaoAIService();
    }

    public function test_calcula_fator_frequencia_alta(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calcularFatorFrequencia');
        $method->setAccessible(true);
        
        $fator = $method->invoke($this->service, 95.0);
        
        $this->assertEquals(0.1, $fator);
    }

    public function test_calcula_fator_frequencia_baixa(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calcularFatorFrequencia');
        $method->setAccessible(true);
        
        $fator = $method->invoke($this->service, 50.0);
        
        $this->assertEquals(0.9, $fator);
    }

    public function test_calcula_fator_desempenho_alto(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calcularFatorDesempenho');
        $method->setAccessible(true);
        
        $fator = $method->invoke($this->service, 9.0);
        
        $this->assertEquals(0.1, $fator);
    }

    public function test_calcula_fator_desempenho_baixo(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calcularFatorDesempenho');
        $method->setAccessible(true);
        
        $fator = $method->invoke($this->service, 3.0);
        
        $this->assertEquals(0.95, $fator);
    }

    public function test_score_retorna_valor_entre_0_e_1(): void
    {
        $aluno = Aluno::factory()->create();
        
        $score = $this->service->analisarRiscoEvasao($aluno);
        
        $this->assertGreaterThanOrEqual(0, $score);
        $this->assertLessThanOrEqual(1, $score);
    }
}
