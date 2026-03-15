@extends('admin.layouts.app')

@section('title', __('Reports'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Reports'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Reports') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Reports') }}</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">{{ __('Select a report to view.') }}</p>
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><a href="{{ route('admin.reports.leads-over-time') }}">{{ __('Leads added over time') }}</a></li>
                <li class="mb-2"><a href="{{ route('admin.reports.source-performance') }}">{{ __('Source performance') }}</a></li>
                <li class="mb-2"><a href="{{ route('admin.reports.most-active-users') }}">{{ __('Most active users') }}</a></li>
                <li class="mb-2"><a href="{{ route('admin.reports.revenue-by-month') }}">{{ __('Revenue by month') }}</a></li>
                <li class="mb-2"><a href="{{ route('admin.reports.plan-distribution') }}">{{ __('Plan distribution') }}</a></li>
                <li class="mb-2"><a href="{{ route('admin.reports.export-usage-trends') }}">{{ __('Export usage trends') }}</a></li>
                <li class="mb-2"><a href="{{ route('admin.reports.lead-verification-trends') }}">{{ __('Lead verification trends') }}</a></li>
            </ul>
        </div>
    </div>
@endsection
