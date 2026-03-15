<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeadController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Lead::class);

        $validated = $request->validate([
            'q' => ['sometimes', 'nullable', 'string', 'max:255'],
            'industry' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'sort' => ['sometimes', 'nullable', 'string', 'in:newest,highest_quality,most_relevant'],
            'sort_dir' => ['sometimes', 'nullable', 'string', 'in:asc,desc'],
        ]);

        $perPage = $validated['per_page'] ?? 15;
        $query = Lead::query()
            ->search($validated['q'] ?? null)
            ->when(! empty($validated['industry']), fn ($q) => $q->where('industry', $validated['industry']))
            ->when(! empty($validated['country']), fn ($q) => $q->where('country', $validated['country']));

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
