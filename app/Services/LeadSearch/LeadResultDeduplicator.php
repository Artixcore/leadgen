<?php

namespace App\Services\LeadSearch;

use Illuminate\Support\Str;

class LeadResultDeduplicator
{
    /**
     * Deduplicate candidates by normalized website or company_name+city.
     * Keeps the first occurrence (or the one with highest trust_score if same).
     *
     * @param  array<int, array<string, mixed>>  $candidates
     * @return array<int, array<string, mixed>>
     */
    public function deduplicate(array $candidates): array
    {
        $seen = [];
        $out = [];

        foreach ($candidates as $candidate) {
            $key = $this->dedupeKey($candidate);
            if ($key === '') {
                $out[] = $candidate;

                continue;
            }
            if (isset($seen[$key])) {
                $existing = $out[$seen[$key]];
                if (($candidate['trust_score'] ?? 0) > ($existing['trust_score'] ?? 0)) {
                    $out[$seen[$key]] = $candidate;
                }

                continue;
            }
            $seen[$key] = count($out);
            $out[] = $candidate;
        }

        return array_values($out);
    }

    /**
     * @param  array<string, mixed>  $candidate
     */
    private function dedupeKey(array $candidate): string
    {
        $website = $candidate['website'] ?? null;
        if ($website !== null && $website !== '') {
            $host = parse_url($website, PHP_URL_HOST);
            if ($host) {
                return 'url:'.Str::lower($host);
            }
        }
        $company = trim((string) ($candidate['company_name'] ?? $candidate['company'] ?? ''));
        $city = trim((string) ($candidate['city'] ?? ''));
        if ($company !== '') {
            return 'company:'.Str::lower($company).':'.Str::lower($city);
        }

        return '';
    }
}
