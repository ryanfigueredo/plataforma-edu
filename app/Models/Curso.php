<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'codigo',
        'carga_horaria',
        'duracao_meses',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function alunos(): HasMany
    {
        return $this->hasMany(Aluno::class);
    }

    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class);
    }

    public function disciplinas(): HasMany
    {
        return $this->hasMany(Disciplina::class);
    }
}
