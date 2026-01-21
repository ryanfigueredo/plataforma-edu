<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\LgpdService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LgpdTest extends TestCase
{
    use RefreshDatabase;

    protected LgpdService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LgpdService();
    }

    public function test_criptografa_dados_sensiveis(): void
    {
        $dado = '12345678900';
        $criptografado = $this->service->criptografar($dado);
        
        $this->assertNotEquals($dado, $criptografado);
        $this->assertNotEmpty($criptografado);
    }

    public function test_descriptografa_dados_sensiveis(): void
    {
        $dado = '12345678900';
        $criptografado = $this->service->criptografar($dado);
        $descriptografado = $this->service->descriptografar($criptografado);
        
        $this->assertEquals($dado, $descriptografado);
    }

    public function test_registra_auditoria_lgpd(): void
    {
        $user = User::factory()->create();
        
        $this->service->registrarAuditoria(
            $user->id,
            'consulta_dados',
            'dados_pessoais',
            null,
            ['campo' => 'valor']
        );
        
        $this->assertDatabaseHas('lgpd_auditoria', [
            'user_id' => $user->id,
            'acao' => 'consulta_dados',
            'tipo_dado' => 'dados_pessoais',
        ]);
    }

    public function test_exporta_dados_usuario(): void
    {
        $user = User::factory()->create();
        
        $dados = $this->service->exportarDadosUsuario($user->id);
        
        $this->assertArrayHasKey('usuario', $dados);
        $this->assertEquals($user->name, $dados['usuario']['nome']);
        $this->assertEquals($user->email, $dados['usuario']['email']);
    }

    public function test_processa_exclusao_dados(): void
    {
        $user = User::factory()->create([
            'name' => 'Teste Usuario',
            'email' => 'teste@teste.com',
        ]);
        
        $sucesso = $this->service->processarExclusaoDados($user->id);
        
        $this->assertTrue($sucesso);
        
        $user->refresh();
        $this->assertEquals('Usuário Excluído', $user->name);
        $this->assertStringContainsString('excluido', $user->email);
    }
}
