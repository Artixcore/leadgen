@extends('admin.layouts.app')

@section('title', __('Import runs'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Import runs'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Import Logs') => null,
        ],
    ])

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.import-runs.index') }}" class="row g-3">
                <div class="col-auto">
                    <label for="source" class="form-label">{{ __('Source') }}</label>
                    <select id="source" name="source" class="form-select form-select-sm" style="width: 12rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\Models\LeadSource::orderBy('name')->get() as $s)
                            <option value="{{ $s->id }}" @selected(($filters['source'] ?? '') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label for="status" class="form-label">{{ __('Status') }}</label>
                    <select id="status" name="status" class="form-select form-select-sm" style="width: 10rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\ImportRunStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary btn-sm">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Import run list') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Source') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Started') }}</th>
                            <th>{{ __('Stats') }}</th>
                            <th class="table-action">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($runs as $run)
                            <tr>
                                <td>{{ $run->id }}</td>
                                <td>{{ $run->leadSource?->name ?? '—' }}</td>
                                <td>
                                    <span class="badge
                                        @if ($run->status->value === 'completed') bg-success
                                        @elseif ($run->status->value === 'failed') bg-danger
                                        @elseif ($run->status->value === 'running') bg-primary
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($run->status->value) }}
                                    </span>
                                </td>
                                <td>{{ $run->started_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td>{{ $run->stats ? json_encode($run->stats) : '—' }}</td>
                                <td class="table-action">
                                    <a href="{{ route('admin.import-runs.show', $run) }}"><i class="align-middle fas fa-fw fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ __('No import runs yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $runs->links() }}
            </div>
        </div>
    </div>
@endsection
