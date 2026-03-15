@extends('admin.layouts.app')

@section('title', __('Most active users'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Most active users'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Reports') => route('admin.reports.index'),
            __('Most active users') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Most active users') }}</h5>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-4">{{ __('Activity = leads viewed + exports in current period.') }}</p>
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th class="text-end">{{ __('Activity (this period)') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }} <span class="text-muted">({{ $user->email }})</span></td>
                                <td class="text-end">{{ $usageMap->get($user->id, 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">{{ __('No data.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
