<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExportResource;
use App\Jobs\ExportLeadsJob;
use App\Models\Export;
use App\Models\LeadList;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function store(Request $request): JsonResponse|ExportResource
    {
        $this->authorize('create', Export::class);

        $validated = $request->validate([
            'format' => ['required', 'string', 'in:csv,xlsx'],
            'lead_ids' => ['required_without:list_id', 'array', 'min:1'],
            'lead_ids.*' => ['integer', 'exists:leads,id'],
            'list_id' => ['required_without:lead_ids', 'integer', 'exists:lead_lists,id'],
        ]);

        $user = $request->user();
        $subscriptionService = app(SubscriptionService::class);

        if (! $subscriptionService->userCanExport($user)) {
            return response()->json(['message' => __('You have reached your export limit for this period.')], 403);
        }

        $leadIds = $validated['lead_ids'] ?? [];
        if (! empty($validated['list_id'])) {
            $list = LeadList::where('id', $validated['list_id'])->where('user_id', $user->id)->firstOrFail();
            $leadIds = $list->leads()->pluck('leads.id')->all();
        }

        if (empty($leadIds)) {
            return response()->json(['message' => __('No leads to export.')], 422);
        }

        $plan = $subscriptionService->getPlanForUser($user);
        $export = Export::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'type' => $validated['format'] === 'xlsx' ? 'xlsx' : 'csv',
            'filters' => $validated,
            'row_count' => 0,
            'status' => 'pending',
        ]);

        ExportLeadsJob::dispatch(
            $user->id,
            $leadIds,
            $validated['format'],
            $validated,
            $validated['list_id'] ?? null
        );

        return new ExportResource($export);
    }

    public function show(Request $request, Export $export): ExportResource
    {
        $this->authorize('view', $export);

        return new ExportResource($export);
    }

    public function download(Export $export): Response|JsonResponse
    {
        $this->authorize('view', $export);

        if ($export->status !== 'completed' || ! $export->file_path) {
            return response()->json(['message' => __('Export is not ready for download.')], 404);
        }

        $path = Storage::disk('local')->path($export->file_path);
        if (! file_exists($path)) {
            return response()->json(['message' => __('Export file no longer available.')], 404);
        }

        $filename = 'leads-'.$export->created_at->format('Y-m-d-His').'.csv';

        return response()->download($path, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
