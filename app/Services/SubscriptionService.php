<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\PlanUsage;
use App\Models\User;

class SubscriptionService
{
    public function getPlanForUser(User $user): Plan
    {
        $subscription = $user->subscription();

        if ($subscription && $subscription->plan_id) {
            $subscription->loadMissing('plan');
            $plan = $subscription->plan;
            if ($plan) {
                return $plan;
            }
        }

        if ($subscription && $subscription->stripe_price) {
            $plan = Plan::where('stripe_price_id_monthly', $subscription->stripe_price)
                ->orWhere('stripe_price_id_yearly', $subscription->stripe_price)
                ->first();
            if ($plan) {
                return $plan;
            }
        }

        return Plan::where('slug', 'free')->firstOrFail();
    }

    public function getUsageForCurrentPeriod(User $user): PlanUsage
    {
        $period = PlanUsage::currentPeriod();

        return PlanUsage::firstOrCreate(
            [
                'user_id' => $user->id,
                'period' => $period,
            ],
            [
                'leads_count' => 0,
                'exports_count' => 0,
            ]
        );
    }

    public function userCanAccessLeads(User $user, int $count = 1): bool
    {
        $plan = $this->getPlanForUser($user);
        $limit = $plan->leadsLimit();
        if ($limit === null) {
            return true;
        }
        $usage = $this->getUsageForCurrentPeriod($user);

        return ($usage->leads_count + $count) <= $limit;
    }

    public function userCanExport(User $user): bool
    {
        $plan = $this->getPlanForUser($user);
        $usage = $this->getUsageForCurrentPeriod($user);

        return $usage->exports_count < $plan->exportsLimit();
    }

    public function userCanCreateList(User $user, int $currentListCount): bool
    {
        $plan = $this->getPlanForUser($user);

        return $currentListCount < $plan->savedListsLimit();
    }

    public function userCanUseApi(User $user): bool
    {
        return $this->getPlanForUser($user)->hasApiAccess();
    }

    public function userCanUseAdvancedFilters(User $user): bool
    {
        return $this->getPlanForUser($user)->hasAdvancedFilters();
    }

    public function userCanInviteTeamMember(User $user, int $currentTeamCount): bool
    {
        $plan = $this->getPlanForUser($user);

        return $plan->hasTeamMembers() && $currentTeamCount < $plan->teamMembersLimit();
    }

    public function incrementLeadsCount(User $user, int $count = 1): void
    {
        $usage = $this->getUsageForCurrentPeriod($user);
        $usage->increment('leads_count', $count);
    }

    public function incrementExportsCount(User $user): void
    {
        $usage = $this->getUsageForCurrentPeriod($user);
        $usage->increment('exports_count');
    }
}
