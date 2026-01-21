<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'telefone',
        'data_nascimento',
        'ativo',
        'lgpd_consentimento',
        'lgpd_consentimento_data',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ativo' => 'boolean',
        'lgpd_consentimento' => 'boolean',
        'lgpd_consentimento_data' => 'datetime',
        'data_nascimento' => 'date',
    ];

    public function aluno()
    {
        return $this->hasOne(Aluno::class);
    }

    public function frequencias()
    {
        return $this->hasManyThrough(Frequencia::class, Aluno::class);
    }

    public function desempenhos()
    {
        return $this->hasManyThrough(Desempenho::class, Aluno::class);
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class);
    }

    public function notificacoesNaoLidas()
    {
        return $this->notificacoes()->where('lida', false);
    }

    public function auditorias()
    {
        return $this->hasMany(LgpdAuditoria::class);
    }

    public function isAluno()
    {
        return $this->hasRole('aluno');
    }

    public function isSupervisor()
    {
        return $this->hasRole('supervisor');
    }

    public function isDiretor()
    {
        return $this->hasRole('diretor');
    }

    public function isAdministrador()
    {
        return $this->hasRole('administrador');
    }

    // Removido mutator - usar Hash::make() diretamente ao criar usuários
}
