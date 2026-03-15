<?php

namespace Database\Factories;

use App\LeadCollectorRunStatus;
use App\Models\LeadCollector;
use App\Models\LeadCollectorRun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadCollectorRun>
 */
class LeadCollectorRunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $started = fake()->dateTimeBetween('-7 days', 'now');
        $finished = fake()->optional(0.8)->dateTimeBetween($started, 'now');

        return [
            'lead_collector_id' => LeadCollector::factory(),
            'run_type' => fake()->randomElement(['manual', 'scheduled']),
            'status' => fake()->randomElement(LeadCollectorRunStatus::cases()),
            'total_found' => fake()->numberBetween(0, 100),
            'total_processed' => fake()->numberBetween(0, 100),
            'total_new' => fake()->numberBetween(0, 50),
            'total_duplicates' => fake()->numberBetween(0, 20),
            'total_failed' => fake()->numberBetween(0, 10),
            'started_at' => $started,
            'finished_at' => $finished,
            'notes' => fake()->optional(0.3)->sentence(),
            'error_message' => fake()->optional(0.2)->sentence(),
            'meta_json' => fake()->optional(0.5)->passthrough([]),
        ];
    }
}
