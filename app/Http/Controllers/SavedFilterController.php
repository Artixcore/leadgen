<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSavedFilterRequest;
use App\Models\SavedFilter;
use Illuminate\Http\RedirectResponse;

class SavedFilterController extends Controller
{
    public function store(StoreSavedFilterRequest $request): RedirectResponse
    {
        $this->authorize('create', SavedFilter::class);

        $request->user()->savedFilters()->create([
            'name' => $request->validated('name'),
            'criteria' => $request->validated('criteria'),
        ]);

        return redirect()
            ->route('leads.index', $request->validated('criteria'))
            ->with('status', __('Saved filter created. You will be notified when new leads match.'));
    }

    public function destroy(SavedFilter $savedFilter): RedirectResponse
    {
        $this->authorize('delete', $savedFilter);

        $savedFilter->delete();

        return redirect()
            ->back()
            ->with('status', __('Saved filter deleted.'));
    }
}
