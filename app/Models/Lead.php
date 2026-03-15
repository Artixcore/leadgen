<?php

namespace App\Models;

use App\LeadFreshness;
use App\LeadStatus;
use App\VerificationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'job_title',
        'email',
        'phone',
        'company_name',
        'website',
        'linkedin_profile',
        'country',
        'state',
        'city',
        'industry',
        'niche',
        'company_size',
        'revenue_range',
        'lead_source_id',
        'lead_source',
        'verification_status',
        'quality_score',
        'is_duplicate',
        'duplicate_of_lead_id',
        'notes',
        'lead_status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verification_status' => VerificationStatus::class,
            'lead_status' => LeadStatus::class,
            'is_duplicate' => 'boolean',
            'quality_score' => 'integer',
        ];
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'lead_tag')->withTimestamps();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class);
    }

    public function leadLists(): BelongsToMany
    {
        return $this->belongsToMany(LeadList::class, 'lead_lead_list')->withTimestamps();
    }

    public function bookmarkedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lead_user_bookmarks')->withPivot('created_at');
    }

    public function leadSource(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(LeadReminder::class);
    }

    public function duplicateOf(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'duplicate_of_lead_id');
    }

    /**
     * @return HasMany<Lead, $this>
     */
    public function duplicates(): HasMany
    {
        return $this->hasMany(Lead::class, 'duplicate_of_lead_id');
    }

    public function freshness(): LeadFreshness
    {
        $updated = $this->updated_at;
        if (! $updated) {
            return LeadFreshness::Unknown;
        }
        $days = $updated->diffInDays(now(), false);
        if ($days < 30) {
            return LeadFreshness::Fresh;
        }
        if ($days <= 90) {
            return LeadFreshness::Stale;
        }

        return LeadFreshness::Unknown;
    }

    public function scopeSearch(Builder $query, ?string $q): Builder
    {
        if (blank($q)) {
            return $query;
        }

        return $query->where(function (Builder $qry) use ($q) {
            $qry->where('full_name', 'like', '%'.$q.'%')
                ->orWhere('email', 'like', '%'.$q.'%')
                ->orWhere('company_name', 'like', '%'.$q.'%')
                ->orWhere('job_title', 'like', '%'.$q.'%')
                ->orWhere('industry', 'like', '%'.$q.'%');
        });
    }

    public function scopeHasEmail(Builder $query, bool $value = true): Builder
    {
        if (! $value) {
            return $query;
        }

        return $query->whereNotNull('email')->where('email', '!=', '');
    }

    public function scopeHasPhone(Builder $query, bool $value = true): Builder
    {
        if (! $value) {
            return $query;
        }

        return $query->whereNotNull('phone')->where('phone', '!=', '');
    }

    public function scopeHasLinkedIn(Builder $query, bool $value = true): Builder
    {
        if (! $value) {
            return $query;
        }

        return $query->whereNotNull('linkedin_profile')->where('linkedin_profile', '!=', '');
    }

    public function scopeRecentlyAdded(Builder $query, ?int $days): Builder
    {
        if ($days === null || $days < 1) {
            return $query;
        }

        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeFilterByQualityScoreMin(Builder $query, ?int $min): Builder
    {
        if ($min === null) {
            return $query;
        }

        return $query->where('quality_score', '>=', $min);
    }

    public function scopeExcludeDuplicates(Builder $query, bool $value = true): Builder
    {
        if (! $value) {
            return $query;
        }

        return $query->where('is_duplicate', false);
    }

    public function scopeFilterByFreshness(Builder $query, ?string $freshness): Builder
    {
        if (blank($freshness)) {
            return $query;
        }

        $enum = LeadFreshness::tryFrom($freshness);
        if (! $enum) {
            return $query;
        }

        return $query->where(function (Builder $qry) use ($enum) {
            if ($enum === LeadFreshness::Fresh) {
                $qry->where('updated_at', '>=', now()->subDays(30));
            } elseif ($enum === LeadFreshness::Stale) {
                $qry->whereBetween('updated_at', [now()->subDays(90), now()->subDays(30)]);
            } else {
                $qry->whereNull('updated_at')->orWhere('updated_at', '<', now()->subDays(90));
            }
        });
    }

    public function scopeApplySort(Builder $query, string $sort = 'newest', string $dir = 'desc'): Builder
    {
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        return match ($sort) {
            'highest_quality' => $query->orderBy('quality_score', $dir)->orderBy('created_at', 'desc'),
            'most_relevant' => $query->orderByRaw('COALESCE(quality_score, 0) DESC')->orderBy('updated_at', 'desc'),
            default => $query->latest(),
        };
    }
}
