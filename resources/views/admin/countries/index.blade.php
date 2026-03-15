@extends('admin.layouts.app')

@section('title', __('Countries'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Countries'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Countries') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <span></span>
        <a href="{{ route('admin.countries.create') }}" class="btn btn-primary">{{ __('Add country') }}</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Country list') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped my-0">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Code') }}</th>
                        <th class="table-action">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($countries as $country)
                        <tr>
                            <td>{{ $country->name }}</td>
                            <td>{{ $country->code }}</td>
                            <td class="table-action">
                                <a href="{{ route('admin.countries.show', $country) }}" class="me-2"><i class="align-middle fas fa-fw fa-eye"></i></a>
                                <a href="{{ route('admin.countries.edit', $country) }}" class="me-2"><i class="align-middle fas fa-fw fa-pen"></i></a>
                                <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this country?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger"><i class="align-middle fas fa-fw fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">{{ __('No countries yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3 border-top">
                {{ $countries->links() }}
            </div>
        </div>
    </div>
@endsection
