@extends('admin.layouts.app')

@section('title', __('Add category'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Add category'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Categories') => route('admin.categories.index'),
            __('Add') => null,
        ],
    ])

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('New category') }}</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <x-admin.alert type="danger" class="mb-4">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </x-admin.alert>
                    @endif

                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">{{ __('Slug') }} ({{ __('optional') }})</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}">
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type') }} ({{ __('optional') }})</label>
                            <input type="text" id="type" name="type" class="form-control" value="{{ old('type') }}">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
