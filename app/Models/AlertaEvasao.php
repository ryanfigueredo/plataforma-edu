<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertaEvasao extends Model
{
    use HasFactory;

    protected $table = 'alertas_evasao';

    protected $fillable = [
        'aluno_id',
        'score_evasao',
        'motivos',
        'status',
        'visualizado_por',
        'visualizado_em',
        'acoes_tomadas',
    ];

    protected $casts = [
        'score_evasao' => 'decimal:2',
        'motivos' => 'array',
        'visualizado_em' => 'datetime',
        'acoes_tomadas' => 'array',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function visualizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visualizado_por');
    }

    public function marcarComoVisualizado($userId)
    {
        $this->update([
            'visualizado_por' => $userId,
            'visualizado_em' => now(),
        ]);
    }
}
