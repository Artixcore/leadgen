<?php

namespace App\Services\LeadSearch;

class LeadResultAggregator
{
    public function __construct(
        protected LeadResultNormalizer $normalizer,
        protected LeadResultDeduplicator $deduplicator
    ) {}

    /**
     * Aggregate raw results from multiple providers: normalize each, then deduplicate.
     * Each item in $providerResults is ['source_name' => string, 'source_type' => string, 'candidates' => array].
     *
     * @param  array<int, array{source_name: string, source_type: string, candidates: array<int, array<string, mixed>>}>  $providerResults
     * @return array<int, array<string, mixed>>
     */
    public function aggregate(array $providerResults): array
    {
        $all = [];
        foreach ($providerResults as $result) {
            $sourceName = $result['source_name'] ?? 'unknown';
            $sourceType = $result['source_type'] ?? 'api';
            $candidates = $result['candidates'] ?? [];
            foreach ($candidates as $raw) {
                $all[] = $this->normalizer->normalize($raw, $sourceName, $sourceType);
            }
        }

        return $this->deduplicator->deduplicate($all);
    }
}
