<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
    }

    public function test_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_user_can_access_dashboard(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
