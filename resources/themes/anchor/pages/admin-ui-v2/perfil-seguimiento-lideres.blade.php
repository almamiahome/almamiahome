<?php

use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use App\Models\User;
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

name('admin-ui-v2.perfil-seguimiento-lideres');

new class extends Component {
    public ?int $liderId = null;

    public array $lideres = [];

    public array $perfil = [];

    public array $metricas = [];

    public function mount(): void
    {
        $this->lideres = User::role('lider')
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name'])
            ->map(fn (User $lider) => ['id' => $lider->id, 'name' => $lider->name])
            ->all();

        $this->liderId = $this->lideres[0]['id'] ?? null;
        $this->cargarPerfil();
    }

    public function updatedLiderId(): void
    {
        $this->cargarPerfil();
    }

    public function cargarPerfil(): void
    {
        if (! $this->liderId) {
            $this->perfil = [];
            $this->metricas = [];

            return;
        }

        $lider = User::find($this->liderId);
        if (! $lider) {
            return;
        }

        $pedidos = Pedido::query()->where('lider_id', $lider->id);

        $this->perfil = [
            'nombre' => $lider->name,
            'zona' => $lider->profile('zona') ?: 'Sin zona',
            'pedidos' => (clone $pedidos)->count(),
            'unidades' => (int) (clone $pedidos)->sum('cantidad_unidades'),
            'monto' => (float) (clone $pedidos)->sum('total_a_pagar'),
        ];

        $this->metricas = MetricaLiderCampana::query()
            ->where('lider_id', $lider->id)
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(fn (MetricaLiderCampana $m) => [
                'actividad' => (int) ($m->cantidad_1c + $m->cantidad_2c + $m->cantidad_3c),
                'objetivo' => (int) ($m->objetivo_proximo_cierre ?? 0),
                'premio' => (float) $m->premio_total,
                'estado' => $m->plus_crecimiento_ok ? 'plus activo' : 'base',
            ])
            ->all();
    }
};
?>

<x-layouts.app>
    @volt('admin-ui-v2.perfil-seguimiento-lideres')
        <x-app.container class="space-y-6 rounded-3xl border border-white/10 bg-slate-950/80 p-6 text-white shadow-2xl">
            <x-app.heading
                title="UI V2 · Perfil y seguimiento de líderes"
                description="Vista de perfil individual con métricas históricas reales por líder."
                :border="false"
            />

            <div class="rounded-3xl border border-white/10 bg-black/25 p-4 backdrop-blur-xl">
                <label class="text-xs uppercase tracking-wider text-white/60">Seleccionar líder</label>
                <select wire:model.live="liderId" class="mt-2 w-full rounded-xl border border-white/20 bg-black/30 px-3 py-2 text-sm text-white">
                    @foreach($lideres as $lider)
                        <option value="{{ $lider['id'] }}">{{ $lider['name'] }}</option>
                    @endforeach
                </select>
            </div>

            @if($perfil)
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-4 backdrop-blur-xl">
                        <p class="text-xs text-white/50">Líder</p>
                        <p class="text-lg font-semibold text-white">{{ $perfil['nombre'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-4 backdrop-blur-xl">
                        <p class="text-xs text-white/50">Zona</p>
                        <p class="text-lg font-semibold text-white">{{ $perfil['zona'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-4 backdrop-blur-xl">
                        <p class="text-xs text-white/50">Pedidos</p>
                        <p class="text-lg font-semibold text-white">{{ number_format($perfil['pedidos']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-4 backdrop-blur-xl">
                        <p class="text-xs text-white/50">Monto acumulado</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($perfil['monto'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-3xl border border-white/10 bg-black/25 shadow-xl backdrop-blur-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/5 text-white/70">
                                <tr>
                                    <th class="px-4 py-3 text-left">Actividad</th>
                                    <th class="px-4 py-3 text-left">Objetivo siguiente</th>
                                    <th class="px-4 py-3 text-left">Premio total</th>
                                    <th class="px-4 py-3 text-left">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($metricas as $m)
                                    <tr class="border-t border-white/10 text-white/90">
                                        <td class="px-4 py-2">{{ number_format($m['actividad']) }}</td>
                                        <td class="px-4 py-2">{{ number_format($m['objetivo']) }}</td>
                                        <td class="px-4 py-2">${{ number_format($m['premio'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 uppercase text-xs">{{ $m['estado'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-5 text-center text-white/60">Sin métricas del líder seleccionado.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
