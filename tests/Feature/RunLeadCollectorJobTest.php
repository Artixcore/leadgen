<?php

namespace Tests\Feature;

use App\Jobs\NormalizeRawLeadRecordsJob;
use App\Jobs\RunLeadCollectorJob;
use App\Models\LeadCollector;
use App\Models\LeadCollectorRun;
use App\Models\RawLeadRecord;
use App\Services\LeadCollectors\CollectorDriverResolver;
use App\Services\LeadCollectors\Drivers\LeadCollectorDriverInterface;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RunLeadCollectorJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_job_creates_collector_run_and_dispatches_normalize_when_driver_returns_empty(): void
    {
        Queue::fake([NormalizeRawLeadRecordsJob::class]);
        $collector = LeadCollector::factory()->create();

        RunLeadCollectorJob::dispatchSync($collector);

        $run = LeadCollectorRun::where('lead_collector_id', $collector->id)->first();
        $this->assertNotNull($run);
        $this->assertSame(0, $run->total_found);
        $this->assertTrue($run->status->value === 'completed');
        Queue::assertPushed(NormalizeRawLeadRecordsJob::class, fn ($job) => $job->run->id === $run->id);
    }

    public function test_job_creates_run_and_raw_records_when_driver_returns_data(): void
    {
        $collector = LeadCollector::factory()->create();
        $driver = new class implements LeadCollectorDriverInterface
        {
            public function collect($collector): array
            {
                return [
                    ['email' => 'test@example.com', 'company_name' => 'TestCo', 'website' => 'https://test.com'],
                ];
            }
        };
        $this->app->instance(CollectorDriverResolver::class, new CollectorDriverResolver([
            $collector->type->value => $driver,
        ]));

        RunLeadCollectorJob::dispatchSync($collector);

        $run = LeadCollectorRun::where('lead_collector_id', $collector->id)->first();
        $this->assertNotNull($run);
        $this->assertSame(1, $run->total_found);
        $records = RawLeadRecord::where('lead_collector_run_id', $run->id)->get();
        $this->assertCount(1, $records);
        $this->assertSame('test@example.com', $records[0]->email);
        $this->assertSame('TestCo', $records[0]->company_name);
    }
}
