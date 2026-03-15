@extends('admin.layouts.app')

@section('title', __('Subscription Plans'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Subscription Plans'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Subscription Plans') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Plans') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Leads/mo') }}</th>
                            <th>{{ __('Exports/mo') }}</th>
                            <th>{{ __('Saved lists') }}</th>
                            <th>{{ __('API') }}</th>
                            <th>{{ __('Active') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td>{{ $plan->slug }}</td>
                                <td>{{ $plan->leads_per_month === null ? '∞' : number_format($plan->leads_per_month) }}</td>
                                <td>{{ number_format($plan->exports_per_month) }}</td>
                                <td>{{ number_format($plan->saved_lists_count) }}</td>
                                <td>{{ $plan->api_access ? __('Yes') : __('No') }}</td>
                                <td>{{ $plan->is_active ? __('Yes') : __('No') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
