@extends('admin.layouts.app')

@section('title', __('Admin Dashboard'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Welcome back, :name!', ['name' => Auth::user()->name]),
        'breadcrumbs' => [__('Dashboard') => null],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('Total users') }}</h5>
                        </div>
                        <div class="col-auto">
                            <div class="avatar">
                                <div class="avatar-title rounded-circle bg-primary-dark">
                                    <i class="align-middle" data-feather="users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ number_format($stats['total_users']) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('Active subscriptions') }}</h5>
                        </div>
                        <div class="col-auto">
                            <div class="avatar">
                                <div class="avatar-title rounded-circle bg-primary-dark">
                                    <i class="align-middle" data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ number_format($stats['active_subscriptions']) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('Total leads') }}</h5>
                        </div>
                        <div class="col-auto">
                            <div class="avatar">
                                <div class="avatar-title rounded-circle bg-primary-dark">
                                    <i class="align-middle" data-feather="database"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ number_format($stats['total_leads']) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('New leads today') }}</h5>
                        </div>
                        <div class="col-auto">
                            <div class="avatar">
                                <div class="avatar-title rounded-circle bg-primary-dark">
                                    <i class="align-middle" data-feather="trending-up"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ number_format($stats['new_leads_today']) }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('Failed imports') }}</h5>
                        </div>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ number_format($stats['failed_imports_count']) }}</h1>
                    @if ($stats['failed_imports_count'] > 0)
                        <a href="{{ route('admin.import-runs.index') }}?status=failed" class="mb-0">{{ __('View failed runs') }}</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('Export usage (this period)') }}</h5>
                        </div>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ number_format($stats['export_usage_current_period']) }}</h1>
                    <div class="mb-0 text-muted small">{{ __('Previous period') }}: {{ number_format($stats['export_usage_previous_period']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('Revenue this month') }}</h5>
                        </div>
                    </div>
                    <h1 class="display-5 mt-1 mb-3">{{ config('app.currency', 'USD') === 'USD' ? '$' : '' }}{{ number_format($stats['revenue_current_month'], 2) }}</h1>
                    <div class="mb-0 text-muted small">{{ __('Last month') }}: {{ config('app.currency', 'USD') === 'USD' ? '$' : '' }}{{ number_format($stats['revenue_previous_month'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">{{ __('Source sync status') }}</h5>
                        </div>
                    </div>
                    <p class="mt-1 mb-3">{{ count($stats['source_sync_status']) }} {{ __('sources') }}</p>
                    <a href="{{ route('admin.lead-sources.index') }}" class="mb-0">{{ __('Manage sources') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Source sync status') }}</h5>
                </div>
                <div class="card-body p-0">
                    @if (count($stats['source_sync_status']) > 0)
                        <table class="table table-striped my-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Last sync') }}</th>
                                    <th>{{ __('Last run') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats['source_sync_status'] as $src)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.lead-sources.show', $src['id']) }}">{{ $src['name'] }}</a>
                                        </td>
                                        <td>
                                            <span class="badge {{ $src['status'] === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $src['status'] }}</span>
                                        </td>
                                        <td>{{ $src['last_sync_at']?->diffForHumans() ?? '—' }}</td>
                                        <td>{{ $src['last_run_status'] ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="p-4 text-muted mb-0">{{ __('No lead sources yet.') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Recent failed imports') }}</h5>
                </div>
                <div class="card-body p-0">
                    @if ($stats['recent_failed_imports']->isNotEmpty())
                        <table class="table table-striped my-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Completed at') }}</th>
                                    <th class="table-action">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats['recent_failed_imports'] as $run)
                                    <tr>
                                        <td>{{ $run->leadSource?->name ?? '—' }}</td>
                                        <td>{{ $run->completed_at?->format('M j, Y H:i') ?? '—' }}</td>
                                        <td class="table-action">
                                            <a href="{{ route('admin.import-runs.show', $run) }}"><i class="align-middle fas fa-fw fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="p-3 border-top">
                            <a href="{{ route('admin.import-runs.index') }}?status=failed">{{ __('All failed runs') }}</a>
                        </div>
                    @else
                        <p class="p-4 text-muted mb-0">{{ __('No failed imports.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Top countries') }}</h5>
                </div>
                <div class="card-body p-0">
                    @if (count($stats['top_countries']) > 0)
                        <table class="table table-striped my-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Country') }}</th>
                                    <th class="text-end">{{ __('Leads') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats['top_countries'] as $row)
                                    <tr>
                                        <td>{{ $row['country'] ?: __('Unknown') }}</td>
                                        <td class="text-end">{{ number_format($row['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="p-4 text-muted mb-0">{{ __('No data.') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Top industries') }}</h5>
                </div>
                <div class="card-body p-0">
                    @if (count($stats['top_industries']) > 0)
                        <table class="table table-striped my-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Industry') }}</th>
                                    <th class="text-end">{{ __('Leads') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats['top_industries'] as $row)
                                    <tr>
                                        <td>{{ $row['industry'] ?: __('Unknown') }}</td>
                                        <td class="text-end">{{ number_format($row['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="p-4 text-muted mb-0">{{ __('No data.') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Most used filters') }}</h5>
                </div>
                <div class="card-body p-0">
                    @if (count($stats['most_used_filters']) > 0)
                        <table class="table table-striped my-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Filter') }}</th>
                                    <th class="text-end">{{ __('Uses') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats['most_used_filters'] as $row)
                                    <tr>
                                        <td>{{ $row['name'] }}</td>
                                        <td class="text-end">{{ number_format($row['usage_count']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="p-4 text-muted mb-0">{{ __('No filter usage data yet.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
