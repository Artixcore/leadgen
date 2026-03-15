@extends('admin.layouts.app')

@section('title', $country->name)

@section('content')
    @include('admin.partials.page-header', [
        'title' => $country->name,
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Countries') => route('admin.countries.index'),
            $country->name => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.countries.edit', $country) }}" class="btn btn-primary">{{ __('Edit') }}</a>
        <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Country details') }}</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3 text-muted">{{ __('Name') }}</dt>
                <dd class="col-sm-9">{{ $country->name }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Code') }}</dt>
                <dd class="col-sm-9">{{ $country->code }}</dd>
            </dl>
        </div>
    </div>
@endsection
