<?php

namespace Database\Factories;

use App\Models\Turma;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TurmaFactory extends Factory
{
    protected $model = Turma::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->words(4, true) . ' - Turma ' . fake()->randomLetter(),
            'curso_id' => Curso::factory(),
            'ano_letivo' => fake()->year(),
            'periodo' => fake()->randomElement(['Matutino', 'Vespertino', 'Noturno']),
            'supervisor_id' => User::factory(),
            'ativo' => true,
        ];
    }
}
