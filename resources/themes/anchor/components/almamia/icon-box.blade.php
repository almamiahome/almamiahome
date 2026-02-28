<div {{ $attributes->twMerge('flex items-start space-x-4') }}>
    <div class="flex items-center justify-center w-10 h-10 rounded-full liquid-glass-chip">
        {{ $icon ?? '' }}
    </div>
    <div>
        <h4 class="font-semibold text-primary-900 dark:text-slate-100">{{ $title ?? 'Título' }}</h4>
        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $slot ?? 'Descripción.' }}</p>
    </div>
</div>