<?php

use function Laravel\Folio\{middleware, name};

middleware([
    'auth',
    function ($request, $next) {
        if (! $request->user() || ! $request->user()->can('view_backend')) {
            abort(403, 'No tiene permisos para acceder al panel de liderazgo avanzado.');
        }

        return $next($request);
    },
]);

name('lideres.panel-etapa-5');
?>

<x-layouts.app>
    <x-app.container class="space-y-6">
        <x-app.heading
            title="Panel de liderazgo avanzado — Etapa 5"
            description="Acceso operativo a seguimiento por cierres y liquidación auditable con enfoque de control y trazabilidad."
            :border="false"
        />

        <div class="grid gap-4 md:grid-cols-2">
            <a href="{{ route('lideres.seguimiento-cierres') }}" class="rounded-2xl border border-white/50 bg-white/45 p-5 shadow-xl backdrop-blur-xl transition hover:bg-white/60 dark:border-white/10 dark:bg-slate-900/40 dark:hover:bg-slate-900/60">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Seguimiento de cierres</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Filtra por zona, departamento, catálogo y cierre para revisar desglose operativo y KPIs del período.</p>
            </a>
            <a href="{{ route('lideres.liquidacion') }}" class="rounded-2xl border border-white/50 bg-white/45 p-5 shadow-xl backdrop-blur-xl transition hover:bg-white/60 dark:border-white/10 dark:bg-slate-900/40 dark:hover:bg-slate-900/60">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Liquidación auditable</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Consulta cierres por rango seleccionado y valida consistencia de actividad, crecimiento, plus, unidades y total final.</p>
            </a>
        </div>
    </x-app.container>
</x-layouts.app>
