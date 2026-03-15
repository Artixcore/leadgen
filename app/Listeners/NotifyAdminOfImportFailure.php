<?php

namespace App\Listeners;

use App\Events\ImportRunFailed;
use App\Models\User;
use App\Notifications\ImportRunFailedNotification;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfImportFailure
{
    public function handle(ImportRunFailed $event): void
    {
        $admins = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'admin'))
            ->get();
        Notification::send($admins, new ImportRunFailedNotification($event->run));
    }
}
