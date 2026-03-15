<?php

namespace App\Models;

use App\LeadSourceStatus;
use App\LeadSourceType;
use Database\Factories\LeadSourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadSource extends Model
{
    /** @use HasFactory<LeadSourceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'status',
        'is_trusted',
        'reliability_score',
        'last_sync_at',
        'import_frequency',
        'validation_rules',
        'config',
        'created_by',
        'updated_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => LeadSourceType::class,
            'status' => LeadSourceStatus::class,
            'is_trusted' => 'boolean',
            'last_sync_at' => 'datetime',
            'validation_rules' => 'array',
            'config' => 'array',
        ];
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'lead_source_id');
    }

    public function importRuns(): HasMany
    {
        return $this->hasMany(LeadImportRun::class, 'lead_source_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', LeadSourceStatus::Active);
    }
}
