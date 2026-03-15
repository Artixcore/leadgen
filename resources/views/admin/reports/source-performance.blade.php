@extends('admin.layouts.app')

@section('title', __('Source performance'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Source performance'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Reports') => route('admin.reports.index'),
            __('Source performance') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Source performance') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped my-0">
                <thead>
                    <tr>
                        <th>{{ __('Source') }}</th>
                        <th class="text-end">{{ __('Leads') }}</th>
                        <th>{{ __('Last run') }}</th>
                        <th class="text-end">{{ __('Completed') }}</th>
                        <th class="text-end">{{ __('Failed') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sources as $source)
                        @php
                            $counts = $runCounts->get($source->id, collect());
                            $completed = $counts->where('status', 'completed')->sum('cnt');
                            $failed = $counts->where('status', 'failed')->sum('cnt');
                            $lastRun = $source->importRuns->first();
                        @endphp
                        <tr>
                            <td>{{ $source->name }}</td>
                            <td class="text-end">{{ number_format($source->leads_count) }}</td>
                            <td>{{ $lastRun?->status->value ?? '—' }}</td>
                            <td class="text-end">{{ $completed }}</td>
                            <td class="text-end {{ $failed > 0 ? 'text-danger' : '' }}">{{ $failed }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
