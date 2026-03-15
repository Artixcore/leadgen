<?php

namespace Database\Factories;

use App\Models\SavedLeadSearch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavedLeadSearch>
 */
class SavedLeadSearchFactory extends Factory
{
    protected $model = SavedLeadSearch::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'query' => fake()->sentence(6),
            'parsed_query_json' => [],
            'filters_json' => [],
            'is_active' => true,
        ];
    }
}
