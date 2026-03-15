<?php

namespace App\Services\LeadCollectors\Drivers;

use App\Models\LeadCollector;

class WebsiteScanCollectorDriver implements LeadCollectorDriverInterface
{
    /**
     * Collect raw lead records from website scan.
     * TODO: Implement website crawl and lead extraction.
     *
     * @return array<int, array<string, mixed>>
     */
    public function collect(LeadCollector $collector): array
    {
        return [];
    }
}
