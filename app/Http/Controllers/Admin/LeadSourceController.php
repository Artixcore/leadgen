<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLeadSourceRequest;
use App\Http\Requests\Admin\UpdateLeadSourceRequest;
use App\Jobs\SyncLeadSourceJob;
use App\LeadSourceStatus;
use App\Models\LeadSource;
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
        LeadSource::create($request->validated());

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

        return redirect()
            ->back()
            ->with('status', __('Sync job queued.'));
    }
}
