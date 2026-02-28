<div {{ $attributes->twMerge('flex flex-col md:flex-row liquid-glass-panel overflow-hidden') }}>
    <img src="{{ $image ?? 'https://via.placeholder.com/400x300' }}" alt="{{ $title ?? '' }}" class="w-full md:w-48 h-48 object-cover">
    <div class="p-6 space-y-2 flex-1">
        <h4 class="font-semibold text-primary-900 dark:text-slate-100">{{ $title ?? 'Título' }}</h4>
        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $slot ?? 'Descripción del contenido.' }}</p>
        @if(isset($price))
            <span class="font-bold text-primary-700">{{ $price }}</span>
        @endif
        {{ $actions ?? '' }}
    </div>
</div>