<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Search results') }}
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
            <p class="text-sm text-gray-600 mb-2"><strong>{{ __('Query') }}:</strong> {{ $searchQuery->query }}</p>
            @if ($searchQuery->search_took_ms !== null)
                <p class="text-sm text-gray-500 mb-4">{{ $searchQuery->total_results }} {{ __('results') }} · {{ $searchQuery->search_took_ms }} ms</p>
            @endif

            @if ($searchQuery->status === 'pending' || $searchQuery->status === 'running')
                <p class="text-amber-700 py-4">{{ __('Search is still running. Refresh the page in a moment.') }}</p>
            @elseif ($searchQuery->status === 'failed')
                <p class="text-red-700 py-4">{{ __('This search failed. Please try again.') }}</p>
            @elseif ($results->isEmpty())
                <p class="text-gray-600 py-4">{{ __('No results found. Try adjusting your query or filters.') }}</p>
            @else
                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <form method="GET" action="{{ route('lead-search.results', $searchQuery) }}" class="flex flex-wrap items-center gap-2">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="sort_dir" value="{{ $sortDir }}">
                        <label for="sort" class="text-sm font-medium text-gray-700">{{ __('Sort by') }}</label>
                        <select name="sort" id="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="relevance" {{ $sort === 'relevance' ? 'selected' : '' }}>{{ __('Most relevant') }}</option>
                            <option value="opportunity" {{ $sort === 'opportunity' ? 'selected' : '' }}>{{ __('Highest opportunity') }}</option>
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                            <option value="contact" {{ $sort === 'contact' ? 'selected' : '' }}>{{ __('Best contact info') }}</option>
                        </select>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                    </form>
                </div>

                <ul class="divide-y divide-gray-200 space-y-0">
                    @foreach ($results as $result)
                        <li class="py-4 first:pt-0">
                            <div class="flex flex-wrap justify-between gap-2">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $result->company_name }}</h3>
                                    @if ($result->niche || $result->city || $result->country)
                                        <p class="text-sm text-gray-600 mt-0.5">
                                            @if ($result->niche){{ $result->niche }}@endif
                                            @if ($result->city || $result->country)
                                                · {{ implode(', ', array_filter([$result->city, $result->country])) }}
                                            @endif
                                        </p>
                                    @endif
                                    @if ($result->website)
                                        <a href="{{ $result->website }}" target="_blank" rel="noopener noreferrer" class="text-sm text-indigo-600 hover:text-indigo-800 break-all">{{ $result->website }}</a>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">{{ $result->source_name }}</span>
                                    <p class="text-sm text-gray-600 mt-1">{{ __('Relevance') }}: {{ $result->relevance_score }} · {{ __('Opportunity') }}: {{ $result->opportunity_score }}</p>
                                </div>
                            </div>
                            @if ($result->explanation)
                                <p class="text-sm text-gray-700 mt-2">{{ $result->explanation }}</p>
                            @endif
                            @if ($result->recommended_pitch)
                                <p class="text-sm text-gray-600 mt-1 italic">{{ __('Suggested pitch') }}: {{ $result->recommended_pitch }}</p>
                            @endif
                            @if ($showFullContact && ($result->email || $result->phone))
                                <p class="text-sm text-gray-600 mt-2">
                                    @if ($result->email)<span class="mr-4">{{ $result->email }}</span>@endif
                                    @if ($result->phone){{ $result->phone }}@endif
                                </p>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4">
                    {{ $results->withQueryString()->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
