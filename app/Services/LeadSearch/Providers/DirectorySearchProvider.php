<?php

namespace App\Services\LeadSearch\Providers;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;

class DirectorySearchProvider implements LeadSearchProviderInterface
{
    public function search(LeadSearchIntentDTO $intent): array
    {
        $city = $intent->targetCity ?? 'Toronto';
        $country = $intent->targetCountry ?? 'Canada';

        return [
            [
                'company_name' => 'Directory Listing Inc',
                'website' => 'https://directory-listing.test',
                'email' => 'info@directory-listing.test',
                'phone' => '+14165551234',
                'city' => $city,
                'country' => $country,
                'niche' => $intent->targetNiche ?? 'agencies',
                'trust_score' => 58,
                'opportunity_signals' => ['weak_seo', 'weak_social_presence'],
            ],
        ];
    }
}
