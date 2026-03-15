<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedFilter extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'criteria',
        'usage_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'criteria' => 'array',
            'usage_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function matches(Lead $lead): bool
    {
        $c = $this->criteria ?? [];
        if (isset($c['industry']) && filled($c['industry']) && (string) $lead->industry !== (string) $c['industry']) {
            return false;
        }
        if (isset($c['niche']) && filled($c['niche']) && (string) $lead->niche !== (string) $c['niche']) {
            return false;
        }
        if (isset($c['country']) && filled($c['country']) && (string) $lead->country !== (string) $c['country']) {
            return false;
        }
        if (isset($c['city']) && filled($c['city']) && (string) $lead->city !== (string) $c['city']) {
            return false;
        }
        if (isset($c['job_title']) && filled($c['job_title']) && (string) $lead->job_title !== (string) $c['job_title']) {
            return false;
        }
        if (isset($c['company_size']) && filled($c['company_size']) && (string) $lead->company_size !== (string) $c['company_size']) {
            return false;
        }
        if (isset($c['revenue_range']) && filled($c['revenue_range']) && (string) $lead->revenue_range !== (string) $c['revenue_range']) {
            return false;
        }
        if (isset($c['lead_source']) && filled($c['lead_source']) && (string) $lead->lead_source !== (string) $c['lead_source']) {
            return false;
        }
        if (isset($c['verification_status']) && filled($c['verification_status'])) {
            $leadStatus = $lead->verification_status?->value ?? '';
            if ($leadStatus !== (string) $c['verification_status']) {
                return false;
            }
        }
        if (isset($c['quality_score_min']) && $c['quality_score_min'] !== '' && $c['quality_score_min'] !== null) {
            $min = (int) $c['quality_score_min'];
            if (($lead->quality_score ?? 0) < $min) {
                return false;
            }
        }
        if (! empty($c['exclude_duplicates']) && $lead->is_duplicate === true) {
            return false;
        }
        if (isset($c['freshness']) && filled($c['freshness'])) {
            $fresh = $lead->freshness()->value;
            if ($fresh !== (string) $c['freshness']) {
                return false;
            }
        }
        if (isset($c['recently_added_days']) && filled($c['recently_added_days'])) {
            $days = (int) $c['recently_added_days'];
            if (! $lead->created_at || $lead->created_at->lt(now()->subDays($days))) {
                return false;
            }
        }
        if (! empty($c['has_email']) && (empty($lead->email) || $lead->email === '')) {
            return false;
        }
        if (! empty($c['has_phone']) && (empty($lead->phone) || $lead->phone === '')) {
            return false;
        }
        if (! empty($c['has_linkedin']) && (empty($lead->linkedin_profile) || $lead->linkedin_profile === '')) {
            return false;
        }
        if (isset($c['q']) && filled($c['q'])) {
            $q = (string) $c['q'];
            $match = stripos((string) $lead->full_name, $q) !== false
                || stripos((string) $lead->email, $q) !== false
                || stripos((string) $lead->company_name, $q) !== false
                || stripos((string) $lead->job_title, $q) !== false
                || stripos((string) $lead->industry, $q) !== false;
            if (! $match) {
                return false;
            }
        }

        return true;
    }
}
