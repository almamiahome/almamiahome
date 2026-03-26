<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;

middleware('auth');
name('lideres.avanzado');

new class extends Component {
    public array $kpis = [
        ['label' => 'Crecimiento', 'estado' => 'Pendiente conexión a cálculo real'],
        ['label' => 'Reparto', 'estado' => 'Pendiente conexión a cálculo real'],
        ['label' => 'Plus crecimiento', 'estado' => 'Pendiente conexión a cálculo real'],
        ['label' => 'Premio por unidades', 'estado' => 'Pendiente conexión a cálculo real'],
    ];
};
?>

<x-layouts.app>
    @volt('lideres.avanzado')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Líderes · Vista avanzada (Etapa 5)"
                description="Base Folio/Volt para reportería avanzada de liderazgo sin alterar la arquitectura clásica."
                :border="false"
            />

            <div class="grid gap-4 md:grid-cols-2">
                @foreach($kpis as $kpi)
                    <article class="rounded-xl border bg-white p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-slate-700">{{ $kpi['label'] }}</h3>
                        <p class="mt-2 text-sm text-slate-500">{{ $kpi['estado'] }}</p>
                    </article>
                @endforeach
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
