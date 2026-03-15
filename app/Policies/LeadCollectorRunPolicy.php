<?php

namespace App\Policies;

use App\Models\LeadCollectorRun;
use App\Models\User;

class LeadCollectorRunPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function view(User $user, LeadCollectorRun $run): bool
    {
        return $user->can('manage-lead-collectors');
    }
}
