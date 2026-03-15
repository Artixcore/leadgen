<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BillingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscription = $user->subscription();
        $subscriptionService = app(SubscriptionService::class);
        $plan = $subscriptionService->getPlanForUser($user);
        $usage = $subscriptionService->getUsageForCurrentPeriod($user);

        return response()->json([
            'subscription' => $subscription ? new SubscriptionResource($subscription->load('plan')) : null,
            'plan' => new PlanResource($plan),
            'usage' => [
                'period' => $usage->period,
                'leads_count' => $usage->leads_count,
                'exports_count' => $usage->exports_count,
            ],
        ]);
    }

    public function plans(Request $request): JsonResponse
    {
        $plans = Cache::remember('billing.plans', now()->addMinutes(10), function () {
            return Plan::where('is_active', true)->orderBy('sort_order')->get();
        });
        $currentPlan = $request->user()->currentPlan();

        return response()->json([
            'plans' => PlanResource::collection($plans),
            'current_plan' => new PlanResource($currentPlan),
        ]);
    }

    public function invoices(Request $request): JsonResponse
    {
        $invoices = $request->user()->invoices();

        $data = $invoices->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'date' => $invoice->date()->toIso8601String(),
                'total' => $invoice->total(),
                'download_url' => route('api.billing.invoices.download', ['id' => $invoice->id]),
            ];
        });

        return response()->json(['invoices' => $data]);
    }

    public function downloadInvoice(Request $request, string $id): StreamedResponse|JsonResponse
    {
        try {
            return $request->user()->downloadInvoice($id, [
                'vendor' => config('app.name'),
                'product' => __('Subscription'),
            ]);
        } catch (\Throwable) {
            return response()->json(['message' => __('Invoice not found.')], 404);
        }
    }
}
