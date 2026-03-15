<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(PlanSeeder::class);
    }

    public function test_guest_cannot_access_billing_pages(): void
    {
        $this->get(route('billing.index'))->assertRedirect(route('login'));
        $this->get(route('billing.plans'))->assertRedirect(route('login'));
        $this->get(route('billing.invoices'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_billing_index(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('billing.index'));

        $response->assertOk();
        $response->assertSee(__('Billing'));
        $response->assertSee(__('Current Plan'));
    }

    public function test_authenticated_user_can_access_plans_page(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('billing.plans'));

        $response->assertOk();
        $response->assertSee(__('Subscription Plans'));
        $response->assertSee('Free');
        $response->assertSee('Basic');
        $response->assertSee('Pro');
        $response->assertSee('Enterprise');
    }

    public function test_authenticated_user_can_access_invoices_page(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('billing.invoices'));

        $response->assertOk();
        $response->assertSee(__('Billing History'));
    }

    public function test_user_without_onboarding_redirected_from_billing(): void
    {
        $user = User::factory()->create(['onboarding_completed_at' => null]);
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('billing.index'));

        $response->assertRedirect(route('onboarding'));
    }

    public function test_checkout_free_plan_redirects_with_error(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');
        $freePlan = Plan::where('slug', 'free')->first();

        $response = $this->actingAs($user)->get(route('billing.checkout', ['plan' => $freePlan, 'interval' => 'monthly']));

        $response->assertRedirect(route('billing.plans'));
        $response->assertSessionHas('error');
    }

    public function test_checkout_paid_plan_without_stripe_price_redirects(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');
        $basicPlan = Plan::where('slug', 'basic')->first();
        $this->assertNull($basicPlan->stripe_price_id_monthly);

        $response = $this->actingAs($user)->get(route('billing.checkout', ['plan' => $basicPlan, 'interval' => 'monthly']));

        $response->assertRedirect(route('billing.plans'));
        $response->assertSessionHas('error');
    }
}
