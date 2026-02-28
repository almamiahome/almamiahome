<?php

use function Laravel\Folio\{middleware, name};
use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Volt\Component;

?>


        <x-app.container class="space-y-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <x-app.heading
                    title="Zona Líder"
                    description="Gestioná tu espacio como líder."
                    :border="false"
                />
                <div class="flex flex-wrap gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500">Desde</label>
                        <input type="date" wire:model.debounce.300ms="startDate" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500">Hasta</label>
                        <input type="date" wire:model.debounce.300ms="endDate" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500">Estado</label>
                        <select wire:model="estado" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700">
                            <option value="">Todos</option>
                            @foreach($estadosDisponibles as $value)
                                <option value="{{ $value }}">{{ ucfirst($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @if(!empty($premios))
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Campaña</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $premios['campana'] }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">Rango actual: {{ $premios['rango'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500">Premio total</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">${{ number_format($premios['premio_total'], 0, ',', '.') }}</p>
                            @if($premios['premio_crecimiento'] > 0)
                                <p class="text-xs text-emerald-600">Incluye crecimiento: ${{ number_format($premios['premio_crecimiento'], 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-3">
                        <div class="space-y-1">
                            <p class="text-xs text-slate-500">Revendedoras activas</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $premios['revendedoras_activas'] }}</p>
                            <p class="text-xs text-slate-500">Unidades: {{ $premios['unidades'] }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-slate-500">Cobranzas</p>
                            @if($premios['cobranzas_ok'])
                                <span class="px-3 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">Pago dentro de los 7 días</span>
                            @else
                                <span class="px-3 py-1 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">Pago fuera de ventana</span>
                            @endif
                            <p class="text-xs text-slate-500">Fecha de pago: {{ $premios['fecha_pago_equipo'] ?? 'Sin registrar' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-slate-500">Reparto 1C/2C/3C</p>
                            <p class="text-sm text-slate-700 dark:text-slate-200">1C: {{ $premios['repartos']['1c'] }} • 2C: {{ $premios['repartos']['2c'] }} • 3C: {{ $premios['repartos']['3c'] }}</p>
                            <p class="text-xs text-slate-500">Total reparto: ${{ number_format($premios['repartos']['monto'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Altas del mes</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $premios['altas_mes'] }} altas</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($premios['altas_pagadas'] as $pago)
                                <span class="px-3 py-1 text-[10px] rounded-full bg-indigo-50 text-indigo-700">Cuota {{ $pago['cuota'] }}: ${{ number_format($pago['monto_pagado'], 0, ',', '.') }} ({{ $pago['cierre_codigo'] }})</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-600 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-200">
                    Aún no registramos métricas de campaña para tu red. Cuando cierres tu próxima campaña verás aquí tus revendedoras activas, cobranzas, altas y repartos.
                </div>
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Pedidos</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $resumen['pedidos'] }}</p>
                    <p class="text-xs text-slate-500">Total en el período</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Unidades</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($resumen['unidades'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500">Cantidad total vendida</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Monto</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">${{ number_format($resumen['monto'], 2, ',', '.') }}</p>
                    <p class="text-xs text-slate-500">Total facturado</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Vendedoras asociadas</h3>
                        <x-button size="sm" wire:click="exportVendedoras">Exportar CSV</x-button>
                    </div>
                    <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm dark:bg-blue-900/30 dark:border-blue-800">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Vendedora</th>
                                    <th class="px-4 py-3 text-left">Pedidos</th>
                                    <th class="px-4 py-3 text-left">Unidades</th>
                                    <th class="px-4 py-3 text-left">Monto</th>
                                    <th class="px-4 py-3 text-left">Estado</th>
                                    <th class="px-4 py-3 text-left">Último pedido</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($vendedoras as $fila)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $fila['nombre'] }}</td>
                                        <td class="px-4 py-3">{{ $fila['pedidos'] }}</td>
                                        <td class="px-4 py-3">{{ $fila['unidades'] }}</td>
                                        <td class="px-4 py-3">${{ number_format($fila['monto'], 2, ',', '.') }}</td>
                                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600 dark:bg-blue-800/50 dark:text-blue-100">{{ $fila['estado'] }}</span></td>
                                        <td class="px-4 py-3 text-xs text-slate-500">{{ $fila['ultimo_pedido'] ? \Carbon\Carbon::parse($fila['ultimo_pedido'])->format('d/m/Y') : 'N/D' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">No hay pedidos en el período seleccionado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Estados del período</h3>
                    <div class="bg-white border rounded-2xl shadow-sm divide-y divide-slate-100 dark:bg-blue-900/30 dark:border-blue-800">
                        @forelse($estados as $estado)
                            <div class="flex items-center justify-between px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $estado['estado'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $estado['unidades'] }} unidades</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold bg-slate-100 rounded-full text-slate-700 dark:bg-blue-800/60 dark:text-blue-100">{{ $estado['pedidos'] }} pedidos</span>
                            </div>
                        @empty
                            <p class="px-4 py-6 text-center text-sm text-slate-500">Sin datos para mostrar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-app.container>
    