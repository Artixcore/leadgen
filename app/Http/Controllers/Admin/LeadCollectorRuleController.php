<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLeadCollectorRuleRequest;
use App\Http\Requests\Admin\UpdateLeadCollectorRuleRequest;
use App\Models\LeadCollector;
use App\Models\LeadCollectorRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeadCollectorRuleController extends Controller
{
    public function index(LeadCollector $leadCollector): View
    {
        $this->authorize('view', $leadCollector);
        $this->authorize('viewAny', LeadCollectorRule::class);

        $rules = $leadCollector->rules()->orderBy('rule_key')->get();

        return view('admin.lead-collector-rules.index', [
            'collector' => $leadCollector,
            'rules' => $rules,
        ]);
    }

    public function edit(LeadCollectorRule $rule): View
    {
        $this->authorize('update', $rule);
        $rule->load('leadCollector');

        return view('admin.lead-collector-rules.edit', [
            'collector' => $rule->leadCollector,
            'rule' => $rule,
        ]);
    }

    public function store(StoreLeadCollectorRuleRequest $request, LeadCollector $leadCollector): RedirectResponse
    {
        $this->authorize('update', $leadCollector);

        $leadCollector->rules()->create($request->validated());

        return redirect()
            ->route('admin.lead-collectors.rules.index', $leadCollector)
            ->with('status', __('Rule added.'));
    }

    public function update(UpdateLeadCollectorRuleRequest $request, LeadCollectorRule $rule): RedirectResponse
    {
        $this->authorize('update', $rule);

        $rule->update($request->validated());

        return redirect()
            ->route('admin.lead-collectors.rules.index', $rule->leadCollector)
            ->with('status', __('Rule updated.'));
    }

    public function destroy(LeadCollectorRule $rule): RedirectResponse
    {
        $this->authorize('delete', $rule);

        $collector = $rule->leadCollector;
        $rule->delete();

        return redirect()
            ->route('admin.lead-collectors.rules.index', $collector)
            ->with('status', __('Rule removed.'));
    }
}
