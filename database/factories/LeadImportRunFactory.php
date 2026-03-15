<?php

namespace Database\Factories;

use App\ImportRunStatus;
use App\Models\LeadImportRun;
use App\Models\LeadSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadImportRun>
 */
class LeadImportRunFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_source_id' => LeadSource::factory(),
            'status' => ImportRunStatus::Completed,
            'started_at' => now(),
            'completed_at' => now(),
            'stats' => ['total' => 0, 'imported' => 0],
        ];
    }
}
