<?php
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('bono-lideres');
?>

<x-layouts.app>
    <x-app.container class="space-y-6">
        <x-app.heading
            title="Bono Líderes"
            description="Administra la asignación de bonos para líderes."
            :border="false"
        />

        <div class="p-6 text-sm text-slate-600 bg-white border border-dashed rounded-xl dark:bg-blue-900/40 dark:border-blue-700/60 dark:text-blue-100/80">
            <p>Define aquí los criterios y cálculos para los bonos destinados a líderes.</p>
        </div>
    </x-app.container>
</x-layouts.app>
