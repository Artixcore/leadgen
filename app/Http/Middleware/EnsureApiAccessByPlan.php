<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiAccessByPlan
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $this->subscriptionService->userCanUseApi($user)) {
            if ($request->expectsJson()) {
                abort(403, __('Your plan does not include API access. Please upgrade.'));
            }

            return redirect()->route('billing.plans')
                ->with('error', __('Your plan does not include API access. Please upgrade.'));
        }

        return $next($request);
    }
}
