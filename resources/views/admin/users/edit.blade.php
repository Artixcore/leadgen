@extends('admin.layouts.app')

@section('title', __('Edit user status'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Edit user status'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Users') => route('admin.users.index'),
            __('Edit') => null,
        ],
    ])

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Account status') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        {{ __('User:') }} <strong>{{ $user->name }}</strong> ({{ $user->email }})
                    </p>

                    @if ($errors->any())
                        <x-admin.alert type="danger" class="mb-4">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </x-admin.alert>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Account status') }}</label>
                            <select id="status" name="status" class="form-control">
                                @foreach (\App\UserStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status', $user->status->value) === $status->value)>
                                        {{ ucfirst($status->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <button type="submit" class="btn btn-primary">{{ __('Update status') }}</button>
                            <a href="{{ route('admin.users.index') }}" class="text-muted">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
