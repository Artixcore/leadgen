<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadSearchProvider extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'provider_class',
        'source_type',
        'status',
        'priority',
        'config_json',
        'trust_score',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'config_json' => 'array',
            'priority' => 'integer',
            'trust_score' => 'integer',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
