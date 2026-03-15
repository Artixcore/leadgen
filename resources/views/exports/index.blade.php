<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Export history') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <p class="text-sm text-gray-600">
            {{ __('A list of your past lead exports.') }}
        </p>

        <x-card>
            @if ($exports->isEmpty())
                <p class="text-gray-600">{{ __('You have not performed any exports yet.') }}</p>
                <p class="mt-2">
                    <a href="{{ route('leads.index') }}" class="text-sm font-medium text-gray-800 hover:text-gray-600">
                        {{ __('Search leads') }} &rarr;
                    </a>
                </p>
            @else
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Type') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Rows') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($exports as $export)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $export->created_at->format('M j, Y g:i A') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ strtoupper($export->type ?? 'csv') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ number_format($export->row_count) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                            {{ $export->status === 'completed' ? 'bg-green-100 text-green-800' : ($export->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $export->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        @if ($export->status === 'completed' && $export->file_path)
                                            <a href="{{ route('exports.download', $export) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ __('Download') }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $exports->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
