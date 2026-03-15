<?php

namespace App\Providers;

use App\Events\ImportRunFailed;
use App\Listeners\NotifyAdminOfImportFailure;
use App\Listeners\StorePaymentFromInvoicePaidWebhook;
use App\Listeners\SyncSubscriptionPlanIdFromWebhook;
use App\Models\Lead;
use App\Models\Subscription;
use App\Observers\LeadObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookHandled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::$subscriptionModel = Subscription::class;
        Lead::observe(LeadObserver::class);

        $this->configureRateLimiting();

        Event::listen(WebhookHandled::class, SyncSubscriptionPlanIdFromWebhook::class);
        Event::listen(WebhookHandled::class, StorePaymentFromInvoicePaidWebhook::class);
        Event::listen(ImportRunFailed::class, NotifyAdminOfImportFailure::class);
    }

    /**
     * Configure rate limiting for web, export, admin, and API.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('export', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(180)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('api-auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
    }
}
