<div {{ $attributes->twMerge('liquid-glass-panel p-8 md:p-12 text-center space-y-4') }}>
    <h3 class="text-3xl font-semibold text-primary-900 dark:text-slate-100">{{ $title ?? 'Título de llamada a la acción' }}</h3>
    <p class="text-slate-700 dark:text-slate-300">{{ $slot ?? 'Texto descriptivo.' }}</p>
    <x-elements.button size="lg" class="bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 text-white border-0">
        {{ $button ?? 'Acción' }}
    </x-elements.button>
</div>