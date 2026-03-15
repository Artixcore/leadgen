<?php

namespace App\Models;

use Database\Factories\LeadCollectorRuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @use HasFactory<LeadCollectorRuleFactory>
 */
class LeadCollectorRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_collector_id',
        'rule_key',
        'rule_operator',
        'rule_value',
        'score_weight',
        'is_required',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
        ];
    }

    public function leadCollector(): BelongsTo
    {
        return $this->belongsTo(LeadCollector::class, 'lead_collector_id');
    }
}
