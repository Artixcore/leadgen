<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadTagsRequest;
use App\Models\Lead;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class LeadTagController extends Controller
{
    public function store(StoreLeadTagsRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorize('view', $lead);

        $tagIds = $request->validated('tag_ids') ?? [];

        $names = $request->validated('tag_names') ?? [];
        foreach ($names as $name) {
            $name = trim($name);
            if ($name !== '') {
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
                $tagIds[] = $tag->id;
            }
        }

        $tagIds = array_unique(array_filter($tagIds));
        if ($tagIds !== []) {
            $lead->tags()->syncWithoutDetaching($tagIds);
        }

        return back()->with('status', __('Tags added.'));
    }

    public function destroy(Lead $lead, Tag $tag): RedirectResponse
    {
        $this->authorize('view', $lead);

        $lead->tags()->detach($tag->id);

        return back()->with('status', __('Tag removed.'));
    }
}
