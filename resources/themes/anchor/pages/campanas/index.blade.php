<?php
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('campanas');
?>

<x-layouts.app>
    <x-app.container class="space-y-6">
        <x-app.heading
            title="Campañas"
            description="Diseña y haz seguimiento a tus campañas de venta."
            :border="false"
        />

        <div class="p-6 text-sm text-slate-600 bg-white border border-dashed rounded-xl dark:bg-blue-900/40 dark:border-blue-700/60 dark:text-blue-100/80">
            <p>Desde esta sección podrás planificar campañas y medir sus resultados.</p>
        </div>
    </x-app.container>
</x-layouts.app>
