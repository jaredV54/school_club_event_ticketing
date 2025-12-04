@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => false,
    'type' => 'button',
    'href' => null
])

@if($href)
    <a href="{{ $href }}" class="btn btn-{{ $variant }} {{ $size === 'sm' ? 'btn-sm' : '' }} {{ $icon ? 'btn-icon' : '' }}" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="btn btn-{{ $variant }} {{ $size === 'sm' ? 'btn-sm' : '' }} {{ $icon ? 'btn-icon' : '' }}" {{ $attributes }}>
        {{ $slot }}
    </button>
@endif