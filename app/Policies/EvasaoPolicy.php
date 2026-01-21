<?php

namespace App\Policies;

use App\Models\User;

class EvasaoPolicy
{
    public function analisarEvasao(User $user): bool
    {
        return $user->isDiretor() || $user->isAdministrador();
    }

    public function gerenciarAlertas(User $user): bool
    {
        return $user->isDiretor() || $user->isAdministrador();
    }
}
