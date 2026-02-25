<div {{ $attributes->twMerge('p-8 md:p-12 bg-primary-100 dark:bg-zinc-700 rounded-lg text-center space-y-4') }}>
    <h3 class="text-3xl font-semibold text-primary-800">{{ $title ?? 'Título de llamada a la acción' }}</h3>
    <p class="text-gray-700 dark:text-gray-300">{{ $slot ?? 'Texto descriptivo.' }}</p>
    <x-elements.button size="lg" class="bg-primary-600 hover:bg-primary-700 text-white">
        {{ $button ?? 'Acción' }}
    </x-elements.button>
</div>