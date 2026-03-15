<?php

namespace App\Services;

use App\ImportRunStatus;
use App\Models\Lead;
use App\Models\LeadImportRun;
use App\Models\LeadSource;
use App\Models\PlanUsage;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardStatsService
{
    public function getStats(): array
    {
        return Cache::remember('admin.dashboard.stats', now()->addMinutes(10), function (): array {
            return [
                'total_users' => User::query()->whereHas('roles', fn ($q) => $q->where('name', 'user'))->count(),
                'active_subscriptions' => Subscription::query()->where('stripe_status', 'active')->count(),
                'total_leads' => Lead::count(),
                'new_leads_today' => Lead::query()->whereDate('created_at', today())->count(),
                'source_sync_status' => $this->sourceSyncStatus(),
                'failed_imports_count' => LeadImportRun::query()->where('status', ImportRunStatus::Failed)->count(),
                'export_usage_current_period' => PlanUsage::query()
                    ->where('period', PlanUsage::currentPeriod())
                    ->sum('exports_count'),
                'export_usage_previous_period' => PlanUsage::query()
                    ->where('period', now()->subMonth()->format('Y-m'))
                    ->sum('exports_count'),
                'revenue_current_month' => $this->revenueForMonth(now()),
                'revenue_previous_month' => $this->revenueForMonth(now()->subMonth()),
                'top_countries' => $this->topCountries(10),
                'top_industries' => $this->topIndustries(10),
                'most_used_filters' => $this->mostUsedFilters(10),
                'recent_failed_imports' => LeadImportRun::query()
                    ->where('status', ImportRunStatus::Failed)
                    ->with('leadSource')
                    ->latest()
                    ->limit(5)
                    ->get(),
            ];
        });
    }

    /**
     * @return array<int, array{id: int, name: string, status: string, last_sync_at: Carbon|null, last_run_status: string|null}>
     */
    private function sourceSyncStatus(): array
    {
        $sources = LeadSource::query()->with(['importRuns' => fn ($q) => $q->orderByDesc('started_at')->limit(1)])->get();

        return $sources->map(function (LeadSource $source): array {
            $lastRun = $source->importRuns->first();

            return [
                'id' => $source->id,
                'name' => $source->name,
                'status' => $source->status->value,
                'last_sync_at' => $source->last_sync_at,
                'last_run_status' => $lastRun?->status->value,
            ];
        })->all();
    }

    private function revenueForMonth(\DateTimeInterface $month): float
    {
        if (! \Schema::hasTable('payments')) {
            return 0.0;
        }

        return (float) DB::table('payments')
            ->whereYear('paid_at', $month->format('Y'))
            ->whereMonth('paid_at', $month->format('m'))
            ->sum('amount');
    }

    /**
     * @return array<int, array{country: string|null, total: int}>
     */
    private function topCountries(int $limit): array
    {
        $results = Lead::query()
            ->selectRaw('country, count(*) as total')
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return $results->map(fn ($r) => ['country' => $r->country, 'total' => (int) $r->total])->all();
    }

    /**
     * @return array<int, array{industry: string|null, total: int}>
     */
    private function topIndustries(int $limit): array
    {
        $results = Lead::query()
            ->selectRaw('industry, count(*) as total')
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->groupBy('industry')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return $results->map(fn ($r) => ['industry' => $r->industry, 'total' => (int) $r->total])->all();
    }

    /**
     * @return array<int, array{name: string, usage_count: int}>
     */
    private function mostUsedFilters(int $limit): array
    {
        if (! \Schema::hasColumn('saved_filters', 'usage_count')) {
            return [];
        }

        $results = DB::table('saved_filters')
            ->select('name', 'usage_count')
            ->orderByDesc('usage_count')
            ->limit($limit)
            ->get();

        return $results->map(fn ($r) => ['name' => $r->name, 'usage_count' => (int) $r->usage_count])->all();
    }
}
