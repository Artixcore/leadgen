<?php

namespace App\Services\LeadCollectors;

use App\CollectorType;
use App\Models\LeadCollector;
use App\Services\LeadCollectors\Drivers\LeadCollectorDriverInterface;

class CollectorDriverResolver
{
    /**
     * @param  array<string, LeadCollectorDriverInterface>  $drivers
     */
    public function __construct(
        protected array $drivers = []
    ) {}

    public function resolve(LeadCollector $collector): LeadCollectorDriverInterface
    {
        $type = $collector->type;
        if (! isset($this->drivers[$type->value])) {
            throw new \InvalidArgumentException("No driver registered for collector type: {$type->value}");
        }

        return $this->drivers[$type->value];
    }

    public function driverFor(CollectorType $type): LeadCollectorDriverInterface
    {
        if (! isset($this->drivers[$type->value])) {
            throw new \InvalidArgumentException("No driver registered for collector type: {$type->value}");
        }

        return $this->drivers[$type->value];
    }
}
