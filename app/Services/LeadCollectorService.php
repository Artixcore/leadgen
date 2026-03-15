<?php

namespace App\Services;

use App\CollectorType;
use App\Jobs\RunCollectorJob;
use App\LeadSourceStatus;
use App\LeadSourceType;
use App\Models\LeadCollector;
use App\Models\LeadSource;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeadCollectorService
{
    public function __construct(
        protected ActivityLogService $activityLog
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?User $createdBy = null): LeadCollector
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $leadSourceId = $data['lead_source_id'] ?? null;
            if (! empty($data['create_lead_source']) && ! empty($data['name'])) {
                $source = LeadSource::create([
                    'name' => $data['name'],
                    'type' => $this->collectorTypeToSourceType($data['type'] ?? CollectorType::ApiConnector),
                    'status' => LeadSourceStatus::Active,
                    'config' => [],
                    'created_by' => $createdBy?->id,
                    'updated_by' => $createdBy?->id,
                ]);
                $leadSourceId = $source->id;
            }

            $collector = LeadCollector::create(array_merge([
                'name' => $data['name'],
                'type' => $data['type'],
                'status' => $data['status'],
                'schedule' => $data['schedule'] ?? null,
                'config' => $data['config'] ?? [],
                'config_encrypted' => $data['config_encrypted'] ?? null,
                'lead_source_id' => $leadSourceId,
                'created_by' => $createdBy?->id,
                'updated_by' => $createdBy?->id,
            ], $this->collectorModuleAttributes($data)));

            if ($createdBy) {
                $this->activityLog->log($createdBy, 'lead_collector.created', $collector);
            }

            return $collector;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(LeadCollector $collector, array $data, ?User $updatedBy = null): void
    {
        $collector->update(array_merge([
            'name' => $data['name'] ?? $collector->name,
            'type' => $data['type'] ?? $collector->type,
            'status' => $data['status'] ?? $collector->status,
            'schedule' => array_key_exists('schedule', $data) ? $data['schedule'] : $collector->schedule,
            'config' => $data['config'] ?? $collector->config,
            'config_encrypted' => array_key_exists('config_encrypted', $data) ? $data['config_encrypted'] : $collector->config_encrypted,
            'updated_by' => $updatedBy?->id,
        ], $this->collectorModuleAttributes($data)));

        if ($updatedBy) {
            $this->activityLog->log($updatedBy, 'lead_collector.updated', $collector);
        }
    }

    public function runNow(LeadCollector $collector, ?User $triggeredBy = null): void
    {
        RunCollectorJob::dispatch($collector, $triggeredBy?->id);

        if ($triggeredBy) {
            $this->activityLog->log($triggeredBy, 'lead_collector.run_triggered', $collector);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function collectorModuleAttributes(array $data): array
    {
        $keys = [
            'slug', 'source_name', 'source_type', 'target_service', 'target_niche',
            'target_country', 'target_city', 'keywords', 'filters_json', 'trust_score',
            'priority', 'is_active', 'next_run_at',
        ];
        $out = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                $out[$key] = $key === 'is_active' ? (bool) $data['is_active'] : $data[$key];
            }
        }

        return $out;
    }

    private function collectorTypeToSourceType(CollectorType|string $type): LeadSourceType
    {
        $value = $type instanceof CollectorType ? $type : CollectorType::from($type);

        return match ($value) {
            CollectorType::ApiConnector => LeadSourceType::Api,
            CollectorType::CsvImport => LeadSourceType::Import,
            CollectorType::GoogleMaps, CollectorType::Directory, CollectorType::WebsiteScan => LeadSourceType::Scraper,
        };
    }
}
