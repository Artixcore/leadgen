<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __('Welcome to the admin area.') }}</p>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('You can manage users, leads, lead sources, categories, countries, subscription plans, payments, exports, settings, queue jobs, and reports from here.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
