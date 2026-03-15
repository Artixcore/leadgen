<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_guest_cannot_access_onboarding(): void
    {
        $response = $this->get(route('onboarding'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_without_onboarding_completion_is_redirected_to_onboarding_when_accessing_dashboard(): void
    {
        $user = User::factory()->create(['onboarding_completed_at' => null]);
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('onboarding'));
    }

    public function test_user_can_complete_welcome_step_and_see_profile_step(): void
    {
        $user = User::factory()->create(['onboarding_completed_at' => null]);
        $user->assignRole('user');

        $response = $this->actingAs($user)->post(route('onboarding.store'));

        $response->assertRedirect(route('onboarding'));
        $this->assertNull($user->fresh()->onboarding_completed_at);
    }

    public function test_user_can_skip_profile_step_and_reach_dashboard(): void
    {
        $user = User::factory()->create(['onboarding_completed_at' => null]);
        $user->assignRole('user');
        $this->actingAs($user)->post(route('onboarding.store'));

        $response = $this->actingAs($user)->post(route('onboarding.skip'));

        $response->assertRedirect(route('dashboard'));
        $this->assertNotNull($user->fresh()->onboarding_completed_at);
    }

    public function test_user_can_save_profile_step_and_reach_dashboard(): void
    {
        $user = User::factory()->create(['onboarding_completed_at' => null]);
        $user->assignRole('user');
        $this->actingAs($user)->post(route('onboarding.store'));

        $response = $this->actingAs($user)->post(route('onboarding.profile'), [
            'company_name' => 'Acme Inc',
            'phone' => '1234567890',
            'timezone' => 'America/New_York',
        ]);

        $response->assertRedirect(route('dashboard'));
        $user->refresh();
        $this->assertNotNull($user->onboarding_completed_at);
        $this->assertSame('Acme Inc', $user->company_name);
    }
}
