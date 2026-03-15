<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscriptions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Plan') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Trial ends') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Ends at') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($subscriptions as $sub)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $sub->user?->name ?? '—' }}
                                            <span class="text-gray-500">({{ $sub->user?->email ?? '—' }})</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $sub->plan?->name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $sub->stripe_status }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $sub->trial_ends_at?->format('M j, Y') ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $sub->ends_at?->format('M j, Y') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
