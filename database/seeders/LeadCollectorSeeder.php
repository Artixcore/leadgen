<?php

namespace Database\Seeders;

use App\CollectorStatus;
use App\CollectorType;
use App\LeadCollectorRunStatus;
use App\LeadCollectorSourceType;
use App\LeadCollectorTargetService;
use App\Models\LeadCollector;
use App\Models\LeadCollectorRule;
use App\Models\LeadCollectorRun;
use App\Models\LeadSource;
use App\RawLeadRecordStatus;
use Illuminate\Database\Seeder;

class LeadCollectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSource = LeadSource::query()->first();

        $collectors = [
            [
                'name' => 'Google Maps — Restaurants Dubai — Digital Marketing',
                'slug' => 'google-maps-restaurants-dubai-digital-marketing',
                'source_type' => LeadCollectorSourceType::Scraper,
                'type' => CollectorType::GoogleMaps,
                'target_service' => LeadCollectorTargetService::DigitalMarketing,
                'target_niche' => 'restaurants',
                'target_country' => 'AE',
                'target_city' => 'Dubai',
                'keywords' => 'restaurant, cafe, catering',
                'status' => CollectorStatus::Active,
                'is_active' => true,
                'priority' => 10,
            ],
            [
                'name' => 'Directory — Clinics London — Web Development',
                'slug' => 'directory-clinics-london-web-development',
                'source_type' => LeadCollectorSourceType::Scraper,
                'type' => CollectorType::Directory,
                'target_service' => LeadCollectorTargetService::WebDevelopment,
                'target_niche' => 'clinics',
                'target_country' => 'GB',
                'target_city' => 'London',
                'keywords' => 'clinic, medical, health',
                'status' => CollectorStatus::Active,
                'is_active' => true,
                'priority' => 5,
            ],
            [
                'name' => 'CSV Import — Local Businesses Toronto — SEO',
                'slug' => 'csv-import-toronto-seo',
                'source_type' => LeadCollectorSourceType::Import,
                'type' => CollectorType::CsvImport,
                'target_service' => LeadCollectorTargetService::Seo,
                'target_niche' => 'local businesses',
                'target_country' => 'CA',
                'target_city' => 'Toronto',
                'keywords' => 'local, small business',
                'status' => CollectorStatus::Draft,
                'is_active' => false,
                'priority' => 0,
            ],
        ];

        foreach ($collectors as $data) {
            $collector = LeadCollector::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'lead_source_id' => $defaultSource?->id,
                    'schedule' => '0 */6 * * *',
                    'config' => [],
                    'filters_json' => [],
                ])
            );

            if ($collector->wasRecentlyCreated) {
                LeadCollectorRule::insert([
                    [
                        'lead_collector_id' => $collector->id,
                        'rule_key' => 'has_website',
                        'rule_operator' => 'exists',
                        'rule_value' => null,
                        'score_weight' => 5,
                        'is_required' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'lead_collector_id' => $collector->id,
                        'rule_key' => 'missing_ssl',
                        'rule_operator' => 'not_exists',
                        'rule_value' => null,
                        'score_weight' => -3,
                        'is_required' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'lead_collector_id' => $collector->id,
                        'rule_key' => 'no_social_links',
                        'rule_operator' => 'not_exists',
                        'rule_value' => null,
                        'score_weight' => -2,
                        'is_required' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'lead_collector_id' => $collector->id,
                        'rule_key' => 'location_match',
                        'rule_operator' => 'eq',
                        'rule_value' => $collector->target_city ?? '',
                        'score_weight' => 10,
                        'is_required' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            $runCount = $collector->runs()->count();
            if ($runCount === 0) {
                $runCompleted = LeadCollectorRun::create([
                    'lead_collector_id' => $collector->id,
                    'run_type' => 'manual',
                    'status' => LeadCollectorRunStatus::Completed,
                    'total_found' => 5,
                    'total_processed' => 5,
                    'total_new' => 3,
                    'total_duplicates' => 1,
                    'total_failed' => 1,
                    'started_at' => now()->subHours(2),
                    'finished_at' => now()->subHours(2)->addMinutes(3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $runFailed = LeadCollectorRun::create([
                    'lead_collector_id' => $collector->id,
                    'run_type' => 'scheduled',
                    'status' => LeadCollectorRunStatus::Failed,
                    'total_found' => 0,
                    'total_processed' => 0,
                    'total_new' => 0,
                    'total_duplicates' => 0,
                    'total_failed' => 0,
                    'started_at' => now()->subDays(1),
                    'finished_at' => now()->subDays(1),
                    'error_message' => 'Connection timeout (demo)',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ([$runCompleted, $runFailed] as $run) {
                    if ($run->status === LeadCollectorRunStatus::Completed) {
                        for ($i = 0; $i < 3; $i++) {
                            $collector->rawLeadRecords()->create([
                                'lead_collector_run_id' => $run->id,
                                'company_name' => 'Demo Company '.($i + 1),
                                'website' => 'https://demo'.($i + 1).'.example.com',
                                'email' => 'contact'.($i + 1).'@demo.example.com',
                                'phone' => '+1234567890',
                                'city' => $collector->target_city,
                                'country' => $collector->target_country,
                                'raw_payload' => ['name' => 'Demo '.($i + 1), 'company' => 'Demo Company '.($i + 1)],
                                'processing_status' => $i === 0 ? RawLeadRecordStatus::Accepted : ($i === 1 ? RawLeadRecordStatus::Normalized : RawLeadRecordStatus::Pending),
                                'discovered_at' => $run->started_at,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
