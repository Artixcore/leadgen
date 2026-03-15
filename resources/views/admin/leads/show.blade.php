@extends('admin.layouts.app')

@section('title', __('Lead') . ': ' . ($lead->full_name ?? $lead->email ?? __('Unknown')))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Lead') . ': ' . ($lead->full_name ?? $lead->email ?? __('Unknown')),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Leads') => route('admin.leads.index'),
            __('View') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.leads.edit', $lead) }}" class="btn btn-primary">{{ __('Edit') }}</a>
        <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">{{ __('Back to list') }}</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Lead details') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">{{ __('Full name') }}</dt>
                        <dd class="col-sm-8">{{ $lead->full_name ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Job title') }}</dt>
                        <dd class="col-sm-8">{{ $lead->job_title ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Email') }}</dt>
                        <dd class="col-sm-8">{{ $lead->email ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Phone') }}</dt>
                        <dd class="col-sm-8">{{ $lead->phone ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Company name') }}</dt>
                        <dd class="col-sm-8">{{ $lead->company_name ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Website') }}</dt>
                        <dd class="col-sm-8">@if ($lead->website)<a href="{{ $lead->website }}" target="_blank" rel="noopener">{{ $lead->website }}</a>@else — @endif</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Industry') }}</dt>
                        <dd class="col-sm-8">{{ $lead->industry ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Niche') }}</dt>
                        <dd class="col-sm-8">{{ $lead->niche ?? '—' }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">{{ __('Country') }}</dt>
                        <dd class="col-sm-8">{{ $lead->country ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('City') }}</dt>
                        <dd class="col-sm-8">{{ $lead->city ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('State') }}</dt>
                        <dd class="col-sm-8">{{ $lead->state ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('LinkedIn') }}</dt>
                        <dd class="col-sm-8">@if ($lead->linkedin_profile)<a href="{{ $lead->linkedin_profile }}" target="_blank" rel="noopener">{{ Str::limit($lead->linkedin_profile, 40) }}</a>@else — @endif</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Company size') }}</dt>
                        <dd class="col-sm-8">{{ $lead->company_size ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Revenue range') }}</dt>
                        <dd class="col-sm-8">{{ $lead->revenue_range ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Lead source') }}</dt>
                        <dd class="col-sm-8">{{ $lead->leadSource?->name ?? $lead->lead_source ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Verification status') }}</dt>
                        <dd class="col-sm-8">
                            @if ($lead->verification_status)
                                <span class="badge {{ $lead->verification_status->value === 'verified' ? 'bg-success' : ($lead->verification_status->value === 'invalid' ? 'bg-danger' : 'bg-secondary') }}">{{ ucfirst($lead->verification_status->value) }}</span>
                            @else
                                —
                            @endif
                        </dd>
                        <dt class="col-sm-4 text-muted">{{ __('Quality score') }}</dt>
                        <dd class="col-sm-8">{{ $lead->quality_score !== null ? $lead->quality_score : '—' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('Lead status') }}</dt>
                        <dd class="col-sm-8">{{ $lead->lead_status ? ucfirst($lead->lead_status->value) : '—' }}</dd>
                    </dl>
                </div>
            </div>
            <hr>
            <dt class="text-muted">{{ __('Notes') }}</dt>
            <dd>{{ $lead->notes ?? '—' }}</dd>
        </div>
    </div>
@endsection
