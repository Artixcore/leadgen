<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $list->name }}
                </h2>
                @if ($list->user_id !== auth()->id())
                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800">{{ __('Shared with you') }}</span>
                @endif
                <a href="{{ route('lists.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Back to lists') }}</a>
            </div>
            <div class="flex gap-2 items-center flex-wrap">
                @can('export-leads')
                    <form method="POST" action="{{ route('leads.export') }}" class="inline">
                        @csrf
                        <input type="hidden" name="format" value="csv">
                        <input type="hidden" name="list_id" value="{{ $list->id }}">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            {{ __('Export list') }}
                        </button>
                    </form>
                @endcan
                @can('update', $list)
                    <a href="{{ route('leads.index') }}?add_to_list={{ $list->id }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        {{ __('Add leads') }}
                    </a>
                @endcan
            </div>
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

        @can('update', $list)
            <x-card>
                    <form method="POST" action="{{ route('lists.update', $list) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-4 items-end flex-wrap">
                            <div class="min-w-[200px]">
                                <label for="list-name" class="block text-sm font-medium text-gray-700">{{ __('List name') }}</label>
                                <input type="text" name="name" id="list-name" value="{{ old('name', $list->name) }}" required maxlength="255"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('Save') }}</button>
                        </div>
                        <div>
                            <label for="list-notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                            <textarea name="notes" id="list-notes" rows="3" maxlength="65535" placeholder="{{ __('Add notes about this list...') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes', $list->notes ?? '') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>
            </x-card>
        @else
            <x-card>
                    @if ($list->notes)
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $list->notes }}</p>
                    @endif
            </x-card>
        @endcan

        @can('update', $list)
            @if ($list->sharedWithUsers->isNotEmpty())
                <x-card>
                    <h3 class="text-sm font-medium text-gray-900 mb-2">{{ __('Shared with') }}</h3>
                        <ul class="space-y-1 text-sm">
                            @foreach ($list->sharedWithUsers as $sharedUser)
                                <li class="flex items-center justify-between gap-2">
                                    <span>{{ $sharedUser->name }} ({{ $sharedUser->email }})</span>
                                    <form method="POST" action="{{ route('lists.unshare', [$list, $sharedUser]) }}" class="inline" onsubmit="return confirm('{{ __('Remove access for this user?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs">{{ __('Remove') }}</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                        <form method="POST" action="{{ route('lists.share', $list) }}" class="mt-4 flex gap-2 items-end">
                            @csrf
                            <div class="min-w-[200px]">
                                <label for="share-email" class="block text-xs font-medium text-gray-700">{{ __('Share with user (email)') }}</label>
                                <input type="email" name="email" id="share-email" required placeholder="{{ __('user@example.com') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                {{ __('Share') }}
                            </button>
                        </form>
                </x-card>
            @else
                <x-card>
                    <h3 class="text-sm font-medium text-gray-900 mb-2">{{ __('Share list') }}</h3>
                        <form method="POST" action="{{ route('lists.share', $list) }}" class="flex gap-2 items-end">
                            @csrf
                            <div class="min-w-[200px]">
                                <label for="share-email" class="block text-xs font-medium text-gray-700">{{ __('Share with user (email)') }}</label>
                                <input type="email" name="email" id="share-email" required placeholder="{{ __('user@example.com') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                {{ __('Share') }}
                            </button>
                        </form>
                </x-card>
            @endif
        @endcan

        <x-card>
            <x-slot name="header">
                <h3 class="text-sm font-medium text-gray-900">{{ __('Leads in list') }} ({{ $leads->total() }})</h3>
            </x-slot>
            <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Name') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Email') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Company') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($leads as $lead)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ $lead->full_name ?? '—' }}</a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->email ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lead->company_name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}</a>
                                            @can('update', $list)
                                                <form method="POST" action="{{ route('lists.leads.remove', [$list, $lead]) }}" class="inline" onsubmit="return confirm('{{ __('Remove this lead from the list?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 ms-2">{{ __('Remove') }}</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('No leads in this list yet.') }} <a href="{{ route('leads.index') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Add leads') }}</a></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
            <div class="mt-4">
                {{ $leads->links() }}
            </div>
        </x-card>

        @if ($activities->isNotEmpty())
            <x-card>
                <x-slot name="header">
                    <h3 class="text-sm font-medium text-gray-900">{{ __('Activity') }}</h3>
                </x-slot>
                        <ul class="space-y-2 text-sm">
                            @foreach ($activities as $activity)
                                <li class="text-gray-600">
                                    @switch($activity->action)
                                        @case('list_created')
                                            {{ __('List created') }}
                                            @break
                                        @case('list_renamed')
                                            {{ __('Renamed from') }} {{ $activity->meta['old_name'] ?? '—' }}
                                            @break
                                        @case('lead_added')
                                            {{ $activity->user?->name ?? __('Someone') }} {{ __('added a lead') }}
                                            @if ($activity->subject_id && ($lead = $activityLeads->get($activity->subject_id)))
                                                — <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ $lead->full_name ?? $lead->email }}</a>
                                            @endif
                                            @break
                                        @case('lead_removed')
                                            {{ $activity->user?->name ?? __('Someone') }} {{ __('removed a lead') }}
                                            @if ($activity->subject_id && ($lead = $activityLeads->get($activity->subject_id)))
                                                — <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ $lead->full_name ?? $lead->email }}</a>
                                            @endif
                                            @break
                                        @case('list_exported')
                                            {{ $activity->user?->name ?? __('Someone') }} {{ __('exported this list') }}
                                            @break
                                        @default
                                            {{ $activity->action }}
                                    @endswitch
                                    <span class="text-gray-400">· {{ $activity->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
            </x-card>
        @endif

        @can('update', $list)
            <p>
                <form method="POST" action="{{ route('lists.destroy', $list) }}" class="inline" onsubmit="return confirm('{{ __('Delete this list? Leads will not be deleted.') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">{{ __('Delete list') }}</button>
                </form>
            </p>
        @endcan
    </div>
</x-app-layout>
