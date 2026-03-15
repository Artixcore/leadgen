<?php

namespace App\Services\LeadCollectors;

use App\CollectorStatus;
use App\Models\LeadCollector;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\LeadCollectorService;

class LeadCollectorManager
{
    public function __construct(
        protected LeadCollectorService $collectorService,
        protected ActivityLogService $activityLog
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?User $createdBy = null): LeadCollector
    {
        return $this->collectorService->create($this->normalizeData($data), $createdBy);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(LeadCollector $collector, array $data, ?User $updatedBy = null): void
    {
        $payload = $this->normalizeData($data);
        $this->collectorService->update($collector, $payload, $updatedBy);
    }

    public function activate(LeadCollector $collector, ?User $updatedBy = null): void
    {
        $collector->update(['is_active' => true, 'status' => CollectorStatus::Active, 'updated_by' => $updatedBy?->id]);
        if ($updatedBy) {
            $this->activityLog->log($updatedBy, 'lead_collector.activated', $collector);
        }
    }

    public function deactivate(LeadCollector $collector, ?User $updatedBy = null): void
    {
        $collector->update(['is_active' => false, 'status' => CollectorStatus::Paused, 'updated_by' => $updatedBy?->id]);
        if ($updatedBy) {
            $this->activityLog->log($updatedBy, 'lead_collector.deactivated', $collector);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalizeData(array $data): array
    {
        $out = $data;
        if (isset($data['filters_json'])) {
            $out['filters_json'] = is_string($data['filters_json'])
                ? (json_decode($data['filters_json'], true) ?? [])
                : $data['filters_json'];
        }
        if (isset($data['config']) && is_string($data['config'])) {
            $out['config'] = json_decode($data['config'], true) ?? [];
        }

        return $out;
    }
}
