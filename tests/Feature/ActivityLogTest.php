<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_log_can_be_created_with_user_and_subject(): void
    {
        $user = User::factory()->create();
        $lead = Lead::factory()->create();

        $log = ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'viewed',
            'subject_type' => Lead::class,
            'subject_id' => $lead->id,
            'properties' => ['ip' => '127.0.0.1'],
        ]);

        $this->assertDatabaseHas('activity_logs', ['id' => $log->id, 'action' => 'viewed']);
        $this->assertSame($user->id, $log->user->id);
        $this->assertTrue($log->subject->is($lead));
    }
}
