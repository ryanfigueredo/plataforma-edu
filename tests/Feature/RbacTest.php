<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_aluno_nao_pode_acessar_evasao(): void
    {
        $user = User::factory()->create();
        $user->assignRole('aluno');
        
        $response = $this->actingAs($user)->get('/evasao');
        
        $response->assertStatus(403);
    }

    public function test_diretor_pode_acessar_evasao(): void
    {
        $user = User::factory()->create();
        $user->assignRole('diretor');
        
        $response = $this->actingAs($user)->get('/evasao');
        
        $response->assertStatus(200);
    }

    public function test_administrador_pode_acessar_evasao(): void
    {
        $user = User::factory()->create();
        $user->assignRole('administrador');
        
        $response = $this->actingAs($user)->get('/evasao');
        
        $response->assertStatus(200);
    }

    public function test_supervisor_pode_ver_alertas_de_suas_turmas(): void
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');
        
        $response = $this->actingAs($user)->get('/evasao');
        
        $response->assertStatus(200);
    }
}
