<?php

use function Laravel\Folio\{middleware, name};
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

    public $lideres;

    public array $resumen = [
        'actividad' => 0,
        'retencion' => 0,
        'altas' => 0,
        'unidades' => 0,
        'cobranza' => 0,
        'reparto' => 0,
        'total' => 0,
    ];

    public function mount(): void
    {
        $this->lideres = User::role('lider')->orderBy('name')->get(['id', 'name']);
        $this->liderId = $this->lideres->first()?->id;
        $this->cargarResumen();
    }

    public function updatedLiderId(): void
    {
        $this->cargarResumen();
    }

    protected function cargarResumen(): void
    {
        if (! $this->liderId) {
            return;
        }

        $metrica = MetricaLiderCampana::query()
            ->where('lider_id', $this->liderId)
            ->latest('id')
            ->first();

        if (! $metrica) {
            $this->resumen = [
                'actividad' => 0,
                'retencion' => 0,
                'altas' => 0,
                'unidades' => 0,
                'cobranza' => 0,
                'reparto' => 0,
                'total' => 0,
            ];

            return;
        }

        $actividad = (int) ($metrica->premio_actividad ?? 0);
        $retencion = (int) ($metrica->premio_retencion ?? 0);
        $altas = (int) ($metrica->premio_altas ?? 0);
        $unidades = (int) ($metrica->premio_unidades ?? 0);
        $cobranza = (int) ($metrica->premio_cobranzas ?? 0);
        $reparto = (int) ($metrica->monto_reparto_total ?? 0);
        $total = $actividad + $retencion + $altas + $unidades + $cobranza + $reparto;

        $this->resumen = compact('actividad', 'retencion', 'altas', 'unidades', 'cobranza', 'reparto', 'total');
    }
};
?>

<x-layouts.app>
    @volt('lideres.liquidacion')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Liquidación de líderes por cierre"
                description="Resumen de premios por actividad, retención, altas, cobranza, reparto y unidades, en formato operativo para cierre."
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

            <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                <table class="min-w-full text-sm">
                    <thead class="bg-sky-900 text-white">
                        <tr>
                            <th class="px-3 py-2 text-left">Concepto</th>
                            <th class="px-3 py-2 text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white/90 dark:bg-slate-900/70">
                        <tr><td class="px-3 py-2">Premio por actividad</td><td class="px-3 py-2 text-right">${{ number_format($resumen['actividad'], 0, ',', '.') }}</td></tr>
                        <tr><td class="px-3 py-2">Premio por retención</td><td class="px-3 py-2 text-right">${{ number_format($resumen['retencion'], 0, ',', '.') }}</td></tr>
                        <tr><td class="px-3 py-2">Premio por altas</td><td class="px-3 py-2 text-right">${{ number_format($resumen['altas'], 0, ',', '.') }}</td></tr>
                        <tr><td class="px-3 py-2">Premio por unidades</td><td class="px-3 py-2 text-right">${{ number_format($resumen['unidades'], 0, ',', '.') }}</td></tr>
                        <tr><td class="px-3 py-2">Premio por cobranza</td><td class="px-3 py-2 text-right">${{ number_format($resumen['cobranza'], 0, ',', '.') }}</td></tr>
                        <tr><td class="px-3 py-2">Premio por reparto</td><td class="px-3 py-2 text-right">${{ number_format($resumen['reparto'], 0, ',', '.') }}</td></tr>
                    </tbody>
                    <tfoot class="bg-fuchsia-500/90 text-white font-semibold">
                        <tr>
                            <td class="px-3 py-2">TOTAL A COBRAR</td>
                            <td class="px-3 py-2 text-right">${{ number_format($resumen['total'], 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
