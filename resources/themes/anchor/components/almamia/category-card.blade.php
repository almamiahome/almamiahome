<div {{ $attributes->twMerge('flex flex-col items-center p-5 bg-white dark:bg-zinc-800 rounded-lg shadow') }}>
    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 text-primary-700">
        {{-- El icono se pasa como atributo; si se omite se mostrará un símbolo genérico --}}
        {{ $icon ?? '' }}
    </div>
    <h4 class="mt-3 font-medium text-primary-800">{{ $title ?? 'Título' }}</h4>
    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
        {{ $slot ?? 'Descripción de la categoría.' }}
    </p>
</div>