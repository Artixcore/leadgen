<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\RedirectResponse;

class LeadBookmarkController extends Controller
{
    public function store(Lead $lead): RedirectResponse
    {
        $this->authorize('view', $lead);

        if (! request()->user()->can('bookmark-leads')) {
            abort(403);
        }

        request()->user()->bookmarkedLeads()->syncWithoutDetaching([$lead->id]);

        return back()->with('status', __('Lead bookmarked.'));
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->authorize('view', $lead);

        if (! request()->user()->can('bookmark-leads')) {
            abort(403);
        }

        request()->user()->bookmarkedLeads()->detach($lead->id);

        return back()->with('status', __('Bookmark removed.'));
    }
}
