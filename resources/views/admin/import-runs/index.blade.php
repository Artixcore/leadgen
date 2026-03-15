<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import runs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.import-runs.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label for="source" class="block text-xs font-medium text-gray-700">{{ __('Source') }}</label>
                            <select id="source" name="source" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">{{ __('All') }}</option>
                                @foreach (\App\Models\LeadSource::orderBy('name')->get() as $s)
                                    <option value="{{ $s->id }}" @selected(($filters['source'] ?? '') == $s->id)>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-700">{{ __('Status') }}</label>
                            <select id="status" name="status" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">{{ __('All') }}</option>
                                @foreach (\App\ImportRunStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ ucfirst($status->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-3 py-1.5 bg-gray-200 text-gray-800 rounded-md text-sm hover:bg-gray-300">{{ __('Filter') }}</button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Source') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Started') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Stats') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($runs as $run)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $run->id }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $run->leadSource?->name ?? '—' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                                @if($run->status->value === 'completed') bg-green-100 text-green-800
                                                @elseif($run->status->value === 'failed') bg-red-100 text-red-800
                                                @elseif($run->status->value === 'running') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($run->status->value) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $run->started_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            @if ($run->stats)
                                                {{ json_encode($run->stats) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <a href="{{ route('admin.import-runs.show', $run) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">{{ __('No import runs yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $runs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
