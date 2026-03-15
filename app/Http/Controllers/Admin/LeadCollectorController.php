<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLeadCollectorRequest;
use App\Http\Requests\Admin\UpdateLeadCollectorRequest;
use App\Jobs\RunLeadCollectorJob;
use App\Models\LeadCollector;
use App\Models\LeadSource;
use App\Services\ActivityLogService;
use App\Services\LeadCollectorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadCollectorController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', LeadCollector::class);

        $collectors = LeadCollector::query()
            ->with(['leadSource:id,name', 'runs' => fn ($q) => $q->latest()->limit(1)])
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('target_service'), fn ($q) => $q->where('target_service', $request->input('target_service')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.lead-collectors.index', [
            'collectors' => $collectors,
            'filters' => $request->only(['type', 'status', 'target_service']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', LeadCollector::class);

        $leadSources = LeadSource::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.lead-collectors.create', ['leadSources' => $leadSources]);
    }

    public function store(StoreLeadCollectorRequest $request, LeadCollectorService $service): RedirectResponse
    {
        $data = $this->normalizeCollectorData($request->validated());
        $collector = $service->create($data, $request->user());

        return redirect()
            ->route('admin.lead-collectors.index')
            ->with('status', __('Lead collector created.'));
    }

    public function show(LeadCollector $leadCollector): View
    {
        $this->authorize('view', $leadCollector);

        $leadCollector->load(['leadSource', 'rules']);
        $recentRuns = $leadCollector->runs()->latest()->limit(10)->get();
        $recentRawRecords = $leadCollector->rawLeadRecords()->with('leadCollectorRun:id,lead_collector_id,status,started_at')->latest()->limit(10)->get();

        return view('admin.lead-collectors.show', [
            'collector' => $leadCollector,
            'recentRuns' => $recentRuns,
            'recentRawRecords' => $recentRawRecords,
        ]);
    }

    public function edit(LeadCollector $leadCollector): View
    {
        $this->authorize('update', $leadCollector);

        $leadSources = LeadSource::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.lead-collectors.edit', [
            'collector' => $leadCollector,
            'leadSources' => $leadSources,
        ]);
    }

    public function update(UpdateLeadCollectorRequest $request, LeadCollector $leadCollector, LeadCollectorService $service): RedirectResponse
    {
        $data = $this->normalizeCollectorData($request->validated());
        $service->update($leadCollector, $data, $request->user());

        return redirect()
            ->route('admin.lead-collectors.show', $leadCollector)
            ->with('status', __('Lead collector updated.'));
    }

    public function run(LeadCollector $leadCollector, ActivityLogService $activityLog): RedirectResponse
    {
        $this->authorize('run', $leadCollector);

        RunLeadCollectorJob::dispatch($leadCollector, 'manual', request()->user()?->id);
        if (request()->user()) {
            $activityLog->log(request()->user(), 'lead_collector.run_triggered', $leadCollector);
        }

        return redirect()
            ->back()
            ->with('status', __('Collector run queued.'));
    }

    public function rawRecords(LeadCollector $leadCollector, Request $request): View
    {
        $this->authorize('view', $leadCollector);

        $rawRecords = $leadCollector->rawLeadRecords()
            ->with('leadCollectorRun:id,lead_collector_id,status,started_at')
            ->when($request->filled('processing_status'), fn ($q) => $q->where('processing_status', $request->input('processing_status')))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.raw-lead-records.index', [
            'collector' => $leadCollector,
            'rawRecords' => $rawRecords,
            'filters' => $request->only(['processing_status']),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalizeCollectorData(array $data): array
    {
        if (isset($data['config']) && is_string($data['config'])) {
            $data['config'] = json_decode($data['config'], true) ?? [];
        }
        if (isset($data['filters_json']) && is_string($data['filters_json'])) {
            $data['filters_json'] = json_decode($data['filters_json'], true) ?? [];
        }

        return $data;
    }
}
