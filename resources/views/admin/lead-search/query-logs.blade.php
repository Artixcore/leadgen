@extends('admin.layouts.app')

@section('title', __('Lead Search Query Logs'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Lead Search Query Logs'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Search') => route('admin.lead-search.analytics'),
            __('Query logs') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Recent searches') }}</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Query') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Results') }}</th>
                        <th>{{ __('Time (ms)') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($queries as $query)
                        <tr>
                            <td>{{ $query->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $query->user?->name ?? $query->user_id }}<br><small class="text-muted">{{ $query->user?->email }}</small></td>
                            <td>{{ Str::limit($query->query, 50) }}</td>
                            <td><span class="badge bg-{{ $query->status === 'completed' ? 'success' : ($query->status === 'failed' ? 'danger' : 'secondary') }}">{{ $query->status }}</span></td>
                            <td>{{ $query->total_results }}</td>
                            <td>{{ $query->search_took_ms ?? '–' }}</td>
                            <td>
                                <a href="{{ route('admin.lead-search.query-logs.results', $query) }}" class="btn btn-sm btn-outline-primary">{{ __('View results') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $queries->withQueryString()->links() }}
        </div>
    </div>
@endsection
