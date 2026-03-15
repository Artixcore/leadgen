<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexLeadsRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Models\SavedFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeadController extends Controller
{
    public function index(IndexLeadsRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Lead::class);

        $validated = $request->validated();

        if (! empty($validated['saved_filter_id'])) {
            $savedFilter = SavedFilter::query()
                ->where('id', $validated['saved_filter_id'])
                ->where('user_id', $request->user()->id)
                ->first();
            if ($savedFilter && ! empty($savedFilter->criteria)) {
                $validated = array_merge($savedFilter->criteria, $validated);
                $savedFilter->increment('usage_count');
            }
        }

        $perPage = $validated['per_page'] ?? 15;
        $query = Lead::query()
            ->search($validated['q'] ?? null)
            ->when(! empty($validated['industry']), fn ($q) => $q->where('industry', $validated['industry']))
            ->when(! empty($validated['niche']), fn ($q) => $q->where('niche', $validated['niche']))
            ->when(! empty($validated['country']), fn ($q) => $q->where('country', $validated['country']))
            ->when(! empty($validated['city']), fn ($q) => $q->where('city', $validated['city']))
            ->when(! empty($validated['job_title']), fn ($q) => $q->where('job_title', $validated['job_title']))
            ->when(! empty($validated['company_size']), fn ($q) => $q->where('company_size', $validated['company_size']))
            ->when(! empty($validated['revenue_range']), fn ($q) => $q->where('revenue_range', $validated['revenue_range']))
            ->when(! empty($validated['lead_source']), fn ($q) => $q->where('lead_source', $validated['lead_source']))
            ->when(! empty($validated['verification_status']), fn ($q) => $q->where('verification_status', $validated['verification_status']))
            ->filterByQualityScoreMin(isset($validated['quality_score_min']) ? (int) $validated['quality_score_min'] : null)
            ->excludeDuplicates((bool) ($validated['exclude_duplicates'] ?? false))
            ->filterByFreshness($validated['freshness'] ?? null)
            ->recentlyAdded(isset($validated['recently_added_days']) ? (int) $validated['recently_added_days'] : null)
            ->hasEmail((bool) ($validated['has_email'] ?? false))
            ->hasPhone((bool) ($validated['has_phone'] ?? false))
            ->hasLinkedIn((bool) ($validated['has_linkedin'] ?? false));

        $sort = $validated['sort'] ?? 'newest';
        $sortDir = $validated['sort_dir'] ?? 'desc';
        $query->applySort($sort, $sortDir);

        $leads = $query->paginate($perPage)->withQueryString();

        return LeadResource::collection($leads);
    }

    public function show(Lead $lead): LeadResource
    {
        $this->authorize('view', $lead);

        return new LeadResource($lead);
    }
}
