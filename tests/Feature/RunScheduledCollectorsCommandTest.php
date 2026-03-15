<?php

namespace Tests\Feature;

use App\CollectorStatus;
use App\Jobs\RunCollectorJob;
use App\Models\LeadCollector;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RunScheduledCollectorsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_command_dispatches_job_for_due_collectors_only(): void
    {
        Queue::fake([RunCollectorJob::class]);

        $due = LeadCollector::factory()->create([
            'status' => CollectorStatus::Active,
            'schedule' => '* * * * *',
        ]);
        $notDue = LeadCollector::factory()->create([
            'status' => CollectorStatus::Active,
            'schedule' => '0 0 1 1 *',
        ]);
        $inactive = LeadCollector::factory()->create([
            'status' => CollectorStatus::Paused,
            'schedule' => '* * * * *',
        ]);

        $this->artisan('collectors:run-scheduled')->assertSuccessful();

        Queue::assertPushed(RunCollectorJob::class, fn ($job) => $job->collector->id === $due->id);
        Queue::assertNotPushed(RunCollectorJob::class, fn ($job) => $job->collector->id === $notDue->id);
        Queue::assertNotPushed(RunCollectorJob::class, fn ($job) => $job->collector->id === $inactive->id);
    }

    public function test_command_dispatches_nothing_when_no_collectors_due(): void
    {
        Queue::fake([RunCollectorJob::class]);
        LeadCollector::factory()->create([
            'status' => CollectorStatus::Active,
            'schedule' => '0 0 1 1 *',
        ]);

        $this->artisan('collectors:run-scheduled')->assertSuccessful();

        Queue::assertNotPushed(RunCollectorJob::class);
    }
}
