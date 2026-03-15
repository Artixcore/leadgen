@extends('admin.layouts.app')

@section('title', __('Leads added over time'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Leads added over time'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Reports') => route('admin.reports.index'),
            __('Leads over time') => null,
        ],
    ])

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.leads-over-time') }}" class="row g-3">
                <div class="col-auto">
                    <label for="group" class="form-label">{{ __('Group by') }}</label>
                    <select id="group" name="group" class="form-select form-select-sm" style="width: 8rem;">
                        <option value="month" @selected($groupBy === 'month')>{{ __('Month') }}</option>
                        <option value="day" @selected($groupBy === 'day')>{{ __('Day') }}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="months" class="form-label">{{ __('Months') }}</label>
                    <select id="months" name="months" class="form-select form-select-sm" style="width: 6rem;">
                        @foreach ([6, 12, 24] as $m)
                            <option value="{{ $m }}" @selected($months === $m)>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Leads by period') }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped my-0">
                <thead>
                    <tr>
                        <th>{{ __('Period') }}</th>
                        <th class="text-end">{{ __('Leads') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $row)
                        <tr>
                            <td>{{ $row->date }}</td>
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
