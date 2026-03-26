<?php

use App\Models\CierreCampana;
use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

middleware([
    'auth',
    function ($request, $next) {
        if (! $request->user() || ! $request->user()->hasRole('admin')) {
            abort(403, 'No tiene permisos para acceder a este módulo.');
        }

        return $next($request);
    },
]);

name('admin-ui-v2.seguimiento-lideres');

new class extends Component {
    public array $resumen = [];

    public array $lideres = [];

    public ?string $campana = null;

    public function mount(): void
    {
        $ahora = Carbon::now();
        $inicioMes = $ahora->copy()->startOfMonth()->toDateString();

        $pedidosBase = Pedido::query()
            ->whereNotNull('lider_id')
            ->whereDate('fecha', '>=', $inicioMes)
            ->whereDate('fecha', '<=', $ahora->toDateString());

        $cierreActual = CierreCampana::query()->latest('id')->first();
        $this->campana = $cierreActual?->nombre ?? 'Sin campaña activa';

        $this->resumen = [
            'lideres_activos' => User::role('lider')->count(),
            'pedidos_mes' => (clone $pedidosBase)->count(),
            'unidades_mes' => (int) (clone $pedidosBase)->sum('cantidad_unidades'),
            'monto_mes' => (float) (clone $pedidosBase)->sum('total_a_pagar'),
        ];

        $metricas = MetricaLiderCampana::query()
            ->with(['lider:id,name'])
            ->when($cierreActual, fn ($query) => $query->where('cierre_campana_id', $cierreActual->id))
            ->orderByDesc('premio_total')
            ->limit(8)
            ->get();

        $this->lideres = $metricas->map(function (MetricaLiderCampana $metrica) {
            return [
                'nombre' => $metrica->lider?->name ?? 'Sin líder',
                'actividad' => (int) ($metrica->cantidad_1c + $metrica->cantidad_2c + $metrica->cantidad_3c),
                'unidades' => (int) $metrica->unidades_ok,
                'premio_total' => (float) $metrica->premio_total,
                'objetivo' => (int) ($metrica->objetivo_proximo_cierre ?? 0),
                'crecimiento_ok' => (bool) $metrica->crecimiento_ok,
            ];
        })->all();
    }
};
?>

<x-layouts.app>
    @volt('admin-ui-v2.seguimiento-lideres')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="UI V2 · Seguimiento de líderes"
                description="Vista operativa basada en actividad real del mes y métricas del cierre vigente."
                :border="false"
            />

            <div class="rounded-3xl border border-white/40 bg-white/45 p-5 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/45">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-300">Campaña en foco</p>
                <p class="mt-1 text-2xl font-bold text-slate-800 dark:text-white">{{ $campana }}</p>
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-white/40 bg-white/45 p-4 backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs text-slate-500 dark:text-slate-300">Líderes activos</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($resumen['lideres_activos']) }}</p>
                </div>
                <div class="rounded-2xl border border-white/40 bg-white/45 p-4 backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs text-slate-500 dark:text-slate-300">Pedidos del mes</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($resumen['pedidos_mes']) }}</p>
                </div>
                <div class="rounded-2xl border border-white/40 bg-white/45 p-4 backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs text-slate-500 dark:text-slate-300">Unidades del mes</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($resumen['unidades_mes']) }}</p>
                </div>
                <div class="rounded-2xl border border-white/40 bg-white/45 p-4 backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs text-slate-500 dark:text-slate-300">Monto del mes</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">${{ number_format($resumen['monto_mes'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-white/40 bg-white/45 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/45">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-white/50 dark:bg-slate-800/50">
                            <tr class="text-left text-slate-600 dark:text-slate-200">
                                <th class="px-4 py-3">Líder</th>
                                <th class="px-4 py-3">Actividad</th>
                                <th class="px-4 py-3">Meta próximo cierre</th>
                                <th class="px-4 py-3">Premio estimado</th>
                                <th class="px-4 py-3">Crecimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lideres as $fila)
                                <tr class="border-t border-white/40 text-slate-700 dark:border-white/10 dark:text-slate-200">
                                    <td class="px-4 py-3 font-semibold">{{ $fila['nombre'] }}</td>
                                    <td class="px-4 py-3">{{ number_format($fila['actividad']) }}</td>
                                    <td class="px-4 py-3">{{ number_format($fila['objetivo']) }}</td>
                                    <td class="px-4 py-3">${{ number_format($fila['premio_total'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-1 text-xs {{ $fila['crecimiento_ok'] ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : 'bg-amber-500/20 text-amber-700 dark:text-amber-300' }}">
                                            {{ $fila['crecimiento_ok'] ? 'Cumple' : 'Pendiente' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500 dark:text-slate-300">No hay métricas cargadas para el cierre actual.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
