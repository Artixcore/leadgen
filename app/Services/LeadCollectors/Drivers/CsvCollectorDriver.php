<?php

namespace App\Services\LeadCollectors\Drivers;

use App\Models\LeadCollector;

class CsvCollectorDriver implements LeadCollectorDriverInterface
{
    /**
     * Collect raw lead records from CSV import.
     * TODO: Implement CSV parse from config path or upload.
     *
     * @return array<int, array<string, mixed>>
     */
    public function collect(LeadCollector $collector): array
    {
        return [];
    }
}
