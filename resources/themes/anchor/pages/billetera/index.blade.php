<?php

use App\Services\BilleteraService;
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;

middleware('auth');
name('billetera');

new class extends Component {
    public array $resumen = [];

    public function mount(): void
    {
        $this->resumen = app(BilleteraService::class)->construirResumen(auth()->user());
    }
};
?>

<x-layouts.app>
    @volt('billetera')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Billetera"
                description="Panel consolidado de saldos, puntaje y trazabilidad auditable por campaña/cierre."
                :border="false"
            />

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Saldo actual</p>
                    <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-slate-100">${{ number_format($resumen['saldo_actual'] ?? 0, 2, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-500">Puntos acumulados: {{ number_format($resumen['saldo_puntos_actual'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Saldo a cobrar (mes vigente)</p>
                    <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-slate-100">${{ number_format($resumen['saldo_a_cobrar_mes_vigente'] ?? 0, 2, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-500">Cierre vigente: {{ $resumen['cierre_vigente'] ?? 'N/D' }}</p>
                </div>
                <div class="rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Saldo proyectado (siguiente cierre)</p>
                    <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-slate-100">${{ number_format($resumen['saldo_proyectado_siguiente_cierre'] ?? 0, 2, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-500">Siguiente cierre: {{ $resumen['cierre_siguiente'] ?? 'Sin definir' }}</p>
                </div>
                <div class="rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Rango actual</p>
                    <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $resumen['rango_actual'] ?? 'Inicial' }}</p>
                    <p class="mt-1 text-xs text-slate-500">Faltante para próximo rango: {{ $resumen['faltante_proximo_rango'] ?? 0 }}</p>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Clasificación a premios</p>
                    <p class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ ($resumen['clasificacion_premios']['clasifica'] ?? false) ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ ($resumen['clasificacion_premios']['clasifica'] ?? false) ? 'Sí clasifica' : 'No clasifica' }}
                    </p>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $resumen['clasificacion_premios']['regla'] ?? 'Sin regla disponible.' }}</p>
                </div>

                <div class="rounded-2xl border border-white/50 bg-white/45 p-4 shadow-lg backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Puntaje ganado por período</p>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        @forelse(($resumen['puntaje_ganado_por_periodo'] ?? []) as $periodo)
                            <li class="flex items-center justify-between rounded-xl bg-white/60 px-3 py-2 dark:bg-slate-800/40">
                                <span>{{ $periodo['periodo'] }}</span>
                                <span class="font-semibold">{{ number_format($periodo['puntos'], 0, ',', '.') }} pts</span>
                            </li>
                        @empty
                            <li class="text-slate-500">Sin movimientos de puntos para mostrar.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-white/50 bg-white/50 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Historial de movimientos (créditos/débitos)</h3>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-900 text-white">
                        <tr>
                            <th class="px-3 py-2 text-left">Fecha</th>
                            <th class="px-3 py-2 text-left">Origen</th>
                            <th class="px-3 py-2 text-left">Campaña/Cierre</th>
                            <th class="px-3 py-2 text-left">Detalle</th>
                            <th class="px-3 py-2 text-left">Tipo</th>
                            <th class="px-3 py-2 text-right">Monto/Puntos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse(($resumen['movimientos'] ?? []) as $mov)
                            <tr>
                                <td class="px-3 py-2">{{ $mov['fecha'] }}</td>
                                <td class="px-3 py-2">{{ $mov['origen'] }}</td>
                                <td class="px-3 py-2">{{ $mov['campana_cierre'] }}</td>
                                <td class="px-3 py-2">{{ $mov['detalle'] }}</td>
                                <td class="px-3 py-2">
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $mov['naturaleza'] === 'credito' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ strtoupper($mov['naturaleza']) }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-right font-semibold">
                                    @if($mov['tipo_saldo'] === 'puntos')
                                        {{ number_format($mov['puntos'] ?? 0, 0, ',', '.') }} pts
                                    @else
                                        ${{ number_format($mov['monto'] ?? 0, 2, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-slate-500">No hay movimientos cargados en la billetera auditable.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
