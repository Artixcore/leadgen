<?php

namespace App\Models;

use App\CollectorStatus;
use App\CollectorType;
use App\LeadCollectorSourceType;
use Database\Factories\LeadCollectorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LeadCollector extends Model
{
    /** @use HasFactory<LeadCollectorFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'source_name',
        'source_type',
        'type',
        'target_service',
        'target_niche',
        'target_country',
        'target_city',
        'keywords',
        'filters_json',
        'config',
        'config_encrypted',
        'trust_score',
        'priority',
        'status',
        'is_active',
        'schedule',
        'lead_source_id',
        'last_run_at',
        'next_run_at',
        'created_by',
        'updated_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => CollectorType::class,
            'status' => CollectorStatus::class,
            'source_type' => LeadCollectorSourceType::class,
            'config' => 'array',
            'config_encrypted' => 'encrypted:array',
            'filters_json' => 'array',
            'is_active' => 'boolean',
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (LeadCollector $collector) {
            if (empty($collector->slug) && ! empty($collector->name)) {
                $collector->slug = Str::slug($collector->name);
            }
        });
    }

    /**
     * Get config merged with decrypted sensitive config (e.g. API keys).
     *
     * @return array<string, mixed>
     */
    public function getMergedConfig(): array
    {
        return array_merge(
            (array) ($this->config ?? []),
            (array) ($this->config_encrypted ?? [])
        );
    }

    public function leadSource(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function importRuns(): HasMany
    {
        return $this->hasMany(LeadImportRun::class, 'lead_collector_id');
    }

    public function runs(): HasMany
    {
        return $this->hasMany(LeadCollectorRun::class, 'lead_collector_id');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(LeadCollectorRule::class, 'lead_collector_id');
    }

    public function rawLeadRecords(): HasMany
    {
        return $this->hasMany(RawLeadRecord::class, 'lead_collector_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', CollectorStatus::Active);
    }
}
