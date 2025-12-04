@props([
    'variant' => 'neutral'
])

<span class="badge badge-{{ $variant }}">
    {{ $slot }}
</span>