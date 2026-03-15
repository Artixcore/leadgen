<?php

namespace Database\Factories;

use App\Models\LeadSearchQuery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadSearchQuery>
 */
class LeadSearchQueryFactory extends Factory
{
    protected $model = LeadSearchQuery::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'query' => fake()->sentence(6),
            'parsed_query_json' => [],
            'target_service' => 'seo',
            'target_niche' => null,
            'target_country' => 'United Arab Emirates',
            'target_city' => 'Dubai',
            'filters_json' => [],
            'status' => 'completed',
            'total_results' => fake()->numberBetween(0, 50),
            'search_took_ms' => fake()->numberBetween(100, 2000),
        ];
    }
}
