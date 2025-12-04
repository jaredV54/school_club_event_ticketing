@props([
    'label',
    'value',
    'delta' => null,
    'trend' => 'neutral'
])

<div class="kpi-tile">
    <div class="kpi-label">{{ $label }}</div>
    <div class="kpi-value">{{ $value }}</div>
    @if($delta !== null)
        <span class="kpi-delta {{ $trend }}">
            @if($trend === 'up')
                <i class='bx bx-trending-up'></i>
            @elseif($trend === 'down')
                <i class='bx bx-trending-down'></i>
            @else
                <i class='bx bx-minus'></i>
            @endif
            {{ $delta > 0 ? '+' : '' }}{{ number_format($delta, 1) }}%
        </span>
    @endif
</div>