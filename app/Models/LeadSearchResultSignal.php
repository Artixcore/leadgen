<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadSearchResultSignal extends Model
{
    protected $fillable = [
        'lead_search_result_id',
        'signal_key',
        'signal_value',
        'score_impact',
        'explanation',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score_impact' => 'integer',
        ];
    }

    public function leadSearchResult(): BelongsTo
    {
        return $this->belongsTo(LeadSearchResult::class, 'lead_search_result_id');
    }
}
