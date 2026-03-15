<div class="header">
    <h1 class="header-title">
        {{ $title ?? __('Page') }}
    </h1>
    @if (!empty($breadcrumbs))
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $label => $url)
                @if ($loop->last || $url === null)
                <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                @else
                <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
    @endif
</div>
