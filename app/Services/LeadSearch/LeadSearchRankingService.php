<?php

namespace App\Services\LeadSearch;

use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;
use Illuminate\Support\Str;

class LeadSearchRankingService
{
    private const SCORE_EXACT_LOCATION = 20;

    private const SCORE_NICHE_MATCH = 20;

    private const SCORE_SERVICE_FIT = 25;

    private const SCORE_SOURCE_TRUST = 10;

    private const SCORE_CONTACT_COMPLETE = 10;

    private const SCORE_OPPORTUNITY_SIGNAL = 15;

    /**
     * Score and rank candidates against the search intent.
     * Adds relevance_score and opportunity_score to each candidate; sorts by relevance_score desc.
     *
     * @param  array<int, array<string, mixed>>  $candidates
     * @return array<int, array<string, mixed>>
     */
    public function rank(array $candidates, LeadSearchIntentDTO $intent): array
    {
        foreach ($candidates as $i => $candidate) {
            $relevance = 0;
            $opportunity = 0;

            if ($intent->targetCity && $this->matchCity($candidate, $intent->targetCity)) {
                $relevance += self::SCORE_EXACT_LOCATION;
            }
            if ($intent->targetCountry && $this->matchCountry($candidate, $intent->targetCountry)) {
                $relevance += self::SCORE_EXACT_LOCATION;
            }
            if ($intent->targetNiche && $this->matchNiche($candidate, $intent->targetNiche)) {
                $relevance += self::SCORE_NICHE_MATCH;
            }
            if ($intent->targetService && $this->matchService($candidate, $intent->targetService)) {
                $relevance += self::SCORE_SERVICE_FIT;
            }

            $trust = (int) ($candidate['trust_score'] ?? 50);
            $relevance += (int) round((self::SCORE_SOURCE_TRUST / 100) * $trust);

            $contactScore = $this->contactCompletenessScore($candidate);
            $relevance += (int) round((self::SCORE_CONTACT_COMPLETE / 100) * $contactScore);

            $signals = $candidate['opportunity_signals'] ?? [];
            if (is_array($signals)) {
                foreach ($signals as $signal) {
                    $opportunity += self::SCORE_OPPORTUNITY_SIGNAL;
                }
            }
            $relevance += $opportunity;

            $candidates[$i]['relevance_score'] = min(100, $relevance);
            $candidates[$i]['opportunity_score'] = min(100, $opportunity);
        }

        usort($candidates, fn ($a, $b) => ($b['relevance_score'] ?? 0) <=> ($a['relevance_score'] ?? 0));

        return array_values($candidates);
    }

    /**
     * @param  array<string, mixed>  $candidate
     */
    private function matchCity(array $candidate, string $targetCity): bool
    {
        $city = (string) ($candidate['city'] ?? '');

        return Str::lower(trim($city)) === Str::lower(trim($targetCity));
    }

    /**
     * @param  array<string, mixed>  $candidate
     */
    private function matchCountry(array $candidate, string $targetCountry): bool
    {
        $country = (string) ($candidate['country'] ?? '');

        return Str::contains(Str::lower($country), Str::lower(trim($targetCountry)))
            || Str::contains(Str::lower(trim($targetCountry)), Str::lower($country));
    }

    /**
     * @param  array<string, mixed>  $candidate
     */
    private function matchNiche(array $candidate, string $targetNiche): bool
    {
        $niche = (string) ($candidate['niche'] ?? '');

        return $niche !== '' && Str::contains(Str::lower($niche), Str::lower($targetNiche));
    }

    /**
     * @param  array<string, mixed>  $candidate
     */
    private function matchService(array $candidate, string $targetService): bool
    {
        $niche = (string) ($candidate['niche'] ?? '');
        $raw = $candidate['raw_payload'] ?? [];
        $targetServiceLower = Str::lower($targetService);
        if ($niche !== '' && Str::contains(Str::lower($niche), $targetServiceLower)) {
            return true;
        }
        foreach ((array) $raw as $v) {
            if (is_string($v) && Str::contains(Str::lower($v), $targetServiceLower)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $candidate
     */
    private function contactCompletenessScore(array $candidate): int
    {
        $score = 0;
        if (! empty(trim((string) ($candidate['email'] ?? '')))) {
            $score += 50;
        }
        if (! empty(trim((string) ($candidate['phone'] ?? '')))) {
            $score += 30;
        }
        if (! empty(trim((string) ($candidate['website'] ?? '')))) {
            $score += 20;
        }

        return min(100, $score);
    }
}
