<?php

namespace App\Models;

use App\RawLeadRecordStatus;
use Database\Factories\RawLeadRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @use HasFactory<RawLeadRecordFactory>
 */
class RawLeadRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_collector_id',
        'lead_collector_run_id',
        'source_record_id',
        'company_name',
        'website',
        'email',
        'phone',
        'address',
        'country',
        'city',
        'niche',
        'source_url',
        'raw_payload',
        'normalized_payload',
        'verification_status',
        'processing_status',
        'dedupe_hash',
        'discovered_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'raw_payload' => 'array',
            'normalized_payload' => 'array',
            'processing_status' => RawLeadRecordStatus::class,
            'discovered_at' => 'datetime',
        ];
    }

    public function leadCollector(): BelongsTo
    {
        return $this->belongsTo(LeadCollector::class, 'lead_collector_id');
    }

    public function leadCollectorRun(): BelongsTo
    {
        return $this->belongsTo(LeadCollectorRun::class, 'lead_collector_run_id');
    }
}
