<?php

namespace App\Models;

use App\ImportRowStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadImportRow extends Model
{
    protected $fillable = [
        'lead_import_run_id',
        'row_index',
        'raw_data',
        'normalized_data',
        'status',
        'lead_id',
        'validation_errors',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'raw_data' => 'array',
            'normalized_data' => 'array',
            'status' => ImportRowStatus::class,
            'validation_errors' => 'array',
        ];
    }

    public function leadImportRun(): BelongsTo
    {
        return $this->belongsTo(LeadImportRun::class, 'lead_import_run_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
