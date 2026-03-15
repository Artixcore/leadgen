@extends('admin.layouts.app')

@section('title', __('Categories'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Categories'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Categories') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <span></span>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">{{ __('Add category') }}</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Category list') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped my-0">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th class="table-action">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->type ?? '—' }}</td>
                            <td class="table-action">
                                <a href="{{ route('admin.categories.show', $category) }}" class="me-2"><i class="align-middle fas fa-fw fa-eye"></i></a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="me-2"><i class="align-middle fas fa-fw fa-pen"></i></a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this category?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger"><i class="align-middle fas fa-fw fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">{{ __('No categories yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3 border-top">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
