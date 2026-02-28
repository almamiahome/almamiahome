<?php
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('stock');
?>

<x-layouts.app>
    <x-app.container class="space-y-6">
        <x-app.heading
            title="Stock"
            description="Controla y actualiza el inventario disponible."
            :border="false"
        />

        <div class="p-6 text-sm text-slate-600 bg-white border border-dashed rounded-xl dark:bg-blue-900/40 dark:border-blue-700/60 dark:text-blue-100/80">
            <p>Contenido en construcción. Utiliza este espacio para construir el módulo de gestión de stock.</p>
        </div>
    </x-app.container>
</x-layouts.app>
