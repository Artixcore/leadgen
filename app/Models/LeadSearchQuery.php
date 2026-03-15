<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadSearchQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'query',
        'parsed_query_json',
        'target_service',
        'target_niche',
        'target_country',
        'target_city',
        'filters_json',
        'status',
        'total_results',
        'search_took_ms',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'parsed_query_json' => 'array',
            'filters_json' => 'array',
            'total_results' => 'integer',
            'search_took_ms' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<LeadSearchResult, $this>
     */
    public function results(): HasMany
    {
        return $this->hasMany(LeadSearchResult::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
