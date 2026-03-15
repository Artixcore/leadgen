<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leads') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.leads.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label for="q" class="block text-xs font-medium text-gray-700">{{ __('Search') }}</label>
                            <input type="text" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('Name, email, company...') }}" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm w-48">
                        </div>
                        <div>
                            <label for="industry" class="block text-xs font-medium text-gray-700">{{ __('Industry') }}</label>
                            <input type="text" id="industry" name="industry" value="{{ $filters['industry'] ?? '' }}" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm w-40">
                        </div>
                        <div>
                            <label for="country" class="block text-xs font-medium text-gray-700">{{ __('Country') }}</label>
                            <input type="text" id="country" name="country" value="{{ $filters['country'] ?? '' }}" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm w-40">
                        </div>
                        <div>
                            <label for="per_page" class="block text-xs font-medium text-gray-700">{{ __('Per page') }}</label>
                            <select id="per_page" name="per_page" class="mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                @foreach ([15, 25, 50] as $n)
                                    <option value="{{ $n }}" @selected(($filters['per_page'] ?? 15) == $n)>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">{{ __('Filter') }}</button>
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
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Email') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Company') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Country') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Source') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($leads as $lead)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $lead->full_name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->email ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->company_name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->country ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->leadSource?->name ?? $lead->lead_source ?? '—' }}</td>
                                        <td class="px-4 py-3 text-right text-sm space-x-2">
                                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                            <a href="{{ route('admin.leads.edit', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('No leads found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $leads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
