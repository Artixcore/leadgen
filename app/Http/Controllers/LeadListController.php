<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadListRequest;
use App\Http\Requests\UpdateLeadListRequest;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadListController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', LeadList::class);

        $user = request()->user();
        $lists = LeadList::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('sharedWithUsers', fn ($q2) => $q2->where('user_id', $user->id));
            })
            ->withCount('leads')
            ->latest()
            ->get();

        $subscription = app(SubscriptionService::class);
        $ownedCount = $user->leadLists()->count();
        $canCreateList = $subscription->userCanCreateList($user, $ownedCount);

        return view('lists.index', [
            'lists' => $lists,
            'canCreateList' => $canCreateList,
        ]);
    }

    public function store(StoreLeadListRequest $request): RedirectResponse
    {
        $user = $request->user();
        $subscription = app(SubscriptionService::class);

        if (! $subscription->userCanCreateList($user, $user->leadLists()->count())) {
            return back()->with('error', __('You have reached your saved lists limit. Please upgrade your plan.'));
        }

        $list = $user->leadLists()->create($request->validated());
        $list->logActivity('list_created');

        return back()->with('status', __('List created.'));
    }

    public function show(LeadList $list): View
    {
        $this->authorize('view', $list);

        $leads = $list->leads()
            ->orderByPivot('created_at', 'desc')
            ->paginate(15);

        $list->load(['sharedWithUsers']);
        $activities = $list->activities()->with('user')->limit(30)->get();
        $activityLeadIds = $activities->whereIn('action', ['lead_added', 'lead_removed'])->pluck('subject_id')->filter()->unique()->values()->all();
        $activityLeads = $activityLeadIds ? Lead::whereIn('id', $activityLeadIds)->get()->keyBy('id') : collect();

        return view('lists.show', [
            'list' => $list,
            'leads' => $leads,
            'activities' => $activities,
            'activityLeads' => $activityLeads,
        ]);
    }

    public function update(UpdateLeadListRequest $request, LeadList $list): RedirectResponse
    {
        $oldName = $list->name;
        $list->update($request->validated());
        if ($oldName !== $list->name) {
            $list->logActivity('list_renamed', null, null, null, ['old_name' => $oldName]);
        }

        return redirect()->route('lists.show', $list)->with('status', __('List updated.'));
    }

    public function destroy(LeadList $list): RedirectResponse
    {
        $this->authorize('delete', $list);

        $list->delete();

        return redirect()->route('lists.index')->with('status', __('List deleted.'));
    }

    public function addLead(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorize('view', $lead);

        if (! request()->user()->can('manage-lists')) {
            abort(403);
        }

        $request->validate(['list_id' => ['required', 'integer', 'exists:lead_lists,id']]);

        $list = LeadList::where('id', $request->list_id)->where('user_id', request()->user()->id)->firstOrFail();
        $list->leads()->syncWithoutDetaching([$lead->id]);
        $list->logActivity('lead_added', request()->user()->id, 'lead', $lead->id);

        return back()->with('status', __('Lead added to list.'));
    }

    public function removeLead(LeadList $list, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $list);

        if (! $list->leads()->where('leads.id', $lead->id)->exists()) {
            return back()->with('error', __('Lead is not in this list.'));
        }

        $list->leads()->detach($lead->id);
        $list->logActivity('lead_removed', request()->user()->id, 'lead', $lead->id);

        return back()->with('status', __('Lead removed from list.'));
    }

    public function share(Request $request, LeadList $list): RedirectResponse
    {
        $this->authorize('update', $list);

        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $shareWithUser = User::where('email', $request->email)->firstOrFail();
        if ($shareWithUser->id === $list->user_id) {
            return back()->with('error', __('You cannot share a list with yourself.'));
        }
        if ($list->sharedWithUsers()->where('user_id', $shareWithUser->id)->exists()) {
            return back()->with('error', __('List is already shared with this user.'));
        }

        $list->sharedWithUsers()->syncWithoutDetaching([$shareWithUser->id]);

        return back()->with('status', __('List shared with :name.', ['name' => $shareWithUser->name]));
    }

    public function unshare(LeadList $list, User $user): RedirectResponse
    {
        $this->authorize('update', $list);

        $list->sharedWithUsers()->detach($user->id);

        return back()->with('status', __('List access removed for :name.', ['name' => $user->name]));
    }
}
