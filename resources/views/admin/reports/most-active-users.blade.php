<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Most active users') }}</h2>
            <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Back to reports') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-4">{{ __('Activity = leads viewed + exports in current period.') }}</p>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Activity (this period)') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $user->name }} <span class="text-gray-500">({{ $user->email }})</span></td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900">{{ $usageMap->get($user->id, 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('No data.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
