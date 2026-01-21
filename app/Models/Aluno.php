<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricula',
        'turma_id',
        'curso_id',
        'data_ingresso',
        'status',
        'evasao_score',
        'ultima_analise_evasao',
    ];

    protected $casts = [
        'data_ingresso' => 'date',
        'evasao_score' => 'decimal:2',
        'ultima_analise_evasao' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

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

    public function alertasEvasao(): HasMany
    {
        return $this->hasMany(AlertaEvasao::class);
    }

    public function calcularFrequenciaMedia($periodoDias = 30)
    {
        $dataInicio = now()->subDays($periodoDias);
        
        $totalAulas = $this->frequencias()
            ->where('data', '>=', $dataInicio)
            ->count();
            
        $presencas = $this->frequencias()
            ->where('data', '>=', $dataInicio)
            ->where('presente', true)
            ->count();

        if ($totalAulas === 0) {
            return 0;
        }

        return ($presencas / $totalAulas) * 100;
    }

    public function calcularDesempenhoMedio()
    {
        $desempenhos = $this->desempenhos()
            ->whereNotNull('nota')
            ->get();

        if ($desempenhos->isEmpty()) {
            return 0;
        }

        return $desempenhos->avg('nota');
    }

    public function estaEmRisco()
    {
        $threshold = config('ai.evasao_threshold', 0.7);
        return $this->evasao_score >= $threshold;
    }
}
