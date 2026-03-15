<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Search history') }}
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

        <x-card>
            @if ($queries->isEmpty())
                <p class="text-gray-600">{{ __('No searches yet.') }} <a href="{{ route('lead-search.index') }}" class="text-indigo-600 hover:text-indigo-800">{{ __('Run a search') }}</a></p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach ($queries as $query)
                        <li class="py-3 first:pt-0 flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="font-medium text-gray-900">{{ Str::limit($query->query, 80) }}</p>
                                <p class="text-sm text-gray-500">{{ $query->created_at->format('M j, Y g:i A') }} · {{ $query->total_results }} {{ __('results') }} · {{ $query->status }}</p>
                            </div>
                            <a href="{{ route('lead-search.results', $query) }}" class="text-sm text-indigo-600 hover:text-indigo-800">{{ __('View results') }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    {{ $queries->withQueryString()->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
