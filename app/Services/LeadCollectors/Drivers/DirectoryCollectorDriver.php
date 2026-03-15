<?php

namespace App\Services\LeadCollectors\Drivers;

use App\Models\LeadCollector;

class DirectoryCollectorDriver implements LeadCollectorDriverInterface
{
    /**
     * Collect raw lead records from directory source.
     * TODO: Implement directory crawl or API integration.
     *
     * @return array<int, array<string, mixed>>
     */
    public function collect(LeadCollector $collector): array
    {
        return [];
    }
}
