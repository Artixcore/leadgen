<?php

namespace Database\Factories;

use App\Models\LeadCollector;
use App\Models\RawLeadRecord;
use App\RawLeadRecordStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RawLeadRecord>
 */
class RawLeadRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = fake()->company();
        $raw = [
            'name' => fake()->name(),
            'company' => $company,
            'email' => fake()->optional(0.8)->companyEmail(),
            'phone' => fake()->optional(0.6)->phoneNumber(),
            'website' => fake()->optional(0.7)->url(),
            'address' => fake()->optional(0.5)->address(),
        ];

        return [
            'lead_collector_id' => LeadCollector::factory(),
            'lead_collector_run_id' => null,
            'source_record_id' => fake()->optional(0.7)->uuid(),
            'company_name' => $company,
            'website' => $raw['website'],
            'email' => $raw['email'],
            'phone' => $raw['phone'],
            'address' => $raw['address'],
            'country' => fake()->optional(0.6)->countryCode(),
            'city' => fake()->optional(0.6)->city(),
            'niche' => fake()->optional(0.4)->word(),
            'source_url' => fake()->optional(0.5)->url(),
            'raw_payload' => $raw,
            'normalized_payload' => null,
            'verification_status' => null,
            'processing_status' => fake()->randomElement(RawLeadRecordStatus::cases()),
            'dedupe_hash' => fake()->optional(0.5)->sha1(),
            'discovered_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
