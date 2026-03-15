<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Get started with limited access.',
                'stripe_product_id' => null,
                'stripe_price_id_monthly' => null,
                'stripe_price_id_yearly' => null,
                'leads_per_month' => 50,
                'exports_per_month' => 1,
                'saved_lists_count' => 1,
                'team_members_limit' => 0,
                'api_access' => false,
                'advanced_filters' => false,
                'trial_days' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'For individuals and small projects.',
                'stripe_product_id' => null,
                'stripe_price_id_monthly' => null,
                'stripe_price_id_yearly' => null,
                'leads_per_month' => 500,
                'exports_per_month' => 10,
                'saved_lists_count' => 5,
                'team_members_limit' => 0,
                'api_access' => false,
                'advanced_filters' => false,
                'trial_days' => 14,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For growing teams and power users.',
                'stripe_product_id' => null,
                'stripe_price_id_monthly' => null,
                'stripe_price_id_yearly' => null,
                'leads_per_month' => 5000,
                'exports_per_month' => 50,
                'saved_lists_count' => 20,
                'team_members_limit' => 5,
                'api_access' => true,
                'advanced_filters' => true,
                'trial_days' => 14,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Unlimited access for large organizations.',
                'stripe_product_id' => null,
                'stripe_price_id_monthly' => null,
                'stripe_price_id_yearly' => null,
                'leads_per_month' => null,
                'exports_per_month' => 500,
                'saved_lists_count' => 100,
                'team_members_limit' => 50,
                'api_access' => true,
                'advanced_filters' => true,
                'trial_days' => 14,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
