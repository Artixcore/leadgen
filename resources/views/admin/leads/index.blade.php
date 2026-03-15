@extends('admin.layouts.app')

@section('title', __('Leads'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Leads'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Leads') => null,
        ],
    ])

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-3">
                <div class="col-auto">
                    <label for="q" class="form-label">{{ __('Search') }}</label>
                    <input type="text" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('Name, email, company...') }}" class="form-control form-control-sm" style="width: 12rem;">
                </div>
                <div class="col-auto">
                    <label for="industry" class="form-label">{{ __('Industry') }}</label>
                    <input type="text" id="industry" name="industry" value="{{ $filters['industry'] ?? '' }}" class="form-control form-control-sm" style="width: 10rem;">
                </div>
                <div class="col-auto">
                    <label for="country" class="form-label">{{ __('Country') }}</label>
                    <input type="text" id="country" name="country" value="{{ $filters['country'] ?? '' }}" class="form-control form-control-sm" style="width: 10rem;">
                </div>
                <div class="col-auto">
                    <label for="per_page" class="form-label">{{ __('Per page') }}</label>
                    <select id="per_page" name="per_page" class="form-select form-select-sm" style="width: 5rem;">
                        @foreach ([15, 25, 50] as $n)
                            <option value="{{ $n }}" @selected(($filters['per_page'] ?? 15) == $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Lead list') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Company') }}</th>
                            <th>{{ __('Country') }}</th>
                            <th>{{ __('Source') }}</th>
                            <th class="table-action">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $lead)
                            <tr>
                                <td>{{ $lead->full_name ?? '—' }}</td>
                                <td>{{ $lead->email ?? '—' }}</td>
                                <td>{{ $lead->company_name ?? '—' }}</td>
                                <td>{{ $lead->country ?? '—' }}</td>
                                <td>{{ $lead->leadSource?->name ?? $lead->lead_source ?? '—' }}</td>
                                <td class="table-action">
                                    <a href="{{ route('admin.leads.show', $lead) }}" class="me-2"><i class="align-middle fas fa-fw fa-eye"></i></a>
                                    <a href="{{ route('admin.leads.edit', $lead) }}"><i class="align-middle fas fa-fw fa-pen"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ __('No leads found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $leads->links() }}
            </div>
        </div>
    </div>
@endsection
