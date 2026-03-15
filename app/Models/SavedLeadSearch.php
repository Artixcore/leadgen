<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedLeadSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'query',
        'parsed_query_json',
        'filters_json',
        'auto_refresh_frequency',
        'is_active',
        'last_run_at',
        'next_run_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'parsed_query_json' => 'array',
            'filters_json' => 'array',
            'is_active' => 'boolean',
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
