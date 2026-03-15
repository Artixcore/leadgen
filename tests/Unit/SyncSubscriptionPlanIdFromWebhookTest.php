<?php

namespace Tests\Unit;

use App\Listeners\SyncSubscriptionPlanIdFromWebhook;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Events\WebhookHandled;
use Tests\TestCase;

class SyncSubscriptionPlanIdFromWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(PlanSeeder::class);
    }

    public function test_listener_ignores_non_subscription_events(): void
    {
        $listener = new SyncSubscriptionPlanIdFromWebhook;
        $event = new WebhookHandled(['type' => 'invoice.paid']);

        $listener->handle($event);

        $this->assertDatabaseCount('subscriptions', 0);
    }

    public function test_listener_sets_plan_id_when_plan_matches_stripe_price(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');
        $plan = Plan::where('slug', 'basic')->first();
        $plan->update(['stripe_price_id_monthly' => 'price_test_monthly_123']);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'type' => 'default',
            'stripe_id' => 'sub_123',
            'stripe_status' => 'active',
            'stripe_price' => 'price_test_monthly_123',
            'plan_id' => null,
        ]);

        $payload = [
            'type' => 'customer.subscription.updated',
            'data' => [
                'object' => [
                    'id' => 'sub_123',
                    'items' => [
                        'data' => [
                            ['price' => ['id' => 'price_test_monthly_123']],
                        ],
                    ],
                ],
            ],
        ];
        $event = new WebhookHandled($payload);
        $listener = new SyncSubscriptionPlanIdFromWebhook;

        $listener->handle($event);

        $subscription->refresh();
        $this->assertSame($plan->id, $subscription->plan_id);
    }
}
