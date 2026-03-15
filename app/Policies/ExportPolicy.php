<?php

namespace App\Policies;

use App\Models\Export;
use App\Models\User;
use App\Services\SubscriptionService;

class ExportPolicy
{
    /**
     * Determine whether the user can create exports.
     */
    public function create(User $user): bool
    {
        if (! $user->can('export-leads')) {
            return false;
        }

        return app(SubscriptionService::class)->userCanExport($user);
    }

    /**
     * Determine whether the user can view/download the export.
     */
    public function view(User $user, Export $export): bool
    {
        return (int) $export->user_id === (int) $user->id;
    }
}
