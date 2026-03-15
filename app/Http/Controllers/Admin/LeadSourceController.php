<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLeadSourceRequest;
use App\Http\Requests\Admin\UpdateLeadSourceRequest;
use App\Http\Requests\StoreImportedFileRequest;
use App\Jobs\ProcessFileImportJob;
use App\Jobs\SyncLeadSourceJob;
use App\LeadSourceStatus;
use App\Models\LeadSource;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadSourceController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', LeadSource::class);

        $sources = LeadSource::query()
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->withCount('leads')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.lead-sources.index', [
            'sources' => $sources,
            'filters' => $request->only(['type', 'status']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', LeadSource::class);

        return view('admin.lead-sources.create');
    }

    public function store(StoreLeadSourceRequest $request): RedirectResponse
    {
        $leadSource = LeadSource::create($request->validated());

        app(ActivityLogService::class)->log($request->user(), 'lead_source.created', $leadSource);

        return redirect()
            ->route('admin.lead-sources.index')
            ->with('status', __('Lead source created.'));
    }

    public function show(LeadSource $leadSource): View
    {
        $this->authorize('view', $leadSource);

        $leadSource->loadCount('leads');
        $recentRuns = $leadSource->importRuns()->latest()->limit(10)->get();

        return view('admin.lead-sources.show', [
            'source' => $leadSource,
            'recentRuns' => $recentRuns,
        ]);
    }

    public function edit(LeadSource $leadSource): View
    {
        $this->authorize('update', $leadSource);

        return view('admin.lead-sources.edit', ['source' => $leadSource]);
    }

    public function update(UpdateLeadSourceRequest $request, LeadSource $leadSource): RedirectResponse
    {
        $leadSource->update($request->validated());

        app(ActivityLogService::class)->log($request->user(), 'lead_source.updated', $leadSource);

        return redirect()
            ->route('admin.lead-sources.show', $leadSource)
            ->with('status', __('Lead source updated.'));
    }

    public function pause(LeadSource $leadSource): RedirectResponse
    {
        $this->authorize('update', $leadSource);

        $newStatus = $leadSource->status === LeadSourceStatus::Active
            ? LeadSourceStatus::Inactive
            : LeadSourceStatus::Active;
        $leadSource->update(['status' => $newStatus]);

        app(ActivityLogService::class)->log(request()->user(), 'lead_source.paused', $leadSource, [
            'new_status' => $newStatus->value,
        ]);

        $message = $newStatus === LeadSourceStatus::Inactive
            ? __('Lead source paused.')
            : __('Lead source resumed.');

        return redirect()
            ->back()
            ->with('status', $message);
    }

    public function sync(LeadSource $leadSource): RedirectResponse
    {
        $this->authorize('update', $leadSource);

        SyncLeadSourceJob::dispatch($leadSource);

        app(ActivityLogService::class)->log(request()->user(), 'lead_source.synced', $leadSource);

        return redirect()
            ->back()
            ->with('status', __('Sync job queued.'));
    }

    /**
     * Store an uploaded CSV file and queue it for import.
     */
    public function importFile(StoreImportedFileRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        $path = $file->store('imports', 'local');

        ProcessFileImportJob::dispatch(
            $path,
            $request->validated('lead_source_id'),
            $request->user()->id
        );

        return redirect()
            ->back()
            ->with('status', __('Import file queued. You will be notified when processing completes.'));
    }
}
