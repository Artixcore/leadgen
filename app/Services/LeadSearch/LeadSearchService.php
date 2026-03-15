<?php

namespace App\Services\LeadSearch;

use App\Jobs\RunLeadSearchJob;
use App\Models\LeadSearchQuery;
use App\Models\User;
use App\Services\LeadSearch\DTO\LeadSearchIntentDTO;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Log;

class LeadSearchService
{
    public function __construct(
        protected LeadQueryParserService $parser,
        protected LeadSearchPipeline $pipeline,
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * Create a search query, run pipeline synchronously, and return the updated query.
     *
     * @param  array<string, mixed>  $filters
     */
    public function runSearch(User $user, string $query, array $filters = []): LeadSearchQuery
    {
        $this->subscriptionService->incrementLeadSearchCount($user);

        $intent = $this->parser->parse($query, $filters);
        $searchQuery = LeadSearchQuery::create([
            'user_id' => $user->id,
            'query' => $query,
            'parsed_query_json' => $intent->toArray(),
            'target_service' => $intent->targetService,
            'target_niche' => $intent->targetNiche,
            'target_country' => $intent->targetCountry,
            'target_city' => $intent->targetCity,
            'filters_json' => $filters,
            'status' => 'pending',
        ]);

        return $this->pipeline->run($searchQuery, $intent);
    }

    /**
     * Create a search query and dispatch job for async execution. Returns the query (status pending).
     *
     * @param  array<string, mixed>  $filters
     */
    public function runSearchAsync(User $user, string $query, array $filters = []): LeadSearchQuery
    {
        $this->subscriptionService->incrementLeadSearchCount($user);

        $intent = $this->parser->parse($query, $filters);
        $searchQuery = LeadSearchQuery::create([
            'user_id' => $user->id,
            'query' => $query,
            'parsed_query_json' => $intent->toArray(),
            'target_service' => $intent->targetService,
            'target_niche' => $intent->targetNiche,
            'target_country' => $intent->targetCountry,
            'target_city' => $intent->targetCity,
            'filters_json' => $filters,
            'status' => 'pending',
        ]);

        RunLeadSearchJob::dispatch($searchQuery);

        Log::info('Lead search dispatched async', ['query_id' => $searchQuery->id, 'user_id' => $user->id]);

        return $searchQuery;
    }

    /**
     * Run pipeline for an existing query (e.g. from job or re-run).
     */
    public function runPipeline(LeadSearchQuery $searchQuery): LeadSearchQuery
    {
        $parsed = $searchQuery->parsed_query_json ?? [];
        $filters = $searchQuery->filters_json ?? [];
        $intent = new LeadSearchIntentDTO(
            targetService: $parsed['target_service'] ?? $searchQuery->target_service,
            targetNiche: $parsed['target_niche'] ?? $searchQuery->target_niche,
            targetCountry: $parsed['target_country'] ?? $searchQuery->target_country,
            targetCity: $parsed['target_city'] ?? $searchQuery->target_city,
            companySize: $filters['company_size'] ?? $parsed['company_size'] ?? null,
            opportunitySignals: $parsed['opportunity_signals'] ?? [],
            sourceHints: $parsed['source_hints'] ?? [],
            minScore: $filters['min_score'] ?? $parsed['min_score'] ?? null,
            verifiedOnly: (bool) ($filters['verified_only'] ?? $parsed['verified_only'] ?? false),
            includeWebsiteAnalysis: (bool) ($parsed['include_website_analysis'] ?? true),
        );

        return $this->pipeline->run($searchQuery, $intent);
    }
}
