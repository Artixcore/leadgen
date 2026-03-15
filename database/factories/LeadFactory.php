<?php

namespace Database\Factories;

use App\LeadStatus;
use App\Models\Lead;
use App\VerificationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'job_title' => fake()->jobTitle(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company_name' => fake()->company(),
            'website' => fake()->url(),
            'linkedin_profile' => 'https://linkedin.com/in/'.fake()->slug(),
            'country' => fake()->country(),
            'state' => fake()->state(),
            'city' => fake()->city(),
            'industry' => fake()->randomElement(['Technology', 'Healthcare', 'Finance', 'Retail', 'Manufacturing']),
            'niche' => fake()->optional(0.6)->randomElement(['SaaS', 'E-commerce', 'Fintech', 'EdTech', 'HealthTech', 'CleanTech']),
            'company_size' => fake()->randomElement(['1-10', '11-50', '51-200', '201-500', '500+']),
            'revenue_range' => fake()->randomElement(['$0-$1M', '$1M-$10M', '$10M-$50M', '$50M+']),
            'lead_source' => fake()->randomElement(['Website', 'Referral', 'LinkedIn', 'Cold outreach']),
            'verification_status' => fake()->randomElement(VerificationStatus::cases()),
            'quality_score' => fake()->optional(0.7)->numberBetween(0, 100),
            'lead_status' => fake()->randomElement(LeadStatus::cases()),
            'is_duplicate' => false,
            'notes' => fake()->optional(0.3)->paragraph(),
        ];
    }
}
