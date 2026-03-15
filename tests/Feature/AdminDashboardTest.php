<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_admin_can_view_dashboard_with_stats(): void
    {
        $admin = User::factory()->completedOnboarding()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(__('Admin Dashboard'));
        $response->assertSee(__('Total users'));
        $response->assertSee(__('Total leads'));
        $response->assertSee(__('New leads today'));
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }
}
