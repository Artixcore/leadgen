<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My lists') }}
            </h2>
            <a href="{{ route('leads.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Back to leads') }}</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if ($canCreateList)
            <x-card>
                <h3 class="text-sm font-medium text-gray-900 mb-2">{{ __('Create new list') }}</h3>
                        <form method="POST" action="{{ route('lists.store') }}" class="flex gap-2 items-end">
                            @csrf
                            <div class="min-w-[200px]">
                                <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="{{ __('List name') }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('Create') }}
                            </button>
                        </form>
            </x-card>
        @endif

        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Leads') }}</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($lists as $list)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <a href="{{ route('lists.show', $list) }}" class="text-indigo-600 hover:text-indigo-900">{{ $list->name }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $list->leads_count }}</td>
                                    <td class="px-4 py-3 text-right text-sm space-x-2">
                                        <a href="{{ route('lists.show', $list) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                        <form method="POST" action="{{ route('lists.destroy', $list) }}" class="inline" onsubmit="return confirm('{{ __('Delete this list?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('No lists yet.') }} @if ($canCreateList) {{ __('Create one above.') }} @endif</td>
                                </tr>
                            @endforelse
                        </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>
