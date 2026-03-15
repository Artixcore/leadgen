<?php

namespace App\Services\LeadCollectors\Drivers;

use App\Models\LeadCollector;

class GoogleMapsCollectorDriver implements LeadCollectorDriverInterface
{
    /**
     * Collect raw lead records from Google Maps.
     * TODO: Implement actual Google Maps Places API or scraping integration.
     *
     * @return array<int, array<string, mixed>>
     */
    public function collect(LeadCollector $collector): array
    {
        return [];
    }
}
