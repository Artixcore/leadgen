<?php

namespace Tests\Feature;

use App\CollectorType;
use App\Contracts\CollectorDriverInterface;
use App\Jobs\RunCollectorJob;
use App\Jobs\ValidateImportRowsJob;
use App\Models\LeadCollector;
use App\Models\LeadImportRow;
use App\Models\LeadImportRun;
use App\Services\CollectorDriverResolver;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RunCollectorJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_job_creates_run_and_dispatches_validate_when_driver_returns_empty(): void
    {
        Queue::fake([ValidateImportRowsJob::class]);
        $collector = LeadCollector::factory()->create();

        RunCollectorJob::dispatchSync($collector);

        $run = LeadImportRun::where('lead_collector_id', $collector->id)->first();
        $this->assertNotNull($run);
        $this->assertSame($collector->lead_source_id, $run->lead_source_id);
        Queue::assertPushed(ValidateImportRowsJob::class, fn ($job) => $job->run->id === $run->id);
    }

    public function test_job_creates_run_and_rows_when_driver_returns_data(): void
    {
        $collector = LeadCollector::factory()->create();
        $driver = new class implements CollectorDriverInterface
        {
            public function fetch($collector): array
            {
                return [
                    ['email' => 'collector@example.com', 'full_name' => 'Collector Lead', 'company_name' => 'CollectorCo'],
                ];
            }
        };
        $this->app->instance(CollectorDriverResolver::class, new CollectorDriverResolver([
            $collector->type->value => $driver,
            CollectorType::GoogleMaps->value => $driver,
            CollectorType::Directory->value => $driver,
            CollectorType::WebsiteScan->value => $driver,
            CollectorType::ApiConnector->value => $driver,
            CollectorType::CsvImport->value => $driver,
        ]));

        RunCollectorJob::dispatchSync($collector);

        $run = LeadImportRun::where('lead_collector_id', $collector->id)->first();
        $this->assertNotNull($run);
        $rows = LeadImportRow::where('lead_import_run_id', $run->id)->orderBy('row_index')->get();
        $this->assertCount(1, $rows);
        $this->assertSame('collector@example.com', $rows[0]->raw_data['email'] ?? null);
    }
}
