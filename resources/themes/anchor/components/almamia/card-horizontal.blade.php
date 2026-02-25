<div {{ $attributes->twMerge('flex flex-col md:flex-row bg-white dark:bg-zinc-800 rounded-lg overflow-hidden shadow') }}>
    <img src="{{ $image ?? 'https://via.placeholder.com/400x300' }}" alt="{{ $title ?? '' }}" class="w-full md:w-48 h-48 object-cover">
    <div class="p-6 space-y-2 flex-1">
        <h4 class="font-semibold text-primary-800">{{ $title ?? 'Título' }}</h4>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $slot ?? 'Descripción del contenido.' }}</p>
        @if(isset($price))
            <span class="font-bold text-primary-700">{{ $price }}</span>
        @endif
        {{ $actions ?? '' }}
    </div>
</div>