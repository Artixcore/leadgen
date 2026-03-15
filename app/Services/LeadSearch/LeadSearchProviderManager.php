<?php

namespace App\Services\LeadSearch;

use App\Models\LeadSearchProvider as LeadSearchProviderModel;
use App\Services\LeadSearch\Providers\LeadSearchProviderInterface;
use Illuminate\Support\Facades\Cache;

class LeadSearchProviderManager
{
    /**
     * @param  array<string, LeadSearchProviderInterface>  $providers
     */
    public function __construct(
        protected array $providers = []
    ) {}

    /**
     * Get enabled providers ordered by priority (highest first).
     * Each element: ['slug' => string, 'name' => string, 'instance' => LeadSearchProviderInterface]
     *
     * @return array<int, array{slug: string, name: string, instance: LeadSearchProviderInterface}>
     */
    public function enabledProviders(): array
    {
        $fromDb = $this->getEnabledProvidersFromDatabase();
        if ($fromDb !== []) {
            return $fromDb;
        }

        return $this->getEnabledFromConfig();
    }

    /**
     * Get a single provider by slug.
     */
    public function provider(string $slug): LeadSearchProviderInterface
    {
        if (isset($this->providers[$slug])) {
            return $this->providers[$slug];
        }
        $model = LeadSearchProviderModel::where('slug', $slug)->where('status', 'active')->first();
        if ($model) {
            return $this->resolveProviderInstance($model);
        }

        throw new \InvalidArgumentException("Lead search provider not found: {$slug}");
    }

    /**
     * @return array<int, array{slug: string, name: string, instance: LeadSearchProviderInterface}>
     */
    private function getEnabledProvidersFromDatabase(): array
    {
        $models = Cache::remember('lead_search_providers_enabled', 60, function () {
            return LeadSearchProviderModel::where('status', 'active')
                ->orderByDesc('priority')
                ->orderBy('id')
                ->get();
        });

        if ($models->isEmpty()) {
            return [];
        }

        $out = [];
        foreach ($models as $model) {
            try {
                $out[] = [
                    'slug' => $model->slug,
                    'name' => $model->name,
                    'instance' => $this->resolveProviderInstance($model),
                ];
            } catch (\Throwable) {
                continue;
            }
        }

        return $out;
    }

    /**
     * @return array<int, array{slug: string, name: string, instance: LeadSearchProviderInterface}>
     */
    private function getEnabledFromConfig(): array
    {
        $out = [];
        $names = [
            'collector' => 'Collector',
            'google_maps' => 'Google Maps',
            'directory' => 'Directory',
            'api' => 'API',
            'imported' => 'Imported Data',
        ];
        foreach ($this->providers as $slug => $instance) {
            $out[] = [
                'slug' => $slug,
                'name' => $names[$slug] ?? $slug,
                'instance' => $instance,
            ];
        }

        return $out;
    }

    private function resolveProviderInstance(LeadSearchProviderModel $model): LeadSearchProviderInterface
    {
        $class = $model->provider_class;
        if (! class_exists($class)) {
            throw new \InvalidArgumentException("Provider class does not exist: {$class}");
        }
        $instance = app($class);
        if (! $instance instanceof LeadSearchProviderInterface) {
            throw new \InvalidArgumentException("Provider class must implement LeadSearchProviderInterface: {$class}");
        }

        return $instance;
    }

    public function resolveProviderInstanceFromSlug(string $slug): LeadSearchProviderInterface
    {
        if (isset($this->providers[$slug])) {
            return $this->providers[$slug];
        }
        $model = LeadSearchProviderModel::where('slug', $slug)->where('status', 'active')->first();
        if ($model) {
            return $this->resolveProviderInstance($model);
        }

        throw new \InvalidArgumentException("Lead search provider not found: {$slug}");
    }
}
