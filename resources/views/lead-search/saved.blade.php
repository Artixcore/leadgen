<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Saved searches') }}
            </h2>
            <a href="{{ route('lead-search.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">{{ __('New search') }}</a>
        </div>
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
            <h3 class="font-medium text-gray-900 mb-3">{{ __('Save current search') }}</h3>
            <form method="POST" action="{{ route('lead-search.saved.store') }}" class="flex flex-wrap items-end gap-4">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="{{ __('e.g. Dubai digital marketing leads') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="query" class="block text-sm font-medium text-gray-700">{{ __('Query') }}</label>
                    <input type="text" name="query" id="query" value="{{ old('query') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="{{ __('e.g. digital marketing leads in Dubai') }}">
                    @error('query')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" name="query_id" value="{{ old('query_id') }}">
                <x-primary-button type="submit">{{ __('Save search') }}</x-primary-button>
            </form>
        </x-card>

        <x-card>
            <h3 class="font-medium text-gray-900 mb-3">{{ __('Your saved searches') }}</h3>
            @if ($savedSearches->isEmpty())
                <p class="text-gray-600">{{ __('No saved searches yet.') }}</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach ($savedSearches as $saved)
                        <li class="py-3 first:pt-0 flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="font-medium text-gray-900">{{ $saved->name }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($saved->query, 60) }}</p>
                                @if ($saved->last_run_at)
                                    <p class="text-xs text-gray-500">{{ __('Last run') }}: {{ $saved->last_run_at->format('M j, Y') }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('lead-search.saved.run', $saved) }}" class="inline">
                                    @csrf
                                    <x-primary-button type="submit" class="!py-1 !px-2 text-sm">{{ __('Run') }}</x-primary-button>
                                </form>
                                <form method="POST" action="{{ route('lead-search.saved.destroy', $saved) }}" class="inline" onsubmit="return confirm('{{ __('Remove this saved search?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit" class="!py-1 !px-2 text-sm">{{ __('Remove') }}</x-danger-button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>
    </div>
</x-app-layout>
