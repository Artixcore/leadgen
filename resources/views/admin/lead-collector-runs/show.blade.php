@extends('admin.layouts.app')

@section('title', __('Run') . ' #' . $run->id)

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Run') . ' #' . $run->id,
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            __('Lead Collectors') => route('admin.lead-collectors.index'),
            $run->leadCollector->name => route('admin.lead-collectors.show', $run->leadCollector),
            __('Run') . ' #' . $run->id => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.lead-collectors.show', $run->leadCollector) }}" class="btn btn-outline-secondary">{{ __('Back to collector') }}</a>
        <a href="{{ route('admin.lead-collectors.runs.index', $run->leadCollector) }}" class="btn btn-outline-secondary">{{ __('All runs') }}</a>
        <a href="{{ route('admin.lead-collector-runs.raw-records.index', $run) }}" class="btn btn-primary">{{ __('View raw records') }}</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Run summary') }}</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3 text-muted">{{ __('Status') }}</dt>
                <dd class="col-sm-9"><span class="badge {{ $run->status->value === 'completed' ? 'bg-success' : ($run->status->value === 'failed' ? 'bg-danger' : 'bg-secondary') }}">{{ $run->status->value }}</span></dd>
                <dt class="col-sm-3 text-muted">{{ __('Run type') }}</dt>
                <dd class="col-sm-9">{{ $run->run_type }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Total found') }}</dt>
                <dd class="col-sm-9">{{ $run->total_found }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Total processed') }}</dt>
                <dd class="col-sm-9">{{ $run->total_processed }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Total new') }}</dt>
                <dd class="col-sm-9">{{ $run->total_new }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Total duplicates') }}</dt>
                <dd class="col-sm-9">{{ $run->total_duplicates }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Total failed') }}</dt>
                <dd class="col-sm-9">{{ $run->total_failed }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Started at') }}</dt>
                <dd class="col-sm-9">{{ $run->started_at?->format('Y-m-d H:i:s') ?? '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Finished at') }}</dt>
                <dd class="col-sm-9">{{ $run->finished_at?->format('Y-m-d H:i:s') ?? '—' }}</dd>
                @if ($run->error_message)
                    <dt class="col-sm-3 text-muted">{{ __('Error') }}</dt>
                    <dd class="col-sm-9"><span class="text-danger">{{ $run->error_message }}</span></dd>
                @endif
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ __('Raw records for this run') }}</h5>
            <a href="{{ route('admin.lead-collector-runs.raw-records.index', $run) }}" class="btn btn-sm btn-primary">{{ __('View all') }}</a>
        </div>
        <div class="card-body p-0">
            @if ($rawRecords->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm my-0">
                        <thead>
                            <tr>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Website') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rawRecords as $rec)
                                <tr>
                                    <td>{{ $rec->company_name ?? '—' }}</td>
                                    <td>{{ Str::limit($rec->website ?? '—', 35) }}</td>
                                    <td>{{ Str::limit($rec->email ?? '—', 30) }}</td>
                                    <td><span class="badge bg-secondary">{{ $rec->processing_status->value }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    {{ $rawRecords->links() }}
                </div>
            @else
                <p class="text-muted mb-0 p-3">{{ __('No raw records for this run.') }}</p>
            @endif
        </div>
    </div>
@endsection
