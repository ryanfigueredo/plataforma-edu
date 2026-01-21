<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        Curso::create([
            'nome' => 'Técnico em Informática',
            'codigo' => 'TEC-INFO',
            'carga_horaria' => 1200,
            'duracao_meses' => 18,
            'ativo' => true,
        ]);

        Curso::create([
            'nome' => 'Técnico em Administração',
            'codigo' => 'TEC-ADM',
            'carga_horaria' => 1000,
            'duracao_meses' => 15,
            'ativo' => true,
        ]);
    }
}
