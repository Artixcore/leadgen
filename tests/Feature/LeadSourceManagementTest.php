<?php

namespace Tests\Feature;

use App\LeadSourceStatus;
use App\LeadSourceType;
use App\Models\LeadSource;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadSourceManagementTest extends TestCase
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

    public function test_admin_with_permission_can_view_lead_sources_index(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-sources');

        $response = $this->actingAs($admin)->get(route('admin.lead-sources.index'));

        $response->assertOk();
        $response->assertSee(__('Lead Sources'));
    }

    public function test_admin_can_create_lead_source(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-sources');

        $response = $this->actingAs($admin)->post(route('admin.lead-sources.store'), [
            'name' => 'Test API Source',
            'type' => LeadSourceType::Api->value,
            'status' => LeadSourceStatus::Active->value,
            'reliability_score' => 80,
            'import_frequency' => '0 */6 * * *',
        ]);

        $response->assertRedirect(route('admin.lead-sources.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('lead_sources', ['name' => 'Test API Source']);
    }

    public function test_admin_can_edit_and_update_lead_source(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-sources');
        $source = LeadSource::factory()->create(['name' => 'Original']);

        $response = $this->actingAs($admin)->patch(route('admin.lead-sources.update', $source), [
            'name' => 'Updated Name',
            'type' => $source->type->value,
            'status' => LeadSourceStatus::Inactive->value,
            'reliability_score' => 70,
        ]);

        $response->assertRedirect(route('admin.lead-sources.show', $source));
        $source->refresh();
        $this->assertSame('Updated Name', $source->name);
        $this->assertSame(LeadSourceStatus::Inactive, $source->status);
    }

    public function test_admin_can_pause_lead_source(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-sources');
        $source = LeadSource::factory()->create(['status' => LeadSourceStatus::Active]);

        $response = $this->actingAs($admin)->post(route('admin.lead-sources.pause', $source));

        $response->assertRedirect();
        $source->refresh();
        $this->assertSame(LeadSourceStatus::Inactive, $source->status);
    }

    public function test_admin_can_trigger_sync(): void
    {
        $admin = $this->admin();
        $admin->givePermissionTo('manage-lead-sources');
        $source = LeadSource::factory()->create(['status' => LeadSourceStatus::Active]);

        $response = $this->actingAs($admin)->post(route('admin.lead-sources.sync', $source));

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_user_without_manage_lead_sources_cannot_access_index(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.lead-sources.index'));

        $response->assertForbidden();
    }
}
