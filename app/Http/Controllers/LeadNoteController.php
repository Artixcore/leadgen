<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadNoteRequest;
use App\Models\Lead;
use App\Models\LeadNote;
use Illuminate\Http\RedirectResponse;

class LeadNoteController extends Controller
{
    public function store(StoreLeadNoteRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorize('view', $lead);

        $lead->notes()->create([
            'user_id' => $request->user()->id,
            'body' => $request->validated('body'),
        ]);

        return back()->with('status', __('Note added.'));
    }

    public function destroy(LeadNote $note): RedirectResponse
    {
        $this->authorize('view', $note->lead);

        if ($note->user_id !== request()->user()->id) {
            abort(403);
        }

        $note->delete();

        return back()->with('status', __('Note deleted.'));
    }
}
