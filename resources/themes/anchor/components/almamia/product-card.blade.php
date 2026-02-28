<div {{ $attributes->twMerge('flex flex-col bg-white dark:bg-zinc-800 rounded-lg overflow-hidden shadow') }}>
    <img src="{{ $image ?? 'https://via.placeholder.com/600x400' }}" alt="{{ $title ?? '' }}" class="w-full h-48 object-cover">
    <div class="p-6 space-y-2 flex-1 flex flex-col">
        <h4 class="font-semibold text-primary-800">{{ $title ?? 'Producto' }}</h4>
        <p class="flex-1 text-sm text-gray-600 dark:text-gray-400">{{ $slot ?? 'Descripción' }}</p>
        <span class="font-bold text-primary-700">{{ $price ?? '$0.00' }}</span>
        <x-elements.button size="md" class="mt-4 w-full bg-primary-600 hover:bg-primary-700 text-white">
            {{ $button ?? 'Añadir' }}
        </x-elements.button>
    </div>
</div>