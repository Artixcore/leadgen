<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Lead::class);

        $validated = $request->validate([
            'q' => ['sometimes', 'nullable', 'string', 'max:255'],
            'industry' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $perPage = $validated['per_page'] ?? 15;
        $query = Lead::query()
            ->with('leadSource')
            ->search($validated['q'] ?? null)
            ->when(! empty($validated['industry']), fn ($q) => $q->where('industry', $validated['industry']))
            ->when(! empty($validated['country']), fn ($q) => $q->where('country', $validated['country']));

        $query->orderByDesc('created_at');
        $leads = $query->paginate($perPage)->withQueryString();

        return view('admin.leads.index', [
            'leads' => $leads,
            'filters' => $validated,
        ]);
    }

    public function show(Lead $lead): View
    {
        $this->authorize('view', $lead);

        $lead->load(['leadSource', 'tags']);

        return view('admin.leads.show', ['lead' => $lead]);
    }

    public function edit(Lead $lead): View
    {
        $this->authorize('update', $lead);

        $lead->load('leadSource');
        $leadSources = LeadSource::orderBy('name')->get();

        return view('admin.leads.edit', [
            'lead' => $lead,
            'leadSources' => $leadSources,
        ]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validated());

        app(ActivityLogService::class)->log($request->user(), 'lead.updated', $lead);

        return redirect()->route('admin.leads.show', $lead)->with('status', __('Lead updated.'));
    }
}
