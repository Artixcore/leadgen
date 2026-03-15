<?php

namespace App\Services\LeadSearch;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;
use Illuminate\Support\Str;

class LeadQueryParserService
{
    public function __construct() {}

    private function intentsConfig(): array
    {
        return config('lead_search_intents', []);
    }

    /**
     * Parse raw query and optional filters into a structured search intent.
     *
     * @param  array<string, mixed>|null  $filters  Optional structured filters (target_service, niche, country, city, company_size, min_score, verified_only, source_hints, include_website_analysis)
     */
    public function parse(string $query, ?array $filters = null): LeadSearchIntentDTO
    {
        $normalized = Str::lower(trim($query));
        $filters = $filters ?? [];

        $targetService = $this->extractFromMap($normalized, 'services')
            ?? (isset($filters['target_service']) ? (string) $filters['target_service'] : null);
        $targetNiche = $this->extractFromMap($normalized, 'niches')
            ?? (isset($filters['target_niche']) ? (string) $filters['target_niche'] : null);
        $targetCountry = $this->extractCountry($normalized, $filters);
        $targetCity = $this->extractCity($normalized, $filters);
        if ($targetCountry === null && $targetCity !== null) {
            $cityCountryMap = $this->intentsConfig()['city_to_country'] ?? [];
            $cityKey = Str::lower(trim($targetCity));
            $targetCountry = $cityCountryMap[$cityKey] ?? null;
        }
        $companySize = $this->extractFromMap($normalized, 'company_sizes')
            ?? (isset($filters['company_size']) ? (string) $filters['company_size'] : null);
        $opportunitySignals = $this->extractOpportunitySignals($normalized, $filters);
        $sourceHints = isset($filters['source_hints']) && is_array($filters['source_hints'])
            ? $filters['source_hints']
            : [];
        $minScore = isset($filters['min_score']) ? (int) $filters['min_score'] : null;
        $verifiedOnly = (bool) ($filters['verified_only'] ?? false);
        $includeWebsiteAnalysis = ! isset($filters['include_website_analysis']) || (bool) $filters['include_website_analysis'];

        return new LeadSearchIntentDTO(
            targetService: $targetService,
            targetNiche: $targetNiche,
            targetCountry: $targetCountry,
            targetCity: $targetCity,
            companySize: $companySize,
            opportunitySignals: $opportunitySignals,
            sourceHints: $sourceHints,
            minScore: $minScore,
            verifiedOnly: $verifiedOnly,
            includeWebsiteAnalysis: $includeWebsiteAnalysis,
        );
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, string>
     */
    private function extractOpportunitySignals(string $normalized, array $filters): array
    {
        $signals = [];
        $map = $this->intentsConfig()['opportunity_signals'] ?? [];
        foreach ($map as $phrase => $key) {
            if (Str::contains($normalized, Str::lower($phrase))) {
                $signals[] = $key;
            }
        }
        if (isset($filters['opportunity_signals']) && is_array($filters['opportunity_signals'])) {
            foreach ($filters['opportunity_signals'] as $s) {
                $signals[] = (string) $s;
            }
        }

        return array_values(array_unique($signals));
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function extractCountry(string $normalized, array $filters): ?string
    {
        if (isset($filters['target_country']) && $filters['target_country'] !== '') {
            return (string) $filters['target_country'];
        }
        $countries = $this->intentsConfig()['countries'] ?? [];
        $keys = array_keys($countries);
        usort($keys, fn ($a, $b) => strlen((string) $b) <=> strlen((string) $a));
        foreach ($keys as $term) {
            $label = $countries[$term];
            if (Str::contains($normalized, Str::lower((string) $term))) {
                return $label;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function extractCity(string $normalized, array $filters): ?string
    {
        if (isset($filters['target_city']) && $filters['target_city'] !== '') {
            return (string) $filters['target_city'];
        }
        $cities = $this->intentsConfig()['cities'] ?? [];
        foreach ($cities as $city) {
            if (Str::contains($normalized, $city)) {
                return Str::title($city);
            }
        }

        return null;
    }

    private function extractFromMap(string $normalized, string $configKey): ?string
    {
        $map = $this->intentsConfig()[$configKey] ?? [];
        $bestMatch = null;
        $bestLength = 0;
        foreach ($map as $phrase => $value) {
            $phraseLower = Str::lower($phrase);
            if (Str::contains($normalized, $phraseLower) && strlen($phraseLower) > $bestLength) {
                $bestMatch = $value;
                $bestLength = strlen($phraseLower);
            }
        }

        return $bestMatch;
    }
}
