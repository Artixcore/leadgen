<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-gray-600">
                        {{ __('Welcome, :name. A few quick steps and you\'re all set.', ['name' => Auth::user()->name]) }}
                    </p>

                    <form method="POST" action="{{ route('onboarding.store') }}" class="mt-6">
                        @csrf
                        <x-primary-button type="submit">
                            {{ __('Continue') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
