<?php

namespace App\Policies;

use App\Models\Resservasion;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResservasionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Admin can view any models.
     *
     * @param  \App\Models\Admin  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Admin $user)
    {
        return true;
    }

    /**
     * Determine whether the Admin can view the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Resservasion  $resservasion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $user, Resservasion $resservasion)
    {
        return true;
    }

    /**
     * Determine whether the Admin can create models.
     *
     * @param  \App\Models\Admin  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $user)
    {
        return false;
    }

    /**
     * Determine whether the Admin can update the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Resservasion  $resservasion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $user, Resservasion $resservasion)
    {
        return true;
    }

    /**
     * Determine whether the Admin can delete the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Resservasion  $resservasion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $user, Resservasion $resservasion)
    {
        return true;
    }

    /**
     * Determine whether the Admin can restore the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Resservasion  $resservasion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $user, Resservasion $resservasion)
    {
        return true;
    }

    /**
     * Determine whether the Admin can permanently delete the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Resservasion  $resservasion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $user, Resservasion $resservasion)
    {
        return true;
    }
}
