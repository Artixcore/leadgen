<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @can('search-leads')
                <x-card>
                    <a href="{{ route('leads.index') }}" class="block group">
                        <p class="text-sm font-medium text-gray-500">{{ __('Lead search') }}</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 group-hover:text-gray-700">{{ __('Search leads') }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Filter and export leads') }}</p>
                    </a>
                </x-card>
            @endcan
            @can('manage-lists')
                <x-card>
                    <a href="{{ route('lists.index') }}" class="block group">
                        <p class="text-sm font-medium text-gray-500">{{ __('Saved lists') }}</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 group-hover:text-gray-700">{{ number_format(auth()->user()->leadLists()->count()) }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ __('lists') }}</p>
                    </a>
                </x-card>
            @endcan
            <x-card>
                <a href="{{ route('exports.index') }}" class="block group">
                    <p class="text-sm font-medium text-gray-500">{{ __('Export history') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 group-hover:text-gray-700">{{ number_format(auth()->user()->exports()->count()) }}</p>
                    <p class="mt-1 text-sm text-gray-600">{{ __('total exports') }}</p>
                </a>
            </x-card>
            <x-card>
                <a href="{{ route('billing.index') }}" class="block group">
                    <p class="text-sm font-medium text-gray-500">{{ __('Billing') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 group-hover:text-gray-700">{{ __('Manage') }}</p>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Plan and invoices') }}</p>
                </a>
            </x-card>
        </div>

        <x-card>
            <h3 class="text-lg font-medium text-gray-900">{{ __('Quick links') }}</h3>
            <ul class="mt-4 space-y-2">
                @can('search-leads')
                    <li><a href="{{ route('leads.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">{{ __('Search leads') }}</a></li>
                @endcan
                @can('manage-lists')
                    <li><a href="{{ route('lists.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">{{ __('Saved lists') }}</a></li>
                @endcan
                <li><a href="{{ route('exports.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">{{ __('Export history') }}</a></li>
                <li><a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">{{ __('Notifications') }}</a></li>
                <li><a href="{{ route('billing.index') }}" class="text-gray-700 hover:text-gray-900 font-medium">{{ __('Billing') }}</a></li>
            </ul>
        </x-card>
    </div>
</x-app-layout>
