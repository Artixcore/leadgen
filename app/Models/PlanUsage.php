<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanUsage extends Model
{
    protected $fillable = [
        'user_id',
        'period',
        'leads_count',
        'exports_count',
        'lead_search_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'leads_count' => 'integer',
            'exports_count' => 'integer',
            'lead_search_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function currentPeriod(): string
    {
        return now()->format('Y-m');
    }
}
