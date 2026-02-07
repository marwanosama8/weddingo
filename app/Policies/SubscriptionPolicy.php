<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
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
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $user, Subscription $subscription)
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
        return true;
    }

    /**
     * Determine whether the Admin can update the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $user, Subscription $subscription)
    {
        return true;
    }

    /**
     * Determine whether the Admin can delete the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $user, Subscription $subscription)
    {
        return true;
    }

    /**
     * Determine whether the Admin can restore the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $user, Subscription $subscription)
    {
        return true;
    }

    /**
     * Determine whether the Admin can permanently delete the model.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $user, Subscription $subscription)
    {
        return true;
    }
}
