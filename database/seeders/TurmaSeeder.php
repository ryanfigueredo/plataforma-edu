<?php

namespace Database\Seeders;

use App\Models\Turma;
use App\Models\User;
use App\Models\Curso;
use Illuminate\Database\Seeder;

class TurmaSeeder extends Seeder
{
    public function run(): void
    {
        $supervisor = User::role('supervisor')->first();
        $cursoInfo = Curso::where('codigo', 'TEC-INFO')->first();
        $cursoAdm = Curso::where('codigo', 'TEC-ADM')->first();

        Turma::create([
            'nome' => 'Técnico Informática - Turma A',
            'curso_id' => $cursoInfo->id,
            'ano_letivo' => '2024',
            'periodo' => 'Matutino',
            'supervisor_id' => $supervisor->id,
            'ativo' => true,
        ]);

        Turma::create([
            'nome' => 'Técnico Administração - Turma A',
            'curso_id' => $cursoAdm->id,
            'ano_letivo' => '2024',
            'periodo' => 'Vespertino',
            'supervisor_id' => $supervisor->id,
            'ativo' => true,
        ]);
    }
}
