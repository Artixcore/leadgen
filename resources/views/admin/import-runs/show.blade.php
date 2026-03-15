@extends('admin.layouts.app')

@section('title', __('Import run') . ' #' . $run->id)

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Import run') . ' #' . $run->id,
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Import Logs') => route('admin.import-runs.index'),
            '#' . $run->id => null,
        ],
    ])

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.import-runs.index') }}" class="btn btn-secondary">{{ __('Back to list') }}</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Run details') }}</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3 text-muted">{{ __('Source') }}</dt>
                <dd class="col-sm-9">{{ $run->leadSource?->name ?? '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Status') }}</dt>
                <dd class="col-sm-9">
                    <span class="badge
                        @if ($run->status->value === 'completed') bg-success
                        @elseif ($run->status->value === 'failed') bg-danger
                        @elseif ($run->status->value === 'running') bg-primary
                        @else bg-secondary
                        @endif">
                        {{ ucfirst($run->status->value) }}
                    </span>
                </dd>
                <dt class="col-sm-3 text-muted">{{ __('Started') }}</dt>
                <dd class="col-sm-9">{{ $run->started_at?->format('Y-m-d H:i:s') ?? '—' }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Completed') }}</dt>
                <dd class="col-sm-9">{{ $run->completed_at?->format('Y-m-d H:i:s') ?? '—' }}</dd>
                @if ($run->error_message)
                    <dt class="col-sm-3 text-muted">{{ __('Error') }}</dt>
                    <dd class="col-sm-9 text-danger">{{ $run->error_message }}</dd>
                @endif
                @if ($run->stats)
                    <dt class="col-sm-3 text-muted">{{ __('Stats') }}</dt>
                    <dd class="col-sm-9"><pre class="bg-light p-3 rounded small mb-0">{{ json_encode($run->stats, JSON_PRETTY_PRINT) }}</pre></dd>
                @endif
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Rows') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Raw / normalized') }}</th>
                            <th>{{ __('Validation errors') }}</th>
                            <th>{{ __('Lead') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($run->rows as $row)
                            <tr>
                                <td>{{ $row->row_index }}</td>
                                <td>
                                    <span class="badge
                                        @if ($row->status->value === 'imported') bg-success
                                        @elseif ($row->status->value === 'invalid') bg-danger
                                        @elseif ($row->status->value === 'duplicate') bg-warning
                                        @elseif ($row->status->value === 'valid') bg-info
                                        @else bg-secondary
                                        @endif">
                                        {{ $row->status->value }}
                                    </span>
                                </td>
                                <td>
                                    <details class="small">
                                        <summary class="cursor-pointer text-primary">{{ __('View data') }}</summary>
                                        <pre class="mt-1 bg-light p-2 rounded overflow-auto" style="max-height: 8rem;">{{ json_encode($row->normalized_data ?? $row->raw_data, JSON_PRETTY_PRINT) }}</pre>
                                    </details>
                                </td>
                                <td>{{ $row->validation_errors ? implode(', ', $row->validation_errors) : '—' }}</td>
                                <td>
                                    @if ($row->lead_id)
                                        <a href="{{ route('leads.show', $row->lead_id) }}">{{ __('View lead') }} #{{ $row->lead_id }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('No rows.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
