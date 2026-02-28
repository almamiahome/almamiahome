<div {{ $attributes->twMerge('flex flex-col liquid-glass-panel overflow-hidden') }}>
    <img src="{{ $image ?? 'https://via.placeholder.com/600x400' }}" alt="{{ $title ?? '' }}" class="w-full h-48 object-cover">
    <div class="p-6 space-y-2 flex-1 flex flex-col">
        <h4 class="font-semibold text-primary-900 dark:text-slate-100">{{ $title ?? 'Producto' }}</h4>
        <p class="flex-1 text-sm text-slate-600 dark:text-slate-300">{{ $slot ?? 'Descripción' }}</p>
        <span class="font-bold text-primary-700">{{ $price ?? '$0.00' }}</span>
        <x-elements.button size="md" class="mt-4 w-full bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 text-white border-0">
            {{ $button ?? 'Añadir' }}
        </x-elements.button>
    </div>
</div>