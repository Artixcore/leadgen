@extends('admin.layouts.app')

@section('title', __('Saved Lead Searches'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Saved Lead Searches'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Search') => route('admin.lead-search.analytics'),
            __('Saved searches') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Saved searches') }}</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Query') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Last run') }}</th>
                        <th>{{ __('Next run') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($savedSearches as $saved)
                        <tr>
                            <td>{{ $saved->name }}</td>
                            <td>{{ Str::limit($saved->query, 50) }}</td>
                            <td>{{ $saved->user?->name ?? $saved->user_id }}<br><small class="text-muted">{{ $saved->user?->email }}</small></td>
                            <td>{{ $saved->last_run_at?->format('Y-m-d H:i') ?? '–' }}</td>
                            <td>{{ $saved->next_run_at?->format('Y-m-d H:i') ?? '–' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $savedSearches->links() }}
        </div>
    </div>
@endsection
