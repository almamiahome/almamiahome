@props(['filters' => []])

<aside {{ $attributes->twMerge('w-full sm:w-64 p-4 liquid-glass-panel space-y-4') }}>
    <h3 class="text-lg font-semibold text-primary-900 dark:text-slate-100">Filtrar</h3>
    <div class="space-y-2">
        @foreach($filters as $filter)
            <label class="flex items-center space-x-2 text-sm text-slate-700 dark:text-slate-300">
                <input type="checkbox" value="{{ $filter['value'] ?? $filter }}" class="rounded border-primary-300/80 text-primary-600 focus:ring-primary-400 dark:border-sky-300/30 dark:bg-slate-900/60">
                <span>{{ $filter['label'] ?? $filter }}</span>
            </label>
        @endforeach
    </div>
</aside>