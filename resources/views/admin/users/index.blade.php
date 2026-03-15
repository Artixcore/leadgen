@extends('admin.layouts.app')

@section('title', __('Users'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Users'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Users') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('User list') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="table-action">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                                <td>
                                    <span class="badge
                                        @if ($user->status->value === 'active') bg-success
                                        @elseif ($user->status->value === 'suspended') bg-danger
                                        @else bg-warning
                                        @endif">
                                        {{ ucfirst($user->status->value) }}
                                    </span>
                                </td>
                                <td class="table-action">
                                    <a href="{{ route('admin.users.edit', $user) }}"><i class="align-middle fas fa-fw fa-pen"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
