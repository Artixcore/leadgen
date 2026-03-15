@extends('admin.layouts.app')

@section('title', $category->name)

@section('content')
    @include('admin.partials.page-header', [
        'title' => $category->name,
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Categories') => route('admin.categories.index'),
            $category->name => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">{{ __('Edit') }}</a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Category details') }}</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3 text-muted">{{ __('Name') }}</dt>
                <dd class="col-sm-9">{{ $category->name }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Slug') }}</dt>
                <dd class="col-sm-9">{{ $category->slug }}</dd>
                <dt class="col-sm-3 text-muted">{{ __('Type') }}</dt>
                <dd class="col-sm-9">{{ $category->type ?? '—' }}</dd>
            </dl>
        </div>
    </div>
@endsection
