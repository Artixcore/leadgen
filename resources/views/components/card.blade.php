<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg']) }}>
    @if(isset($header))
        <div class="px-4 py-4 sm:px-6 border-b border-gray-200">
            {{ $header }}
        </div>
    @endif

    <div class="p-4 sm:p-6">
        {{ $slot }}
    </div>
</div>
