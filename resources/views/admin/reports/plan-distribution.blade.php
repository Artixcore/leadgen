@extends('admin.layouts.app')

@section('title', __('Plan distribution'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Plan distribution'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Reports') => route('admin.reports.index'),
            __('Plan distribution') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Plan distribution') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped my-0">
                <thead>
                    <tr>
                        <th>{{ __('Plan') }}</th>
                        <th class="text-end">{{ __('Active subscriptions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($byPlan as $planName => $count)
                        <tr>
                            <td>{{ $planName }}</td>
                            <td class="text-end">{{ number_format($count) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">{{ __('No active subscriptions.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
