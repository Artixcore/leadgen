<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadSearchRequest;
use App\Models\LeadSearchQuery;
use App\Models\SavedLeadSearch;
use App\Services\LeadSearch\LeadSearchService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadSearchController extends Controller
{
    public function __construct(
        protected LeadSearchService $leadSearchService,
        protected SubscriptionService $subscriptionService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', LeadSearchQuery::class);

        return view('lead-search.index');
    }

    public function run(LeadSearchRequest $request): RedirectResponse
    {
        $this->authorize('create', LeadSearchQuery::class);

        $user = $request->user();
        if (! $this->subscriptionService->userCanUseLeadSearch($user, 1)) {
            return redirect()
                ->route('lead-search.index')
                ->with('error', __('You have reached your lead search limit for this period. Please upgrade your plan.'));
        }

        $query = $request->validated('query');
        $filters = $request->filters();

        if ($request->boolean('async')) {
            $searchQuery = $this->leadSearchService->runSearchAsync($user, $query, $filters);

            return redirect()
                ->route('lead-search.results', ['query' => $searchQuery->id])
                ->with('status', __('Search is running. Results will appear shortly.'));
        }

        $searchQuery = $this->leadSearchService->runSearch($user, $query, $filters);

        return redirect()->route('lead-search.results', ['query' => $searchQuery->id]);
    }

    public function results(Request $request, LeadSearchQuery $query): View|RedirectResponse
    {
        $this->authorize('view', $query);

        if ($query->user_id !== $request->user()->id) {
            abort(403);
        }

        $sort = $request->get('sort', 'relevance');
        $sortDir = $request->get('sort_dir', 'desc');
        $perPage = (int) $request->get('per_page', 15);
        $perPage = min(max(1, $perPage), 100);

        $resultsQuery = $query->results()->getQuery();
        match ($sort) {
            'opportunity' => $resultsQuery->orderBy('opportunity_score', $sortDir)->orderBy('relevance_score', 'desc'),
            'newest' => $resultsQuery->orderBy('created_at', $sortDir),
            'contact' => $resultsQuery->orderByRaw('CASE WHEN email IS NOT NULL AND email != \'\' THEN 1 ELSE 0 END + CASE WHEN phone IS NOT NULL AND phone != \'\' THEN 1 ELSE 0 END DESC')->orderBy('relevance_score', 'desc'),
            default => $resultsQuery->orderBy('relevance_score', $sortDir)->orderBy('opportunity_score', 'desc'),
        };

        $results = $resultsQuery->paginate($perPage)->withQueryString();

        $showFullContact = $this->subscriptionService->getPlanForUser($request->user())->leadSearchFullContact();

        return view('lead-search.results', [
            'searchQuery' => $query,
            'results' => $results,
            'showFullContact' => $showFullContact,
            'sort' => $sort,
            'sortDir' => $sortDir,
        ]);
    }

    public function history(Request $request): View
    {
        $this->authorize('viewAny', LeadSearchQuery::class);

        $queries = LeadSearchQuery::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('lead-search.history', ['queries' => $queries]);
    }

    public function saved(Request $request): View
    {
        $this->authorize('viewAny', LeadSearchQuery::class);

        $savedSearches = SavedLeadSearch::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();

        return view('lead-search.saved', ['savedSearches' => $savedSearches]);
    }

    public function storeSavedSearch(Request $request): RedirectResponse
    {
        $this->authorize('createSavedSearch', SavedLeadSearch::class);

        $user = $request->user();
        $currentCount = SavedLeadSearch::where('user_id', $user->id)->count();
        if (! $this->subscriptionService->userCanSaveLeadSearch($user, $currentCount)) {
            return redirect()
                ->route('lead-search.saved')
                ->with('error', __('You have reached your saved search limit. Please upgrade your plan.'));
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'query' => ['required', 'string', 'max:500'],
            'query_id' => ['sometimes', 'nullable', 'integer', 'exists:lead_search_queries,id'],
        ]);

        $parsedQueryJson = null;
        $filtersJson = [];
        if (! empty($validated['query_id'])) {
            $existing = LeadSearchQuery::where('id', $validated['query_id'])->where('user_id', $user->id)->first();
            if ($existing) {
                $parsedQueryJson = $existing->parsed_query_json;
                $filtersJson = $existing->filters_json ?? [];
            }
        }

        SavedLeadSearch::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'query' => $validated['query'],
            'parsed_query_json' => $parsedQueryJson,
            'filters_json' => $filtersJson,
        ]);

        return redirect()->route('lead-search.saved')->with('status', __('Search saved.'));
    }

    public function destroySavedSearch(Request $request, SavedLeadSearch $savedLeadSearch): RedirectResponse
    {
        $this->authorize('delete', $savedLeadSearch);

        $savedLeadSearch->delete();

        return redirect()->route('lead-search.saved')->with('status', __('Saved search removed.'));
    }

    public function runSavedSearch(Request $request, SavedLeadSearch $savedLeadSearch): RedirectResponse
    {
        $this->authorize('view', $savedLeadSearch);

        $user = $request->user();
        if (! $this->subscriptionService->userCanUseLeadSearch($user, 1)) {
            return redirect()
                ->route('lead-search.saved')
                ->with('error', __('You have reached your lead search limit for this period.'));
        }

        $searchQuery = $this->leadSearchService->runSearch(
            $user,
            $savedLeadSearch->query,
            $savedLeadSearch->filters_json ?? []
        );

        $savedLeadSearch->update(['last_run_at' => now()]);

        return redirect()->route('lead-search.results', ['query' => $searchQuery->id]);
    }
}
