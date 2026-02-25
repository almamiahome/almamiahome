<?php
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('resumen-revendedoras');
?>

<x-layouts.app>
    <x-app.container class="space-y-6">
        <x-app.heading
            title="Resumen de Revendedoras"
            description="Revisa resultados y oportunidades con tus revendedoras."
            :border="false"
        />

        <div class="p-6 text-sm text-slate-600 bg-white border border-dashed rounded-xl dark:bg-blue-900/40 dark:border-blue-700/60 dark:text-blue-100/80">
            <p>Visualiza en este módulo los indicadores más relevantes de tus revendedoras.</p>
        </div>
    </x-app.container>
</x-layouts.app>
