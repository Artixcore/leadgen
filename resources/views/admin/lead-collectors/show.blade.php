@extends('admin.layouts.app')

@section('title', $collector->name)

@section('content')
    @include('admin.partials.page-header', [
        'title' => $collector->name,
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            __('Lead Collectors') => route('admin.lead-collectors.index'),
            $collector->name => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.lead-collectors.edit', $collector) }}" class="btn btn-secondary">{{ __('Edit') }}</a>
        <form action="{{ route('admin.lead-collectors.run', $collector) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary">{{ __('Run now') }}</button>
        </form>
        <a href="{{ route('admin.lead-collectors.runs.index', $collector) }}" class="btn btn-outline-secondary">{{ __('Run logs') }}</a>
        <a href="{{ route('admin.lead-collectors.rules.index', $collector) }}" class="btn btn-outline-secondary">{{ __('Rules') }}</a>
        <a href="{{ route('admin.lead-collectors.raw-records.index', $collector) }}" class="btn btn-outline-secondary">{{ __('Raw records') }}</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Collector details') }}</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3 text-muted">{{ __('Name') }}</dt>
                <dd class="col-sm-9">{{ $collector->name }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Type') }}</dt>
                <dd class="col-sm-9">{{ ucfirst(str_replace('_', ' ', $collector->type->value)) }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Status') }}</dt>
                <dd class="col-sm-9">
                    <span class="badge {{ $collector->status->value === 'active' ? 'bg-success' : ($collector->status->value === 'draft' ? 'bg-secondary' : 'bg-warning text-dark') }}">{{ ucfirst($collector->status->value) }}</span>
                    @if (!$collector->is_active)
                        <span class="badge bg-warning text-dark ms-1">{{ __('Inactive') }}</span>
                    @endif
                </dd>
                <dt class="col-sm-3 text-muted">{{ __('Target service') }}</dt>
                <dd class="col-sm-9">{{ $collector->target_service ? ucfirst(str_replace('_', ' ', $collector->target_service)) : '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Target niche / location') }}</dt>
                <dd class="col-sm-9">{{ $collector->target_niche ?? '—' }} @if($collector->target_city) — {{ $collector->target_city }}@endif @if($collector->target_country) ({{ $collector->target_country }})@endif</dd>
                <dt class="col-sm-3 text-muted">{{ __('Schedule') }}</dt>
                <dd class="col-sm-9">{{ $collector->schedule ?? '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Lead source') }}</dt>
                <dd class="col-sm-9">
                    @if ($collector->leadSource)
                        <a href="{{ route('admin.lead-sources.show', $collector->leadSource) }}">{{ $collector->leadSource->name }}</a>
                    @else
                        —
                    @endif
                </dd>
                <dt class="col-sm-3 text-muted">{{ __('Last run') }}</dt>
                <dd class="col-sm-9">{{ $collector->last_run_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Next run') }}</dt>
                <dd class="col-sm-9">{{ $collector->next_run_at?->format('Y-m-d H:i') ?? '—' }}</dd>
            </dl>

            @if ($collector->config && count((array) $collector->config) > 0)
                <hr>
                <h6 class="text-muted">{{ __('Config') }}</h6>
                <pre class="bg-light p-3 rounded overflow-auto small mb-0">{{ json_encode($collector->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            @endif
            @if ($collector->filters_json && count((array) $collector->filters_json) > 0)
                <hr>
                <h6 class="text-muted">{{ __('Filters') }}</h6>
                <pre class="bg-light p-3 rounded overflow-auto small mb-0">{{ json_encode($collector->filters_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ __('Rules') }}</h5>
                    <a href="{{ route('admin.lead-collectors.rules.index', $collector) }}" class="btn btn-sm btn-outline-primary">{{ __('Manage') }}</a>
                </div>
                <div class="card-body p-0">
                    @if ($collector->rules->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach ($collector->rules->take(5) as $rule)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><code>{{ $rule->rule_key }}</code> {{ $rule->rule_operator }} @if($rule->rule_value){{ $rule->rule_value }}@endif</span>
                                    <span class="badge bg-secondary">{{ $rule->score_weight }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0 p-3">{{ __('No rules defined.') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ __('Latest runs') }}</h5>
                    <a href="{{ route('admin.lead-collectors.runs.index', $collector) }}" class="btn btn-sm btn-outline-primary">{{ __('View all') }}</a>
                </div>
                <div class="card-body p-0">
                    @if (isset($recentRuns) && $recentRuns->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach ($recentRuns as $run)
                                <li class="list-group-item">
                                    <a href="{{ route('admin.lead-collector-runs.show', $run) }}">#{{ $run->id }}</a>
                                    <span class="badge {{ $run->status->value === 'completed' ? 'bg-success' : ($run->status->value === 'failed' ? 'bg-danger' : 'bg-secondary') }} ms-2">{{ $run->status->value }}</span>
                                    — {{ $run->total_found }} found — {{ $run->started_at?->format('Y-m-d H:i') }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0 p-3">{{ __('No runs yet.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ __('Recent raw records') }}</h5>
            <a href="{{ route('admin.lead-collectors.raw-records.index', $collector) }}" class="btn btn-sm btn-outline-primary">{{ __('View all') }}</a>
        </div>
        <div class="card-body p-0">
            @if (isset($recentRawRecords) && $recentRawRecords->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm my-0">
                        <thead>
                            <tr>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Website') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Discovered') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentRawRecords as $rec)
                                <tr>
                                    <td>{{ $rec->company_name ?? '—' }}</td>
                                    <td>{{ Str::limit($rec->website ?? '—', 30) }}</td>
                                    <td><span class="badge bg-secondary">{{ $rec->processing_status->value }}</span></td>
                                    <td>{{ $rec->discovered_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0 p-3">{{ __('No raw records yet.') }}</p>
            @endif
        </div>
    </div>
@endsection
