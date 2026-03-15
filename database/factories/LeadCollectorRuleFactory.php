<?php

namespace Database\Factories;

use App\Models\LeadCollector;
use App\Models\LeadCollectorRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadCollectorRule>
 */
class LeadCollectorRuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_collector_id' => LeadCollector::factory(),
            'rule_key' => fake()->randomElement(['has_website', 'missing_ssl', 'no_social_links', 'location_match']),
            'rule_operator' => fake()->randomElement(['eq', 'neq', 'exists', 'not_exists']),
            'rule_value' => fake()->optional(0.6)->word(),
            'score_weight' => fake()->numberBetween(-10, 10),
            'is_required' => fake()->boolean(20),
        ];
    }
}
