@php
    $collector = $collector ?? null;
    $isCreate = $collector === null;
@endphp
<div class="mb-3">
    <label for="name" class="form-label">{{ __('Name') }}</label>
    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $collector?->name) }}" required>
    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="slug" class="form-label">{{ __('Slug') }}</label>
    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $collector?->slug) }}" placeholder="{{ __('Auto-generated if empty') }}">
    @error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="source_type" class="form-label">{{ __('Source type') }}</label>
    <select id="source_type" name="source_type" class="form-select">
        <option value="">{{ __('—') }}</option>
        @foreach (\App\LeadCollectorSourceType::cases() as $st)
            <option value="{{ $st->value }}" @selected(old('source_type', $collector?->source_type?->value) === $st->value)>{{ ucfirst($st->value) }}</option>
        @endforeach
    </select>
    @error('source_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="type" class="form-label">{{ __('Collector type') }}</label>
    <select id="type" name="type" class="form-select" required>
        @foreach (\App\CollectorType::cases() as $type)
            <option value="{{ $type->value }}" @selected(old('type', $collector?->type?->value) === $type->value)>{{ ucfirst(str_replace('_', ' ', $type->value)) }}</option>
        @endforeach
    </select>
    @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="target_service" class="form-label">{{ __('Target service') }}</label>
    <select id="target_service" name="target_service" class="form-select">
        <option value="">{{ __('—') }}</option>
        @foreach (\App\LeadCollectorTargetService::cases() as $svc)
            <option value="{{ $svc->value }}" @selected(old('target_service', $collector?->target_service) === $svc->value)>{{ ucfirst(str_replace('_', ' ', $svc->value)) }}</option>
        @endforeach
    </select>
    @error('target_service')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="target_niche" class="form-label">{{ __('Target niche') }}</label>
        <input type="text" id="target_niche" name="target_niche" class="form-control" value="{{ old('target_niche', $collector?->target_niche) }}">
        @error('target_niche')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="target_country" class="form-label">{{ __('Target country') }}</label>
        <input type="text" id="target_country" name="target_country" class="form-control" value="{{ old('target_country', $collector?->target_country) }}">
        @error('target_country')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>
<div class="mb-3">
    <label for="target_city" class="form-label">{{ __('Target city') }}</label>
    <input type="text" id="target_city" name="target_city" class="form-control" value="{{ old('target_city', $collector?->target_city) }}">
    @error('target_city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="keywords" class="form-label">{{ __('Keywords') }}</label>
    <textarea id="keywords" name="keywords" class="form-control" rows="2">{{ old('keywords', $collector?->keywords) }}</textarea>
    @error('keywords')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="status" class="form-label">{{ __('Status') }}</label>
    <select id="status" name="status" class="form-select" required>
        @foreach (\App\CollectorStatus::cases() as $status)
            <option value="{{ $status->value }}" @selected(old('status', $collector?->status?->value) === $status->value)>{{ ucfirst($status->value) }}</option>
        @endforeach
    </select>
    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $collector?->is_active ?? true))>
        <label for="is_active" class="form-check-label">{{ __('Active') }}</label>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="trust_score" class="form-label">{{ __('Trust score (0–100)') }}</label>
        <input type="number" id="trust_score" name="trust_score" class="form-control" min="0" max="100" value="{{ old('trust_score', $collector?->trust_score) }}">
        @error('trust_score')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="priority" class="form-label">{{ __('Priority') }}</label>
        <input type="number" id="priority" name="priority" class="form-control" min="0" value="{{ old('priority', $collector?->priority ?? 0) }}">
        @error('priority')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>
<div class="mb-3">
    <label for="schedule" class="form-label">{{ __('Schedule (cron expression)') }}</label>
    <input type="text" id="schedule" name="schedule" class="form-control" value="{{ old('schedule', $collector?->schedule) }}" placeholder="e.g. 0 */6 * * *">
    @error('schedule')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
@if ($isCreate)
<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" id="create_lead_source" name="create_lead_source" value="1" class="form-check-input" @checked(old('create_lead_source'))>
        <label for="create_lead_source" class="form-check-label">{{ __('Create new lead source') }}</label>
    </div>
</div>
<div class="mb-3" id="lead_source_id_wrapper">
    <label for="lead_source_id" class="form-label">{{ __('Lead source') }}</label>
    <select id="lead_source_id" name="lead_source_id" class="form-select">
        <option value="">{{ __('Select a source') }}</option>
        @foreach ($leadSources as $source)
            <option value="{{ $source->id }}" @selected(old('lead_source_id') == $source->id)>{{ $source->name }}</option>
        @endforeach
    </select>
    @error('lead_source_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
@else
<div class="mb-3">
    <label for="lead_source_id" class="form-label">{{ __('Lead source') }}</label>
    <select id="lead_source_id" name="lead_source_id" class="form-select">
        <option value="">{{ __('— Optional —') }}</option>
        @foreach ($leadSources as $source)
            <option value="{{ $source->id }}" @selected(old('lead_source_id', $collector?->lead_source_id) == $source->id)>{{ $source->name }}</option>
        @endforeach
    </select>
    @error('lead_source_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
@endif
<div class="mb-3">
    <label for="filters_json" class="form-label">{{ __('Filters (JSON)') }}</label>
    <textarea id="filters_json" name="filters_json" class="form-control font-monospace small" rows="2" placeholder='{}'>{{ old('filters_json', $collector && $collector->filters_json ? json_encode($collector->filters_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
    @error('filters_json')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="config" class="form-label">{{ __('Config (JSON)') }}</label>
    <textarea id="config" name="config" class="form-control font-monospace small" rows="4" placeholder='{"key": "value"}'>{{ old('config', $collector && $collector->config ? json_encode($collector->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '{}') }}</textarea>
    @error('config')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

@if ($isCreate)
@push('scripts')
<script>
document.getElementById('create_lead_source').addEventListener('change', function() {
    var wrapper = document.getElementById('lead_source_id_wrapper');
    wrapper.style.display = this.checked ? 'none' : 'block';
    if (this.checked) document.getElementById('lead_source_id').value = '';
});
if (document.getElementById('create_lead_source').checked) {
    document.getElementById('lead_source_id_wrapper').style.display = 'none';
}
</script>
@endpush
@endif
