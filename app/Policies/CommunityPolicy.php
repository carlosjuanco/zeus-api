<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunityPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si un usuario puede ver algún modelo
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->role->name === "Administrativo";
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role->name === "Administrativo";
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Community $community)
    {
        return $user->role->name === "Administrativo";
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Community $community)
    {
        return $user->role->name === "Administrativo";
    }
}