<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;

middleware('auth');
name('resumen-coordinadoras');

new class extends Component {
    public string $startDate;

    public string $endDate;

    public string $estado = '';

    public string $zona = '';

    public array $resumen = [];

    public Collection $coordinadoras;

    public Collection $estados;

    public array $estadosDisponibles = [];

    public int $metaMonto = 75000;

    public int $metaPuntos = 8000;

    public int $metaPedidos = 80;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->estadosDisponibles = Pedido::query()
            ->whereNotNull('estado')
            ->distinct()
            ->pluck('estado')
            ->filter()
            ->values()
            ->all();

        $this->coordinadoras = collect();
        $this->estados = collect();
        $this->loadData();
    }

    public function updated($property): void
    {
        if (in_array($property, ['startDate', 'endDate', 'estado', 'zona'], true)) {
            $this->loadData();
        }
    }

    protected function filteredPedidos(): Collection
    {
        $query = Pedido::query()
            ->with(['coordinadora', 'lider'])
            ->whereNotNull('coordinadora_id');

        if ($this->startDate) {
            $query->whereDate('fecha', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('fecha', '<=', $this->endDate);
        }

        if ($this->estado !== '') {
            $query->where('estado', $this->estado);
        }

        if ($this->zona !== '') {
            $query->whereHas('coordinadora.profileKeyValues', function ($q) {
                $q->where('key', 'zona')
                    ->where('value', 'like', "%{$this->zona}%");
            });
        }

        return $query->get();
    }

    protected function loadData(): void
    {
        $pedidos = $this->filteredPedidos();

        $this->resumen = [
            'pedidos' => $pedidos->count(),
            'monto' => $pedidos->sum('total_a_pagar'),
            'puntos' => $pedidos->sum('total_puntos'),
            'unidades' => $pedidos->sum('cantidad_unidades'),
        ];

        $this->estados = $pedidos
            ->groupBy('estado')
            ->map(fn ($items, $estado) => [
                'estado' => $estado ?? 'Sin estado',
                'pedidos' => $items->count(),
                'monto' => $items->sum('total_a_pagar'),
            ])
            ->values()
            ->sortByDesc('pedidos');

        $this->coordinadoras = $pedidos
            ->groupBy('coordinadora_id')
            ->map(function ($items) {
                $coordinadora = $items->first()?->coordinadora;
                $estadoFrecuente = $items
                    ->groupBy('estado')
                    ->sortByDesc(fn ($group) => $group->count())
                    ->keys()
                    ->first();

                return [
                    'nombre' => $coordinadora?->name ?? 'Sin coordinadora',
                    'zona' => $coordinadora?->profile('zona') ?: 'Sin zona',
                    'pedidos' => $items->count(),
                    'monto' => $items->sum('total_a_pagar'),
                    'puntos' => $items->sum('total_puntos'),
                    'estado' => $estadoFrecuente ?? 'Sin estado',
                    'ultimo_pedido' => $items->max('fecha'),
                ];
            })
            ->values()
            ->sortBy('nombre');
    }

    public function exportCoordinadoras()
    {
        $filename = 'resumen-coordinadoras-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Coordinadora', 'Zona', 'Pedidos', 'Monto', 'Puntos', 'Estado más frecuente', 'Último pedido']);
            foreach ($this->coordinadoras as $fila) {
                fputcsv($handle, [
                    $fila['nombre'],
                    $fila['zona'],
                    $fila['pedidos'],
                    $fila['monto'],
                    $fila['puntos'],
                    $fila['estado'],
                    $fila['ultimo_pedido'],
                ]);
            }
            fclose($handle);
        }, $filename, $headers);
    }

    public function exportPedidos()
    {
        $pedidos = $this->filteredPedidos();
        $filename = 'pedidos-coordinadoras-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->streamDownload(function () use ($pedidos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Código', 'Coordinadora', 'Líder', 'Fecha', 'Estado', 'Monto', 'Puntos']);
            foreach ($pedidos as $pedido) {
                fputcsv($handle, [
                    $pedido->codigo_pedido,
                    $pedido->coordinadora?->name,
                    $pedido->lider?->name,
                    $pedido->fecha,
                    $pedido->estado,
                    $pedido->total_a_pagar,
                    $pedido->total_puntos,
                ]);
            }
            fclose($handle);
        }, $filename, $headers);
    }
};
?>

