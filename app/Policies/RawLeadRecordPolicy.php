<?php

namespace App\Policies;

use App\Models\RawLeadRecord;
use App\Models\User;

class RawLeadRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage-lead-collectors');
    }

    public function view(User $user, RawLeadRecord $rawLeadRecord): bool
    {
        return $user->can('manage-lead-collectors');
    }
}
