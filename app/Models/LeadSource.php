<?php

namespace App\Models;

use App\LeadSourceStatus;
use App\LeadSourceType;
use Database\Factories\LeadSourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadSource extends Model
{
    /** @use HasFactory<LeadSourceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'status',
        'reliability_score',
        'last_sync_at',
        'import_frequency',
        'validation_rules',
        'config',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => LeadSourceType::class,
            'status' => LeadSourceStatus::class,
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

    public function scopeActive($query)
    {
        return $query->where('status', LeadSourceStatus::Active);
    }
}
