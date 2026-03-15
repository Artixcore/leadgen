<?php

namespace App\Services\LeadSearch\DTO;

readonly class LeadSearchIntentDTO
{
    public function __construct(
        public ?string $targetService = null,
        public ?string $targetNiche = null,
        public ?string $targetCountry = null,
        public ?string $targetCity = null,
        public ?string $companySize = null,
        /** @var array<int, string> */
        public array $opportunitySignals = [],
        /** @var array<int, string> */
        public array $sourceHints = [],
        public ?int $minScore = null,
        public bool $verifiedOnly = false,
        public bool $includeWebsiteAnalysis = true,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'target_service' => $this->targetService,
            'target_niche' => $this->targetNiche,
            'target_country' => $this->targetCountry,
            'target_city' => $this->targetCity,
            'company_size' => $this->companySize,
            'opportunity_signals' => $this->opportunitySignals,
            'source_hints' => $this->sourceHints,
            'min_score' => $this->minScore,
            'verified_only' => $this->verifiedOnly,
            'include_website_analysis' => $this->includeWebsiteAnalysis,
        ];
    }
}
