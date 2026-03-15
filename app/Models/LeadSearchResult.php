<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadSearchResult extends Model
{
    protected $fillable = [
        'lead_search_query_id',
        'source_name',
        'source_type',
        'company_name',
        'website',
        'email',
        'phone',
        'niche',
        'city',
        'country',
        'trust_score',
        'relevance_score',
        'opportunity_score',
        'verification_status',
        'explanation',
        'recommended_pitch',
        'raw_payload',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'trust_score' => 'integer',
            'relevance_score' => 'integer',
            'opportunity_score' => 'integer',
            'raw_payload' => 'array',
        ];
    }

    public function leadSearchQuery(): BelongsTo
    {
        return $this->belongsTo(LeadSearchQuery::class);
    }

    /**
     * @return HasMany<LeadSearchResultSignal, $this>
     */
    public function signals(): HasMany
    {
        return $this->hasMany(LeadSearchResultSignal::class, 'lead_search_result_id');
    }
}
