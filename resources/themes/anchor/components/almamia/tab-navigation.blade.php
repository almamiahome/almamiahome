@props(['tabs' => [], 'active' => null])

<div {{ $attributes->twMerge('liquid-glass-muted rounded-2xl border border-white/60 dark:border-sky-300/15 p-1') }}>
    <nav class="flex space-x-2">
        @foreach($tabs as $tab)
            @php $value = $tab['value'] ?? $tab['label']; $isActive = $value === $active; @endphp
            <a href="#" class="px-4 py-2 text-sm font-medium rounded-xl border {{ $isActive ? 'border-primary-300 bg-white/70 text-secondary-700 dark:bg-slate-900/60 dark:text-sky-200 font-semibold backdrop-blur-lg' : 'border-transparent text-slate-600 dark:text-slate-400 hover:bg-white/50 dark:hover:bg-slate-900/50' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div>