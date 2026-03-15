<?php

namespace App\Services\LeadSearch;

class LeadResultNormalizer
{
    /**
     * Normalize a raw candidate from any provider to a single schema.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    public function normalize(array $raw, string $sourceName, string $sourceType = 'api'): array
    {
        $normalized = [
            'company_name' => (string) ($raw['company_name'] ?? $raw['company'] ?? ''),
            'website' => $this->normalizeUrl($raw['website'] ?? $raw['url'] ?? $raw['website_url'] ?? null),
            'email' => isset($raw['email']) ? (string) $raw['email'] : null,
            'phone' => isset($raw['phone']) ? (string) $raw['phone'] : null,
            'niche' => isset($raw['niche']) ? (string) $raw['niche'] : ($raw['industry'] ?? null),
            'city' => isset($raw['city']) ? (string) $raw['city'] : null,
            'country' => isset($raw['country']) ? (string) $raw['country'] : null,
            'source_name' => $sourceName,
            'source_type' => $sourceType,
            'trust_score' => (int) ($raw['trust_score'] ?? 50),
            'opportunity_signals' => $raw['opportunity_signals'] ?? [],
            'verification_status' => $raw['verification_status'] ?? null,
            'raw_payload' => $raw,
        ];

        if (is_string($normalized['niche'])) {
            $normalized['niche'] = trim($normalized['niche']) ?: null;
        }

        return $normalized;
    }

    /**
     * Normalize multiple raw candidates.
     *
     * @param  array<int, array<string, mixed>>  $rawCandidates
     * @return array<int, array<string, mixed>>
     */
    public function normalizeBatch(array $rawCandidates, string $sourceName, string $sourceType = 'api'): array
    {
        $out = [];
        foreach ($rawCandidates as $raw) {
            $out[] = $this->normalize($raw, $sourceName, $sourceType);
        }

        return $out;
    }

    private function normalizeUrl(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $url = (string) $value;
        $url = trim($url);
        if ($url === '') {
            return null;
        }
        if (! str_starts_with(strtolower($url), 'http')) {
            $url = 'https://'.$url;
        }

        return $url;
    }
}
