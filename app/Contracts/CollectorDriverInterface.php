<?php

namespace App\Contracts;

use App\Models\LeadCollector;

interface CollectorDriverInterface
{
    /**
     * Fetch raw lead rows from the collector's source.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetch(LeadCollector $collector): array;
}
