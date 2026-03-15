<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityLogService
{
    /**
     * Log an admin action for audit trail.
     *
     * @param  array<string, mixed>  $properties
     */
    public function log(User $user, string $action, ?Model $subject = null, array $properties = [], ?Request $request = null): ActivityLog
    {
        $request = $request ?? request();

        return ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'subject_type' => $subject ? $subject->getMorphClass() : null,
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit($request->userAgent(), 512),
        ]);
    }
}
