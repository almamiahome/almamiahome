<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;

middleware('auth');
name('lideres.filtros');

new class extends Component {
    public array $filtros = [
        ['campo' => 'zona', 'estado' => 'pendiente'],
        ['campo' => 'departamento', 'estado' => 'pendiente'],
        ['campo' => 'cierre', 'estado' => 'base disponible'],
    ];
};
?>

<x-layouts.app>
    @volt('lideres.filtros')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Líderes · Filtros de gestión (Etapa 5)"
                description="Estructura inicial para filtros por zona/departamento y comparativas por cierre."
                :border="false"
            />

            <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Filtro</th>
                            <th class="px-4 py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filtros as $filtro)
                            <tr class="border-t">
                                <td class="px-4 py-3 capitalize">{{ $filtro['campo'] }}</td>
                                <td class="px-4 py-3">{{ $filtro['estado'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
