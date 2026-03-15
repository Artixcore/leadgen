<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit lead') }}: {{ $lead->full_name ?? $lead->email ?? __('Unknown') }}
            </h2>
            <a href="{{ route('admin.leads.show', $lead) }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Back to lead') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">{{ __('Full name') }}</label>
                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $lead->full_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('full_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700">{{ __('Job title') }}</label>
                        <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $lead->job_title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('job_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $lead->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $lead->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">{{ __('Company name') }}</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $lead->company_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('company_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">{{ __('Website') }}</label>
                        <input type="text" id="website" name="website" value="{{ old('website', $lead->website) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('website')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">{{ __('Country') }}</label>
                        <input type="text" id="country" name="country" value="{{ old('country', $lead->country) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">{{ __('State') }}</label>
                        <input type="text" id="state" name="state" value="{{ old('state', $lead->state) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('state')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">{{ __('City') }}</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $lead->city) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700">{{ __('Industry') }}</label>
                        <input type="text" id="industry" name="industry" value="{{ old('industry', $lead->industry) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('industry')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="niche" class="block text-sm font-medium text-gray-700">{{ __('Niche') }}</label>
                        <input type="text" id="niche" name="niche" value="{{ old('niche', $lead->niche) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('niche')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="company_size" class="block text-sm font-medium text-gray-700">{{ __('Company size') }}</label>
                        <input type="text" id="company_size" name="company_size" value="{{ old('company_size', $lead->company_size) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('company_size')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="revenue_range" class="block text-sm font-medium text-gray-700">{{ __('Revenue range') }}</label>
                        <input type="text" id="revenue_range" name="revenue_range" value="{{ old('revenue_range', $lead->revenue_range) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('revenue_range')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="lead_source_id" class="block text-sm font-medium text-gray-700">{{ __('Lead source') }}</label>
                        <select id="lead_source_id" name="lead_source_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('— None —') }}</option>
                            @foreach ($leadSources as $source)
                                <option value="{{ $source->id }}" {{ old('lead_source_id', $lead->lead_source_id) == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                            @endforeach
                        </select>
                        @error('lead_source_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="verification_status" class="block text-sm font-medium text-gray-700">{{ __('Verification status') }}</label>
                        <select id="verification_status" name="verification_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('— None —') }}</option>
                            @foreach (\App\VerificationStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ old('verification_status', $lead->verification_status?->value) === $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
                            @endforeach
                        </select>
                        @error('verification_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="quality_score" class="block text-sm font-medium text-gray-700">{{ __('Quality score') }}</label>
                        <input type="number" id="quality_score" name="quality_score" min="0" max="100" value="{{ old('quality_score', $lead->quality_score) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('quality_score')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="lead_status" class="block text-sm font-medium text-gray-700">{{ __('Lead status') }}</label>
                        <select id="lead_status" name="lead_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('— None —') }}</option>
                            @foreach (\App\LeadStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ old('lead_status', $lead->lead_status?->value) === $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
                            @endforeach
                        </select>
                        @error('lead_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('notes', $lead->notes) }}</textarea>
                        @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">{{ __('Update lead') }}</button>
                    <a href="{{ route('admin.leads.show', $lead) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
