<?php

namespace App\Models;

use App\Services\SubscriptionService;
use App\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use Billable, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'oauth_provider',
        'oauth_id',
        'avatar',
        'status',
        'status_changed_at',
        'status_changed_by',
        'onboarding_completed_at',
        'company_name',
        'phone',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'status_changed_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
        ];
    }

    public function hasCompletedOnboarding(): bool
    {
        return $this->onboarding_completed_at !== null;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function statusChangedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_changed_by');
    }

    public function currentPlan(): Plan
    {
        return app(SubscriptionService::class)->getPlanForUser($this);
    }

    public function bookmarkedLeads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'lead_user_bookmarks')->withPivot('created_at');
    }

    public function leadLists(): HasMany
    {
        return $this->hasMany(LeadList::class);
    }

    public function sharedLeadLists(): BelongsToMany
    {
        return $this->belongsToMany(LeadList::class, 'lead_list_user')->withTimestamps();
    }

    public function savedFilters(): HasMany
    {
        return $this->hasMany(SavedFilter::class, 'user_id');
    }

    public function planUsages(): HasMany
    {
        return $this->hasMany(PlanUsage::class);
    }
}
