@props([
    'title' => null,
    'actions' => null,
    'footer' => null,
    'padding' => 'md',
    'variant' => 'default'
])

<div class="card {{ $variant === 'tinted' ? 'bg-page' : '' }}" {{ $attributes }}>
    @if($title || $actions)
        <div class="card-header">
            @if($title)
                <h3 class="card-title">{{ $title }}</h3>
            @endif
            @if($actions)
                <div class="card-actions">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body{{ $padding === 'sm' ? '-sm' : '' }}">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>