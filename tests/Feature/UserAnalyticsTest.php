<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_user_can_view_analytics_page(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('analytics.index'));

        $response->assertOk();
        $response->assertSee(__('My Analytics'));
        $response->assertSee(__('Leads viewed'));
        $response->assertSee(__('Leads saved'));
        $response->assertSee(__('Leads exported'));
    }

    public function test_guest_cannot_access_analytics(): void
    {
        $this->get(route('analytics.index'))->assertRedirect(route('login'));
    }

    public function test_admin_cannot_access_user_analytics_without_user_role(): void
    {
        $admin = User::factory()->completedOnboarding()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('analytics.index'));

        $response->assertForbidden();
    }
}
