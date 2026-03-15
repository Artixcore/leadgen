<?php

namespace App\Services\LeadSearch;

use App\Models\LeadSearchQuery;
use App\Models\LeadSearchResult;
use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;
use Illuminate\Support\Facades\Log;

class LeadSearchPipeline
{
    public function __construct(
        protected LeadSearchProviderManager $providerManager,
        protected LeadResultAggregator $aggregator,
        protected LeadSearchRankingService $rankingService,
        protected LeadOpportunityExplainerService $explainerService
    ) {}

    /**
     * Run the full pipeline: search providers, aggregate, rank, explain, persist.
     */
    public function run(LeadSearchQuery $searchQuery, LeadSearchIntentDTO $intent): LeadSearchQuery
    {
        $start = microtime(true);
        $searchQuery->update(['status' => 'running']);

        Log::info('Lead search started', ['query_id' => $searchQuery->id, 'user_id' => $searchQuery->user_id]);

        try {
            $providerResults = [];
            foreach ($this->providerManager->enabledProviders() as $providerInfo) {
                $slug = $providerInfo['slug'];
                $name = $providerInfo['name'];
                $instance = $providerInfo['instance'];
                try {
                    $candidates = $instance->search($intent);
                    $providerResults[] = [
                        'source_name' => $name,
                        'source_type' => $slug,
                        'candidates' => $candidates,
                    ];
                } catch (\Throwable $e) {
                    Log::warning('Lead search provider failed', [
                        'query_id' => $searchQuery->id,
                        'provider' => $slug,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $candidates = $this->aggregator->aggregate($providerResults);
            $candidates = $this->rankingService->rank($candidates, $intent);
            $candidates = $this->explainerService->addExplanations($candidates);

            $minScore = $intent->minScore;
            if ($minScore !== null) {
                $candidates = array_values(array_filter($candidates, fn ($c) => ($c['relevance_score'] ?? 0) >= $minScore));
            }

            foreach ($candidates as $candidate) {
                LeadSearchResult::create([
                    'lead_search_query_id' => $searchQuery->id,
                    'source_name' => $candidate['source_name'] ?? 'unknown',
                    'source_type' => $candidate['source_type'] ?? null,
                    'company_name' => $candidate['company_name'] ?? '',
                    'website' => $candidate['website'] ?? null,
                    'email' => $candidate['email'] ?? null,
                    'phone' => $candidate['phone'] ?? null,
                    'niche' => $candidate['niche'] ?? null,
                    'city' => $candidate['city'] ?? null,
                    'country' => $candidate['country'] ?? null,
                    'trust_score' => $candidate['trust_score'] ?? 50,
                    'relevance_score' => $candidate['relevance_score'] ?? 0,
                    'opportunity_score' => $candidate['opportunity_score'] ?? 0,
                    'verification_status' => $candidate['verification_status'] ?? null,
                    'explanation' => $candidate['explanation'] ?? null,
                    'recommended_pitch' => $candidate['recommended_pitch'] ?? null,
                    'raw_payload' => $candidate['raw_payload'] ?? null,
                ]);
            }

            $tookMs = (int) round((microtime(true) - $start) * 1000);
            $searchQuery->update([
                'status' => 'completed',
                'total_results' => count($candidates),
                'search_took_ms' => $tookMs,
            ]);

            Log::info('Lead search completed', [
                'query_id' => $searchQuery->id,
                'total_results' => count($candidates),
                'took_ms' => $tookMs,
            ]);
        } catch (\Throwable $e) {
            Log::error('Lead search failed', ['query_id' => $searchQuery->id, 'error' => $e->getMessage()]);
            $searchQuery->update(['status' => 'failed']);
            throw $e;
        }

        return $searchQuery;
    }
}
