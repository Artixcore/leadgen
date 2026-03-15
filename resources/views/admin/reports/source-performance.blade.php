<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Source performance') }}</h2>
            <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Back to reports') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Source') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Leads') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Last run') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Completed') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Failed') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($sources as $source)
                            @php
                                $counts = $runCounts->get($source->id, collect());
                                $completed = $counts->where('status', 'completed')->sum('cnt');
                                $failed = $counts->where('status', 'failed')->sum('cnt');
                                $lastRun = $source->importRuns->first();
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $source->name }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($source->leads_count) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $lastRun?->status->value ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600">{{ $completed }}</td>
                                <td class="px-4 py-3 text-right text-sm {{ $failed > 0 ? 'text-red-600' : 'text-gray-600' }}">{{ $failed }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
