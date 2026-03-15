<?php

namespace App\Services\LeadSearch\Providers;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;

interface LeadSearchProviderInterface
{
    /**
     * Search for lead candidates matching the given intent.
     * Each candidate should be an array with keys: company_name, website, email, phone,
     * city, country, niche, source_name, source_type, raw_payload (and optionally
     * trust_score, opportunity_signals).
     *
     * @return array<int, array<string, mixed>>
     */
    public function search(LeadSearchIntentDTO $intent): array;
}
