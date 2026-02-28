<?php
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('clientes');
?>

<x-layouts.app>
    <x-app.container class="space-y-6">
        <x-app.heading
            title="Clientes"
            description="Administra la cartera de clientes y su historial."
            :border="false"
        />

        <div class="p-6 text-sm text-slate-600 bg-white border border-dashed rounded-xl dark:bg-blue-900/40 dark:border-blue-700/60 dark:text-blue-100/80">
            <p>Construye aquí herramientas para segmentar y conocer mejor a tus clientes.</p>
        </div>
    </x-app.container>
</x-layouts.app>
