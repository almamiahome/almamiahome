@props(['color' => 'primary'])

@php
    $bgColor = match($color) {
        'primary' => 'bg-primary-100 text-primary-800',
        'secondary' => 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-gray-200',
        'success' => 'bg-emerald-100 text-emerald-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
        default => 'bg-primary-100 text-primary-800',
    };
@endphp

<span {{ $attributes->twMerge('inline-flex px-3 py-1 text-xs font-medium rounded-full ' . $bgColor) }}>
    {{ $slot }}
</span>