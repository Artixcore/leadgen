@extends('admin.layouts.app')

@section('title', __('Subscriptions'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Subscriptions'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Subscriptions') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Subscription list') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Plan') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Trial ends') }}</th>
                            <th>{{ __('Ends at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $sub)
                            <tr>
                                <td>
                                    {{ $sub->user?->name ?? '—' }}
                                    <span class="text-muted">({{ $sub->user?->email ?? '—' }})</span>
                                </td>
                                <td>{{ $sub->plan?->name ?? '—' }}</td>
                                <td>{{ $sub->stripe_status }}</td>
                                <td>{{ $sub->trial_ends_at?->format('M j, Y') ?? '—' }}</td>
                                <td>{{ $sub->ends_at?->format('M j, Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
@endsection
