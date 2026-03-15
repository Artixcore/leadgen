<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadList extends Model
{
    use SoftDeletes;

    protected $table = 'lead_lists';

    protected $fillable = [
        'user_id',
        'name',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'lead_list_items')->withTimestamps();
    }

    public function sharedWithUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lead_list_user')->withTimestamps();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadListActivity::class)->latest();
    }

    public function logActivity(string $action, ?int $userId = null, ?string $subjectType = null, ?int $subjectId = null, ?array $meta = null): LeadListActivity
    {
        return $this->activities()->create([
            'user_id' => $userId ?? $this->user_id,
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'meta' => $meta,
        ]);
    }
}
