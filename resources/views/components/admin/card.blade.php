@props([
    'title' => null,
    'subtitle' => null,
])
<div {{ $attributes->merge(['class' => 'card']) }}>
    @if ($title !== null)
    <div class="card-header">
        <h5 class="card-title mb-0">{{ $title }}</h5>
        @if ($subtitle !== null)
        <h6 class="card-subtitle text-muted">{{ $subtitle }}</h6>
        @endif
    </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
