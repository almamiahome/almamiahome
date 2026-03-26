<?php

declare(strict_types=1);

use App\Models\MetricaLiderCampana;
use Livewire\Volt\Component;
use function Laravel\Folio\name;

\Laravel\Folio\middleware('auth');
name('crecimiento-cierre-general.actividad-equipo');

new class extends Component {
    public array $metricas = [];

    public function mount(): void
    {
        $this->metricas = MetricaLiderCampana::query()
            ->with(['lider:id,name', 'cierreCampana:id,nombre,codigo'])
            ->latest()
            ->limit(20)
            ->get()
            ->map(function (MetricaLiderCampana $metrica): array {
                return [
                    'lider' => $metrica->lider?->name ?? 'Sin líder',
                    'cierre' => $metrica->cierreCampana?->nombre ?? $metrica->cierreCampana?->codigo ?? 'Sin cierre',
                    'actividad_ok' => $metrica->actividad_ok,
                    'altas_ok' => $metrica->altas_ok,
                    'unidades_ok' => $metrica->unidades_ok,
                    'cobranzas_ok' => $metrica->cobranzas_ok,
                    'crecimiento_ok' => $metrica->crecimiento_ok,
                    'premio_total' => (float) $metrica->premio_total,
                    'actualizado' => optional($metrica->updated_at)->format('d/m/Y H:i'),
                ];
            })
            ->all();
    }
};
?>

<x-layouts.app>
    @volt('crecimiento-cierre-general.actividad-equipo')
        <x-app.container>
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900">Version2 · Etapa 4 verificada</h1>
                <p class="text-sm text-slate-600 mt-1">Resumen de actividad por líder/cierre usando la tabla <code>metricas_lider_campana</code>.</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Líder</th>
                            <th class="px-4 py-3 text-left">Cierre</th>
                            <th class="px-4 py-3 text-center">Actividad</th>
                            <th class="px-4 py-3 text-center">Altas</th>
                            <th class="px-4 py-3 text-center">Unidades</th>
                            <th class="px-4 py-3 text-center">Cobranzas</th>
                            <th class="px-4 py-3 text-center">Crecimiento</th>
                            <th class="px-4 py-3 text-right">Premio total</th>
                            <th class="px-4 py-3 text-left">Actualizado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($metricas as $metrica)
                            <tr>
                                <td class="px-4 py-3">{{ $metrica['lider'] }}</td>
                                <td class="px-4 py-3">{{ $metrica['cierre'] }}</td>
                                <td class="px-4 py-3 text-center">{{ $metrica['actividad_ok'] ? '✅' : '—' }}</td>
                                <td class="px-4 py-3 text-center">{{ $metrica['altas_ok'] ? '✅' : '—' }}</td>
                                <td class="px-4 py-3 text-center">{{ $metrica['unidades_ok'] ? '✅' : '—' }}</td>
                                <td class="px-4 py-3 text-center">{{ $metrica['cobranzas_ok'] ? '✅' : '—' }}</td>
                                <td class="px-4 py-3 text-center">{{ $metrica['crecimiento_ok'] ? '✅' : '—' }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($metrica['premio_total'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $metrica['actualizado'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-slate-500">No hay métricas cargadas todavía.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
