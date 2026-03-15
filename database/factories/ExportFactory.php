<?php

namespace Database\Factories;

use App\Models\Export;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Export>
 */
class ExportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => null,
            'type' => fake()->randomElement(['csv', 'xlsx']),
            'filters' => ['search' => null, 'country' => null],
            'row_count' => fake()->numberBetween(0, 1000),
            'status' => fake()->randomElement(['pending', 'completed', 'failed']),
            'file_path' => fake()->optional(0.7)->filePath(),
            'error_message' => null,
        ];
    }
}
