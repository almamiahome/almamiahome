<?php

declare(strict_types=1);

use App\Models\CierreCampana;
use App\Models\MetricaLiderCampana;
use App\Models\PremioLiderCierre;
use Livewire\Volt\Component;
use function Laravel\Folio\name;

\Laravel\Folio\middleware('auth');
name('crecimiento-cierre-general.premios-lideres');

new class extends Component {
    public array $cierres = [];

    public ?int $cierreSeleccionado = null;

    public array $resultados = [];

    public function mount(): void
    {
        $this->cierres = CierreCampana::query()
            ->orderByDesc('fecha_cierre')
            ->get(['id', 'nombre', 'codigo'])
            ->map(fn (CierreCampana $cierre): array => [
                'id' => $cierre->id,
                'nombre' => $cierre->nombre ?: $cierre->codigo,
            ])
            ->all();

        $this->cierreSeleccionado = $this->cierres[0]['id'] ?? null;
        $this->cargarResultados();
    }

    public function updatedCierreSeleccionado(): void
    {
        $this->cargarResultados();
    }

    public function generarDesdeEtapa4(): void
    {
        if (! $this->cierreSeleccionado) {
            return;
        }

        $metricas = MetricaLiderCampana::query()
            ->where('cierre_campana_id', $this->cierreSeleccionado)
            ->get();

        foreach ($metricas as $metrica) {
            PremioLiderCierre::query()->updateOrCreate(
                [
                    'lider_id' => $metrica->lider_id,
                    'cierre_campana_id' => $metrica->cierre_campana_id,
                ],
                [
                    'rango_lider_id' => $metrica->rango_lider_id,
                    'metrica_lider_campana_id' => $metrica->id,
                    'premio_actividad' => (float) $metrica->premio_actividad,
                    'premio_retencion' => 0,
                    'premio_altas' => (float) $metrica->premio_altas,
                    'premio_cobranza' => (float) $metrica->premio_cobranzas,
                    'premio_crecimiento' => (float) $metrica->premio_crecimiento,
                    'premio_reparto' => (float) $metrica->monto_reparto_total,
                    'premio_plus_crecimiento' => 0,
                    'premio_unidades' => (float) $metrica->premio_unidades,
                    'premio_total' => (float) $metrica->premio_total,
                    'detalle' => [
                        'origen' => 'etapa4.metricas_lider_campana',
                        'metrica_id' => $metrica->id,
                    ],
                ]
            );
        }

        $this->cargarResultados();
        session()->flash('status', 'Etapa 5 iniciada: premios consolidados desde la etapa 4.');
    }

    protected function cargarResultados(): void
    {
        if (! $this->cierreSeleccionado) {
            $this->resultados = [];

            return;
        }

        $this->resultados = PremioLiderCierre::query()
            ->where('cierre_campana_id', $this->cierreSeleccionado)
            ->with(['lider:id,name'])
            ->orderByDesc('premio_total')
            ->get()
            ->map(fn (PremioLiderCierre $premio): array => [
                'lider' => $premio->lider?->name ?? 'Sin líder',
                'actividad' => (float) $premio->premio_actividad,
                'altas' => (float) $premio->premio_altas,
                'cobranza' => (float) $premio->premio_cobranza,
                'crecimiento' => (float) $premio->premio_crecimiento,
                'reparto' => (float) $premio->premio_reparto,
                'unidades' => (float) $premio->premio_unidades,
                'total' => (float) $premio->premio_total,
            ])
            ->all();
    }
};
?>

<x-layouts.app>
    @volt('crecimiento-cierre-general.premios-lideres')
        <x-app.container>
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Version2 · Etapa 5 (inicio)</h1>
                    <p class="text-sm text-slate-600 mt-1">Consolidación de premios de líderes por cierre en <code>premio_lider_cierre</code>.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <select wire:model.live="cierreSeleccionado" class="rounded-lg border-slate-300 text-sm">
                        @foreach ($cierres as $cierre)
                            <option value="{{ $cierre['id'] }}">{{ $cierre['nombre'] }}</option>
                        @endforeach
                    </select>
                    <x-button wire:click="generarDesdeEtapa4" color="primary">Generar desde etapa 4</x-button>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Líder</th>
                            <th class="px-4 py-3 text-right">Actividad</th>
                            <th class="px-4 py-3 text-right">Altas</th>
                            <th class="px-4 py-3 text-right">Cobranza</th>
                            <th class="px-4 py-3 text-right">Crecimiento</th>
                            <th class="px-4 py-3 text-right">Reparto</th>
                            <th class="px-4 py-3 text-right">Unidades</th>
                            <th class="px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($resultados as $row)
                            <tr>
                                <td class="px-4 py-3">{{ $row['lider'] }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($row['actividad'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($row['altas'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($row['cobranza'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($row['crecimiento'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($row['reparto'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($row['unidades'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold">${{ number_format($row['total'], 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-slate-500">
                                    Aún no hay premios consolidados para el cierre seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
