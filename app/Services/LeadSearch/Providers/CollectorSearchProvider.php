<?php

namespace App\Services\LeadSearch\Providers;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;

class CollectorSearchProvider implements LeadSearchProviderInterface
{
    public function search(LeadSearchIntentDTO $intent): array
    {
        return $this->mockCandidates($intent, 'Collector', 'collector', 3);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mockCandidates(LeadSearchIntentDTO $intent, string $sourceName, string $sourceType, int $count): array
    {
        $city = $intent->targetCity ?? 'Dubai';
        $country = $intent->targetCountry ?? 'United Arab Emirates';
        $niche = $intent->targetNiche ?? 'general';
        $candidates = [
            [
                'company_name' => 'Sample Restaurant '.$city,
                'website' => 'https://example-restaurant-'.strtolower($city).'.test',
                'email' => 'contact@example-restaurant.test',
                'phone' => '+971501234567',
                'city' => $city,
                'country' => $country,
                'niche' => $niche,
                'trust_score' => 60,
                'opportunity_signals' => ['outdated_website', 'weak_seo'],
            ],
            [
                'company_name' => 'Local Clinic '.$city,
                'website' => 'https://clinic-'.strtolower($city).'.test',
                'email' => null,
                'phone' => '+971509876543',
                'city' => $city,
                'country' => $country,
                'niche' => 'clinics',
                'trust_score' => 55,
                'opportunity_signals' => ['weak_online_presence'],
            ],
            [
                'company_name' => 'Small Business Co',
                'website' => null,
                'email' => 'info@smallbiz.test',
                'phone' => null,
                'city' => $city,
                'country' => $country,
                'niche' => 'small_business',
                'trust_score' => 50,
                'opportunity_signals' => ['no_website', 'weak_social_presence'],
            ],
        ];

        return array_slice($candidates, 0, $count);
    }
}
