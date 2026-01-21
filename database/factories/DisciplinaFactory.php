<?php

namespace Database\Factories;

use App\Models\Disciplina;
use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisciplinaFactory extends Factory
{
    protected $model = Disciplina::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->words(3, true),
            'codigo' => fake()->unique()->bothify('???-###'),
            'curso_id' => Curso::factory(),
            'carga_horaria' => fake()->numberBetween(40, 120),
            'ativo' => true,
        ];
    }
}
