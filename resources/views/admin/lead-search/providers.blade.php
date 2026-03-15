@extends('admin.layouts.app')

@section('title', __('Lead Search Providers'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Lead Search Providers'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Search') => route('admin.lead-search.analytics'),
            __('Providers') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Providers') }}</h5>
        </div>
        <div class="card-body">
            @if ($providers->isEmpty())
                <p class="text-muted mb-0">{{ __('No providers in database. Searches use config-based providers.') }}</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Priority') }}</th>
                            <th>{{ __('Trust score') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($providers as $provider)
                            <tr>
                                <td>{{ $provider->name }}</td>
                                <td><code>{{ $provider->slug }}</code></td>
                                <td>{{ $provider->status }}</td>
                                <td>{{ $provider->priority }}</td>
                                <td>{{ $provider->trust_score }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.lead-search.providers.update', $provider) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                            <option value="active" {{ $provider->status === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="disabled" {{ $provider->status === 'disabled' ? 'selected' : '' }}>{{ __('Disabled') }}</option>
                                        </select>
                                        <input type="number" name="priority" value="{{ $provider->priority }}" min="0" max="999" class="form-control form-control-sm d-inline-block w-75 ms-1">
                                        <input type="number" name="trust_score" value="{{ $provider->trust_score }}" min="0" max="100" class="form-control form-control-sm d-inline-block w-75 ms-1">
                                        <button type="submit" class="btn btn-sm btn-primary ms-1">{{ __('Update') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
