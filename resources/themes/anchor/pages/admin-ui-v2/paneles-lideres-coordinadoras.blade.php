<?php

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

name('admin-ui-v2.paneles-lideres-coordinadoras');

new class extends Component {
    public array $lideres = [];

    public array $coordinadoras = [];

    public function mount(): void
    {
        $this->lideres = Pedido::query()
            ->join('users as lider', 'lider.id', '=', 'pedidos.lider_id')
            ->select('lider.name as nombre')
            ->selectRaw('COUNT(pedidos.id) as pedidos')
            ->selectRaw('SUM(pedidos.cantidad_unidades) as unidades')
            ->selectRaw('SUM(pedidos.total_a_pagar) as monto')
            ->whereNotNull('pedidos.lider_id')
            ->groupBy('lider.id', 'lider.name')
            ->orderByDesc('pedidos')
            ->limit(10)
            ->get()
            ->map(fn ($fila) => [
                'nombre' => $fila->nombre,
                'pedidos' => (int) $fila->pedidos,
                'unidades' => (int) $fila->unidades,
                'monto' => (float) $fila->monto,
            ])
            ->all();

        $this->coordinadoras = User::role('coordinadora')
            ->leftJoin('pedidos', 'users.id', '=', 'pedidos.coordinadora_id')
            ->select('users.name')
            ->selectRaw('COUNT(pedidos.id) as pedidos')
            ->selectRaw('COALESCE(SUM(pedidos.total_a_pagar), 0) as monto')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('monto')
            ->limit(10)
            ->get()
            ->map(fn ($fila) => [
                'nombre' => $fila->name,
                'pedidos' => (int) $fila->pedidos,
                'monto' => (float) $fila->monto,
            ])
            ->all();
    }
};
?>

<x-layouts.app>
    @volt('admin-ui-v2.paneles-lideres-coordinadoras')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="UI V2 · Paneles de líderes y coordinadoras"
                description="Comparativa operativa con datos acumulados de pedidos reales."
                :border="false"
            />

            <div class="grid gap-6 xl:grid-cols-2">
                <div class="overflow-hidden rounded-3xl border border-white/40 bg-white/45 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/45">
                    <div class="border-b border-white/40 px-4 py-3 text-sm font-semibold text-slate-700 dark:border-white/10 dark:text-slate-200">Top líderes por pedidos</div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/50 text-slate-600 dark:bg-slate-800/50 dark:text-slate-200">
                                <tr>
                                    <th class="px-4 py-2 text-left">Líder</th>
                                    <th class="px-4 py-2 text-left">Pedidos</th>
                                    <th class="px-4 py-2 text-left">Unidades</th>
                                    <th class="px-4 py-2 text-left">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lideres as $fila)
                                    <tr class="border-t border-white/40 text-slate-700 dark:border-white/10 dark:text-slate-200">
                                        <td class="px-4 py-2 font-medium">{{ $fila['nombre'] }}</td>
                                        <td class="px-4 py-2">{{ number_format($fila['pedidos']) }}</td>
                                        <td class="px-4 py-2">{{ number_format($fila['unidades']) }}</td>
                                        <td class="px-4 py-2">${{ number_format($fila['monto'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-5 text-center text-slate-500 dark:text-slate-300">Sin datos de líderes.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="overflow-hidden rounded-3xl border border-white/40 bg-white/45 shadow-xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/45">
                    <div class="border-b border-white/40 px-4 py-3 text-sm font-semibold text-slate-700 dark:border-white/10 dark:text-slate-200">Top coordinadoras por facturación</div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/50 text-slate-600 dark:bg-slate-800/50 dark:text-slate-200">
                                <tr>
                                    <th class="px-4 py-2 text-left">Coordinadora</th>
                                    <th class="px-4 py-2 text-left">Pedidos</th>
                                    <th class="px-4 py-2 text-left">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coordinadoras as $fila)
                                    <tr class="border-t border-white/40 text-slate-700 dark:border-white/10 dark:text-slate-200">
                                        <td class="px-4 py-2 font-medium">{{ $fila['nombre'] }}</td>
                                        <td class="px-4 py-2">{{ number_format($fila['pedidos']) }}</td>
                                        <td class="px-4 py-2">${{ number_format($fila['monto'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-5 text-center text-slate-500 dark:text-slate-300">Sin datos de coordinadoras.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
