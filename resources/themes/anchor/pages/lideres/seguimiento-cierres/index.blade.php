<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Catalogo;
use App\Models\CierreCampana;
use App\Models\Departamento;
use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;

middleware([
    'auth',
    function ($request, $next) {
        if (! $request->user() || ! $request->user()->can('view_backend')) {
            abort(403, 'No tiene permisos para acceder al seguimiento de cierres.');
        }

        return $next($request);
    },
]);

name('lideres.seguimiento-cierres');

new class extends Component {
    public ?int $zonaId = null;

    public ?int $departamentoId = null;

    public ?int $catalogoId = null;

    public ?int $cierreId = null;

    public ?int $liderId = null;

    public array $zonas = [];

    public array $departamentos = [];

    public array $catalogos = [];

    public array $cierres = [];

    public Collection $lideres;

    public array $desglosePedidos = [];

    public array $kpis = [
        'actividad' => 0,
        'crecimiento' => 0,
        'plus' => 0,
        'unidades' => 0,
        'total' => 0,
    ];

    public function mount(): void
    {
        $this->zonas = Zona::query()
            ->where('activa', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre'])
            ->map(fn ($zona) => ['id' => $zona->id, 'label' => $zona->nombre])
            ->values()
            ->all();

        $this->catalogos = Catalogo::query()
            ->orderByDesc('anio')
            ->orderByDesc('numero')
            ->take(8)
            ->get(['id', 'numero', 'anio'])
            ->map(fn ($catalogo) => [
                'id' => $catalogo->id,
                'label' => "Catálogo N°{$catalogo->numero} ({$catalogo->anio})",
            ])
            ->values()
            ->all();

        $this->catalogoId = $this->catalogos[0]['id'] ?? null;

        $this->hidratarDepartamentos();
        $this->hidratarCierres();
        $this->hidratarLideres();
        $this->cargarPanel();
    }

    public function updatedZonaId(): void
    {
        $this->departamentoId = null;
        $this->hidratarDepartamentos();
        $this->hidratarLideres();
        $this->cargarPanel();
    }

    public function updatedDepartamentoId(): void
    {
        $this->hidratarLideres();
        $this->cargarPanel();
    }

    public function updatedCatalogoId(): void
    {
        $this->hidratarCierres();
        $this->cargarPanel();
    }

    public function updatedCierreId(): void
    {
        $this->cargarPanel();
    }

    public function updatedLiderId(): void
    {
        $this->cargarPanel();
    }

    protected function hidratarDepartamentos(): void
    {
        $query = Departamento::query()->where('activo', true);

        if ($this->zonaId) {
            $query->where('zona_id', $this->zonaId);
        }

        $this->departamentos = $query
            ->orderBy('nombre')
            ->get(['id', 'nombre'])
            ->map(fn ($departamento) => ['id' => $departamento->id, 'label' => $departamento->nombre])
            ->values()
            ->all();
    }

    protected function hidratarCierres(): void
    {
        $query = CierreCampana::query()
            ->when($this->catalogoId, fn ($builder) => $builder->where('catalogo_id', $this->catalogoId))
            ->orderByDesc('catalogo_id')
            ->orderBy('numero_cierre');

        $this->cierres = $query
            ->get(['id', 'numero_cierre', 'codigo'])
            ->map(fn ($cierre) => [
                'id' => $cierre->id,
                'label' => "Cierre {$cierre->numero_cierre} · {$cierre->codigo}",
            ])
            ->values()
            ->all();

        $idsValidos = collect($this->cierres)->pluck('id')->all();
        if (! in_array($this->cierreId, $idsValidos, true)) {
            $this->cierreId = $this->cierres[0]['id'] ?? null;
        }
    }

    protected function hidratarLideres(): void
    {
        $query = User::role('lider')->orderBy('name');

        if ($this->zonaId) {
            $query->where('zona_id', $this->zonaId);
        }

        if ($this->departamentoId) {
            $query->where('departamento_id', $this->departamentoId);
        }

        $this->lideres = $query->get(['id', 'name']);
        if (! $this->lideres->contains('id', $this->liderId)) {
            $this->liderId = $this->lideres->first()?->id;
        }
    }

    protected function cargarPanel(): void
    {
        $this->desglosePedidos = [];
        $this->kpis = [
            'actividad' => 0,
            'crecimiento' => 0,
            'plus' => 0,
            'unidades' => 0,
            'total' => 0,
        ];

        if (! $this->liderId || ! $this->catalogoId || ! $this->cierreId) {
            return;
        }

        $pedidos = Pedido::query()
            ->with(['vendedora:id,name', 'cierreCampana:id,numero_cierre,codigo'])
            ->where('lider_id', $this->liderId)
            ->where('catalogo_id', $this->catalogoId)
            ->where('cierre_id', $this->cierreId)
            ->selectRaw('vendedora_id, catalogo_id, cierre_id, COUNT(*) as cantidad_pedidos, COALESCE(SUM(cantidad_unidades),0) as unidades, COALESCE(SUM(unidades_auxiliares),0) as auxiliares, COALESCE(SUM(total_a_pagar),0) as total_a_pagar')
            ->groupBy('vendedora_id', 'catalogo_id', 'cierre_id')
            ->orderByDesc('unidades')
            ->get();

        $this->desglosePedidos = $pedidos->map(function ($fila) {
            return [
                'vendedora' => $fila->vendedora?->name ?? 'Sin vendedora',
                'cantidad_pedidos' => (int) $fila->cantidad_pedidos,
                'unidades' => (int) $fila->unidades,
                'auxiliares' => (int) $fila->auxiliares,
                'total_a_pagar' => (float) $fila->total_a_pagar,
                'cierre' => $fila->cierreCampana?->codigo,
            ];
        })->values()->all();

        $metrica = MetricaLiderCampana::query()
            ->where('lider_id', $this->liderId)
            ->where('cierre_campana_id', $this->cierreId)
            ->first();

        if (! $metrica) {
            return;
        }

        $actividad = (float) ($metrica->premio_actividad ?? 0);
        $crecimiento = (float) ($metrica->premio_crecimiento ?? 0);
        $plus = (float) ($metrica->premio_plus_crecimiento ?? 0);
        $unidades = (float) ($metrica->premio_unidades ?? 0);
        $total = (float) ($metrica->premio_total ?? ($actividad + $crecimiento + $plus + $unidades));

        $this->kpis = compact('actividad', 'crecimiento', 'plus', 'unidades', 'total');
    }
};
?>

<x-layouts.app>
    @volt('lideres.seguimiento-cierres')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Seguimiento operativo de cierres"
                description="Filtros por zona, departamento, catálogo y cierre con KPIs de liderazgo y desglose operativo por vendedora."
                :border="false"
            />

            <div class="grid gap-4 rounded-2xl border border-white/50 bg-white/40 p-4 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40 md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-500">Zona</label>
                    <select wire:model.live="zonaId" class="mt-1 w-full rounded-xl border-white/60 bg-white/70 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                        <option value="">Todas</option>
                        @foreach($zonas as $zona)
                            <option value="{{ $zona['id'] }}">{{ $zona['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500">Departamento</label>
                    <select wire:model.live="departamentoId" class="mt-1 w-full rounded-xl border-white/60 bg-white/70 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                        <option value="">Todos</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento['id'] }}">{{ $departamento['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500">Catálogo</label>
                    <select wire:model.live="catalogoId" class="mt-1 w-full rounded-xl border-white/60 bg-white/70 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                        @foreach($catalogos as $catalogo)
                            <option value="{{ $catalogo['id'] }}">{{ $catalogo['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500">Cierre</label>
                    <select wire:model.live="cierreId" class="mt-1 w-full rounded-xl border-white/60 bg-white/70 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                        @foreach($cierres as $cierre)
                            <option value="{{ $cierre['id'] }}">{{ $cierre['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500">Líder</label>
                    <select wire:model.live="liderId" class="mt-1 w-full rounded-xl border-white/60 bg-white/70 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                        @forelse($lideres as $lider)
                            <option value="{{ $lider->id }}">{{ $lider->name }}</option>
                        @empty
                            <option value="">Sin líderes disponibles</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                @foreach([
                    'actividad' => 'KPI Actividad',
                    'crecimiento' => 'KPI Crecimiento',
                    'plus' => 'KPI Plus',
                    'unidades' => 'KPI Unidades',
                    'total' => 'KPI Total',
                ] as $clave => $titulo)
                    <div class="rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                        <p class="text-xs uppercase tracking-wide text-slate-500">{{ $titulo }}</p>
                        <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-slate-100">${{ number_format($kpis[$clave] ?? 0, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>

            <div class="overflow-x-auto rounded-2xl border border-white/50 bg-white/50 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                <table class="min-w-full text-sm">
                    <thead class="bg-fuchsia-500/90 text-white">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold">Vendedora</th>
                            <th class="px-3 py-2 text-center font-semibold">Pedidos</th>
                            <th class="px-3 py-2 text-center font-semibold">Unidades</th>
                            <th class="px-3 py-2 text-center font-semibold">Auxiliares</th>
                            <th class="px-3 py-2 text-right font-semibold">Total a pagar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($desglosePedidos as $fila)
                            <tr class="border-t border-slate-200/70 dark:border-slate-700/80">
                                <td class="px-3 py-3 font-medium text-slate-800 dark:text-slate-100">{{ $fila['vendedora'] }}</td>
                                <td class="px-3 py-3 text-center">{{ $fila['cantidad_pedidos'] }}</td>
                                <td class="px-3 py-3 text-center">{{ $fila['unidades'] }}</td>
                                <td class="px-3 py-3 text-center">{{ $fila['auxiliares'] }}</td>
                                <td class="px-3 py-3 text-right">${{ number_format($fila['total_a_pagar'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-5 text-center text-slate-500">No hay pedidos para la combinación seleccionada de filtros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
