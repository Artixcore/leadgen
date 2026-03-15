<?php

namespace App\Policies;

use App\Models\LeadSource;
use App\Models\User;

class LeadSourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-lead-sources');
    }

    public function view(User $user, LeadSource $leadSource): bool
    {
        return $user->can('manage-lead-sources');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-lead-sources');
    }

    public function update(User $user, LeadSource $leadSource): bool
    {
        return $user->can('manage-lead-sources');
    }

    public function delete(User $user, LeadSource $leadSource): bool
    {
        return $user->can('manage-lead-sources');
    }

    public function restore(User $user, LeadSource $leadSource): bool
    {
        return $user->can('manage-lead-sources');
    }

    public function forceDelete(User $user, LeadSource $leadSource): bool
    {
        return $user->can('manage-lead-sources');
    }
}
