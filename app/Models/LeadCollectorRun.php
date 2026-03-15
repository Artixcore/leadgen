<?php

namespace App\Models;

use App\LeadCollectorRunStatus;
use Database\Factories\LeadCollectorRunFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @use HasFactory<LeadCollectorRunFactory>
 */
class LeadCollectorRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_collector_id',
        'run_type',
        'status',
        'total_found',
        'total_processed',
        'total_new',
        'total_duplicates',
        'total_failed',
        'started_at',
        'finished_at',
        'notes',
        'error_message',
        'meta_json',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => LeadCollectorRunStatus::class,
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'meta_json' => 'array',
        ];
    }

    public function leadCollector(): BelongsTo
    {
        return $this->belongsTo(LeadCollector::class, 'lead_collector_id');
    }

    public function rawLeadRecords(): HasMany
    {
        return $this->hasMany(RawLeadRecord::class, 'lead_collector_run_id');
    }
}
