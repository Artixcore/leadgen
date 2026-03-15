<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lead Search') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <x-card>
            <p class="text-sm text-gray-600 mb-4">{{ __('Search for business leads by describing what you offer and who you want to find. Examples: "digital marketing leads in Dubai", "restaurants in London needing SEO".') }}</p>
            <form method="POST" action="{{ route('lead-search.run') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="query" class="block text-sm font-medium text-gray-700">{{ __('Search query') }}</label>
                    <input type="text" name="query" id="query" value="{{ old('query') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg"
                        placeholder="{{ __('e.g. small businesses in Canada needing SEO') }}" required autofocus>
                    @error('query')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <details class="group">
                    <summary class="cursor-pointer text-sm font-medium text-gray-700">{{ __('Advanced filters') }}</summary>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pl-2">
                        <div>
                            <label for="target_service" class="block text-sm font-medium text-gray-700">{{ __('Target service') }}</label>
                            <input type="text" name="target_service" id="target_service" value="{{ old('target_service') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="{{ __('e.g. web_development, seo') }}">
                        </div>
                        <div>
                            <label for="target_niche" class="block text-sm font-medium text-gray-700">{{ __('Niche / industry') }}</label>
                            <input type="text" name="target_niche" id="target_niche" value="{{ old('target_niche') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="{{ __('e.g. restaurants, clinics') }}">
                        </div>
                        <div>
                            <label for="target_country" class="block text-sm font-medium text-gray-700">{{ __('Country') }}</label>
                            <input type="text" name="target_country" id="target_country" value="{{ old('target_country') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="target_city" class="block text-sm font-medium text-gray-700">{{ __('City') }}</label>
                            <input type="text" name="target_city" id="target_city" value="{{ old('target_city') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="company_size" class="block text-sm font-medium text-gray-700">{{ __('Company size') }}</label>
                            <input type="text" name="company_size" id="company_size" value="{{ old('company_size') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="{{ __('e.g. small, enterprise') }}">
                        </div>
                        <div>
                            <label for="min_score" class="block text-sm font-medium text-gray-700">{{ __('Minimum score (0–100)') }}</label>
                            <input type="number" name="min_score" id="min_score" min="0" max="100" value="{{ old('min_score') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center">
                                <input type="hidden" name="verified_only" value="0">
                                <input type="checkbox" name="verified_only" value="1" {{ old('verified_only') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-sm text-gray-700">{{ __('Verified only') }}</span>
                            </label>
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center">
                                <input type="hidden" name="async" value="0">
                                <input type="checkbox" name="async" value="1" {{ old('async') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-sm text-gray-700">{{ __('Run in background') }}</span>
                            </label>
                        </div>
                    </div>
                </details>

                <div class="flex items-center gap-4">
                    <x-primary-button type="submit">{{ __('Search') }}</x-primary-button>
                    <a href="{{ route('lead-search.history') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Search history') }}</a>
                    <a href="{{ route('lead-search.saved') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Saved searches') }}</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
