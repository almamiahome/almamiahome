<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Catalogo;
use App\Models\CierreCampana;
use App\Models\MetricaLiderCampana;
use App\Models\User;
use Livewire\Volt\Component;

middleware([
    'auth',
    function ($request, $next) {
        if (! $request->user() || ! $request->user()->can('view_backend')) {
            abort(403, 'No tiene permisos para acceder a la liquidación de líderes.');
        }

        return $next($request);
    },
]);

name('lideres.liquidacion');

new class extends Component {
    public ?int $liderId = null;

    public ?int $catalogoId = null;

    public ?int $cierreIdDesde = null;

    public ?int $cierreIdHasta = null;

    public $lideres;

    public array $catalogos = [];

    public array $cierres = [];

    public array $resumen = [
        'actividad' => 0,
        'crecimiento' => 0,
        'plus' => 0,
        'unidades' => 0,
        'total' => 0,
    ];

    public array $detalleCierres = [];

    public function mount(): void
    {
        $this->lideres = User::role('lider')->orderBy('name')->get(['id', 'name']);
        $this->liderId = $this->lideres->first()?->id;

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

        $this->hidratarCierres();
        $this->cargarResumen();
    }

    public function updatedLiderId(): void
    {
        $this->cargarResumen();
    }

    public function updatedCatalogoId(): void
    {
        $this->hidratarCierres();
        $this->cargarResumen();
    }

    public function updatedCierreIdDesde(): void
    {
        $this->cargarResumen();
    }

    public function updatedCierreIdHasta(): void
    {
        $this->cargarResumen();
    }

    protected function hidratarCierres(): void
    {
        $this->cierres = CierreCampana::query()
            ->where('catalogo_id', $this->catalogoId)
            ->orderBy('numero_cierre')
            ->get(['id', 'numero_cierre', 'codigo'])
            ->map(fn ($cierre) => [
                'id' => $cierre->id,
                'label' => "Cierre {$cierre->numero_cierre} · {$cierre->codigo}",
            ])
            ->values()
            ->all();

        $primerId = $this->cierres[0]['id'] ?? null;
        $ultimoId = collect($this->cierres)->last()['id'] ?? null;

        $idsValidos = collect($this->cierres)->pluck('id')->all();

        if (! in_array($this->cierreIdDesde, $idsValidos, true)) {
            $this->cierreIdDesde = $primerId;
        }

        if (! in_array($this->cierreIdHasta, $idsValidos, true)) {
            $this->cierreIdHasta = $ultimoId;
        }
    }

    protected function cargarResumen(): void
    {
        $this->resumen = [
            'actividad' => 0,
            'crecimiento' => 0,
            'plus' => 0,
            'unidades' => 0,
            'total' => 0,
        ];
        $this->detalleCierres = [];

        if (! $this->liderId || ! $this->cierreIdDesde || ! $this->cierreIdHasta) {
            return;
        }

        $cierresSeleccionados = CierreCampana::query()
            ->where('catalogo_id', $this->catalogoId)
            ->whereBetween('id', [min($this->cierreIdDesde, $this->cierreIdHasta), max($this->cierreIdDesde, $this->cierreIdHasta)])
            ->pluck('id');

        if ($cierresSeleccionados->isEmpty()) {
            return;
        }

        $metricas = MetricaLiderCampana::query()
            ->with('cierreCampana:id,codigo,numero_cierre')
            ->where('lider_id', $this->liderId)
            ->whereIn('cierre_campana_id', $cierresSeleccionados)
            ->orderBy('cierre_campana_id')
            ->get();

        if ($metricas->isEmpty()) {
            return;
        }

        $this->resumen = [
            'actividad' => (float) $metricas->sum('premio_actividad'),
            'crecimiento' => (float) $metricas->sum('premio_crecimiento'),
            'plus' => (float) $metricas->sum('premio_plus_crecimiento'),
            'unidades' => (float) $metricas->sum('premio_unidades'),
            'total' => (float) $metricas->sum('premio_total'),
        ];

        $this->detalleCierres = $metricas->map(fn ($metrica) => [
            'cierre' => $metrica->cierreCampana?->codigo ?? "ID {$metrica->cierre_campana_id}",
            'actividad' => (float) ($metrica->premio_actividad ?? 0),
            'crecimiento' => (float) ($metrica->premio_crecimiento ?? 0),
            'plus' => (float) ($metrica->premio_plus_crecimiento ?? 0),
            'unidades' => (float) ($metrica->premio_unidades ?? 0),
            'total' => (float) ($metrica->premio_total ?? 0),
        ])->values()->all();
    }
};
?>

<x-layouts.app>
    @volt('lideres.liquidacion')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Liquidación auditable por cierre"
                description="Selección explícita de catálogo y rango de cierres para consolidar KPIs operativos de Etapa 5."
                :border="false"
            />

            <div class="grid gap-4 rounded-2xl border border-white/50 bg-white/40 p-4 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40 md:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500">Líder</label>
                    <select wire:model.live="liderId" class="mt-1 w-full rounded-xl border-white/60 bg-white/70 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                        @forelse($lideres as $lider)
                            <option value="{{ $lider->id }}">{{ $lider->name }}</option>
                        @empty
                            <option value="">Sin líderes cargadas</option>
                        @endforelse
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
                    <label class="block text-xs font-semibold text-slate-500">Cierre desde</label>
                    <select wire:model.live="cierreIdDesde" class="mt-1 w-full rounded-xl border-white/60 bg-white/70 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                        @foreach($cierres as $cierre)
                            <option value="{{ $cierre['id'] }}">{{ $cierre['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500">Cierre hasta</label>
                    <select wire:model.live="cierreIdHasta" class="mt-1 w-full rounded-xl border-white/60 bg-white/70 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                        @foreach($cierres as $cierre)
                            <option value="{{ $cierre['id'] }}">{{ $cierre['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                @foreach([
                    'actividad' => 'Actividad',
                    'crecimiento' => 'Crecimiento',
                    'plus' => 'Plus crecimiento',
                    'unidades' => 'Unidades',
                    'total' => 'Total a cobrar',
                ] as $clave => $titulo)
                    <div class="rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                        <p class="text-xs uppercase tracking-wide text-slate-500">{{ $titulo }}</p>
                        <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-slate-100">${{ number_format($resumen[$clave] ?? 0, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>

            <div class="overflow-hidden rounded-2xl border border-white/50 bg-white/50 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-900 text-white">
                        <tr>
                            <th class="px-3 py-2 text-left">Cierre</th>
                            <th class="px-3 py-2 text-right">Actividad</th>
                            <th class="px-3 py-2 text-right">Crecimiento</th>
                            <th class="px-3 py-2 text-right">Plus</th>
                            <th class="px-3 py-2 text-right">Unidades</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($detalleCierres as $fila)
                            <tr>
                                <td class="px-3 py-2 font-medium">{{ $fila['cierre'] }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format($fila['actividad'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format($fila['crecimiento'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format($fila['plus'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format($fila['unidades'], 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right font-semibold">${{ number_format($fila['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-slate-500">No hay métricas de liquidación para los cierres seleccionados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
