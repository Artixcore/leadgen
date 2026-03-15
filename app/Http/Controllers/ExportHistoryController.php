<?php

namespace App\Http\Controllers;

use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExportHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('create', Export::class);

        $exports = Export::where('user_id', $request->user()->id)
            ->with('plan')
            ->latest()
            ->paginate(15);

        return view('exports.index', ['exports' => $exports]);
    }
}
