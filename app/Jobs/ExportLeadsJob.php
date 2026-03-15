<?php

namespace App\Jobs;

use App\Models\Export;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportLeadsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    /**
     * @param  array<int, int>  $leadIds
     */
    public function __construct(
        public int $userId,
        public array $leadIds,
        public string $format,
        public array $filters,
        public ?int $listId = null
    ) {}

    public function handle(): void
    {
        $subscriptionService = app(SubscriptionService::class);
        $user = User::find($this->userId);
        if (! $user || ! $subscriptionService->userCanExport($user)) {
            return;
        }

        if ($this->listId) {
            $list = LeadList::where('id', $this->listId)->where('user_id', $this->userId)->first();
            if ($list) {
                $list->logActivity('list_exported', $this->userId);
            }
        }

        if (empty($this->leadIds)) {
            return;
        }

        $plan = $subscriptionService->getPlanForUser($user);
        $export = Export::create([
            'user_id' => $this->userId,
            'plan_id' => $plan->id,
            'type' => $this->format === 'xlsx' ? 'xlsx' : 'csv',
            'filters' => $this->filters,
            'row_count' => 0,
            'status' => 'pending',
        ]);

        try {
            $path = 'exports/'.$this->userId.'/'.Str::uuid().'.csv';
            $fullPath = Storage::disk('local')->path($path);

            $directory = dirname($fullPath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $handle = fopen($fullPath, 'w');
            if (! $handle) {
                $export->update(['status' => 'failed', 'error_message' => 'Could not create file']);

                return;
            }

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            $columns = [
                'id', 'full_name', 'job_title', 'email', 'phone', 'company_name', 'website',
                'linkedin_profile', 'country', 'state', 'city', 'industry', 'company_size',
                'revenue_range', 'lead_source', 'verification_status', 'quality_score',
                'lead_status', 'is_duplicate', 'notes', 'updated_at',
            ];
            fputcsv($handle, $columns);

            $rowCount = 0;
            Lead::whereIn('id', $this->leadIds)
                ->orderBy('id')
                ->chunkById(500, function ($leads) use ($handle, &$rowCount): void {
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
                        $rowCount++;
                    }
                });

            fclose($handle);

            $subscriptionService->incrementExportsCount($user);

            $export->update([
                'file_path' => $path,
                'row_count' => $rowCount,
                'status' => 'completed',
            ]);
        } catch (\Throwable $e) {
            $export->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function backoff(): array
    {
        return [60, 300];
    }
}
