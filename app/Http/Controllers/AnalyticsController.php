<?php

namespace App\Http\Controllers;

use App\Models\PlanUsage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $currentPeriod = PlanUsage::currentPeriod();
        $currentUsage = PlanUsage::query()
            ->where('user_id', $user->id)
            ->where('period', $currentPeriod)
            ->first();

        $leadsViewed = $currentUsage?->leads_count ?? 0;
        $leadsExported = $currentUsage?->exports_count ?? 0;
        $leadsSaved = $user->bookmarkedLeads()->count();

        $monthlyUsage = PlanUsage::query()
            ->where('user_id', $user->id)
            ->orderByDesc('period')
            ->limit(12)
            ->get();

        $topFilters = $user->savedFilters()
            ->orderByDesc('usage_count')
            ->limit(10)
            ->get();

        return view('analytics.index', [
            'leadsViewed' => $leadsViewed,
            'leadsExported' => $leadsExported,
            'leadsSaved' => $leadsSaved,
            'currentPeriod' => $currentPeriod,
            'monthlyUsage' => $monthlyUsage,
            'topFilters' => $topFilters,
        ]);
    }
}
