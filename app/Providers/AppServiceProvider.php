<?php

namespace App\Providers;

use App\Events\ImportRunFailed;
use App\Listeners\NotifyAdminOfImportFailure;
use App\Listeners\SyncSubscriptionPlanIdFromWebhook;
use App\Models\Lead;
use App\Models\Subscription;
use App\Observers\LeadObserver;
use Illuminate\Support\Facades\Event;
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

        Event::listen(WebhookHandled::class, SyncSubscriptionPlanIdFromWebhook::class);
        Event::listen(ImportRunFailed::class, NotifyAdminOfImportFailure::class);
    }
}
