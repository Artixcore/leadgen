<?php

namespace App\Policies;

use App\Models\SavedFilter;
use App\Models\User;

class SavedFilterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SavedFilter $savedFilter): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('receive-notifications');
    }

    public function update(User $user, SavedFilter $savedFilter): bool
    {
        return $savedFilter->user_id === $user->id;
    }

    public function delete(User $user, SavedFilter $savedFilter): bool
    {
        return $savedFilter->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SavedFilter $savedFilter): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SavedFilter $savedFilter): bool
    {
        return false;
    }
}
