<div {{ $attributes->twMerge('bg-primary-50 dark:bg-zinc-800/40 border border-primary-100 dark:border-zinc-700 rounded-lg p-8 text-center space-y-4') }}>
    <h3 class="text-2xl font-semibold text-primary-800">{{ $title ?? 'Plan' }}</h3>
    <p class="text-gray-700 dark:text-gray-300">{{ $slot ?? 'Descripción del plan' }}</p>
    <h4 class="text-4xl font-bold text-primary-800">{{ $price ?? '$0' }}<span class="text-2xl">/{{ $period ?? 'mes' }}</span></h4>
    <x-elements.button size="lg" class="bg-primary-600 hover:bg-primary-700 text-white">
        {{ $button ?? 'Elegir' }}
    </x-elements.button>
</div>