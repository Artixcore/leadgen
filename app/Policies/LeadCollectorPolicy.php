<?php

namespace App\Policies;

use App\Models\LeadCollector;
use App\Models\User;

class LeadCollectorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function view(User $user, LeadCollector $leadCollector): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function update(User $user, LeadCollector $leadCollector): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function delete(User $user, LeadCollector $leadCollector): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function restore(User $user, LeadCollector $leadCollector): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function forceDelete(User $user, LeadCollector $leadCollector): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function run(User $user, LeadCollector $leadCollector): bool
    {
        return $user->can('manage-lead-collectors');
    }
}
