<?php

namespace App\Policies;

use App\Models\Churche;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChurchePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->role->name == "Secretaria de iglesia";
    }

    /**
     * Determina que solo un usuario con el rol "Secretaria de distrito", ingrese a la función
     * getForEachChurchTheSumOfAllTheWeeksOfTheMonthOpened
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function getForEachChurchTheSumOfAllTheWeeksOfTheMonthOpened(User $user): bool
    {
        return $user->role->name === 'Secretaria de distrito';
    }

    /**
     * Determina que solo un usuario con el rol "Secretaria de distrito", ingrese a la función
     * getForEachChurchTheSumOfAllTheWeeksOfTheMonthSelected
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function getForEachChurchTheSumOfAllTheWeeksOfTheMonthSelected(User $user): bool
    {
        return $user->role->name === 'Secretaria de distrito';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Churche  $churche
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function view(User $user, Churche $churche)
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function create(User $user)
    // {
    //     //
    // }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Churche  $churche
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function update(User $user, Churche $churche)
    // {
    //     //
    // }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Churche  $churche
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function delete(User $user, Churche $churche)
    // {
    //     //
    // }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Churche  $churche
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function restore(User $user, Churche $churche)
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Churche  $churche
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function forceDelete(User $user, Churche $churche)
    // {
    //     //
    // }
}
