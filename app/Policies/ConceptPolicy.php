<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConceptPolicy
{
    use HandlesAuthorization;

    /**
     * Determina que solo los roles "Secretaria de distrito" y "Secretaria de iglesia" pueden entrar
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->role->name == "Secretaria de distrito" || $user->role->name == "Secretaria de iglesia";
    }
}
