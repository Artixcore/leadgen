<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadSearchProvider;
use App\Models\LeadSearchQuery;
use App\Models\SavedLeadSearch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadSearchAdminController extends Controller
{
    public function analytics(): View
    {
        $searchesToday = LeadSearchQuery::whereDate('created_at', today())->count();
        $searchesThisWeek = LeadSearchQuery::where('created_at', '>=', now()->startOfWeek())->count();
        $totalSearches = LeadSearchQuery::count();
        $failedSearches = LeadSearchQuery::where('status', 'failed')->count();
        $topQueries = LeadSearchQuery::selectRaw('query, count(*) as cnt')
            ->groupBy('query')
            ->orderByDesc('cnt')
            ->limit(10)
            ->get();

        return view('admin.lead-search.analytics', [
            'searchesToday' => $searchesToday,
            'searchesThisWeek' => $searchesThisWeek,
            'totalSearches' => $totalSearches,
            'failedSearches' => $failedSearches,
            'topQueries' => $topQueries,
        ]);
    }

    public function providers(): View
    {
        $providers = LeadSearchProvider::orderByDesc('priority')->orderBy('name')->get();

        return view('admin.lead-search.providers', ['providers' => $providers]);
    }

    public function updateProvider(Request $request, LeadSearchProvider $provider): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,disabled'],
            'priority' => ['required', 'integer', 'min:0', 'max:999'],
            'trust_score' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $provider->update($validated);

        return redirect()->route('admin.lead-search.providers')->with('status', __('Provider updated.'));
    }

    public function queryLogs(Request $request): View
    {
        $queries = LeadSearchQuery::with('user:id,name,email')
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.lead-search.query-logs', ['queries' => $queries]);
    }

    public function savedSearches(): View
    {
        $savedSearches = SavedLeadSearch::with('user:id,name,email')
            ->orderByDesc('updated_at')
            ->paginate(25);

        return view('admin.lead-search.saved-searches', ['savedSearches' => $savedSearches]);
    }

    public function showResults(LeadSearchQuery $query): View
    {
        $results = $query->results()->orderByDesc('relevance_score')->paginate(20);

        return view('lead-search.results', [
            'searchQuery' => $query,
            'results' => $results,
            'showFullContact' => true,
            'sort' => 'relevance',
            'sortDir' => 'desc',
        ]);
    }
}
