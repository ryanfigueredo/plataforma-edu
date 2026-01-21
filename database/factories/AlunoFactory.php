<?php

namespace Database\Factories;

use App\Models\Aluno;
use App\Models\User;
use App\Models\Turma;
use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlunoFactory extends Factory
{
    protected $model = Aluno::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'matricula' => fake()->unique()->numerify('########'),
            'turma_id' => Turma::factory(),
            'curso_id' => Curso::factory(),
            'data_ingresso' => fake()->dateTimeBetween('-1 year', 'now'),
            'status' => 'ativo',
            'evasao_score' => fake()->randomFloat(2, 0, 1),
            'ultima_analise_evasao' => now(),
        ];
    }
}
