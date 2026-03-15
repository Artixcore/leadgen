<?php

namespace App\Policies;

use App\Models\LeadSearchQuery;
use App\Models\SavedLeadSearch;
use App\Models\User;

class LeadSearchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('use-lead-search') || $user->can('manage-lead-search');
    }

    public function view(User $user, LeadSearchQuery|SavedLeadSearch $model): bool
    {
        if ($user->can('manage-lead-search')) {
            return true;
        }
        if ($model instanceof SavedLeadSearch) {
            return $model->user_id === $user->id;
        }

        return $user->can('use-lead-search') && $model->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('use-lead-search');
    }

    public function viewSavedSearch(User $user, SavedLeadSearch $savedLeadSearch): bool
    {
        return $this->view($user, $savedLeadSearch);
    }

    public function createSavedSearch(User $user): bool
    {
        return $user->can('use-lead-search');
    }

    public function update(User $user, SavedLeadSearch $savedLeadSearch): bool
    {
        return $savedLeadSearch->user_id === $user->id;
    }

    public function updateSavedSearch(User $user, SavedLeadSearch $savedLeadSearch): bool
    {
        return $this->update($user, $savedLeadSearch);
    }

    public function delete(User $user, SavedLeadSearch $savedLeadSearch): bool
    {
        return $savedLeadSearch->user_id === $user->id;
    }

    public function deleteSavedSearch(User $user, SavedLeadSearch $savedLeadSearch): bool
    {
        return $this->delete($user, $savedLeadSearch);
    }
}
