<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;

middleware('auth');
name('lideres.individual');

new class extends Component {
    public array $bloques = [
        'Resumen por cierre',
        'Detalle de premios por concepto',
        'Historial de alertas y desvíos',
    ];
};
?>

<x-layouts.app>
    @volt('lideres.individual')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Líder · Vista individual (Etapa 5)"
                description="Página preparada para gestionar el detalle por líder y por cierre en versión 2."
                :border="false"
            />

            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach($bloques as $bloque)
                        <li>• {{ $bloque }}</li>
                    @endforeach
                </ul>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
