<?php

namespace Tests\Unit;

use App\Models\Plan;
use App\Models\PlanUsage;
use App\Models\User;
use App\Services\SubscriptionService;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SubscriptionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(PlanSeeder::class);
        $this->service = app(SubscriptionService::class);
    }

    public function test_get_plan_for_user_without_subscription_returns_free_plan(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $plan = $this->service->getPlanForUser($user);

        $this->assertInstanceOf(Plan::class, $plan);
        $this->assertSame('free', $plan->slug);
    }

    public function test_user_can_export_respects_plan_limit(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');
        $freePlan = Plan::where('slug', 'free')->first();
        $this->assertSame(1, $freePlan->exports_per_month);

        $this->assertTrue($this->service->userCanExport($user));

        $usage = $this->service->getUsageForCurrentPeriod($user);
        $usage->update(['exports_count' => 1]);

        $this->assertFalse($this->service->userCanExport($user));
    }

    public function test_user_can_access_leads_respects_plan_limit(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');
        $freePlan = Plan::where('slug', 'free')->first();
        $this->assertSame(50, $freePlan->leads_per_month);

        $this->assertTrue($this->service->userCanAccessLeads($user, 1));
        $this->assertTrue($this->service->userCanAccessLeads($user, 50));

        $usage = $this->service->getUsageForCurrentPeriod($user);
        $usage->update(['leads_count' => 50]);

        $this->assertFalse($this->service->userCanAccessLeads($user, 1));
    }

    public function test_user_can_use_api_reflects_plan(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $this->assertFalse($this->service->userCanUseApi($user));

        $proPlan = Plan::where('slug', 'pro')->first();
        $this->assertTrue($proPlan->api_access);
    }

    public function test_user_can_create_list_respects_plan_limit(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');
        $freePlan = Plan::where('slug', 'free')->first();
        $this->assertSame(1, $freePlan->saved_lists_count);

        $this->assertTrue($this->service->userCanCreateList($user, 0));
        $this->assertFalse($this->service->userCanCreateList($user, 1));
    }

    public function test_increment_exports_count_creates_usage_and_increments(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        $this->service->incrementExportsCount($user);

        $usage = PlanUsage::where('user_id', $user->id)->where('period', PlanUsage::currentPeriod())->first();
        $this->assertNotNull($usage);
        $this->assertSame(1, $usage->exports_count);
    }
}
