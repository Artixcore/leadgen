<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete your profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 max-w-xl">
                    <p class="text-sm text-gray-600 mb-6">
                        {{ __('Optional: add a few details. You can skip and update these later in your profile.') }}
                    </p>

                    <form method="POST" action="{{ route('onboarding.profile') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="company_name" :value="__('Company name')" />
                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $user->company_name)" autocomplete="organization" />
                            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div>
                            <x-input-label for="timezone" :value="__('Timezone')" />
                            <x-text-input id="timezone" name="timezone" type="text" class="mt-1 block w-full" :value="old('timezone', $user->timezone)" placeholder="e.g. America/New_York" />
                            <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button type="submit">{{ __('Save and continue') }}</x-primary-button>
                            <form method="POST" action="{{ route('onboarding.skip') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                    {{ __('Skip') }}
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
