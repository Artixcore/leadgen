<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadReminderRequest;
use App\Models\Lead;
use App\Models\LeadReminder;
use Illuminate\Http\RedirectResponse;

class LeadReminderController extends Controller
{
    public function store(StoreLeadReminderRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorize('view', $lead);

        $lead->reminders()->create([
            'user_id' => $request->user()->id,
            'remind_at' => $request->validated('remind_at'),
            'body' => $request->validated('body'),
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
