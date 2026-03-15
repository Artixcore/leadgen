@extends('admin.layouts.app')

@section('title', __('Edit role'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Edit role') . ': ' . ucfirst($role->name),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Roles') => route('admin.roles.index'),
            ucfirst($role->name) => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Permissions') }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PUT')

                <p class="text-muted mb-4">{{ __('Select permissions for this role.') }}</p>
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-4 col-lg-3 mb-2">
                            <label class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-check-input" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <span class="form-check-label">{{ $permission->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('Update role') }}</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
