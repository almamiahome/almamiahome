<div {{ $attributes->twMerge('liquid-glass-panel p-8 text-center space-y-4') }}>
    <h3 class="text-2xl font-semibold text-primary-900 dark:text-slate-100">{{ $title ?? 'Plan' }}</h3>
    <p class="text-slate-700 dark:text-slate-300">{{ $slot ?? 'Descripción del plan' }}</p>
    <h4 class="text-4xl font-bold text-primary-900 dark:text-slate-100">{{ $price ?? '$0' }}<span class="text-2xl">/{{ $period ?? 'mes' }}</span></h4>
    <x-elements.button size="lg" class="bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 text-white border-0">
        {{ $button ?? 'Elegir' }}
    </x-elements.button>
</div>