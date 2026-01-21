<?php

namespace Database\Factories;

use App\Models\Frequencia;
use App\Models\Aluno;
use App\Models\Disciplina;
use Illuminate\Database\Eloquent\Factories\Factory;

class FrequenciaFactory extends Factory
{
    protected $model = Frequencia::class;

    public function definition(): array
    {
        return [
            'aluno_id' => Aluno::factory(),
            'disciplina_id' => Disciplina::factory(),
            'data' => fake()->dateTimeBetween('-30 days', 'now'),
            'presente' => fake()->boolean(80), // 80% de chance de estar presente
            'justificativa' => fake()->optional()->sentence(),
            'observacoes' => fake()->optional()->text(),
        ];
    }
}
