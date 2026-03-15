@extends('admin.layouts.app')

@section('title', __('Edit country'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Edit country') . ': ' . $country->name,
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Countries') => route('admin.countries.index'),
            $country->name => route('admin.countries.show', $country),
            __('Edit') => null,
        ],
    ])

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $country->name }}</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <x-admin.alert type="danger" class="mb-4">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </x-admin.alert>
                    @endif

                    <form method="POST" action="{{ route('admin.countries.update', $country) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $country->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Code') }}</label>
                            <input type="text" id="code" name="code" class="form-control text-uppercase" value="{{ old('code', $country->code) }}" maxlength="3" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            <a href="{{ route('admin.countries.show', $country) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
