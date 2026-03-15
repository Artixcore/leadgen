<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadCollector;
use App\Models\LeadCollectorRun;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadCollectorRunController extends Controller
{
    public function index(Request $request, ?LeadCollector $leadCollector = null): View
    {
        $this->authorize('viewAny', LeadCollectorRun::class);

        $query = LeadCollectorRun::query()->with('leadCollector:id,name,type');
        if ($leadCollector) {
            $this->authorize('view', $leadCollector);
            $query->where('lead_collector_id', $leadCollector->id);
        }
        $runs = $query
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.lead-collector-runs.index', [
            'runs' => $runs,
            'collector' => $leadCollector,
            'filters' => $request->only(['status']),
        ]);
    }

    public function show(LeadCollectorRun $run): View
    {
        $this->authorize('view', $run);

        $run->load('leadCollector');
        $rawRecords = $run->rawLeadRecords()->latest()->paginate(20)->withQueryString();

        return view('admin.lead-collector-runs.show', [
            'run' => $run,
            'rawRecords' => $rawRecords,
        ]);
    }

    public function rawRecords(LeadCollectorRun $run, Request $request): View
    {
        $this->authorize('view', $run);

        $run->load('leadCollector');
        $rawRecords = $run->rawLeadRecords()
            ->when($request->filled('processing_status'), fn ($q) => $q->where('processing_status', $request->input('processing_status')))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.raw-lead-records.index', [
            'run' => $run,
            'rawRecords' => $rawRecords,
            'filters' => $request->only(['processing_status']),
        ]);
    }
}
