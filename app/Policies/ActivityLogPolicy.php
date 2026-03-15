<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogPolicy
{
    /**
     * Determine whether the user can view any activity logs (admin audit log).
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-activity-log');
    }

    /**
     * Determine whether the user can view the activity log entry.
     */
    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('view-activity-log');
    }
}
