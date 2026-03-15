@extends('admin.layouts.app')

@section('title', __('Activity Log'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Activity Log'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Activity Log') => null,
        ],
    ])

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-log.index') }}" class="row g-3">
                <div class="col-auto">
                    <label for="action" class="form-label">{{ __('Action') }}</label>
                    <input type="text" name="action" id="action" class="form-control form-control-sm" style="width: 14rem;" value="{{ old('action', $filters['action'] ?? '') }}" placeholder="{{ __('e.g. user.status_changed') }}">
                </div>
                <div class="col-auto">
                    <label for="user_id" class="form-label">{{ __('User ID') }}</label>
                    <input type="number" name="user_id" id="user_id" class="form-control form-control-sm" style="width: 6rem;" value="{{ old('user_id', $filters['user_id'] ?? '') }}" placeholder="{{ __('User ID') }}">
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary btn-sm">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Activity log') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Action') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('IP') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('M j, Y H:i:s') }}</td>
                                <td>{{ $log->user?->name ?? $log->user_id }}</td>
                                <td>{{ $log->action }}</td>
                                <td>
                                    @if ($log->subject)
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('No activity logged yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($logs->hasPages())
                <div class="p-3 border-top">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
