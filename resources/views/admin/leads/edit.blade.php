@extends('admin.layouts.app')

@section('title', __('Edit lead'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Edit lead') . ': ' . ($lead->full_name ?? $lead->email ?? __('Unknown')),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Leads') => route('admin.leads.index'),
            __('Edit') => null,
        ],
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Lead details') }}</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <x-admin.alert type="danger" class="mb-4">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </x-admin.alert>
            @endif

            <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">{{ __('Full name') }}</label>
                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $lead->full_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="job_title" class="form-label">{{ __('Job title') }}</label>
                        <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $lead->job_title) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $lead->email) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">{{ __('Phone') }}</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $lead->phone) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="company_name" class="form-label">{{ __('Company name') }}</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $lead->company_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="website" class="form-label">{{ __('Website') }}</label>
                        <input type="text" id="website" name="website" value="{{ old('website', $lead->website) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">{{ __('Country') }}</label>
                        <input type="text" id="country" name="country" value="{{ old('country', $lead->country) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="state" class="form-label">{{ __('State') }}</label>
                        <input type="text" id="state" name="state" value="{{ old('state', $lead->state) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">{{ __('City') }}</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $lead->city) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="industry" class="form-label">{{ __('Industry') }}</label>
                        <input type="text" id="industry" name="industry" value="{{ old('industry', $lead->industry) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="niche" class="form-label">{{ __('Niche') }}</label>
                        <input type="text" id="niche" name="niche" value="{{ old('niche', $lead->niche) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="company_size" class="form-label">{{ __('Company size') }}</label>
                        <input type="text" id="company_size" name="company_size" value="{{ old('company_size', $lead->company_size) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="revenue_range" class="form-label">{{ __('Revenue range') }}</label>
                        <input type="text" id="revenue_range" name="revenue_range" value="{{ old('revenue_range', $lead->revenue_range) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lead_source_id" class="form-label">{{ __('Lead source') }}</label>
                        <select id="lead_source_id" name="lead_source_id" class="form-select">
                            <option value="">{{ __('— None —') }}</option>
                            @foreach ($leadSources as $source)
                                <option value="{{ $source->id }}" @selected(old('lead_source_id', $lead->lead_source_id) == $source->id)>{{ $source->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="verification_status" class="form-label">{{ __('Verification status') }}</label>
                        <select id="verification_status" name="verification_status" class="form-select">
                            <option value="">{{ __('— None —') }}</option>
                            @foreach (\App\VerificationStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('verification_status', $lead->verification_status?->value) === $status->value)>{{ ucfirst($status->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="quality_score" class="form-label">{{ __('Quality score') }}</label>
                        <input type="number" id="quality_score" name="quality_score" min="0" max="100" value="{{ old('quality_score', $lead->quality_score) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lead_status" class="form-label">{{ __('Lead status') }}</label>
                        <select id="lead_status" name="lead_status" class="form-select">
                            <option value="">{{ __('— None —') }}</option>
                            @foreach (\App\LeadStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('lead_status', $lead->lead_status?->value) === $status->value)>{{ ucfirst($status->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">{{ __('Notes') }}</label>
                        <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">{{ __('Update lead') }}</button>
                    <a href="{{ route('admin.leads.show', $lead) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
