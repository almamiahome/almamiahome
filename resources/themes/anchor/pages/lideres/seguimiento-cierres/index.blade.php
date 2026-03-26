<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Catalogo;
use App\Models\Pedido;
use App\Models\User;
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
    public ?int $liderId = null;

    public array $catalogos = [];

    public Collection $lideres;

    public array $matriz = [];

    public function mount(): void
    {
        $this->catalogos = Catalogo::query()
            ->orderByDesc('anio')
            ->orderBy('numero')
            ->take(4)
            ->get(['id', 'numero', 'anio'])
            ->map(fn ($catalogo) => [
                'id' => $catalogo->id,
                'label' => "Catálogo N°{$catalogo->numero} ({$catalogo->anio})",
            ])
            ->values()
            ->all();

        $this->lideres = User::role('lider')->orderBy('name')->get(['id', 'name']);
        $this->liderId = $this->lideres->first()?->id;

        $this->construirMatriz();
    }

    public function updatedLiderId(): void
    {
        $this->construirMatriz();
    }

    protected function construirMatriz(): void
    {
        $base = collect($this->catalogos)->mapWithKeys(function (array $catalogo) {
            return [
                $catalogo['id'] => [
                    'label' => $catalogo['label'],
                    'cierres' => [
                        1 => ['unidades' => 0, 'auxiliares' => 0],
                        2 => ['unidades' => 0, 'auxiliares' => 0],
                        3 => ['unidades' => 0, 'auxiliares' => 0],
                    ],
                ],
            ];
        })->all();

        if (! $this->liderId) {
            $this->matriz = $base;

            return;
        }

        $pedidos = Pedido::query()
            ->with('cierreCampana:id,numero_cierre')
            ->selectRaw('catalogo_id, cierre_id, COALESCE(SUM(cantidad_unidades),0) as unidades, COALESCE(SUM(unidades_auxiliares),0) as auxiliares')
            ->where('lider_id', $this->liderId)
            ->whereNotNull('catalogo_id')
            ->groupBy('catalogo_id', 'cierre_id')
            ->get();

        foreach ($pedidos as $fila) {
            $catalogoId = (int) $fila->catalogo_id;
            $numeroCierre = (int) ($fila->cierreCampana?->numero_cierre ?? 0);

            if (! isset($base[$catalogoId]['cierres'][$numeroCierre])) {
                continue;
            }

            $base[$catalogoId]['cierres'][$numeroCierre] = [
                'unidades' => (int) $fila->unidades,
                'auxiliares' => (int) $fila->auxiliares,
            ];
        }

        $this->matriz = $base;
    }
};
?>

<x-layouts.app>
    @volt('lideres.seguimiento-cierres')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Seguimiento de cierres por líder"
                description="Vista operativa por catálogo y cierre (unidades y auxiliares), basada en la estructura real de planillas."
                :border="false"
            />

            <div class="max-w-md">
                <label class="block text-xs font-semibold text-slate-500">Líder</label>
                <select wire:model.live="liderId" class="mt-1 w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700">
                    @forelse($lideres as $lider)
                        <option value="{{ $lider->id }}">{{ $lider->name }}</option>
                    @empty
                        <option value="">Sin líderes cargadas</option>
                    @endforelse
                </select>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white/80 dark:bg-slate-900/70 dark:border-slate-700">
                <table class="min-w-full text-sm">
                    <thead class="bg-fuchsia-500/90 text-white">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold">Catálogo</th>
                            <th class="px-3 py-2 text-center font-semibold">Cierre 1</th>
                            <th class="px-3 py-2 text-center font-semibold">Cierre 2</th>
                            <th class="px-3 py-2 text-center font-semibold">Cierre 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matriz as $bloque)
                            <tr class="border-t border-slate-200 dark:border-slate-700">
                                <td class="px-3 py-3 font-medium text-slate-800 dark:text-slate-100">{{ $bloque['label'] }}</td>
                                @foreach([1,2,3] as $cierre)
                                    <td class="px-3 py-3 text-center text-slate-700 dark:text-slate-200">
                                        <div class="font-semibold">U: {{ $bloque['cierres'][$cierre]['unidades'] }}</div>
                                        <div class="text-xs text-slate-500">Aux: {{ $bloque['cierres'][$cierre]['auxiliares'] }}</div>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-slate-500">No hay catálogos cargados para mostrar la matriz.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
