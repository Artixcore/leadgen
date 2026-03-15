<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-6">{{ __('Select a report to view.') }}</p>
                <ul class="space-y-2">
                    <li><a href="{{ route('admin.reports.leads-over-time') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Leads added over time') }}</a></li>
                    <li><a href="{{ route('admin.reports.source-performance') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Source performance') }}</a></li>
                    <li><a href="{{ route('admin.reports.most-active-users') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Most active users') }}</a></li>
                    <li><a href="{{ route('admin.reports.revenue-by-month') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Revenue by month') }}</a></li>
                    <li><a href="{{ route('admin.reports.plan-distribution') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Plan distribution') }}</a></li>
                    <li><a href="{{ route('admin.reports.export-usage-trends') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Export usage trends') }}</a></li>
                    <li><a href="{{ route('admin.reports.lead-verification-trends') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Lead verification trends') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
