@props(['value' => 0, 'label' => ''])

<div {{ $attributes->twMerge('text-center liquid-glass-muted rounded-2xl p-4') }}>
    <span class="text-4xl font-bold text-primary-900 dark:text-sky-200">{{ $value }}</span>
    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $label }}</p>
</div>