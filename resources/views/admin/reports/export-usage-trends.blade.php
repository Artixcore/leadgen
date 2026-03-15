@extends('admin.layouts.app')

@section('title', __('Export usage trends'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Export usage trends'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Reports') => route('admin.reports.index'),
            __('Export usage trends') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Export usage trends') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped my-0">
                <thead>
                    <tr>
                        <th>{{ __('Period') }}</th>
                        <th class="text-end">{{ __('Exports') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $row)
                        <tr>
                            <td>{{ $row->period }}</td>
                            <td class="text-end">{{ number_format($row->total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">{{ __('No data.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
