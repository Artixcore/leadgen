<?php

namespace App\Services\LeadSearch\Providers;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;

class ApiLeadSearchProvider implements LeadSearchProviderInterface
{
    public function search(LeadSearchIntentDTO $intent): array
    {
        $city = $intent->targetCity ?? 'New York';
        $country = $intent->targetCountry ?? 'United States';

        return [
            [
                'company_name' => 'API Sourced Business',
                'website' => 'https://api-sourced.test',
                'email' => 'contact@api-sourced.test',
                'phone' => '+12125551234',
                'city' => $city,
                'country' => $country,
                'niche' => $intent->targetNiche ?? 'small_business',
                'trust_score' => 72,
                'opportunity_signals' => ['outdated_website', 'no_ssl'],
            ],
        ];
    }
}
