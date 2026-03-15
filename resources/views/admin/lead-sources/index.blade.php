<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lead Sources') }}
            </h2>
            <a href="{{ route('admin.lead-sources.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                {{ __('Add source') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.lead-sources.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label for="type" class="block text-xs font-medium text-gray-700">{{ __('Type') }}</label>
                            <select id="type" name="type" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">{{ __('All') }}</option>
                                @foreach (\App\LeadSourceType::cases() as $type)
                                    <option value="{{ $type->value }}" @selected(($filters['type'] ?? '') === $type->value)>{{ ucfirst($type->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-700">{{ __('Status') }}</label>
                            <select id="status" name="status" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">{{ __('All') }}</option>
                                @foreach (\App\LeadSourceStatus::cases() as $status)
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
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Reliability') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Last sync') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Leads') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($sources as $source)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $source->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($source->type->value) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $source->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($source->status->value) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $source->reliability_score !== null ? $source->reliability_score . '%' : '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $source->last_sync_at?->diffForHumans() ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $source->leads_count }}</td>
                                        <td class="px-4 py-3 text-right text-sm space-x-2">
                                            <a href="{{ route('admin.lead-sources.show', $source) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                            <a href="{{ route('admin.lead-sources.edit', $source) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                            <form action="{{ route('admin.lead-sources.pause', $source) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-amber-600 hover:text-amber-900">{{ $source->status->value === 'active' ? __('Pause') : __('Resume') }}</button>
                                            </form>
                                            @if ($source->status->value === 'active')
                                                <form action="{{ route('admin.lead-sources.sync', $source) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">{{ __('Sync now') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">{{ __('No lead sources yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $sources->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
