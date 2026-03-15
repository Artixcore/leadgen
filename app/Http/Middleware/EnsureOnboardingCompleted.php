<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() &&
            ! $request->user()->hasCompletedOnboarding() &&
            ! $request->routeIs('onboarding', 'onboarding.*', 'account.suspended', 'logout')) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}
