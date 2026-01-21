<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Desempenho extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'disciplina_id',
        'tipo_avaliacao',
        'nota',
        'data_avaliacao',
        'observacoes',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'data_avaliacao' => 'date',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }
}
