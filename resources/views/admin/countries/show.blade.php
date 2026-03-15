<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $country->name }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.countries.edit', $country) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">{{ __('Edit') }}</a>
                <a href="{{ route('admin.countries.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('Back') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 gap-4">
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Name') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $country->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">{{ __('Code') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $country->code }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
