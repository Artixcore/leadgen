<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Leads added over time') }}</h2>
            <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Back to reports') }}</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('admin.reports.leads-over-time') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label for="group" class="block text-xs font-medium text-gray-700">{{ __('Group by') }}</label>
                        <select id="group" name="group" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                            <option value="month" @selected($groupBy === 'month')>{{ __('Month') }}</option>
                            <option value="day" @selected($groupBy === 'day')>{{ __('Day') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="months" class="block text-xs font-medium text-gray-700">{{ __('Months') }}</label>
                        <select id="months" name="months" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                            @foreach ([6, 12, 24] as $m)
                                <option value="{{ $m }}" @selected($months === $m)>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">{{ __('Update') }}</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Period') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Leads') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($data as $row)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $row->date }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($row->total) }}</td>
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
