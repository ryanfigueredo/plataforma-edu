<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Aluno;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_pode_acessar_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('administrador');
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
    }

    public function test_diretor_pode_acessar_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('diretor');
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
    }

    public function test_aluno_pode_acessar_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('aluno');
        
        $aluno = Aluno::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
    }

    public function test_usuario_nao_autenticado_redireciona_para_login(): void
    {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect('/login');
    }
}
