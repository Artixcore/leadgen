@extends('admin.layouts.app')

@section('title', $source->name)

@section('content')
    @include('admin.partials.page-header', [
        'title' => $source->name,
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            $source->name => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.lead-sources.edit', $source) }}" class="btn btn-secondary">{{ __('Edit') }}</a>
        <form action="{{ route('admin.lead-sources.pause', $source) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning">{{ $source->status->value === 'active' ? __('Pause') : __('Resume') }}</button>
        </form>
        @if ($source->status->value === 'active')
            <form action="{{ route('admin.lead-sources.sync', $source) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('Sync now') }}</button>
            </form>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Source details') }}</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3 text-muted">{{ __('Name') }}</dt>
                <dd class="col-sm-9">{{ $source->name }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Type') }}</dt>
                <dd class="col-sm-9">{{ ucfirst($source->type->value) }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Status') }}</dt>
                <dd class="col-sm-9"><span class="badge {{ $source->status->value === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($source->status->value) }}</span></dd>
                <dt class="col-sm-3 text-muted">{{ __('Reliability score') }}</dt>
                <dd class="col-sm-9">{{ $source->reliability_score !== null ? $source->reliability_score . '%' : '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Last sync') }}</dt>
                <dd class="col-sm-9">{{ $source->last_sync_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Import frequency') }}</dt>
                <dd class="col-sm-9">{{ $source->import_frequency ?? '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Leads count') }}</dt>
                <dd class="col-sm-9">{{ $source->leads_count }}</dd>
            </dl>

            @if ($source->validation_rules)
                <hr>
                <dt class="text-muted">{{ __('Validation rules') }}</dt>
                <dd><pre class="bg-light p-3 rounded overflow-auto small">{{ json_encode($source->validation_rules, JSON_PRETTY_PRINT) }}</pre></dd>
            @endif

            @if (isset($recentRuns) && $recentRuns->isNotEmpty())
                <hr>
                <dt class="text-muted">{{ __('Recent import runs') }}</dt>
                <dd>
                    <ul class="list-unstyled mb-0">
                        @foreach ($recentRuns as $run)
                            <li>
                                <a href="{{ route('admin.import-runs.show', $run) }}">#{{ $run->id }} — {{ $run->status->value }} — {{ $run->created_at->format('Y-m-d H:i') }}</a>
                            </li>
                        @endforeach
                    </ul>
                </dd>
            @endif

            <hr>
            <a href="{{ route('admin.import-runs.index', ['source' => $source->id]) }}">{{ __('View all import runs') }}</a>
        </div>
    </div>
@endsection
