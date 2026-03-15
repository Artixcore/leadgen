<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchLeadsRequest;
use App\Http\Requests\UpdateLeadStatusRequest;
use App\Models\Lead;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(SearchLeadsRequest $request): View
    {
        $this->authorize('viewAny', Lead::class);

        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 15;

        $query = Lead::query()
            ->search($validated['q'] ?? null)
            ->when(! empty($validated['industry']), fn ($q) => $q->where('industry', $validated['industry']))
            ->when(! empty($validated['niche']), fn ($q) => $q->where('niche', $validated['niche']))
            ->when(! empty($validated['country']), fn ($q) => $q->where('country', $validated['country']))
            ->when(! empty($validated['city']), fn ($q) => $q->where('city', $validated['city']))
            ->when(! empty($validated['job_title']), fn ($q) => $q->where('job_title', $validated['job_title']))
            ->when(! empty($validated['company_size']), fn ($q) => $q->where('company_size', $validated['company_size']))
            ->when(! empty($validated['revenue_range']), fn ($q) => $q->where('revenue_range', $validated['revenue_range']))
            ->when(! empty($validated['lead_source']), fn ($q) => $q->where('lead_source', $validated['lead_source']))
            ->when(! empty($validated['verification_status']), fn ($q) => $q->where('verification_status', $validated['verification_status']))
            ->filterByQualityScoreMin(isset($validated['quality_score_min']) ? (int) $validated['quality_score_min'] : null)
            ->excludeDuplicates((bool) ($validated['exclude_duplicates'] ?? false))
            ->filterByFreshness($validated['freshness'] ?? null)
            ->recentlyAdded(isset($validated['recently_added_days']) ? (int) $validated['recently_added_days'] : null)
            ->hasEmail((bool) ($validated['has_email'] ?? false))
            ->hasPhone((bool) ($validated['has_phone'] ?? false))
            ->hasLinkedIn((bool) ($validated['has_linkedin'] ?? false));

        $sort = $validated['sort'] ?? 'newest';
        $sortDir = $validated['sort_dir'] ?? 'desc';
        $query->applySort($sort, $sortDir);

        $leads = $query->paginate($perPage)->withQueryString();

        $bookmarkedLeadIds = [];
        if ($request->user()->can('bookmark-leads') && $leads->isNotEmpty()) {
            $bookmarkedLeadIds = $request->user()
                ->bookmarkedLeads()
                ->whereIn('leads.id', $leads->pluck('id'))
                ->pluck('leads.id')
                ->all();
        }

        $savedFilters = $request->user()->savedFilters()->orderBy('name')->get();

        return view('leads.index', [
            'leads' => $leads,
            'filters' => $validated,
            'bookmarkedLeadIds' => $bookmarkedLeadIds,
            'savedFilters' => $savedFilters,
        ]);
    }

    public function show(Lead $lead): View|RedirectResponse
    {
        $this->authorize('view', $lead);

        $user = request()->user();
        $subscription = app(SubscriptionService::class);

        if (! $subscription->userCanAccessLeads($user, 1)) {
            return redirect()
                ->route('leads.index')
                ->with('error', __('You have reached your lead access limit for this period. Please upgrade your plan.'));
        }

        $subscription->incrementLeadsCount($user, 1);

        $lead->load(['tags', 'leadLists']);
        $lead->load(['notes' => fn ($q) => $q->where('user_id', $user->id)->latest()]);

        $isBookmarked = $user->bookmarkedLeads()->where('leads.id', $lead->id)->exists();
        $userLists = $user->leadLists()->orderBy('name')->get();

        $userReminders = $lead->reminders()->where('user_id', $user->id)->orderBy('remind_at')->get();

        return view('leads.show', [
            'lead' => $lead,
            'isBookmarked' => $isBookmarked,
            'userLists' => $userLists,
            'userReminders' => $userReminders,
        ]);
    }

    public function updateStatus(UpdateLeadStatusRequest $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validated());

        return redirect()->route('leads.show', $lead)->with('status', __('Status updated.'));
    }
}
