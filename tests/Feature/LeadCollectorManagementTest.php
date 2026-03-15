<?php

namespace Tests\Feature;

use App\CollectorStatus;
use App\CollectorType;
use App\Models\LeadCollector;
use App\Models\LeadSource;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadCollectorManagementTest extends TestCase
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

        return $user;
    }

    public function test_admin_with_permission_can_view_lead_collectors_index(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-collectors');

        $response = $this->actingAs($admin)->get(route('admin.lead-collectors.index'));

        $response->assertOk();
        $response->assertSee(__('Lead Collectors'));
    }

    public function test_admin_can_create_lead_collector_with_existing_source(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-collectors');
        $source = LeadSource::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.lead-collectors.store'), [
            'name' => 'Test Google Maps Collector',
            'type' => CollectorType::GoogleMaps->value,
            'status' => CollectorStatus::Active->value,
            'schedule' => '0 */6 * * *',
            'config' => '{}',
            'lead_source_id' => $source->id,
        ]);

        $response->assertRedirect(route('admin.lead-collectors.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('lead_collectors', ['name' => 'Test Google Maps Collector', 'lead_source_id' => $source->id]);
    }

    public function test_admin_can_create_lead_collector_with_new_source(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-collectors');

        $response = $this->actingAs($admin)->post(route('admin.lead-collectors.store'), [
            'name' => 'New Directory Collector',
            'type' => CollectorType::Directory->value,
            'status' => CollectorStatus::Active->value,
            'schedule' => null,
            'config' => '{}',
            'create_lead_source' => '1',
        ]);

        $response->assertRedirect(route('admin.lead-collectors.index'));
        $this->assertDatabaseHas('lead_collectors', ['name' => 'New Directory Collector']);
        $collector = LeadCollector::where('name', 'New Directory Collector')->first();
        $this->assertNotNull($collector->lead_source_id);
        $this->assertDatabaseHas('lead_sources', ['id' => $collector->lead_source_id]);
    }

    public function test_admin_can_edit_and_update_lead_collector(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-collectors');
        $source = LeadSource::factory()->create();
        $collector = LeadCollector::factory()->create(['name' => 'Original', 'lead_source_id' => $source->id]);

        $response = $this->actingAs($admin)->put(route('admin.lead-collectors.update', $collector), [
            'name' => 'Updated Collector',
            'type' => $collector->type->value,
            'status' => CollectorStatus::Paused->value,
            'schedule' => '0 0 * * *',
            'config' => '{}',
            'lead_source_id' => $source->id,
        ]);

        $response->assertRedirect(route('admin.lead-collectors.show', $collector));
        $collector->refresh();
        $this->assertSame('Updated Collector', $collector->name);
        $this->assertSame(CollectorStatus::Paused, $collector->status);
    }

    public function test_admin_can_view_lead_collector_show(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-collectors');
        $collector = LeadCollector::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.lead-collectors.show', $collector));

        $response->assertOk();
        $response->assertSee($collector->name);
    }

    public function test_admin_can_trigger_run_now(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-collectors');
        $collector = LeadCollector::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.lead-collectors.run', $collector));

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_user_without_manage_lead_collectors_cannot_access_index(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.lead-collectors.index'));

        $response->assertForbidden();
    }
}
