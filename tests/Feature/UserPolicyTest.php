<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_admin_can_view_any_users(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->assertTrue($admin->can('viewAny', User::class));
    }

    public function test_user_cannot_view_any_users(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->assertFalse($user->can('viewAny', User::class));
    }

    public function test_admin_can_update_any_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $other = User::factory()->create();

        $this->assertTrue($admin->can('update', $other));
    }

    public function test_user_can_update_self(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->assertTrue($user->can('update', $user));
    }

    public function test_user_cannot_update_other_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $other = User::factory()->create();

        $this->assertFalse($user->can('update', $other));
    }

    public function test_admin_can_delete_any_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $other = User::factory()->create();

        $this->assertTrue($admin->can('delete', $other));
    }

    public function test_user_can_delete_self(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->assertTrue($user->can('delete', $user));
    }

    public function test_user_cannot_delete_other_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $other = User::factory()->create();

        $this->assertFalse($user->can('delete', $other));
    }
}
