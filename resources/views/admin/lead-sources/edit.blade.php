@extends('admin.layouts.app')

@section('title', __('Edit lead source'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Edit lead source'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            $source->name => route('admin.lead-sources.show', $source),
            __('Edit') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $source->name }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.lead-sources.update', $source) }}">
                        @csrf
                        @method('patch')
                        @include('admin.lead-sources._form', ['source' => $source])

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('Update source') }}</button>
                            <a href="{{ route('admin.lead-sources.show', $source) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
