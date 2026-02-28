<div {{ $attributes->twMerge('liquid-glass-panel p-6') }}>
    <p class="italic text-slate-700 dark:text-slate-300">{{ $quote ?? '' }}</p>
    <p class="mt-2 font-semibold text-primary-900 dark:text-sky-100">{{ $author ?? '' }}</p>
</div>