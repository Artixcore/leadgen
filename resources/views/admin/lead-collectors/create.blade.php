@extends('admin.layouts.app')

@section('title', __('Add lead collector'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Add lead collector'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            __('Lead Collectors') => route('admin.lead-collectors.index'),
            __('Add') => null,
        ],
    ])

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('New lead collector') }}</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <x-admin.alert type="danger" class="mb-4">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </x-admin.alert>
                    @endif

                    <form method="POST" action="{{ route('admin.lead-collectors.store') }}">
                        @csrf
                        @include('admin.lead-collectors._form', ['collector' => null])

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('Create collector') }}</button>
                            <a href="{{ route('admin.lead-collectors.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
