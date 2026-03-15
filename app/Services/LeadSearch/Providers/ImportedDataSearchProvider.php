<?php

namespace App\Services\LeadSearch\Providers;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;

class ImportedDataSearchProvider implements LeadSearchProviderInterface
{
    public function search(LeadSearchIntentDTO $intent): array
    {
        $city = $intent->targetCity ?? 'Sydney';
        $country = $intent->targetCountry ?? 'Australia';

        return [
            [
                'company_name' => 'Imported Data Co',
                'website' => 'https://imported-data.test',
                'email' => 'hello@imported-data.test',
                'phone' => '+61291234567',
                'city' => $city,
                'country' => $country,
                'niche' => $intent->targetNiche ?? 'general',
                'trust_score' => 62,
                'opportunity_signals' => ['web_opportunity'],
            ],
        ];
    }
}
