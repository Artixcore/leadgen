<?php

namespace Database\Seeders;

use App\LeadSourceStatus;
use App\LeadSourceType;
use App\Models\LeadSource;
use Illuminate\Database\Seeder;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'LinkedIn API',
                'type' => LeadSourceType::Api,
                'status' => LeadSourceStatus::Active,
                'reliability_score' => 85,
                'import_frequency' => '0 */6 * * *',
                'validation_rules' => ['required' => ['email', 'full_name', 'company_name'], 'email_format' => true],
            ],
            [
                'name' => 'CSV Import',
                'type' => LeadSourceType::Import,
                'status' => LeadSourceStatus::Active,
                'reliability_score' => 70,
                'import_frequency' => null,
                'validation_rules' => ['required' => ['email'], 'email_format' => true],
            ],
        ];

        foreach ($sources as $source) {
            LeadSource::firstOrCreate(
                ['name' => $source['name']],
                $source
            );
        }
    }
}
