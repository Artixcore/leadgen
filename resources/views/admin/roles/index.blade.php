@extends('admin.layouts.app')

@section('title', __('Roles'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Roles'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Roles') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Role list') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped my-0">
                <thead>
                    <tr>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Permissions') }}</th>
                        <th class="table-action">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td class="fw-bold">{{ ucfirst($role->name) }}</td>
                            <td>{{ $role->permissions->pluck('name')->join(', ') ?: '—' }}</td>
                            <td class="table-action">
                                @if ($role->name !== 'admin')
                                    <a href="{{ route('admin.roles.edit', $role) }}"><i class="align-middle fas fa-fw fa-pen"></i> {{ __('Edit permissions') }}</a>
                                @else
                                    <span class="text-muted">{{ __('System role') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
