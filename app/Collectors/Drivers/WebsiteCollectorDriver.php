<?php

namespace App\Collectors\Drivers;

use App\Contracts\CollectorDriverInterface;
use App\Models\LeadCollector;

class WebsiteCollectorDriver implements CollectorDriverInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetch(LeadCollector $collector): array
    {
        return [];
    }
}
