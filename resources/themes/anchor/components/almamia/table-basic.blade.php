<table {{ $attributes->twMerge('min-w-full divide-y divide-primary-100/70 dark:divide-sky-300/15 liquid-glass-panel overflow-hidden') }}>
    <thead class="bg-white/60 dark:bg-slate-900/70 text-primary-900 dark:text-slate-100">
        {{ $head ?? '' }}
    </thead>
    <tbody class="bg-transparent divide-y divide-primary-100/70 dark:divide-sky-300/15">
        {{ $slot }}
    </tbody>
</table>