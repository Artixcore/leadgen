<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'stripe_product_id',
        'stripe_price_id_monthly',
        'stripe_price_id_yearly',
        'leads_per_month',
        'exports_per_month',
        'saved_lists_count',
        'team_members_limit',
        'api_access',
        'advanced_filters',
        'lead_search_searches_per_month',
        'saved_lead_searches_limit',
        'lead_search_full_contact',
        'trial_days',
        'sort_order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'api_access' => 'boolean',
            'advanced_filters' => 'boolean',
            'lead_search_full_contact' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function isFree(): bool
    {
        return $this->slug === 'free';
    }

    public function hasApiAccess(): bool
    {
        return $this->api_access;
    }

    public function hasAdvancedFilters(): bool
    {
        return $this->advanced_filters;
    }

    public function hasTeamMembers(): bool
    {
        return $this->team_members_limit > 0;
    }

    /**
     * @return int|null Null means unlimited.
     */
    public function leadsLimit(): ?int
    {
        return $this->leads_per_month;
    }

    public function exportsLimit(): int
    {
        return $this->exports_per_month;
    }

    public function savedListsLimit(): int
    {
        return $this->saved_lists_count;
    }

    public function teamMembersLimit(): int
    {
        return $this->team_members_limit;
    }

    /**
     * @return int|null Null means unlimited lead searches per month.
     */
    public function leadSearchSearchesLimit(): ?int
    {
        return $this->lead_search_searches_per_month;
    }

    public function savedLeadSearchesLimit(): int
    {
        return (int) ($this->saved_lead_searches_limit ?? 0);
    }

    public function leadSearchFullContact(): bool
    {
        return (bool) ($this->lead_search_full_contact ?? false);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function stripePriceIdForInterval(string $interval): ?string
    {
        return match ($interval) {
            'monthly' => $this->stripe_price_id_monthly,
            'yearly' => $this->stripe_price_id_yearly,
            default => null,
        };
    }
}
