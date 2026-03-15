<?php

namespace Database\Factories;

use App\CollectorStatus;
use App\CollectorType;
use App\Models\LeadCollector;
use App\Models\LeadSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadCollector>
 */
class LeadCollectorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true).' Collector',
            'type' => fake()->randomElement(CollectorType::cases()),
            'status' => fake()->randomElement(CollectorStatus::cases()),
            'schedule' => fake()->optional(0.7)->randomElement(['0 * * * *', '0 */6 * * *', '0 0 * * *']),
            'config' => [],
            'lead_source_id' => LeadSource::factory(),
            'last_run_at' => fake()->optional(0.5)->dateTimeBetween('-14 days', 'now'),
        ];
    }
}
