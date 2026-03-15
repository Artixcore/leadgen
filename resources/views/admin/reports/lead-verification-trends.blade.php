@extends('admin.layouts.app')

@section('title', __('Lead verification trends'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Lead verification trends'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Reports') => route('admin.reports.index'),
            __('Lead verification trends') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Lead verification trends') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped my-0">
                <thead>
                    <tr>
                        <th>{{ __('Verification status') }}</th>
                        <th class="text-end">{{ __('Count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $row)
                        <tr>
                            <td>{{ $row->verification_status ?? '—' }}</td>
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
