<div {{ $attributes->twMerge('liquid-glass-panel p-8 md:p-16 text-center space-y-6') }}>
    <h2 class="text-4xl md:text-5xl font-bold text-primary-900 dark:text-slate-100">{{ $title ?? 'Título del banner' }}</h2>
    <p class="text-lg md:text-xl text-secondary-700 dark:text-sky-300">{{ $description ?? 'Descripción del banner.' }}</p>
    <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
        {{ $slot }}
    </div>
</div>