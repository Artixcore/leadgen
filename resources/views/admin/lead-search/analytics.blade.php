@extends('admin.layouts.app')

@section('title', __('Lead Search Analytics'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Lead Search Analytics'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Search') => route('admin.lead-search.analytics'),
            __('Analytics') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="row">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">{{ __('Searches today') }}</h5>
                    <p class="h3 mb-0">{{ $searchesToday }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">{{ __('Searches this week') }}</h5>
                    <p class="h3 mb-0">{{ $searchesThisWeek }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">{{ __('Total searches') }}</h5>
                    <p class="h3 mb-0">{{ $totalSearches }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-muted">{{ __('Failed searches') }}</h5>
                    <p class="h3 mb-0">{{ $failedSearches }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Top queries') }}</h5>
                </div>
                <div class="card-body">
                    @if ($topQueries->isEmpty())
                        <p class="text-muted mb-0">{{ __('No search data yet.') }}</p>
                    @else
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('Query') }}</th>
                                    <th>{{ __('Count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topQueries as $row)
                                    <tr>
                                        <td>{{ Str::limit($row->query, 80) }}</td>
                                        <td>{{ $row->cnt }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
