<?php

namespace Database\Factories;

use App\Models\Desempenho;
use App\Models\Aluno;
use App\Models\Disciplina;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesempenhoFactory extends Factory
{
    protected $model = Desempenho::class;

    public function definition(): array
    {
        return [
            'aluno_id' => Aluno::factory(),
            'disciplina_id' => Disciplina::factory(),
            'tipo_avaliacao' => fake()->randomElement(['Prova', 'Trabalho', 'Apresentação', 'Projeto']),
            'nota' => fake()->randomFloat(2, 0, 10),
            'data_avaliacao' => fake()->dateTimeBetween('-6 months', 'now'),
            'observacoes' => fake()->optional()->text(),
        ];
    }
}
