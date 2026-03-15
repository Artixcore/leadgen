@extends('admin.layouts.app')

@section('title', $collector ? __('Run logs') . ' — ' . $collector->name : __('Collector run logs'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => $collector ? __('Run logs') . ' — ' . $collector->name : __('Collector run logs'),
        'breadcrumbs' => array_filter([
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            __('Lead Collectors') => route('admin.lead-collectors.index'),
            $collector ? $collector->name => $collector ? route('admin.lead-collectors.show', $collector) : null,
            $collector ? __('Run logs') : null => null,
        ]),
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    @if ($collector)
        <div class="mb-4">
            <a href="{{ route('admin.lead-collectors.show', $collector) }}" class="btn btn-outline-secondary">{{ __('Back to collector') }}</a>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ $collector ? route('admin.lead-collectors.runs.index', $collector) : route('admin.lead-collector-runs.index') }}" class="row g-3">
                <div class="col-auto">
                    <label for="status" class="form-label">{{ __('Status') }}</label>
                    <select id="status" name="status" class="form-select form-select-sm" style="width: 10rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\LeadCollectorRunStatus::cases() as $s)
                            <option value="{{ $s->value }}" @selected(($filters['status'] ?? '') === $s->value)>{{ ucfirst($s->value) }}</option>
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
            <h5 class="card-title mb-0">{{ __('Runs') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            @if (!$collector)<th>{{ __('Collector') }}</th>@endif
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Found') }}</th>
                            <th>{{ __('Processed') }}</th>
                            <th>{{ __('Started') }}</th>
                            <th>{{ __('Finished') }}</th>
                            <th class="table-action">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($runs as $run)
                            <tr>
                                @if (!$collector)
                                    <td><a href="{{ route('admin.lead-collectors.show', $run->leadCollector) }}">{{ $run->leadCollector->name }}</a></td>
                                @endif
                                <td>#{{ $run->id }}</td>
                                <td>{{ $run->run_type }}</td>
                                <td><span class="badge {{ $run->status->value === 'completed' ? 'bg-success' : ($run->status->value === 'failed' ? 'bg-danger' : 'bg-secondary') }}">{{ $run->status->value }}</span></td>
                                <td>{{ $run->total_found }}</td>
                                <td>{{ $run->total_processed }}</td>
                                <td>{{ $run->started_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td>{{ $run->finished_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="table-action">
                                    <a href="{{ route('admin.lead-collector-runs.show', $run) }}" class="me-2" title="{{ __('View') }}"><i class="align-middle fas fa-fw fa-eye"></i></a>
                                    <a href="{{ route('admin.lead-collector-runs.raw-records.index', $run) }}" title="{{ __('Raw records') }}"><i class="align-middle fas fa-fw fa-list"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $collector ? 8 : 9 }}" class="text-center text-muted py-4">{{ __('No runs yet.') }}</td>
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
