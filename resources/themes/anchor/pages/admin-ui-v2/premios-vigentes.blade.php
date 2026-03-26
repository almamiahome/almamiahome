<?php

use App\Models\CierreCampana;
use App\Models\PremioRegla;
use App\Models\PuntajeRegla;
use App\Models\RevendedoraPunto;
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

name('admin-ui-v2.premios-vigentes');

new class extends Component {
    public ?string $campana = null;

    public array $reglasPremio = [];

    public array $reglasPuntaje = [];

    public int $puntosEntregados = 0;

    public function mount(): void
    {
        $cierre = CierreCampana::query()->latest('id')->first();
        $this->campana = $cierre?->nombre ?? 'Sin campaña activa';

        $this->reglasPremio = PremioRegla::query()
            ->with('rangoLider:id,nombre')
            ->when($cierre, fn ($query) => $query->where('campana_id', $cierre->id))
            ->orderBy('tipo')
            ->limit(12)
            ->get()
            ->map(fn (PremioRegla $regla) => [
                'rango' => $regla->rangoLider?->nombre ?? 'Sin rango',
                'tipo' => str_replace('_', ' ', (string) $regla->tipo),
                'umbral' => trim(($regla->umbral_minimo ?? 0) . ' - ' . ($regla->umbral_maximo ?? '∞')),
                'monto' => (float) $regla->monto,
            ])->all();

        $this->reglasPuntaje = PuntajeRegla::query()
            ->orderBy('min_unidades')
            ->limit(8)
            ->get()
            ->map(fn (PuntajeRegla $regla) => [
                'descripcion' => $regla->descripcion ?: 'Regla sin descripción',
                'tramo' => ($regla->min_unidades ?? 0) . ' - ' . ($regla->max_unidades ?? '∞') . ' unidades',
                'puntos' => (int) ($regla->puntos_por_campania ?? 0),
            ])->all();

        $this->puntosEntregados = (int) RevendedoraPunto::query()
            ->when($cierre, fn ($query) => $query->where('cierre_id', $cierre->id))
            ->sum('puntos');
    }
};
?>

<x-layouts.app>
    @volt('admin-ui-v2.premios-vigentes')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="UI V2 · Premios vigentes"
                description="Panel de incentivos alineado a las reglas reales cargadas en el sistema."
                :border="false"
            />

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-white/40 bg-white/45 p-4 backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs text-slate-500 dark:text-slate-300">Campaña</p>
                    <p class="mt-1 text-lg font-semibold text-slate-800 dark:text-white">{{ $campana }}</p>
                </div>
                <div class="rounded-2xl border border-white/40 bg-white/45 p-4 backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs text-slate-500 dark:text-slate-300">Reglas de premio activas</p>
                    <p class="mt-1 text-2xl font-bold text-slate-800 dark:text-white">{{ number_format(count($reglasPremio)) }}</p>
                </div>
                <div class="rounded-2xl border border-white/40 bg-white/45 p-4 backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/40">
                    <p class="text-xs text-slate-500 dark:text-slate-300">Puntos entregados</p>
                    <p class="mt-1 text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($puntosEntregados) }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-white/40 bg-white/45 p-4 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/45">
                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-200">Premios por rango</h3>
                    <div class="space-y-2">
                        @forelse($reglasPremio as $regla)
                            <div class="rounded-xl border border-white/30 bg-white/40 p-3 dark:border-white/10 dark:bg-slate-800/40">
                                <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ ucfirst($regla['rango']) }} · {{ ucfirst($regla['tipo']) }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-300">Umbral {{ $regla['umbral'] }}</p>
                                <p class="text-sm font-bold text-pink-600 dark:text-pink-300">${{ number_format($regla['monto'], 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-300">No hay reglas de premios registradas para la campaña seleccionada.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl border border-white/40 bg-white/45 p-4 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/45">
                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-200">Reglas de puntos de revendedoras</h3>
                    <div class="space-y-2">
                        @forelse($reglasPuntaje as $regla)
                            <div class="rounded-xl border border-white/30 bg-white/40 p-3 dark:border-white/10 dark:bg-slate-800/40">
                                <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $regla['descripcion'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-300">{{ $regla['tramo'] }}</p>
                                <p class="text-sm font-bold text-indigo-600 dark:text-indigo-300">{{ number_format($regla['puntos']) }} pts/campaña</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-300">No hay reglas de puntaje configuradas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
