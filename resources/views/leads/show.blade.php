<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lead') }}: {{ $lead->full_name ?? $lead->email ?? __('Unknown') }}
            </h2>
            <div class="flex gap-2">
                @can('bookmark-leads')
                    @if ($isBookmarked)
                        <form method="POST" action="{{ route('leads.bookmark.destroy', $lead) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-800 border border-amber-300 rounded-md text-sm font-medium hover:bg-amber-200">
                                {{ __('Unbookmark') }}
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('leads.bookmark.store', $lead) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                {{ __('Bookmark') }}
                            </button>
                        </form>
                    @endif
                @endcan
                <a href="{{ route('leads.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    {{ __('Back to list') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <x-card>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Full name') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->full_name ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Job title') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->job_title ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->email ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Phone') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->phone ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Company name') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->company_name ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Website') }}</dt><dd class="mt-1 text-sm text-gray-900">@if($lead->website)<a href="{{ $lead->website }}" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">{{ $lead->website }}</a>@else — @endif</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Industry') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->industry ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Country') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->country ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('City') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->city ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('State') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->state ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('LinkedIn') }}</dt><dd class="mt-1 text-sm text-gray-900">@if($lead->linkedin_profile)<a href="{{ $lead->linkedin_profile }}" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">{{ $lead->linkedin_profile }}</a>@else — @endif</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Company size') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->company_size ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Revenue range') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->revenue_range ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Lead source') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->lead_source ?? '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Verification status') }}</dt>
                            <dd class="mt-1">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                    @if($lead->verification_status->value === 'verified') bg-green-100 text-green-800
                                    @elseif($lead->verification_status->value === 'invalid') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($lead->verification_status->value) }}
                                </span>
                            </dd>
                        </div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Quality score') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->quality_score !== null ? $lead->quality_score : '—' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Pipeline status') }}</dt>
                            <dd class="mt-1">
                                <form method="POST" action="{{ route('leads.status.update', $lead) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="lead_status" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @foreach (\App\LeadStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ ($lead->lead_status?->value ?? 'new') === $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </dd>
                        </div>
                        <div><dt class="text-sm font-medium text-gray-500">{{ __('Freshness') }}</dt>
                            <dd class="mt-1">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                    @if($lead->freshness()->value === 'fresh') bg-green-100 text-green-800
                                    @elseif($lead->freshness()->value === 'stale') bg-amber-100 text-amber-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($lead->freshness()->value) }}
                                </span>
                            </dd>
                        </div>
                        @if ($lead->is_duplicate)
                            <div><dt class="text-sm font-medium text-gray-500">{{ __('Duplicate') }}</dt><dd class="mt-1"><span class="inline-flex rounded-full px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800">{{ __('Yes') }}</span></dd></div>
                        @endif
                        <div class="md:col-span-2"><dt class="text-sm font-medium text-gray-500">{{ __('Last updated') }}</dt><dd class="mt-1 text-sm text-gray-900">{{ $lead->updated_at?->format('M j, Y g:i A') ?? '—' }}</dd></div>
                        @if ($lead->notes)
                            <div class="md:col-span-2"><dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt><dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $lead->notes }}</dd></div>
                        @endif
                    </dl>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500 mb-2">{{ __('Tags') }}</dt>
                        <dd class="flex flex-wrap gap-2 items-center">
                            @foreach ($lead->tags as $tag)
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $tag->name }}
                                    <form method="POST" action="{{ route('leads.tags.destroy', [$lead, $tag]) }}" class="inline" onsubmit="return confirm('{{ __('Remove this tag?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600">&times;</button>
                                    </form>
                                </span>
                            @endforeach
                            <form method="POST" action="{{ route('leads.tags.store', $lead) }}" class="inline flex gap-1 items-center">
                                @csrf
                                <input type="text" name="tag_names[]" placeholder="{{ __('Add tag') }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" maxlength="255">
                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-gray-200 rounded text-xs hover:bg-gray-300">{{ __('Add') }}</button>
                            </form>
                        </dd>
                    </div>
        </x-card>

        @can('manage-lists')
            <x-card>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">{{ __('Add to list') }}</h3>
                        <form method="POST" action="{{ route('leads.lists.add', $lead) }}" class="flex gap-2 flex-wrap items-end">
                            @csrf
                            <div class="min-w-[200px]">
                                <select name="list_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">{{ __('Select a list') }}</option>
                                    @foreach ($userLists as $list)
                                        <option value="{{ $list->id }}">{{ $list->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('Add') }}
                            </button>
                        </form>
                        @if ($userLists->isEmpty())
                            <p class="mt-2 text-sm text-gray-500">{{ __('You have no lists yet.') }} <a href="{{ route('lists.index') }}" class="text-indigo-600 hover:underline">{{ __('Create a list') }}</a></p>
                        @endif
            </x-card>
        @endcan

        <x-card>
            <x-slot name="header">
                <h3 class="text-sm font-medium text-gray-900">{{ __('Follow-up reminders') }}</h3>
            </x-slot>
                    <form method="POST" action="{{ route('leads.reminders.store', $lead) }}" class="mb-4 flex flex-wrap gap-2 items-end">
                        @csrf
                        <div>
                            <label for="remind_at" class="block text-xs font-medium text-gray-700">{{ __('Remind me at') }}</label>
                            <input type="datetime-local" name="remind_at" id="remind_at" required min="{{ now()->addMinute()->format('Y-m-d\TH:i') }}"
                                class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="min-w-[200px]">
                            <label for="reminder_body" class="block text-xs font-medium text-gray-700">{{ __('Note (optional)') }}</label>
                            <input type="text" name="body" id="reminder_body" maxlength="500" placeholder="{{ __('e.g. Follow up on proposal') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            {{ __('Add reminder') }}
                        </button>
                    </form>
                    <ul class="space-y-2">
                        @forelse ($userReminders as $reminder)
                            <li class="flex justify-between items-center gap-4 py-2 border-b border-gray-100 last:border-0 text-sm">
                                <span>{{ $reminder->remind_at->format('M j, Y g:i A') }}@if($reminder->body) — {{ $reminder->body }}@endif</span>
                                <form method="POST" action="{{ route('leads.reminders.destroy', $reminder) }}" class="inline" onsubmit="return confirm('{{ __('Delete this reminder?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs">{{ __('Delete') }}</button>
                                </form>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">{{ __('No reminders.') }}</li>
                        @endforelse
                    </ul>
        </x-card>

        <x-card>
            <x-slot name="header">
                <h3 class="text-sm font-medium text-gray-900">{{ __('My notes') }}</h3>
            </x-slot>
                    <form method="POST" action="{{ route('leads.notes.store', $lead) }}" class="mb-4">
                        @csrf
                        <textarea name="body" rows="3" required maxlength="65535" placeholder="{{ __('Add a note...') }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        <button type="submit" class="mt-2 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            {{ __('Add note') }}
                        </button>
                    </form>
                    <ul class="space-y-3">
                        @forelse ($lead->notes as $note)
                            <li class="flex justify-between items-start gap-4 py-2 border-b border-gray-100 last:border-0">
                                <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $note->body }}</div>
                                <div class="shrink-0 flex items-center gap-2">
                                    <span class="text-xs text-gray-500">{{ $note->created_at->format('M j, Y g:i A') }}</span>
                                    <form method="POST" action="{{ route('leads.notes.destroy', $note) }}" class="inline" onsubmit="return confirm('{{ __('Delete this note?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">{{ __('No notes yet.') }}</li>
                        @endforelse
                    </ul>
        </x-card>
    </div>
</x-app-layout>
