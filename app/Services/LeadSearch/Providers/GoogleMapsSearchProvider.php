<?php

namespace App\Services\LeadSearch\Providers;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;

class GoogleMapsSearchProvider implements LeadSearchProviderInterface
{
    public function search(LeadSearchIntentDTO $intent): array
    {
        $city = $intent->targetCity ?? 'London';
        $country = $intent->targetCountry ?? 'United Kingdom';

        return [
            [
                'company_name' => 'Maps Business '.$city,
                'website' => 'https://maps-business-'.strtolower($city).'.test',
                'email' => 'hello@maps-business.test',
                'phone' => '+442071234567',
                'city' => $city,
                'country' => $country,
                'niche' => $intent->targetNiche ?? 'retail',
                'trust_score' => 70,
                'opportunity_signals' => ['weak_seo', 'poor_mobile_ux'],
            ],
            [
                'company_name' => 'Local Shop '.$city,
                'website' => 'https://localshop.test',
                'email' => null,
                'phone' => '+442079876543',
                'city' => $city,
                'country' => $country,
                'niche' => 'retail',
                'trust_score' => 65,
                'opportunity_signals' => ['outdated_website'],
            ],
        ];
    }
}
