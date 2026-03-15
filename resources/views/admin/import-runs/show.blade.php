<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Import run') }} #{{ $run->id }}
            </h2>
            <a href="{{ route('admin.import-runs.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Back to list') }}</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Source') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $run->leadSource?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                            <dd class="mt-1">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                    @if($run->status->value === 'completed') bg-green-100 text-green-800
                                    @elseif($run->status->value === 'failed') bg-red-100 text-red-800
                                    @elseif($run->status->value === 'running') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($run->status->value) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Started') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $run->started_at?->format('Y-m-d H:i:s') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Completed') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $run->completed_at?->format('Y-m-d H:i:s') ?? '—' }}</dd>
                        </div>
                        @if ($run->error_message)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Error') }}</dt>
                                <dd class="mt-1 text-sm text-red-700">{{ $run->error_message }}</dd>
                            </div>
                        @endif
                        @if ($run->stats)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Stats') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900"><pre class="bg-gray-50 p-2 rounded">{{ json_encode($run->stats, JSON_PRETTY_PRINT) }}</pre></dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">{{ __('Rows') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('#') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Raw / normalized') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Validation errors') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Lead') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($run->rows as $row)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $row->row_index }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                                @if($row->status->value === 'imported') bg-green-100 text-green-800
                                                @elseif($row->status->value === 'invalid') bg-red-100 text-red-800
                                                @elseif($row->status->value === 'duplicate') bg-amber-100 text-amber-800
                                                @elseif($row->status->value === 'valid') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $row->status->value }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <details class="text-xs">
                                                <summary class="cursor-pointer text-indigo-600">{{ __('View data') }}</summary>
                                                <pre class="mt-1 bg-gray-50 p-2 rounded overflow-auto max-h-32">{{ json_encode($row->normalized_data ?? $row->raw_data, JSON_PRETTY_PRINT) }}</pre>
                                            </details>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            @if ($row->validation_errors)
                                                {{ implode(', ', $row->validation_errors) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if ($row->lead_id)
                                                <a href="{{ route('leads.show', $row->lead_id) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View lead') }} #{{ $row->lead_id }}</a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('No rows.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
