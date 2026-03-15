<?php

namespace App\Listeners;

use App\Models\Plan;
use App\Models\Subscription;
use Laravel\Cashier\Events\WebhookHandled;

class SyncSubscriptionPlanIdFromWebhook
{
    public function handle(WebhookHandled $event): void
    {
        $type = $event->payload['type'] ?? null;
        if (! in_array($type, ['customer.subscription.created', 'customer.subscription.updated'], true)) {
            return;
        }

        $data = $event->payload['data']['object'] ?? [];
        $stripeSubscriptionId = $data['id'] ?? null;
        $stripePriceId = null;
        if (isset($data['items']['data'][0]['price']['id'])) {
            $stripePriceId = $data['items']['data'][0]['price']['id'];
        }
        if (! $stripeSubscriptionId || ! $stripePriceId) {
            return;
        }

        $subscription = Subscription::where('stripe_id', $stripeSubscriptionId)->first();
        if (! $subscription) {
            return;
        }

        $plan = Plan::where('stripe_price_id_monthly', $stripePriceId)
            ->orWhere('stripe_price_id_yearly', $stripePriceId)
            ->first();
        if ($plan && $subscription->plan_id !== $plan->id) {
            $subscription->update(['plan_id' => $plan->id]);
        }
    }
}
