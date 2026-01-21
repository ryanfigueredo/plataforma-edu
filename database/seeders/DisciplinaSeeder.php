<?php

namespace Database\Seeders;

use App\Models\Disciplina;
use App\Models\Curso;
use Illuminate\Database\Seeder;

class DisciplinaSeeder extends Seeder
{
    public function run(): void
    {
        $cursoInfo = Curso::where('codigo', 'TEC-INFO')->first();
        $cursoAdm = Curso::where('codigo', 'TEC-ADM')->first();

        // Disciplinas do curso de Informática
        Disciplina::create([
            'nome' => 'Programação Web',
            'codigo' => 'PROG-WEB',
            'curso_id' => $cursoInfo->id,
            'carga_horaria' => 80,
            'ativo' => true,
        ]);

        Disciplina::create([
            'nome' => 'Banco de Dados',
            'codigo' => 'BD',
            'curso_id' => $cursoInfo->id,
            'carga_horaria' => 60,
            'ativo' => true,
        ]);

        // Disciplinas do curso de Administração
        Disciplina::create([
            'nome' => 'Gestão de Pessoas',
            'codigo' => 'GEST-PESS',
            'curso_id' => $cursoAdm->id,
            'carga_horaria' => 60,
            'ativo' => true,
        ]);

        Disciplina::create([
            'nome' => 'Contabilidade',
            'codigo' => 'CONT',
            'curso_id' => $cursoAdm->id,
            'carga_horaria' => 80,
            'ativo' => true,
        ]);
    }
}
