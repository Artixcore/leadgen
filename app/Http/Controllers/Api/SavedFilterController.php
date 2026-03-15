<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSavedFilterRequest;
use App\Http\Resources\SavedFilterResource;
use App\Models\SavedFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SavedFilterController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->user()
            ->savedFilters()
            ->orderBy('name')
            ->paginate($request->input('per_page', 15));

        return SavedFilterResource::collection($filters);
    }

    public function store(StoreSavedFilterRequest $request): SavedFilterResource
    {
        $this->authorize('create', SavedFilter::class);

        $savedFilter = $request->user()->savedFilters()->create([
            'name' => $request->validated('name'),
            'criteria' => $request->validated('criteria'),
        ]);

        return new SavedFilterResource($savedFilter);
    }

    public function show(Request $request, SavedFilter $savedFilter): SavedFilterResource|JsonResponse
    {
        if ($savedFilter->user_id !== $request->user()->id) {
            return response()->json(['message' => __('Unauthorized.')], 403);
        }

        return new SavedFilterResource($savedFilter);
    }

    public function destroy(Request $request, SavedFilter $savedFilter): JsonResponse
    {
        $this->authorize('delete', $savedFilter);

        $savedFilter->delete();

        return response()->json(['message' => __('Saved filter deleted.')]);
    }
}
