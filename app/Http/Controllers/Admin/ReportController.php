<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadImportRun;
use App\Models\LeadSource;
use App\Models\PlanUsage;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index');
    }

    public function leadsOverTime(Request $request): View
    {
        $groupBy = $request->get('group', 'month');
        $months = (int) $request->get('months', 12);

        $query = Lead::query()
            ->where('created_at', '>=', now()->subMonths($months));

        if ($groupBy === 'day') {
            $data = $query->clone()
                ->selectRaw('date(created_at) as date, count(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } else {
            $data = $query->clone()
                ->selectRaw('year(created_at) as year, month(created_at) as month, count(*) as total')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
                ->map(fn ($r) => (object) ['date' => $r->year.'-'.str_pad((string) $r->month, 2, '0', STR_PAD_LEFT).'-01', 'total' => $r->total]);
        }

        return view('admin.reports.leads-over-time', ['data' => $data, 'groupBy' => $groupBy, 'months' => $months]);
    }

    public function sourcePerformance(): View
    {
        $sources = LeadSource::query()
            ->withCount('leads')
            ->with(['importRuns' => fn ($q) => $q->latest()->limit(1)])
            ->orderBy('name')
            ->get();

        $runCounts = LeadImportRun::query()
            ->selectRaw('lead_source_id, status, count(*) as cnt')
            ->groupBy('lead_source_id', 'status')
            ->get()
            ->groupBy('lead_source_id');

        return view('admin.reports.source-performance', [
            'sources' => $sources,
            'runCounts' => $runCounts,
        ]);
    }

    public function mostActiveUsers(): View
    {
        $period = PlanUsage::currentPeriod();
        $usage = PlanUsage::query()
            ->where('period', $period)
            ->selectRaw('user_id, (leads_count + exports_count) as usage_sum')
            ->get()
            ->keyBy('user_id');

        $userIds = $usage->pluck('user_id')->unique()->all();
        $users = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'user'))
            ->whereIn('id', $userIds)
            ->get()
            ->sortByDesc(fn (User $u) => (int) ($usage->get($u->id)?->usage_sum ?? 0))
            ->take(50)
            ->values();

        $usageMap = $usage->mapWithKeys(fn ($u) => [$u->user_id => (int) $u->usage_sum]);

        return view('admin.reports.most-active-users', ['users' => $users, 'usageMap' => $usageMap]);
    }

    public function revenueByMonth(): View
    {
        $data = DB::table('payments')
            ->selectRaw('year(paid_at) as year, month(paid_at) as month, sum(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn ($r) => (object) ['label' => $r->year.'-'.str_pad((string) $r->month, 2, '0', STR_PAD_LEFT), 'total' => (float) $r->total]);

        return view('admin.reports.revenue-by-month', ['data' => $data]);
    }

    public function planDistribution(): View
    {
        $subscriptions = Subscription::query()
            ->where('stripe_status', 'active')
            ->with('plan')
            ->get();

        $byPlan = $subscriptions->groupBy(fn ($s) => $s->plan?->name ?? 'Unknown')->map->count();

        return view('admin.reports.plan-distribution', ['byPlan' => $byPlan]);
    }

    public function exportUsageTrends(): View
    {
        $data = PlanUsage::query()
            ->selectRaw('period, sum(exports_count) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return view('admin.reports.export-usage-trends', ['data' => $data]);
    }

    public function leadVerificationTrends(): View
    {
        $data = Lead::query()
            ->selectRaw('verification_status, count(*) as total')
            ->groupBy('verification_status')
            ->get();

        return view('admin.reports.lead-verification-trends', ['data' => $data]);
    }
}
