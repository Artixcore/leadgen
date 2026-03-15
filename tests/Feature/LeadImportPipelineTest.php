<?php

namespace Tests\Feature;

use App\Jobs\SyncLeadSourceJob;
use App\Models\LeadImportRow;
use App\Models\LeadImportRun;
use App\Models\LeadSource;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadImportPipelineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_sync_job_creates_run_and_rows_when_source_has_sample_data(): void
    {
        $source = LeadSource::factory()->create([
            'config' => [
                'sample_rows' => [
                    ['email' => 'a@example.com', 'full_name' => 'Alice', 'company_name' => 'Acme'],
                    ['email' => 'b@example.com', 'full_name' => 'Bob', 'company_name' => 'Beta'],
                ],
            ],
        ]);

        SyncLeadSourceJob::dispatchSync($source);

        $run = LeadImportRun::where('lead_source_id', $source->id)->first();
        $this->assertNotNull($run);
        $rows = LeadImportRow::where('lead_import_run_id', $run->id)->orderBy('row_index')->get();
        $this->assertCount(2, $rows);
        $this->assertSame('a@example.com', $rows[0]->raw_data['email'] ?? null);
    }

    public function test_full_pipeline_creates_lead_from_valid_row(): void
    {
        $source = LeadSource::factory()->create([
            'validation_rules' => ['required' => ['email', 'company_name'], 'email_format' => true],
            'config' => [
                'sample_rows' => [
                    ['email' => 'new@example.com', 'full_name' => 'New User', 'company_name' => 'NewCo'],
                ],
            ],
        ]);
        $this->assertDatabaseCount('leads', 0);

        SyncLeadSourceJob::dispatchSync($source);

        $this->assertDatabaseHas('leads', ['email' => 'new@example.com', 'company_name' => 'NewCo']);
    }
}
