<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leads') }}
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
            <form method="GET" action="{{ route('leads.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="q" class="block text-sm font-medium text-gray-700">{{ __('Search') }}</label>
                                <input type="text" name="q" id="q" value="{{ old('q', $filters['q'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="{{ __('Name, email, company') }}">
                            </div>
                            <div>
                                <label for="industry" class="block text-sm font-medium text-gray-700">{{ __('Industry') }}</label>
                                <input type="text" name="industry" id="industry" value="{{ old('industry', $filters['industry'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="niche" class="block text-sm font-medium text-gray-700">{{ __('Niche') }}</label>
                                <input type="text" name="niche" id="niche" value="{{ old('niche', $filters['niche'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">{{ __('Country') }}</label>
                                <input type="text" name="country" id="country" value="{{ old('country', $filters['country'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">{{ __('City') }}</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $filters['city'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="job_title" class="block text-sm font-medium text-gray-700">{{ __('Job title') }}</label>
                                <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $filters['job_title'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="company_size" class="block text-sm font-medium text-gray-700">{{ __('Company size') }}</label>
                                <input type="text" name="company_size" id="company_size" value="{{ old('company_size', $filters['company_size'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="revenue_range" class="block text-sm font-medium text-gray-700">{{ __('Revenue range') }}</label>
                                <input type="text" name="revenue_range" id="revenue_range" value="{{ old('revenue_range', $filters['revenue_range'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="lead_source" class="block text-sm font-medium text-gray-700">{{ __('Lead source') }}</label>
                                <input type="text" name="lead_source" id="lead_source" value="{{ old('lead_source', $filters['lead_source'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="verification_status" class="block text-sm font-medium text-gray-700">{{ __('Verification') }}</label>
                                <select name="verification_status" id="verification_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="pending" {{ ($filters['verification_status'] ?? '') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="verified" {{ ($filters['verification_status'] ?? '') === 'verified' ? 'selected' : '' }}>{{ __('Verified') }}</option>
                                    <option value="invalid" {{ ($filters['verification_status'] ?? '') === 'invalid' ? 'selected' : '' }}>{{ __('Invalid') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="quality_score_min" class="block text-sm font-medium text-gray-700">{{ __('Min. lead score') }}</label>
                                <input type="number" name="quality_score_min" id="quality_score_min" min="0" max="100" value="{{ old('quality_score_min', $filters['quality_score_min'] ?? '') }}"
                                    placeholder="0–100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="freshness" class="block text-sm font-medium text-gray-700">{{ __('Freshness') }}</label>
                                <select name="freshness" id="freshness"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="fresh" {{ ($filters['freshness'] ?? '') === 'fresh' ? 'selected' : '' }}>{{ __('Fresh') }}</option>
                                    <option value="stale" {{ ($filters['freshness'] ?? '') === 'stale' ? 'selected' : '' }}>{{ __('Stale') }}</option>
                                    <option value="unknown" {{ ($filters['freshness'] ?? '') === 'unknown' ? 'selected' : '' }}>{{ __('Unknown') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="recently_added_days" class="block text-sm font-medium text-gray-700">{{ __('Recently added (days)') }}</label>
                                <input type="number" name="recently_added_days" id="recently_added_days" min="1" max="365" value="{{ old('recently_added_days', $filters['recently_added_days'] ?? '') }}"
                                    placeholder="{{ __('e.g. 7') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="flex items-end gap-4 flex-wrap">
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="exclude_duplicates" value="0">
                                    <input type="checkbox" name="exclude_duplicates" value="1" {{ !empty($filters['exclude_duplicates']) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-700">{{ __('Exclude duplicates') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="has_email" value="0">
                                    <input type="checkbox" name="has_email" value="1" {{ !empty($filters['has_email']) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-700">{{ __('Has email') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="has_phone" value="0">
                                    <input type="checkbox" name="has_phone" value="1" {{ !empty($filters['has_phone']) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-700">{{ __('Has phone') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="has_linkedin" value="0">
                                    <input type="checkbox" name="has_linkedin" value="1" {{ !empty($filters['has_linkedin']) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-700">{{ __('Has LinkedIn') }}</span>
                                </label>
                            </div>
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700">{{ __('Sort by') }}</label>
                                <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="newest" {{ ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                                    <option value="highest_quality" {{ ($filters['sort'] ?? '') === 'highest_quality' ? 'selected' : '' }}>{{ __('Highest quality') }}</option>
                                    <option value="most_relevant" {{ ($filters['sort'] ?? '') === 'most_relevant' ? 'selected' : '' }}>{{ __('Most relevant') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap items-center">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Filter') }}
                            </button>
                            <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                            @can('receive-notifications')
                                <form method="POST" action="{{ route('leads.saved-filters.store') }}" class="inline-flex gap-2 items-center flex-wrap">
                                    @csrf
                                    <input type="hidden" name="criteria[q]" value="{{ $filters['q'] ?? '' }}">
                                    <input type="hidden" name="criteria[industry]" value="{{ $filters['industry'] ?? '' }}">
                                    <input type="hidden" name="criteria[niche]" value="{{ $filters['niche'] ?? '' }}">
                                    <input type="hidden" name="criteria[country]" value="{{ $filters['country'] ?? '' }}">
                                    <input type="hidden" name="criteria[city]" value="{{ $filters['city'] ?? '' }}">
                                    <input type="hidden" name="criteria[job_title]" value="{{ $filters['job_title'] ?? '' }}">
                                    <input type="hidden" name="criteria[company_size]" value="{{ $filters['company_size'] ?? '' }}">
                                    <input type="hidden" name="criteria[revenue_range]" value="{{ $filters['revenue_range'] ?? '' }}">
                                    <input type="hidden" name="criteria[lead_source]" value="{{ $filters['lead_source'] ?? '' }}">
                                    <input type="hidden" name="criteria[verification_status]" value="{{ $filters['verification_status'] ?? '' }}">
                                    <input type="hidden" name="criteria[quality_score_min]" value="{{ $filters['quality_score_min'] ?? '' }}">
                                    <input type="hidden" name="criteria[exclude_duplicates]" value="{{ !empty($filters['exclude_duplicates']) ? '1' : '0' }}">
                                    <input type="hidden" name="criteria[freshness]" value="{{ $filters['freshness'] ?? '' }}">
                                    <input type="hidden" name="criteria[recently_added_days]" value="{{ $filters['recently_added_days'] ?? '' }}">
                                    <input type="hidden" name="criteria[has_email]" value="{{ !empty($filters['has_email']) ? '1' : '0' }}">
                                    <input type="hidden" name="criteria[has_phone]" value="{{ !empty($filters['has_phone']) ? '1' : '0' }}">
                                    <input type="hidden" name="criteria[has_linkedin]" value="{{ !empty($filters['has_linkedin']) ? '1' : '0' }}">
                                    <input type="hidden" name="criteria[sort]" value="{{ $filters['sort'] ?? 'newest' }}">
                                    <input type="hidden" name="criteria[sort_dir]" value="{{ $filters['sort_dir'] ?? 'desc' }}">
                                    <input type="text" name="name" required placeholder="{{ __('Filter name') }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" maxlength="255">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                        {{ __('Save search & notify') }}
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </form>
                    @can('receive-notifications')
                        @if (isset($savedFilters) && $savedFilters->isNotEmpty())
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Saved filters') }}</p>
                                <ul class="flex flex-wrap gap-2">
                                    @foreach ($savedFilters as $sf)
                                        <li class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-1 text-sm">
                                            <a href="{{ route('leads.index', array_merge($sf->criteria ?? [], ['saved_filter_id' => $sf->id])) }}" class="text-indigo-600 hover:text-indigo-900">{{ $sf->name }}</a>
                                            <form method="POST" action="{{ route('leads.saved-filters.destroy', $sf) }}" class="inline" onsubmit="return confirm('{{ __('Delete this saved filter?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs" aria-label="{{ __('Delete') }}">×</button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endcan
                </div>
            </form>
        </x-card>

        <x-card>
            @can('export-leads')
                        <form method="POST" action="{{ route('leads.export') }}" id="export-form" class="mb-4 flex gap-2 items-center">
                            @csrf
                            <select name="format" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="csv">CSV</option>
                                <option value="xlsx">XLSX</option>
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                {{ __('Export selected') }}
                            </button>
                            <span class="text-sm text-gray-500">{{ __('Select leads below and click Export.') }}</span>
                        </form>
                    @endcan
                    @can('export-leads')
                        <script>
                            document.getElementById('select-all-leads')?.addEventListener('change', function() {
                                document.querySelectorAll('.lead-checkbox').forEach(cb => { cb.checked = this.checked; });
                            });
                        </script>
                    @endcan
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    @can('export-leads')
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"><input type="checkbox" id="select-all-leads" aria-label="{{ __('Select all') }}"></th>
                                    @endcan
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Email') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Company') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Industry') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Country') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Verification') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Freshness') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($leads as $lead)
                                    <tr>
                                        @can('export-leads')
                                            <td class="px-4 py-3"><input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" form="export-form" class="lead-checkbox rounded border-gray-300"></td>
                                        @endcan
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $lead->full_name ?? '—' }}
                                            @if ($lead->is_duplicate)
                                                <span class="ml-1 inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800">{{ __('Duplicate') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->email ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->company_name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->industry ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->country ?? '—' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                                @if($lead->verification_status->value === 'verified') bg-green-100 text-green-800
                                                @elseif($lead->verification_status->value === 'invalid') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($lead->verification_status->value) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                                @if($lead->freshness()->value === 'fresh') bg-green-100 text-green-800
                                                @elseif($lead->freshness()->value === 'stale') bg-amber-100 text-amber-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($lead->freshness()->value) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm space-x-2">
                                            <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                            @can('bookmark-leads')
                                                @if (in_array($lead->id, $bookmarkedLeadIds))
                                                    <form method="POST" action="{{ route('leads.bookmark.destroy', $lead) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-amber-600 hover:text-amber-900">{{ __('Unbookmark') }}</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('leads.bookmark.store', $lead) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-gray-600 hover:text-gray-900">{{ __('Bookmark') }}</button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->can('export-leads') ? 10 : 9 }}" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('No leads found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

            <div class="mt-4">
                {{ $leads->links() }}
            </div>
        </x-card>
    </div>
</x-app-layout>
