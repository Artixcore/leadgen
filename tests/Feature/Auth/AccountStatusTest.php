<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_suspended_user_is_redirected_to_account_suspended_when_accessing_dashboard(): void
    {
        $user = User::factory()->suspended()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('account.suspended'));
    }

    public function test_suspended_user_cannot_log_in(): void
    {
        $user = User::factory()->suspended()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_update_user_status(): void
    {
        $admin = User::factory()->completedOnboarding()->create();
        $admin->assignRole('admin');
        $target = User::factory()->completedOnboarding()->create();
        $target->assignRole('user');

        $response = $this->actingAs($admin)
            ->patch(route('admin.users.update', $target), [
                'status' => 'suspended',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSame('suspended', $target->fresh()->status->value);
    }
}
