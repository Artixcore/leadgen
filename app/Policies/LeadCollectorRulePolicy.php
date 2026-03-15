<?php

namespace App\Policies;

use App\Models\LeadCollectorRule;
use App\Models\User;

class LeadCollectorRulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function update(User $user, LeadCollectorRule $rule): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function delete(User $user, LeadCollectorRule $rule): bool
    {
        return $user->can('manage-lead-collectors');
    }
}
