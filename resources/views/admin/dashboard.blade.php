<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- Stat cards --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Total users') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Active subscriptions') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($stats['active_subscriptions']) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Total leads') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($stats['total_leads']) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('New leads today') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($stats['new_leads_today']) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Failed imports') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($stats['failed_imports_count']) }}</p>
                    @if ($stats['failed_imports_count'] > 0)
                        <a href="{{ route('admin.import-runs.index') }}?status=failed" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-900">{{ __('View failed runs') }}</a>
                    @endif
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Export usage (this period)') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($stats['export_usage_current_period']) }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ __('Previous period') }}: {{ number_format($stats['export_usage_previous_period']) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Revenue this month') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ config('app.currency', 'USD') === 'USD' ? '$' : '' }}{{ number_format($stats['revenue_current_month'], 2) }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ __('Last month') }}: {{ config('app.currency', 'USD') === 'USD' ? '$' : '' }}{{ number_format($stats['revenue_previous_month'], 2) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500 truncate">{{ __('Source sync status') }}</p>
                    <p class="mt-1 text-sm text-gray-700">{{ count($stats['source_sync_status']) }} {{ __('sources') }}</p>
                    <a href="{{ route('admin.lead-sources.index') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-900">{{ __('Manage sources') }}</a>
                </div>
            </div>

            {{-- Tables row --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('Source sync status') }}</h3>
                    </div>
                    <div class="p-4 overflow-x-auto">
                        @if (count($stats['source_sync_status']) > 0)
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Source') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Last sync') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Last run') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($stats['source_sync_status'] as $src)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900">
                                                <a href="{{ route('admin.lead-sources.show', $src['id']) }}" class="text-indigo-600 hover:text-indigo-900">{{ $src['name'] }}</a>
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $src['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $src['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-gray-600">{{ $src['last_sync_at']?->diffForHumans() ?? '—' }}</td>
                                            <td class="px-3 py-2 text-gray-600">{{ $src['last_run_status'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No lead sources yet.') }}</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('Recent failed imports') }}</h3>
                    </div>
                    <div class="p-4 overflow-x-auto">
                        @if ($stats['recent_failed_imports']->isNotEmpty())
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Source') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Completed at') }}</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($stats['recent_failed_imports'] as $run)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900">{{ $run->leadSource?->name ?? '—' }}</td>
                                            <td class="px-3 py-2 text-gray-600">{{ $run->completed_at?->format('M j, Y H:i') ?? '—' }}</td>
                                            <td class="px-3 py-2 text-right">
                                                <a href="{{ route('admin.import-runs.show', $run) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{ route('admin.import-runs.index') }}?status=failed" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-900">{{ __('All failed runs') }}</a>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No failed imports.') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('Top countries') }}</h3>
                    </div>
                    <div class="p-4">
                        @if (count($stats['top_countries']) > 0)
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Country') }}</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Leads') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($stats['top_countries'] as $row)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900">{{ $row['country'] ?: __('Unknown') }}</td>
                                            <td class="px-3 py-2 text-right text-gray-600">{{ number_format($row['total']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No data.') }}</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('Top industries') }}</h3>
                    </div>
                    <div class="p-4">
                        @if (count($stats['top_industries']) > 0)
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Industry') }}</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Leads') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($stats['top_industries'] as $row)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900">{{ $row['industry'] ?: __('Unknown') }}</td>
                                            <td class="px-3 py-2 text-right text-gray-600">{{ number_format($row['total']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No data.') }}</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('Most used filters') }}</h3>
                    </div>
                    <div class="p-4">
                        @if (count($stats['most_used_filters']) > 0)
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Filter') }}</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Uses') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($stats['most_used_filters'] as $row)
                                        <tr>
                                            <td class="px-3 py-2 text-gray-900">{{ $row['name'] }}</td>
                                            <td class="px-3 py-2 text-right text-gray-600">{{ number_format($row['usage_count']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">{{ __('No filter usage data yet.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
