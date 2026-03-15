<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddLeadsToListRequest;
use App\Http\Requests\Api\StoreLeadListRequest;
use App\Http\Requests\Api\UpdateLeadListRequest;
use App\Http\Resources\LeadListResource;
use App\Models\Lead;
use App\Models\LeadList;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeadListController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $lists = LeadList::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('sharedWithUsers', fn ($q2) => $q2->where('user_id', $user->id));
            })
            ->withCount('leads')
            ->latest()
            ->paginate($request->input('per_page', 15));

        return LeadListResource::collection($lists);
    }

    public function store(StoreLeadListRequest $request): JsonResponse|LeadListResource
    {
        $user = $request->user();
        $subscription = app(SubscriptionService::class);

        if (! $subscription->userCanCreateList($user, $user->leadLists()->count())) {
            return response()->json(['message' => __('You have reached your saved lists limit. Please upgrade your plan.')], 403);
        }

        $list = $user->leadLists()->create($request->validated());
        $list->logActivity('list_created');

        return (new LeadListResource($list))->response()->setStatusCode(201);
    }

    public function show(Request $request, LeadList $list): LeadListResource
    {
        $this->authorize('view', $list);

        $list->loadCount('leads');
        $list->load(['leads' => fn ($q) => $q->orderByPivot('created_at', 'desc')->limit(100)]);

        return new LeadListResource($list);
    }

    public function update(UpdateLeadListRequest $request, LeadList $list): LeadListResource
    {
        $this->authorize('update', $list);

        $oldName = $list->name;
        $list->update($request->validated());
        if ($oldName !== $list->name) {
            $list->logActivity('list_renamed', null, null, null, ['old_name' => $oldName]);
        }

        return new LeadListResource($list->fresh());
    }

    public function destroy(LeadList $list): JsonResponse
    {
        $this->authorize('delete', $list);

        $list->delete();

        return response()->json(['message' => __('List deleted.')]);
    }

    public function addLeads(AddLeadsToListRequest $request, LeadList $list): JsonResponse|LeadListResource
    {
        $this->authorize('update', $list);

        $list->leads()->syncWithoutDetaching($request->validated('lead_ids'));
        foreach ($request->validated('lead_ids') as $leadId) {
            $list->logActivity('lead_added', $request->user()->id, 'lead', $leadId);
        }

        return new LeadListResource($list->loadCount('leads'));
    }

    public function removeLead(Request $request, LeadList $list, Lead $lead): JsonResponse
    {
        $this->authorize('update', $list);

        if (! $list->leads()->where('leads.id', $lead->id)->exists()) {
            return response()->json(['message' => __('Lead is not in this list.')], 422);
        }

        $list->leads()->detach($lead->id);
        $list->logActivity('lead_removed', $request->user()->id, 'lead', $lead->id);

        return response()->json(['message' => __('Lead removed from list.')]);
    }
}
