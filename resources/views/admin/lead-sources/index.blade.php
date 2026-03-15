@extends('admin.layouts.app')

@section('title', __('Lead Sources'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Lead Sources'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <span></span>
        <a href="{{ route('admin.lead-sources.create') }}" class="btn btn-primary">{{ __('Add source') }}</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.lead-sources.index') }}" class="row g-3">
                <div class="col-auto">
                    <label for="type" class="form-label">{{ __('Type') }}</label>
                    <select id="type" name="type" class="form-select form-select-sm" style="width: 10rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\LeadSourceType::cases() as $type)
                            <option value="{{ $type->value }}" @selected(($filters['type'] ?? '') === $type->value)>{{ ucfirst($type->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label for="status" class="form-label">{{ __('Status') }}</label>
                    <select id="status" name="status" class="form-select form-select-sm" style="width: 10rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\LeadSourceStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary btn-sm">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Lead source list') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Reliability') }}</th>
                            <th>{{ __('Last sync') }}</th>
                            <th>{{ __('Leads') }}</th>
                            <th class="table-action">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sources as $source)
                            <tr>
                                <td>{{ $source->name }}</td>
                                <td>{{ ucfirst($source->type->value) }}</td>
                                <td><span class="badge {{ $source->status->value === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($source->status->value) }}</span></td>
                                <td>{{ $source->reliability_score !== null ? $source->reliability_score . '%' : '—' }}</td>
                                <td>{{ $source->last_sync_at?->diffForHumans() ?? '—' }}</td>
                                <td>{{ $source->leads_count }}</td>
                                <td class="table-action">
                                    <a href="{{ route('admin.lead-sources.show', $source) }}" class="me-2" title="{{ __('View') }}"><i class="align-middle fas fa-fw fa-eye"></i></a>
                                    <a href="{{ route('admin.lead-sources.edit', $source) }}" class="me-2" title="{{ __('Edit') }}"><i class="align-middle fas fa-fw fa-pen"></i></a>
                                    <form action="{{ route('admin.lead-sources.pause', $source) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-warning" title="{{ $source->status->value === 'active' ? __('Pause') : __('Resume') }}">
                                            <i class="align-middle fas fa-fw {{ $source->status->value === 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    @if ($source->status->value === 'active')
                                        <form action="{{ route('admin.lead-sources.sync', $source) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm p-0" title="{{ __('Sync now') }}"><i class="align-middle fas fa-fw fa-sync"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">{{ __('No lead sources yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $sources->links() }}
            </div>
        </div>
    </div>
@endsection
