@extends('admin.layouts.app')

@section('title', __('Notifications'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Notifications'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Notifications') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Notification list') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Notifiable') }}</th>
                            <th>{{ __('Read at') }}</th>
                            <th>{{ __('Created') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notifications as $notification)
                            @php
                                $type = $notification->type ? class_basename($notification->type) : '—';
                            @endphp
                            <tr>
                                <td>{{ $type }}</td>
                                <td>{{ $notification->notifiable_type }} #{{ $notification->notifiable_id }}</td>
                                <td>{{ $notification->read_at ? \Carbon\Carbon::parse($notification->read_at)->format('M j, Y H:i') : '—' }}</td>
                                <td>{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->format('M j, Y H:i') : '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">{{ __('No notifications.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
