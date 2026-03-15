@php
    $source = $source ?? null;
@endphp
<div class="mb-3">
    <label for="name" class="form-label">{{ __('Source name') }}</label>
    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $source?->name) }}" required>
    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="type" class="form-label">{{ __('Type') }}</label>
    <select id="type" name="type" class="form-select" required>
        @foreach (\App\LeadSourceType::cases() as $type)
            <option value="{{ $type->value }}" @selected(old('type', $source?->type?->value) === $type->value)>{{ ucfirst($type->value) }}</option>
        @endforeach
    </select>
    @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="status" class="form-label">{{ __('Status') }}</label>
    <select id="status" name="status" class="form-select" required>
        @foreach (\App\LeadSourceStatus::cases() as $status)
            <option value="{{ $status->value }}" @selected(old('status', $source?->status?->value) === $status->value)>{{ ucfirst($status->value) }}</option>
        @endforeach
    </select>
    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="reliability_score" class="form-label">{{ __('Reliability score (0-100)') }}</label>
    <input type="number" id="reliability_score" name="reliability_score" min="0" max="100" class="form-control" value="{{ old('reliability_score', $source?->reliability_score) }}">
    @error('reliability_score')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="import_frequency" class="form-label">{{ __('Import frequency (cron expression)') }}</label>
    <input type="text" id="import_frequency" name="import_frequency" class="form-control" value="{{ old('import_frequency', $source?->import_frequency) }}" placeholder="e.g. 0 */6 * * *">
    @error('import_frequency')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
