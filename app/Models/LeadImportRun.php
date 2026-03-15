<?php

namespace App\Models;

use App\ImportRunStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadImportRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_source_id',
        'triggered_by',
        'status',
        'started_at',
        'completed_at',
        'error_message',
        'stats',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ImportRunStatus::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'stats' => 'array',
        ];
    }

    public function leadSource(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function rows(): HasMany
    {
        return $this->hasMany(LeadImportRow::class, 'lead_import_run_id');
    }
}
