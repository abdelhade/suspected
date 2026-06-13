@props([
    'label',
    'value',
    'trend' => null,
    'highlight' => false,
])

<div @class([
    'brutal-card p-4',
    'neon-glow' => $highlight,
])>
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-brutal-smoke/60">{{ $label }}</p>
            <p @class([
                'mt-1 text-4xl font-bold tabular-nums',
                'neon-text' => $highlight,
                'text-brutal-black' => ! $highlight,
            ])>{{ number_format($value) }}</p>
            @if($trend !== null)
                <p class="mt-1 text-xs font-bold tracking-wide text-brutal-smoke/50">{{ $trend }}</p>
            @endif
        </div>
        <div @class([
            'flex h-9 w-9 shrink-0 items-center justify-center border border-brutal-black',
            'bg-neon text-brutal-black' => $highlight,
            'bg-brutal-white text-brutal-black' => ! $highlight,
        ])>
            {{ $slot }}
        </div>
    </div>
</div>
