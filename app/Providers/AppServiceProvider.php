<?php

namespace App\Providers;

use App\Collectors\Drivers\ApiCollectorDriver;
use App\Collectors\Drivers\CsvUploadCollectorDriver;
use App\Collectors\Drivers\DirectoryCollectorDriver;
use App\Collectors\Drivers\GoogleMapsCollectorDriver;
use App\Collectors\Drivers\WebsiteCollectorDriver;
use App\CollectorType;
use App\Events\ImportRunFailed;
use App\Listeners\NotifyAdminOfImportFailure;
use App\Listeners\StorePaymentFromInvoicePaidWebhook;
use App\Listeners\SyncSubscriptionPlanIdFromWebhook;
use App\Models\Lead;
use App\Models\LeadSearchQuery;
use App\Models\SavedLeadSearch;
use App\Models\Subscription;
use App\Observers\LeadObserver;
use App\Policies\LeadSearchPolicy;
use App\Services\CollectorDriverResolver;
use App\Services\LeadCollectors\CollectorDriverResolver as LeadCollectorsDriverResolver;
use App\Services\LeadCollectors\Drivers\ApiCollectorDriver as LeadCollectorsApiDriver;
use App\Services\LeadCollectors\Drivers\CsvCollectorDriver;
use App\Services\LeadCollectors\Drivers\DirectoryCollectorDriver as LeadCollectorsDirectoryDriver;
use App\Services\LeadCollectors\Drivers\GoogleMapsCollectorDriver as LeadCollectorsGoogleMapsDriver;
use App\Services\LeadCollectors\Drivers\WebsiteScanCollectorDriver;
use App\Services\LeadSearch\LeadSearchProviderManager;
use App\Services\LeadSearch\Providers\ApiLeadSearchProvider;
use App\Services\LeadSearch\Providers\CollectorSearchProvider;
use App\Services\LeadSearch\Providers\DirectorySearchProvider;
use App\Services\LeadSearch\Providers\GoogleMapsSearchProvider;
use App\Services\LeadSearch\Providers\ImportedDataSearchProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
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
        $this->app->singleton(CollectorDriverResolver::class, function () {
            return new CollectorDriverResolver([
                CollectorType::GoogleMaps->value => new GoogleMapsCollectorDriver,
                CollectorType::Directory->value => new DirectoryCollectorDriver,
                CollectorType::WebsiteScan->value => new WebsiteCollectorDriver,
                CollectorType::ApiConnector->value => new ApiCollectorDriver,
                CollectorType::CsvImport->value => new CsvUploadCollectorDriver,
            ]);
        });

        $this->app->singleton(LeadCollectorsDriverResolver::class, function () {
            return new LeadCollectorsDriverResolver([
                CollectorType::GoogleMaps->value => new LeadCollectorsGoogleMapsDriver,
                CollectorType::Directory->value => new LeadCollectorsDirectoryDriver,
                CollectorType::WebsiteScan->value => new WebsiteScanCollectorDriver,
                CollectorType::ApiConnector->value => new LeadCollectorsApiDriver,
                CollectorType::CsvImport->value => new CsvCollectorDriver,
            ]);
        });

        $this->app->singleton(LeadSearchProviderManager::class, function () {
            return new LeadSearchProviderManager([
                'collector' => new CollectorSearchProvider,
                'google_maps' => new GoogleMapsSearchProvider,
                'directory' => new DirectorySearchProvider,
                'api' => new ApiLeadSearchProvider,
                'imported' => new ImportedDataSearchProvider,
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::$subscriptionModel = Subscription::class;
        Lead::observe(LeadObserver::class);

        Gate::policy(LeadSearchQuery::class, LeadSearchPolicy::class);
        Gate::policy(SavedLeadSearch::class, LeadSearchPolicy::class);

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
