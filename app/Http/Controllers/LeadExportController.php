<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportLeadsRequest;
use App\Models\Lead;
use App\Models\LeadList;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadExportController extends Controller
{
    public function store(ExportLeadsRequest $request): StreamedResponse|RedirectResponse
    {
        $user = $request->user();
        $subscription = app(SubscriptionService::class);

        if (! $subscription->userCanExport($user)) {
            return redirect()
                ->back()
                ->with('error', __('You have reached your export limit for this period. Please upgrade your plan.'));
        }

        $leadIds = $request->validated('lead_ids');
        if ($request->filled('list_id')) {
            $list = LeadList::where('id', $request->validated('list_id'))
                ->where('user_id', $user->id)
                ->firstOrFail();
            $leadIds = $list->leads()->pluck('leads.id')->all();
            $list->logActivity('list_exported', $user->id);
        }

        if (empty($leadIds)) {
            return redirect()->back()->with('error', __('No leads to export.'));
        }

        $format = $request->validated('format');
        $leads = Lead::whereIn('id', $leadIds)->orderBy('id')->get();

        $subscription->incrementExportsCount($user);

        $extension = $format === 'xlsx' ? 'xlsx' : 'csv';
        $filename = 'leads-'.now()->format('Y-m-d-His').'.'.$extension;

        if ($format === 'xlsx') {
            return $this->streamCsv($leads, 'leads-'.now()->format('Y-m-d-His').'.csv');
        }

        return $this->streamCsv($leads, $filename);
    }

    private function streamCsv($leads, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return Response::stream(function () use ($leads): void {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            $columns = [
                'id', 'full_name', 'job_title', 'email', 'phone', 'company_name', 'website',
                'linkedin_profile', 'country', 'state', 'city', 'industry', 'company_size',
                'revenue_range', 'lead_source', 'verification_status', 'quality_score',
                'lead_status', 'is_duplicate', 'notes', 'updated_at',
            ];
            fputcsv($handle, $columns);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->full_name,
                    $lead->job_title,
                    $lead->email,
                    $lead->phone,
                    $lead->company_name,
                    $lead->website,
                    $lead->linkedin_profile,
                    $lead->country,
                    $lead->state,
                    $lead->city,
                    $lead->industry,
                    $lead->company_size,
                    $lead->revenue_range,
                    $lead->lead_source,
                    $lead->verification_status?->value,
                    $lead->quality_score,
                    $lead->lead_status?->value,
                    $lead->is_duplicate ? '1' : '0',
                    $lead->notes,
                    $lead->updated_at?->toIso8601String(),
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
