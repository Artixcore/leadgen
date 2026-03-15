<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutPlanRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BillingController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $subscription = $user->subscription();
        $plan = $user->currentPlan();

        return view('billing.index', [
            'user' => $user,
            'subscription' => $subscription,
            'plan' => $plan,
        ]);
    }

    public function plans(Request $request): View
    {
        $plans = Cache::remember('billing.plans', now()->addMinutes(10), function () {
            return Plan::where('is_active', true)->orderBy('sort_order')->get();
        });
        $currentPlan = $request->user()->currentPlan();

        return view('billing.plans', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
        ]);
    }

    public function checkout(CheckoutPlanRequest $request, Plan $plan): RedirectResponse
    {
        if ($plan->isFree()) {
            return redirect()->route('billing.plans')
                ->with('error', __('You are already on the free plan.'));
        }

        $priceId = $plan->stripePriceIdForInterval($request->validated('interval'));
        if (! $priceId) {
            return redirect()->route('billing.plans')
                ->with('error', __('This plan is not available for the selected billing cycle.'));
        }

        $builder = $request->user()
            ->newSubscription('default', $priceId);

        if ($plan->trial_days) {
            $builder->trialDays($plan->trial_days);
        }

        return $builder->checkout([
            'success_url' => route('billing.index').'?checkout=success&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.plans').'?checkout=cancelled',
        ])->redirect();
    }

    public function portal(Request $request): RedirectResponse
    {
        return $request->user()->redirectToBillingPortal(route('billing.index'));
    }

    public function invoices(Request $request): View
    {
        $invoices = $request->user()->invoices();

        return view('billing.invoices', [
            'invoices' => $invoices,
        ]);
    }

    public function downloadInvoice(Request $request, string $id): StreamedResponse
    {
        return $request->user()->downloadInvoice($id, [
            'vendor' => config('app.name'),
            'product' => __('Subscription'),
        ]);
    }
}
