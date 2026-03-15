@extends('admin.layouts.app')

@section('title', __('Add country'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Add country'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Countries') => route('admin.countries.index'),
            __('Add') => null,
        ],
    ])

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('New country') }}</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <x-admin.alert type="danger" class="mb-4">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </x-admin.alert>
                    @endif

                    <form method="POST" action="{{ route('admin.countries.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Code') }} (ISO)</label>
                            <input type="text" id="code" name="code" class="form-control text-uppercase" value="{{ old('code') }}" maxlength="3" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                            <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
