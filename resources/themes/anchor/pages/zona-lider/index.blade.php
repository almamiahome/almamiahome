<?php

use function Laravel\Folio\{middleware, name};
use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Volt\Component;

middleware('auth');
name('zona-lider');

new class extends Component {
    public string $startDate;

    public string $endDate;

    public string $estado = '';

    public array $resumen = [];

    public Collection $vendedoras;

    public Collection $estados;

    public array $estadosDisponibles = [];

    public bool $hasCoordinadoraColumn = false;

    public array $premios = [];

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->hasCoordinadoraColumn = Schema::hasColumn('pedidos', 'coordinadora_id');
        $this->estadosDisponibles = Pedido::query()
            ->whereNotNull('estado')
            ->distinct()
            ->pluck('estado')
            ->filter()
            ->values()
            ->all();

        $this->vendedoras = collect();
        $this->estados = collect();
        $this->loadData();
        $this->loadMetricaCampana();
    }

    public function updated($property): void
    {
        if (in_array($property, ['startDate', 'endDate', 'estado'], true)) {
            $this->loadData();
        }
    }

    protected function filteredPedidos(): Collection
    {
        $query = Pedido::query()
            ->with(['vendedora', 'lider'])
            ->whereNotNull('vendedora_id')
            ->whereNotNull('lider_id')
            ->when($this->hasCoordinadoraColumn, fn ($q) => $q->addSelect('coordinadora_id'))
            ->where('lider_id', Auth::id());

        if ($this->startDate) {
            $query->whereDate('fecha', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('fecha', '<=', $this->endDate);
        }

        if ($this->estado !== '') {
            $query->where('estado', $this->estado);
        }

        return $query->get();
    }

    protected function loadData(): void
    {
        $pedidos = $this->filteredPedidos();

        $this->resumen = [
            'pedidos' => $pedidos->count(),
            'unidades' => $pedidos->sum('cantidad_unidades'),
            'monto' => $pedidos->sum('total_a_pagar'),
        ];

        $this->estados = $pedidos
            ->groupBy('estado')
            ->map(fn ($items, $estado) => [
                'estado' => $estado ?? 'Sin estado',
                'pedidos' => $items->count(),
                'unidades' => $items->sum('cantidad_unidades'),
            ])
            ->values()
            ->sortByDesc('pedidos');

        $this->vendedoras = $pedidos
            ->groupBy('vendedora_id')
            ->map(function ($items) {
                $estadoFrecuente = $items
                    ->groupBy('estado')
                    ->sortByDesc(fn ($group) => $group->count())
                    ->keys()
                    ->first();

                return [
                    'nombre' => $items->first()?->vendedora?->name ?? 'Sin nombre',
                    'pedidos' => $items->count(),
                    'unidades' => $items->sum('cantidad_unidades'),
                    'monto' => $items->sum('total_a_pagar'),
                    'estado' => $estadoFrecuente ?? 'Sin estado',
                    'ultimo_pedido' => $items->max('fecha'),
                ];
            })
            ->values()
            ->sortBy('nombre');
    }

    protected function loadMetricaCampana(): void
    {
        $metrica = MetricaLiderCampana::with(['rangoLider', 'cierreCampana'])
            ->where('lider_id', Auth::id())
            ->latest('created_at')
            ->first();

        if (! $metrica) {
            $this->premios = [];

            return;
        }

        $this->premios = [
            'campana' => $metrica->cierreCampana?->nombre ?? 'Sin campaña',
            'rango' => $metrica->rangoLider?->nombre ?? 'Sin rango',
            'revendedoras_activas' => data_get($metrica->datos, 'revendedoras_activas', 0),
            'unidades' => data_get($metrica->datos, 'unidades', 0),
            'cobranzas_ok' => (bool) $metrica->cobranzas_ok,
            'fecha_pago_equipo' => $metrica->fecha_pago_equipo?->format('d/m/Y'),
            'altas_mes' => data_get($metrica->datos, 'altas_mes', 0),
            'altas_pagadas' => $metrica->altas_pagadas_en_cierre ?? [],
            'repartos' => [
                '1c' => $metrica->cantidad_1c,
                '2c' => $metrica->cantidad_2c,
                '3c' => $metrica->cantidad_3c,
                'monto' => $metrica->monto_reparto_total,
            ],
            'premio_crecimiento' => $metrica->premio_crecimiento,
            'premio_total' => $metrica->premio_total,
        ];
    }

    public function exportVendedoras()
    {
        $filename = 'vendedoras-zona-lider-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Vendedora', 'Pedidos', 'Unidades', 'Monto', 'Estado más frecuente', 'Último pedido']);
            foreach ($this->vendedoras as $fila) {
                fputcsv($handle, [
                    $fila['nombre'],
                    $fila['pedidos'],
                    $fila['unidades'],
                    $fila['monto'],
                    $fila['estado'],
                    $fila['ultimo_pedido'],
                ]);
            }
            fclose($handle);
        }, $filename, $headers);
    }
};
?>

<x-layouts.app>
    @volt('zona-lider')
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
    @endvolt
</x-layouts.app>
