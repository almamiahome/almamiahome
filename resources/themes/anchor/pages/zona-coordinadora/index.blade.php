<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Volt\Component;

middleware('auth');
name('zona-coordinadora');

new class extends Component {
    public string $startDate;

    public string $endDate;

    public string $estado = '';

    public array $resumen = [];

    public Collection $lideres;

    public Collection $estados;

    public array $estadosDisponibles = [];

    public bool $hasCoordinadoraColumn = false;

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

        $this->lideres = collect();
        $this->estados = collect();
        $this->loadData();
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
            ->when($this->hasCoordinadoraColumn, fn ($q) => $q->where('coordinadora_id', Auth::id()))
            ->when(! $this->hasCoordinadoraColumn, fn ($q) => $q->whereNotNull('responsable_id'));

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

        $this->lideres = $pedidos
            ->groupBy('lider_id')
            ->map(function ($items) {
                $estadoFrecuente = $items
                    ->groupBy('estado')
                    ->sortByDesc(fn ($group) => $group->count())
                    ->keys()
                    ->first();

                $vendedoras = $items->groupBy('vendedora_id')->map(fn ($g) => $g->first()?->vendedora?->name ?? 'Sin nombre');

                return [
                    'nombre' => $items->first()?->lider?->name ?? 'Sin nombre',
                    'vendedoras' => $vendedoras->filter()->unique()->values(),
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

    public function exportLideres()
    {
        $filename = 'lideres-zona-coordinadora-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Líder', 'Vendedoras', 'Pedidos', 'Unidades', 'Monto', 'Estado más frecuente', 'Último pedido']);
            foreach ($this->lideres as $fila) {
                fputcsv($handle, [
                    $fila['nombre'],
                    $fila['vendedoras']->implode(' | '),
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
    @volt('zona-coordinadora')
        <x-app.container class="space-y-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <x-app.heading
                    title="Zona Coordinadora"
                    description="Organizá tu red de coordinadoras."
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
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Líderes asociados</h3>
                        <x-button size="sm" wire:click="exportLideres">Exportar CSV</x-button>
                    </div>
                    <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm dark:bg-blue-900/30 dark:border-blue-800">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Líder</th>
                                    <th class="px-4 py-3 text-left">Vendedoras</th>
                                    <th class="px-4 py-3 text-left">Pedidos</th>
                                    <th class="px-4 py-3 text-left">Unidades</th>
                                    <th class="px-4 py-3 text-left">Monto</th>
                                    <th class="px-4 py-3 text-left">Estado</th>
                                    <th class="px-4 py-3 text-left">Último pedido</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($lideres as $fila)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $fila['nombre'] }}</td>
                                        <td class="px-4 py-3 text-xs text-slate-600">{{ $fila['vendedoras']->implode(', ') }}</td>
                                        <td class="px-4 py-3">{{ $fila['pedidos'] }}</td>
                                        <td class="px-4 py-3">{{ $fila['unidades'] }}</td>
                                        <td class="px-4 py-3">${{ number_format($fila['monto'], 2, ',', '.') }}</td>
                                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600 dark:bg-blue-800/50 dark:text-blue-100">{{ $fila['estado'] }}</span></td>
                                        <td class="px-4 py-3 text-xs text-slate-500">{{ $fila['ultimo_pedido'] ? \Carbon\Carbon::parse($fila['ultimo_pedido'])->format('d/m/Y') : 'N/D' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">No hay pedidos en el período seleccionado.</td>
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
