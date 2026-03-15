@php
    $source = $source ?? null;
@endphp
<div class="space-y-4">
    <div>
        <x-input-label for="name" :value="__('Source name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $source?->name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="type" :value="__('Type')" />
        <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            @foreach (\App\LeadSourceType::cases() as $type)
                <option value="{{ $type->value }}" @selected(old('type', $source?->type?->value) === $type->value)>{{ ucfirst($type->value) }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('type')" />
    </div>
    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            @foreach (\App\LeadSourceStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(old('status', $source?->status?->value) === $status->value)>{{ ucfirst($status->value) }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('status')" />
    </div>
    <div>
        <x-input-label for="reliability_score" :value="__('Reliability score (0-100)')" />
        <x-text-input id="reliability_score" name="reliability_score" type="number" min="0" max="100" class="mt-1 block w-full" :value="old('reliability_score', $source?->reliability_score)" />
        <x-input-error class="mt-2" :messages="$errors->get('reliability_score')" />
    </div>
    <div>
        <x-input-label for="import_frequency" :value="__('Import frequency (cron expression)')" />
        <x-text-input id="import_frequency" name="import_frequency" type="text" class="mt-1 block w-full" :value="old('import_frequency', $source?->import_frequency)" placeholder="e.g. 0 */6 * * *" />
        <x-input-error class="mt-2" :messages="$errors->get('import_frequency')" />
    </div>
</div>
