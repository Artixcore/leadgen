<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add lead source') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 max-w-xl">
                    <form method="POST" action="{{ route('admin.lead-sources.store') }}">
                        @csrf
                        @include('admin.lead-sources._form', ['source' => null])

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Create source') }}</x-primary-button>
                            <a href="{{ route('admin.lead-sources.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
