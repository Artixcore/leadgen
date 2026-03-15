<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionFeature
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     * @param  string  $feature  One of: api, advanced_filters
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();
        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['message' => __('Unauthenticated.')], 401)
                : redirect()->guest(route('login'));
        }

        $allowed = match ($feature) {
            'api' => $this->subscriptionService->userCanUseApi($user),
            'advanced_filters' => $this->subscriptionService->userCanUseAdvancedFilters($user),
            default => false,
        };

        if (! $allowed) {
            if ($request->expectsJson()) {
                abort(403, __('Your plan does not include this feature. Please upgrade.'));
            }

            return redirect()->route('billing.plans')
                ->with('error', __('Your plan does not include this feature. Please upgrade.'));
        }

        return $next($request);
    }
}
