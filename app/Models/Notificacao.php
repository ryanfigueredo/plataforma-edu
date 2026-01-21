<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    protected $fillable = [
        'user_id',
        'titulo',
        'mensagem',
        'tipo',
        'lida',
        'data_leitura',
        'notificavel_type',
        'notificavel_id',
    ];

    protected $casts = [
        'lida' => 'boolean',
        'data_leitura' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notificavel(): MorphTo
    {
        return $this->morphTo();
    }

    public function marcarComoLida()
    {
        $this->update([
            'lida' => true,
            'data_leitura' => now(),
        ]);
    }
}
