<?php

namespace App\Services\LeadCollectors\Drivers;

use App\Models\LeadCollector;

interface LeadCollectorDriverInterface
{
    /**
     * Collect raw lead records from the collector's source.
     * Stub implementation; no actual scraping or API calls yet.
     *
     * @return array<int, array<string, mixed>>
     */
    public function collect(LeadCollector $collector): array;
}
