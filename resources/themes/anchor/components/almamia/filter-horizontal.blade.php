@props(['filters' => []])

<div {{ $attributes->twMerge('flex flex-wrap items-center liquid-glass-panel p-4 gap-4') }}>
    <h3 class="text-lg font-semibold text-primary-900 dark:text-slate-100 mr-4">Filtrar:</h3>
    <div class="flex flex-wrap items-center gap-4">
        @foreach($filters as $filter)
            <label class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" value="{{ $filter['value'] ?? $filter }}" class="text-primary-600 rounded border-primary-300 focus:ring-primary-500 dark:bg-zinc-800 dark:border-zinc-700">
                <span>{{ $filter['label'] ?? $filter }}</span>
            </label>
        @endforeach
    </div>
</div>