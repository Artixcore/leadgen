<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportLeadsRequest;
use App\Jobs\ExportLeadsJob;
use App\Models\Export;
use App\Models\LeadList;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class LeadExportController extends Controller
{
    public function store(ExportLeadsRequest $request): RedirectResponse
    {
        $this->authorize('create', Export::class);

        $user = $request->user();
        $subscriptionService = app(SubscriptionService::class);

        if (! $subscriptionService->userCanExport($user)) {
            return redirect()
                ->back()
                ->with('error', __('You have reached your export limit for this period. Please upgrade your plan.'));
        }

        $leadIds = $request->validated('lead_ids') ?? [];
        if ($request->filled('list_id')) {
            $list = LeadList::where('id', $request->validated('list_id'))
                ->where('user_id', $user->id)
                ->firstOrFail();
            $leadIds = $list->leads()->pluck('leads.id')->all();
        }

        if (empty($leadIds)) {
            return redirect()->back()->with('error', __('No leads to export.'));
        }

        ExportLeadsJob::dispatch(
            $user->id,
            $leadIds,
            $request->validated('format'),
            $request->validated(),
            $request->validated('list_id')
        );

        return redirect()
            ->back()
            ->with('status', __('Export started. We will notify you when it is ready, or check "My exports" for the download link.'));
    }

    public function download(Export $export): Response|RedirectResponse
    {
        $this->authorize('view', $export);

        if ($export->status !== 'completed' || ! $export->file_path) {
            return redirect()->back()->with('error', __('Export is not ready for download.'));
        }

        $path = Storage::disk('local')->path($export->file_path);
        if (! file_exists($path)) {
            $export->update(['status' => 'failed', 'error_message' => 'File missing']);

            return redirect()->back()->with('error', __('Export file no longer available.'));
        }

        $filename = 'leads-'.$export->created_at->format('Y-m-d-His').'.csv';

        return response()->download($path, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
