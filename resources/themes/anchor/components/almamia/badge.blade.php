@props(['color' => 'primary'])

@php
    $bgColor = match($color) {
        'primary' => 'liquid-glass-chip',
        'secondary' => 'bg-white/70 text-slate-700 dark:bg-slate-900/70 dark:text-slate-200 border border-white/60 dark:border-slate-200/10 backdrop-blur-lg',
        'success' => 'bg-emerald-100/90 text-emerald-800 border border-emerald-200/80 backdrop-blur-lg',
        'warning' => 'bg-amber-100/90 text-amber-800 border border-amber-200/80 backdrop-blur-lg',
        'danger' => 'bg-red-100/90 text-red-800 border border-red-200/80 backdrop-blur-lg',
        default => 'liquid-glass-chip',
    };
@endphp

<span {{ $attributes->twMerge('inline-flex px-3 py-1 text-xs font-medium rounded-full ' . $bgColor) }}>
    {{ $slot }}
</span>