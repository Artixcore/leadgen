<?php

namespace Database\Seeders;

use App\Models\LeadSearchProvider;
use App\Services\LeadSearch\Providers\ApiLeadSearchProvider;
use App\Services\LeadSearch\Providers\CollectorSearchProvider;
use App\Services\LeadSearch\Providers\DirectorySearchProvider;
use App\Services\LeadSearch\Providers\GoogleMapsSearchProvider;
use App\Services\LeadSearch\Providers\ImportedDataSearchProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class LeadSearchProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'name' => 'Collector',
                'slug' => 'collector',
                'provider_class' => CollectorSearchProvider::class,
                'source_type' => 'collector',
                'status' => 'active',
                'priority' => 100,
                'trust_score' => 60,
            ],
            [
                'name' => 'Google Maps',
                'slug' => 'google_maps',
                'provider_class' => GoogleMapsSearchProvider::class,
                'source_type' => 'directory',
                'status' => 'active',
                'priority' => 90,
                'trust_score' => 70,
            ],
            [
                'name' => 'Directory',
                'slug' => 'directory',
                'provider_class' => DirectorySearchProvider::class,
                'source_type' => 'directory',
                'status' => 'active',
                'priority' => 80,
                'trust_score' => 58,
            ],
            [
                'name' => 'API',
                'slug' => 'api',
                'provider_class' => ApiLeadSearchProvider::class,
                'source_type' => 'api',
                'status' => 'active',
                'priority' => 70,
                'trust_score' => 72,
            ],
            [
                'name' => 'Imported Data',
                'slug' => 'imported',
                'provider_class' => ImportedDataSearchProvider::class,
                'source_type' => 'import',
                'status' => 'active',
                'priority' => 60,
                'trust_score' => 62,
            ],
        ];

        foreach ($providers as $data) {
            LeadSearchProvider::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        Cache::forget('lead_search_providers_enabled');
    }
}
