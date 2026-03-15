<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadReminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadReminderController extends Controller
{
    public function store(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorize('view', $lead);

        $request->validate([
            'remind_at' => ['required', 'date', 'after:now'],
            'body' => ['sometimes', 'nullable', 'string', 'max:65535'],
        ]);

        $lead->reminders()->create([
            'user_id' => $request->user()->id,
            'remind_at' => $request->remind_at,
            'body' => $request->input('body'),
        ]);

        return redirect()->route('leads.show', $lead)->with('status', __('Reminder added.'));
    }

    public function destroy(LeadReminder $reminder): RedirectResponse
    {
        $this->authorize('delete', $reminder);

        $lead = $reminder->lead;
        $reminder->delete();

        return redirect()->route('leads.show', $lead)->with('status', __('Reminder deleted.'));
    }
}
