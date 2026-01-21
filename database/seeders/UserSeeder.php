<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        $admin = User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@plataforma.local',
            'password' => Hash::make('password'),
            'cpf' => '00000000000',
            'ativo' => true,
            'lgpd_consentimento' => true,
            'lgpd_consentimento_data' => now(),
        ]);
        $admin->assignRole('administrador');

        // Diretor
        $diretor = User::create([
            'name' => 'Diretor Geral',
            'email' => 'diretor@plataforma.local',
            'password' => Hash::make('password'),
            'cpf' => '11111111111',
            'ativo' => true,
            'lgpd_consentimento' => true,
            'lgpd_consentimento_data' => now(),
        ]);
        $diretor->assignRole('diretor');

        // Supervisor
        $supervisor = User::create([
            'name' => 'Supervisor Acadêmico',
            'email' => 'supervisor@plataforma.local',
            'password' => Hash::make('password'),
            'cpf' => '22222222222',
            'ativo' => true,
            'lgpd_consentimento' => true,
            'lgpd_consentimento_data' => now(),
        ]);
        $supervisor->assignRole('supervisor');

        // Aluno exemplo
        $aluno = User::create([
            'name' => 'João Silva',
            'email' => 'aluno@plataforma.local',
            'password' => Hash::make('password'),
            'cpf' => '33333333333',
            'data_nascimento' => '2000-01-15',
            'telefone' => '(71) 99999-9999',
            'ativo' => true,
            'lgpd_consentimento' => true,
            'lgpd_consentimento_data' => now(),
        ]);
        $aluno->assignRole('aluno');
    }
}
