<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $source->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.lead-sources.edit', $source) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">{{ __('Edit') }}</a>
                <form action="{{ route('admin.lead-sources.pause', $source) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-amber-300 rounded-md text-sm text-amber-700 hover:bg-amber-50">
                        {{ $source->status->value === 'active' ? __('Pause') : __('Resume') }}
                    </button>
                </form>
                @if ($source->status->value === 'active')
                    <form action="{{ route('admin.lead-sources.sync', $source) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">{{ __('Sync now') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $source->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Type') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($source->type->value) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                            <dd class="mt-1">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $source->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($source->status->value) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Reliability score') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $source->reliability_score !== null ? $source->reliability_score . '%' : '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Last sync') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $source->last_sync_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Import frequency') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $source->import_frequency ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Leads count') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $source->leads_count }}</dd>
                        </div>
                    </dl>

                    @if ($source->validation_rules)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Validation rules') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900"><pre class="bg-gray-50 p-2 rounded overflow-auto">{{ json_encode($source->validation_rules, JSON_PRETTY_PRINT) }}</pre></dd>
                        </div>
                    @endif

                    @if (isset($recentRuns) && $recentRuns->isNotEmpty())
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Recent import runs') }}</dt>
                            <dd class="mt-2">
                                <ul class="text-sm space-y-1">
                                    @foreach ($recentRuns as $run)
                                        <li>
                                            <a href="{{ route('admin.import-runs.show', $run) }}" class="text-indigo-600 hover:text-indigo-900">
                                                #{{ $run->id }} — {{ $run->status->value }} — {{ $run->created_at->format('Y-m-d H:i') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </dd>
                        </div>
                    @endif

                    <div>
                        <a href="{{ route('admin.import-runs.index', ['source' => $source->id]) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View all import runs') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
