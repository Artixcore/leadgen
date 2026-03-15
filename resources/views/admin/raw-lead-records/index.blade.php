@extends('admin.layouts.app')

@section('title', isset($run) ? __('Raw records') . ' — Run #' . $run->id : __('Raw records') . ' — ' . $collector->name)

@section('content')
    @php
        $breadcrumbCollector = $collector ?? $run->leadCollector ?? null;
        $breadcrumbs = [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            __('Lead Collectors') => route('admin.lead-collectors.index'),
            $breadcrumbCollector->name => route('admin.lead-collectors.show', $breadcrumbCollector),
        ];
        if (isset($run)) {
            $breadcrumbs[__('Run') . ' #' . $run->id] = route('admin.lead-collector-runs.show', $run);
        }
        $breadcrumbs[__('Raw records')] = null;
    @endphp
    @include('admin.partials.page-header', [
        'title' => isset($run) ? __('Raw records') . ' — Run #' . $run->id : __('Raw records') . ' — ' . $collector->name,
        'breadcrumbs' => $breadcrumbs,
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="mb-4">
        @if (isset($run))
            <a href="{{ route('admin.lead-collector-runs.show', $run) }}" class="btn btn-outline-secondary">{{ __('Back to run') }}</a>
        @else
            <a href="{{ route('admin.lead-collectors.show', $collector) }}" class="btn btn-outline-secondary">{{ __('Back to collector') }}</a>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ isset($run) ? route('admin.lead-collector-runs.raw-records.index', $run) : route('admin.lead-collectors.raw-records.index', $collector) }}" class="row g-3">
                <div class="col-auto">
                    <label for="processing_status" class="form-label">{{ __('Status') }}</label>
                    <select id="processing_status" name="processing_status" class="form-select form-select-sm" style="width: 10rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\RawLeadRecordStatus::cases() as $s)
                            <option value="{{ $s->value }}" @selected(($filters['processing_status'] ?? '') === $s->value)>{{ ucfirst($s->value) }}</option>
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
            <h5 class="card-title mb-0">{{ __('Raw lead records') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Company') }}</th>
                            <th>{{ __('Website') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('City') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Discovered') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rawRecords as $rec)
                            <tr>
                                <td>{{ $rec->company_name ?? '—' }}</td>
                                <td>{{ Str::limit($rec->website ?? '—', 30) }}</td>
                                <td>{{ Str::limit($rec->email ?? '—', 25) }}</td>
                                <td>{{ Str::limit($rec->phone ?? '—', 15) }}</td>
                                <td>{{ $rec->city ?? '—' }}</td>
                                <td><span class="badge bg-secondary">{{ $rec->processing_status->value }}</span></td>
                                <td>{{ $rec->discovered_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">{{ __('No raw records.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $rawRecords->links() }}
            </div>
        </div>
    </div>
@endsection