<x-layouts.app>
    @volt('resumen-coordinadoras')
        <x-app.container class="space-y-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <x-app.heading
                    title="Resumen de Coordinadoras"
                    description="Monitorea el desempeño de tus coordinadoras."
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
                    <div>
                        <label class="block text-xs font-semibold text-slate-500">Zona</label>
                        <input type="text" wire:model.debounce.400ms="zona" placeholder="Ej: Centro" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700" />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Pedidos</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $resumen['pedidos'] }}</p>
                    <p class="text-xs text-slate-500">Total en el período</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                        <div class="h-2 rounded-full bg-indigo-500" style="width: {{ min(100, ($resumen['pedidos'] / max(1, $metaPedidos)) * 100) }}%"></div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Meta: {{ $metaPedidos }} pedidos</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Monto</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">${{ number_format($resumen['monto'], 2, ',', '.') }}</p>
                    <p class="text-xs text-slate-500">Total facturado</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                        <div class="h-2 rounded-full bg-emerald-500" style="width: {{ min(100, ($resumen['monto'] / max(1, $metaMonto)) * 100) }}%"></div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Meta: ${{ number_format($metaMonto, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Puntos</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($resumen['puntos'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500">Acumulado</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                        <div class="h-2 rounded-full bg-amber-500" style="width: {{ min(100, ($resumen['puntos'] / max(1, $metaPuntos)) * 100) }}%"></div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Meta: {{ number_format($metaPuntos, 0, ',', '.') }} pts</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Unidades</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($resumen['unidades'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500">Cantidad total</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                        <div class="h-2 rounded-full bg-sky-500" style="width: {{ min(100, ($resumen['unidades'] / max(1, $metaPedidos * 5)) * 100) }}%"></div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Objetivo estimado</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Coordinadoras</h3>
                        <div class="flex gap-2">
                            <x-button size="sm" wire:click="exportCoordinadoras">Exportar CSV</x-button>
                            <x-button size="sm" variant="secondary" wire:click="exportPedidos">Pedidos CSV</x-button>
                        </div>
                    </div>
                    <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm dark:bg-blue-900/30 dark:border-blue-800">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Coordinadora</th>
                                    <th class="px-4 py-3 text-left">Zona</th>
                                    <th class="px-4 py-3 text-left">Pedidos</th>
                                    <th class="px-4 py-3 text-left">Monto</th>
                                    <th class="px-4 py-3 text-left">Puntos</th>
                                    <th class="px-4 py-3 text-left">Estado</th>
                                    <th class="px-4 py-3 text-left">Progreso</th>
                                    <th class="px-4 py-3 text-left">Último</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($coordinadoras as $fila)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $fila['nombre'] }}</td>
                                        <td class="px-4 py-3">{{ $fila['zona'] }}</td>
                                        <td class="px-4 py-3">{{ $fila['pedidos'] }}</td>
                                        <td class="px-4 py-3">${{ number_format($fila['monto'], 2, ',', '.') }}</td>
                                        <td class="px-4 py-3">{{ number_format($fila['puntos'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600 dark:bg-blue-800/50 dark:text-blue-100">{{ $fila['estado'] }}</span></td>
                                        <td class="px-4 py-3">
                                            <div class="h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                                                <div class="h-2 rounded-full bg-emerald-500" style="width: {{ min(100, ($fila['monto'] / max(1, $metaMonto)) * 100) }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-500">{{ $fila['ultimo_pedido'] ? Carbon::parse($fila['ultimo_pedido'])->format('d/m/Y') : 'N/D' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">No hay pedidos con los filtros seleccionados.</td>
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
                            <div class="px-4 py-3 space-y-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $estado['estado'] }}</p>
                                    <span class="px-3 py-1 text-xs font-semibold bg-slate-100 rounded-full text-slate-700 dark:bg-blue-800/60 dark:text-blue-100">{{ $estado['pedidos'] }} pedidos</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                                    <div class="h-2 rounded-full bg-indigo-500" style="width: {{ $resumen['pedidos'] > 0 ? ($estado['pedidos'] / $resumen['pedidos']) * 100 : 0 }}%"></div>
                                </div>
                                <p class="text-[11px] text-slate-500">${{ number_format($estado['monto'], 2, ',', '.') }} facturado</p>
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
