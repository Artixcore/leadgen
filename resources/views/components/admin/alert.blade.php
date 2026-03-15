@props([
    'type' => 'primary',
    'dismissible' => true,
])
<div {{ $attributes->merge(['class' => 'alert alert-' . $type . ($dismissible ? ' alert-dismissible' : '') . ' fade show', 'role' => 'alert']) }}>
    <div class="alert-message">
        {{ $slot }}
    </div>
    @if ($dismissible)
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
