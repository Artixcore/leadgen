<?php

namespace Tests\Feature;

use App\ImportRunStatus;
use App\Models\LeadImportRun;
use App\Models\LeadSource;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadImportRunTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    protected function admin(): User
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('admin');
        $user->givePermissionTo('manage-lead-sources');

        return $user;
    }

    public function test_admin_can_view_import_runs_index(): void
    {
        $admin = $this->admin();
        LeadImportRun::factory()->count(2)->create();

        $response = $this->actingAs($admin)->get(route('admin.import-runs.index'));

        $response->assertOk();
        $response->assertSee(__('Import runs'));
    }

    public function test_admin_can_view_import_run_show(): void
    {
        $admin = $this->admin();
        $source = LeadSource::factory()->create();
        $run = LeadImportRun::create([
            'lead_source_id' => $source->id,
            'status' => ImportRunStatus::Completed,
            'started_at' => now(),
            'completed_at' => now(),
            'stats' => ['total' => 0, 'imported' => 0],
        ]);

        $response = $this->actingAs($admin)->get(route('admin.import-runs.show', $run));

        $response->assertOk();
        $response->assertSee((string) $run->id);
    }
}
