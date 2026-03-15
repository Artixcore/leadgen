<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadListResource;
use App\Models\LeadList;
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

    public function show(Request $request, LeadList $list): LeadListResource
    {
        $this->authorize('view', $list);

        $list->loadCount('leads');
        $list->load(['leads' => fn ($q) => $q->orderByPivot('created_at', 'desc')->limit(100)]);

        return new LeadListResource($list);
    }
}
