<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadImportRun;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadImportRunController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', LeadImportRun::class);

        $runs = LeadImportRun::query()
            ->with('leadSource')
            ->when($request->filled('source'), fn ($q) => $q->where('lead_source_id', $request->input('source')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.import-runs.index', [
            'runs' => $runs,
            'filters' => $request->only(['source', 'status']),
        ]);
    }

    public function show(LeadImportRun $importRun): View
    {
        $this->authorize('view', $importRun);

        $importRun->load(['leadSource', 'rows' => fn ($q) => $q->orderBy('row_index')]);

        return view('admin.import-runs.show', ['run' => $importRun]);
    }
}
