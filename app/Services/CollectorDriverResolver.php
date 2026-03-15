<?php

namespace App\Services;

use App\CollectorType;
use App\Contracts\CollectorDriverInterface;
use App\Models\LeadCollector;

class CollectorDriverResolver
{
    /**
     * @param  array<string, CollectorDriverInterface>  $drivers
     */
    public function __construct(
        protected array $drivers = []
    ) {}

    public function resolve(LeadCollector $collector): CollectorDriverInterface
    {
        $type = $collector->type;
        if (! isset($this->drivers[$type->value])) {
            throw new \InvalidArgumentException("No driver registered for collector type: {$type->value}");
        }

        return $this->drivers[$type->value];
    }

    public function driverFor(CollectorType $type): CollectorDriverInterface
    {
        if (! isset($this->drivers[$type->value])) {
            throw new \InvalidArgumentException("No driver registered for collector type: {$type->value}");
        }

        return $this->drivers[$type->value];
    }
}
