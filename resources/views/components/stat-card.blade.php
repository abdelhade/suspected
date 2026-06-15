@props([
    'label',
    'value',
    'trend' => null,
    'highlight' => false,
])

<div class="stat-card {{ $highlight ? 'highlighted' : '' }}">
    <div class="d-flex align-items-start justify-content-between gap-2">
        <div>
            <div class="stat-label">{{ $label }}</div>
            <div class="stat-value mt-1 {{ $highlight ? 'neon-text' : '' }}">{{ number_format($value) }}</div>
            @if($trend !== null)
                <div class="stat-trend mt-1">{{ $trend }}</div>
            @endif
        </div>
        <div class="stat-icon {{ $highlight ? 'neon-bg' : '' }}">
            {{ $slot }}
        </div>
    </div>
</div>
