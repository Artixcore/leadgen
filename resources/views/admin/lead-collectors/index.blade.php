@extends('admin.layouts.app')

@section('title', __('Lead Collectors'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Lead Collectors'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            __('Lead Collectors') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <span></span>
        <a href="{{ route('admin.lead-collectors.create') }}" class="btn btn-primary">{{ __('Add collector') }}</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.lead-collectors.index') }}" class="row g-3">
                <div class="col-auto">
                    <label for="type" class="form-label">{{ __('Type') }}</label>
                    <select id="type" name="type" class="form-select form-select-sm" style="width: 10rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\CollectorType::cases() as $type)
                            <option value="{{ $type->value }}" @selected(($filters['type'] ?? '') === $type->value)>{{ ucfirst(str_replace('_', ' ', $type->value)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label for="status" class="form-label">{{ __('Status') }}</label>
                    <select id="status" name="status" class="form-select form-select-sm" style="width: 10rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\CollectorStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label for="target_service" class="form-label">{{ __('Target service') }}</label>
                    <select id="target_service" name="target_service" class="form-select form-select-sm" style="width: 10rem;">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\LeadCollectorTargetService::cases() as $svc)
                            <option value="{{ $svc->value }}" @selected(($filters['target_service'] ?? '') === $svc->value)>{{ ucfirst(str_replace('_', ' ', $svc->value)) }}</option>
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
            <h5 class="card-title mb-0">{{ __('Collector list') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Target service') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Priority') }}</th>
                            <th>{{ __('Last run') }}</th>
                            <th>{{ __('Next run') }}</th>
                            <th class="table-action">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($collectors as $collector)
                            <tr>
                                <td>
                                    {{ $collector->name }}
                                    @if (!$collector->is_active)
                                        <span class="badge bg-warning text-dark ms-1">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $collector->type->value)) }}</td>
                                <td>{{ $collector->target_service ? ucfirst(str_replace('_', ' ', $collector->target_service)) : '—' }}</td>
                                <td><span class="badge {{ $collector->status->value === 'active' ? 'bg-success' : ($collector->status->value === 'draft' ? 'bg-secondary' : 'bg-warning text-dark') }}">{{ ucfirst($collector->status->value) }}</span></td>
                                <td>{{ $collector->priority ?? 0 }}</td>
                                <td>{{ $collector->last_run_at?->diffForHumans() ?? '—' }}</td>
                                <td>{{ $collector->next_run_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="table-action">
                                    <a href="{{ route('admin.lead-collectors.show', $collector) }}" class="me-2" title="{{ __('View') }}"><i class="align-middle fas fa-fw fa-eye"></i></a>
                                    <a href="{{ route('admin.lead-collectors.edit', $collector) }}" class="me-2" title="{{ __('Edit') }}"><i class="align-middle fas fa-fw fa-pen"></i></a>
                                    <form action="{{ route('admin.lead-collectors.run', $collector) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0" title="{{ __('Run now') }}"><i class="align-middle fas fa-fw fa-play"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">{{ __('No lead collectors yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $collectors->links() }}
            </div>
        </div>
    </div>
@endsection
