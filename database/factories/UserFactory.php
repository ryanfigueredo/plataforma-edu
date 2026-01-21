<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'cpf' => fake()->numerify('###########'),
            'telefone' => fake()->phoneNumber(),
            'data_nascimento' => fake()->date(),
            'ativo' => true,
            'lgpd_consentimento' => true,
            'lgpd_consentimento_data' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
