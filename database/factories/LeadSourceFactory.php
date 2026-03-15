<?php

namespace Database\Factories;

use App\LeadSourceStatus;
use App\LeadSourceType;
use App\Models\LeadSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadSource>
 */
class LeadSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Source',
            'type' => fake()->randomElement(LeadSourceType::cases()),
            'status' => fake()->randomElement(LeadSourceStatus::cases()),
            'reliability_score' => fake()->optional(0.8)->numberBetween(0, 100),
            'last_sync_at' => fake()->optional(0.6)->dateTimeBetween('-30 days', 'now'),
            'import_frequency' => fake()->optional(0.7)->randomElement(['0 * * * *', '0 */6 * * *', '0 0 * * *']),
            'validation_rules' => [
                'required' => ['email', 'company_name'],
                'email_format' => true,
            ],
            'config' => null,
        ];
    }
}
