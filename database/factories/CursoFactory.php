<?php

namespace Database\Factories;

use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
{
    protected $model = Curso::class;

    public function definition(): array
    {
        return [
            'nome' => 'Técnico em ' . fake()->words(2, true),
            'codigo' => fake()->unique()->bothify('???-###'),
            'carga_horaria' => fake()->numberBetween(800, 2000),
            'duracao_meses' => fake()->numberBetween(12, 24),
            'ativo' => true,
        ];
    }
}
