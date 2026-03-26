<?php

use App\Models\CierreCampana;
use App\Models\LiquidacionCierre;
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

name('admin-ui-v2.panel-alternativa');

new class extends Component {
    public array $cierres = [];

    public array $liquidaciones = [];

    public function mount(): void
    {
        $this->cierres = CierreCampana::query()
            ->with('catalogo:id,nombre')
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(fn (CierreCampana $cierre) => [
                'nombre' => $cierre->nombre,
                'estado' => $cierre->estado,
                'catalogo' => $cierre->catalogo?->nombre ?? 'Sin catálogo',
                'fecha' => optional($cierre->fecha_cierre)->format('d/m/Y') ?? 'Pendiente',
            ])
            ->all();

        $this->liquidaciones = LiquidacionCierre::query()
            ->with(['lider:id,name', 'coordinadora:id,name'])
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn (LiquidacionCierre $liquidacion) => [
                'lider' => $liquidacion->lider?->name ?? 'Sin líder',
                'coordinadora' => $liquidacion->coordinadora?->name ?? 'Sin coordinadora',
                'estado' => $liquidacion->estado ?? 'sin_estado',
                'balance' => (float) $liquidacion->balance_neto,
            ])
            ->all();
    }
};
?>

<x-layouts.app>
    @volt('admin-ui-v2.panel-alternativa')
        <x-app.container class="space-y-6 rounded-3xl border border-white/10 bg-slate-950/80 p-6 text-white shadow-2xl">
            <x-app.heading
                title="UI V2 · Panel alternativa"
                description="Lectura rápida de cierres y liquidaciones en formato operativo compacto."
                :border="false"
            />

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-black/25 p-4 shadow-xl backdrop-blur-xl">
                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-white/80">Últimos cierres</h3>
                    <div class="space-y-2">
                        @forelse($cierres as $cierre)
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                <p class="font-semibold text-white">{{ $cierre['nombre'] }}</p>
                                <p class="text-xs text-white/60">{{ $cierre['catalogo'] }} · {{ $cierre['fecha'] }}</p>
                                <p class="mt-1 text-xs uppercase tracking-wide text-indigo-300">Estado: {{ str_replace('_', ' ', $cierre['estado']) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-white/60">No hay cierres disponibles.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-black/25 p-4 shadow-xl backdrop-blur-xl">
                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-white/80">Liquidaciones recientes</h3>
                    <div class="space-y-2">
                        @forelse($liquidaciones as $fila)
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                <p class="font-semibold text-white">{{ $fila['lider'] }}</p>
                                <p class="text-xs text-white/60">Coordina: {{ $fila['coordinadora'] }}</p>
                                <div class="mt-1 flex items-center justify-between">
                                    <span class="text-xs uppercase text-white/60">{{ str_replace('_', ' ', $fila['estado']) }}</span>
                                    <span class="text-sm font-bold text-pink-300">${{ number_format($fila['balance'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-white/60">No hay liquidaciones registradas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
