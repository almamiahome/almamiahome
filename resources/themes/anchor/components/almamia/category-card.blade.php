<div {{ $attributes->twMerge('flex flex-col items-center p-5 liquid-glass-panel') }}>
    <div class="flex items-center justify-center w-12 h-12 rounded-full liquid-glass-chip">
        {{-- El icono se pasa como atributo; si se omite se mostrará un símbolo genérico --}}
        {{ $icon ?? '' }}
    </div>
    <h4 class="mt-3 font-medium text-primary-900 dark:text-slate-100">{{ $title ?? 'Título' }}</h4>
    <p class="text-sm text-slate-600 dark:text-slate-300 text-center">
        {{ $slot ?? 'Descripción de la categoría.' }}
    </p>
</div>