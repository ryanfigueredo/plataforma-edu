<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LgpdAuditoria extends Model
{
    use HasFactory;

    protected $table = 'lgpd_auditoria';

    protected $fillable = [
        'user_id',
        'acao',
        'tipo_dado',
        'dados_anteriores',
        'dados_novos',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function registrar($userId, $acao, $tipoDado, $dadosAnteriores = null, $dadosNovos = null)
    {
        return self::create([
            'user_id' => $userId,
            'acao' => $acao,
            'tipo_dado' => $tipoDado,
            'dados_anteriores' => $dadosAnteriores,
            'dados_novos' => $dadosNovos,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
