@extends('admin.layouts.app')

@section('title', __('Settings'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Settings'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Settings') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Application settings') }}</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <x-admin.alert type="danger" class="mb-4">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </x-admin.alert>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="app_name" class="form-label">{{ __('App name') }}</label>
                            <input type="text" id="app_name" name="app_name" class="form-control" value="{{ old('app_name', $settings['app_name']) }}">
                        </div>
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">{{ __('Contact email') }}</label>
                            <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email']) }}">
                        </div>
                        <div class="mb-3">
                            <label for="maintenance_mode" class="form-label">{{ __('Maintenance mode') }}</label>
                            <select id="maintenance_mode" name="maintenance_mode" class="form-select">
                                <option value="0" @selected(($settings['maintenance_mode'] ?? '0') === '0')>{{ __('No') }}</option>
                                <option value="1" @selected(($settings['maintenance_mode'] ?? '0') === '1')>{{ __('Yes') }}</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Save settings') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
