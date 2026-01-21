<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\User;
use App\Models\Turma;
use App\Models\Curso;
use Illuminate\Database\Seeder;

class AlunoSeeder extends Seeder
{
    public function run(): void
    {
        $alunoUser = User::where('email', 'aluno@plataforma.local')->first();
        $turma = Turma::first();
        $curso = Curso::where('codigo', 'TEC-INFO')->first();

        if ($alunoUser && $turma && $curso) {
            Aluno::create([
                'user_id' => $alunoUser->id,
                'matricula' => '2024001',
                'turma_id' => $turma->id,
                'curso_id' => $curso->id,
                'data_ingresso' => now()->subMonths(3),
                'status' => 'ativo',
                'evasao_score' => 0.3,
            ]);
        }
    }
}
