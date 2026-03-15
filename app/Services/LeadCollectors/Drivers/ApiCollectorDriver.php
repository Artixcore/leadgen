<?php

namespace App\Services\LeadCollectors\Drivers;

use App\Models\LeadCollector;

class ApiCollectorDriver implements LeadCollectorDriverInterface
{
    /**
     * Collect raw lead records from external API.
     * TODO: Implement API client and response mapping.
     *
     * @return array<int, array<string, mixed>>
     */
    public function collect(LeadCollector $collector): array
    {
        return [];
    }
}
