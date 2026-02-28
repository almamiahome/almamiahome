<?php
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('bono-coordinadoras');
?>

<x-layouts.app>
    <x-app.container class="space-y-6">
        <x-app.heading
            title="Bono Coordinadoras"
            description="Gestiona los incentivos para coordinadoras."
            :border="false"
        />

        <div class="p-6 text-sm text-slate-600 bg-white border border-dashed rounded-xl dark:bg-blue-900/40 dark:border-blue-700/60 dark:text-blue-100/80">
            <p>Implementa aquí los criterios de cálculo y aprobación de bonos para coordinadoras.</p>
        </div>
    </x-app.container>
</x-layouts.app>
