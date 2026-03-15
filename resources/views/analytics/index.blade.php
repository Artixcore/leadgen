<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Leads viewed') }} ({{ $currentPeriod }})</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($leadsViewed) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Leads saved') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($leadsSaved) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Leads exported') }} ({{ $currentPeriod }})</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($leadsExported) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Top filters used') }}</p>
                    <p class="mt-1 text-sm text-gray-700">{{ $topFilters->count() }} {{ __('saved filters') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('Monthly usage') }}</h3>
                    </div>
                    <div class="p-4 overflow-x-auto">
                        @if ($monthlyUsage->isNotEmpty())
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Period') }}</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Leads viewed') }}</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Exports') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($monthlyUsage as $usage)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900">{{ $usage->period }}</td>
                                            <td class="px-3 py-2 text-right text-gray-600">{{ number_format($usage->leads_count) }}</td>
                                            <td class="px-3 py-2 text-right text-gray-600">{{ number_format($usage->exports_count) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No usage data yet.') }}</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('Top filters used') }}</h3>
                    </div>
                    <div class="p-4">
                        @if ($topFilters->isNotEmpty())
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Filter') }}</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Uses') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($topFilters as $filter)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900">{{ $filter->name }}</td>
                                            <td class="px-3 py-2 text-right text-gray-600">{{ number_format($filter->usage_count ?? 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No saved filters or usage yet.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
