<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lead') }}: {{ $lead->full_name ?? $lead->email ?? __('Unknown') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.leads.edit', $lead) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">{{ __('Edit') }}</a>
                <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('Back to list') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Full name') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->full_name ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Job title') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->job_title ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->email ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Phone') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->phone ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Company name') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->company_name ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Website') }}</dt><dd class="mt-1 text-sm text-gray-900">@if($lead->website)<a href="{{ $lead->website }}" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">{{ $lead->website }}</a>@else — @endif</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Industry') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->industry ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Niche') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->niche ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Country') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->country ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('City') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->city ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('State') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->state ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('LinkedIn') }}</dt><dd class="mt-1 text-sm text-gray-900">@if($lead->linkedin_profile)<a href="{{ $lead->linkedin_profile }}" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">{{ $lead->linkedin_profile }}</a>@else — @endif</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Company size') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->company_size ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Revenue range') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->revenue_range ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Lead source') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->leadSource?->name ?? $lead->lead_source ?? '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Verification status') }}</dt>
                        <dd class="mt-1">
                            @if($lead->verification_status)
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                    @if($lead->verification_status->value === 'verified') bg-green-100 text-green-800
                                    @elseif($lead->verification_status->value === 'invalid') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($lead->verification_status->value) }}
                                </span>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Quality score') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->quality_score !== null ? $lead->quality_score : '—' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Lead status') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->lead_status ? ucfirst($lead->lead_status->value) : '—' }}</dd></div>
                    <div class="md:col-span-2"><dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->notes ?? '—' }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
