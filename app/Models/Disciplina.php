<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'codigo',
        'curso_id',
        'carga_horaria',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function frequencias(): HasMany
    {
        return $this->hasMany(Frequencia::class);
    }

    public function desempenhos(): HasMany
    {
        return $this->hasMany(Desempenho::class);
    }
}
