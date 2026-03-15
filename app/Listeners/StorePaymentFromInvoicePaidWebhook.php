<?php

namespace App\Listeners;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Laravel\Cashier\Events\WebhookHandled;

class StorePaymentFromInvoicePaidWebhook
{
    public function handle(WebhookHandled $event): void
    {
        if (($event->payload['type'] ?? null) !== 'invoice.paid') {
            return;
        }

        $invoice = $event->payload['data']['object'] ?? [];
        $stripeInvoiceId = $invoice['id'] ?? null;
        if (! $stripeInvoiceId) {
            return;
        }

        if (Payment::where('stripe_invoice_id', $stripeInvoiceId)->exists()) {
            return;
        }

        $amountPaid = $invoice['amount_paid'] ?? 0;
        $currency = strtoupper($invoice['currency'] ?? 'usd');
        $amount = $amountPaid / 100;

        $stripeCustomerId = $invoice['customer'] ?? null;
        $user = $stripeCustomerId ? User::where('stripe_id', $stripeCustomerId)->first() : null;

        $planId = null;
        $stripeSubscriptionId = $invoice['subscription'] ?? null;
        if ($stripeSubscriptionId) {
            $subscription = Subscription::where('stripe_id', $stripeSubscriptionId)->first();
            if ($subscription && $subscription->plan_id) {
                $planId = $subscription->plan_id;
            }
        }

        $paidAt = isset($invoice['status_transitions']['paid_at'])
            ? Carbon::createFromTimestamp($invoice['status_transitions']['paid_at'])
            : now();

        Payment::create([
            'user_id' => $user?->id,
            'amount' => $amount,
            'currency' => $currency,
            'stripe_invoice_id' => $stripeInvoiceId,
            'paid_at' => $paidAt,
            'plan_id' => $planId,
        ]);

        Cache::forget('admin.dashboard.stats');
    }
}
