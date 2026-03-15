<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Slug') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Leads/mo') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Exports/mo') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Saved lists') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('API') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Active') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($plans as $plan)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $plan->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $plan->slug }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $plan->leads_per_month === null ? '∞' : number_format($plan->leads_per_month) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($plan->exports_per_month) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($plan->saved_lists_count) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $plan->api_access ? __('Yes') : __('No') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $plan->is_active ? __('Yes') : __('No') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
