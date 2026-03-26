<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Departamento;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Zona;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Volt\Component;
use Livewire\WithPagination;

middleware('auth');
name('pedidos.facturas-masivas');

new class extends Component {
    use WithPagination;

    public string $lider_id = '';
    public string $zona_id = '';
    public string $departamento_id = '';
    public string $fecha_desde = '';
    public string $fecha_hasta = '';
    public string $campana = '';

    public $lideres = [];
    public $zonas = [];
    public $departamentos = [];

    public function mount(): void
    {
        $user = auth()->user();

        if (! $user || ! $user->hasAnyRole(['admin', 'coordinadora'])) {
            abort(403, 'No tiene permisos para acceder a facturas masivas.');
        }

        $this->lideres = User::role('lider')->orderBy('name')->get(['id', 'name']);
        $this->zonas = Zona::orderBy('nombre')->get(['id', 'nombre']);
        $this->departamentos = Departamento::orderBy('nombre')->get(['id', 'nombre']);
    }

    public function updated($property): void
    {
        if (in_array($property, ['lider_id', 'zona_id', 'departamento_id', 'fecha_desde', 'fecha_hasta', 'campana'], true)) {
            $this->resetPage();
        }
    }

    protected function queryPedidos(): Builder
    {
        $user = auth()->user();

        $query = Pedido::query()
            ->with(['vendedora', 'lider.zona', 'lider.departamento', 'articulos'])
            ->orderByDesc('fecha');

        if ($user?->hasRole('coordinadora')) {
            $query->where('coordinadora_id', (int) $user->id);
        }

        if ($this->lider_id !== '') {
            $query->where('lider_id', (int) $this->lider_id);
        }

        if ($this->zona_id !== '') {
            $query->whereHas('lider', fn (Builder $q) => $q->where('zona_id', (int) $this->zona_id));
        }

        if ($this->departamento_id !== '') {
            $query->whereHas('lider', fn (Builder $q) => $q->where('departamento_id', (int) $this->departamento_id));
        }

        if ($this->fecha_desde !== '') {
            $query->whereDate('fecha', '>=', $this->fecha_desde);
        }

        if ($this->fecha_hasta !== '') {
            $query->whereDate('fecha', '<=', $this->fecha_hasta);
        }

        if ($this->campana !== '') {
            $query->where('catalogo_nro', 'like', '%'.$this->campana.'%');
        }

        return $query;
    }

    public function getPedidosProperty()
    {
        return $this->queryPedidos()->paginate(10);
    }

    public function descargarPdfCompleto()
    {
        $pedidos = $this->queryPedidos()->get();

        $pdf = Pdf::loadView('pages.pedidos.facturas-masivas.pdf', [
            'pedidos' => $pedidos,
            'filtros' => [
                'lider_id' => $this->lider_id,
                'zona_id' => $this->zona_id,
                'departamento_id' => $this->departamento_id,
                'fecha_desde' => $this->fecha_desde,
                'fecha_hasta' => $this->fecha_hasta,
                'campana' => $this->campana,
            ],
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'facturas_masivas_'.now()->format('Ymd_His').'.pdf'
        );
    }
};

?>

<x-layouts.app>
    @volt('pedidos.facturas-masivas')
    <x-app.container data-tour-scope="facturas-masivas">
        <h1
            class="mb-6 text-2xl font-bold"
            data-tour-step="1"
            data-tour-title="Facturas masivas"
            data-tour-text="Desde aquí consolidás pedidos para descargar facturas en lote por campaña o rango de fechas."
        >Facturas masivas</h1>

        <div
            class="mb-6 grid gap-4 rounded-xl border bg-white p-4 md:grid-cols-3 lg:grid-cols-6"
            data-tour-step="2"
            data-tour-title="Segmentación de pedidos"
            data-tour-text="Aplicá filtros por líder, territorio y período para emitir documentación sólo del grupo correcto."
        >
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Líder</label>
                <select wire:model.live="lider_id" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Todas</option>
                    @foreach($lideres as $lider)
                        <option value="{{ $lider->id }}">{{ $lider->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Zona</label>
                <select wire:model.live="zona_id" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Todas</option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Departamento</label>
                <select wire:model.live="departamento_id" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Todos</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Fecha desde</label>
                <input type="date" wire:model.live="fecha_desde" class="w-full rounded-md border-gray-300 shadow-sm" />
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Fecha hasta</label>
                <input type="date" wire:model.live="fecha_hasta" class="w-full rounded-md border-gray-300 shadow-sm" />
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Campaña</label>
                <input
                    type="text"
                    wire:model.live.debounce.350ms="campana"
                    placeholder="Ej: 2026-03"
                    class="w-full rounded-md border-gray-300 shadow-sm"
                />
            </div>
        </div>

        <div
            class="mb-4 flex items-center justify-between gap-3"
            data-tour-step="3"
            data-tour-title="Exportación en PDF"
            data-tour-text="Cuando revises la cantidad encontrada, descargá el PDF completo para compartir o archivar."
        >
            <p class="text-sm text-gray-600">
                Pedidos encontrados: <span class="font-semibold">{{ $this->pedidos->total() }}</span>
            </p>
            <button
                type="button"
                wire:click="descargarPdfCompleto"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
            >
                Descargar PDF completo
            </button>
        </div>

        <div class="overflow-x-auto rounded-xl border bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th class="px-4 py-3">Pedido</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Vendedora</th>
                        <th class="px-4 py-3">Líder</th>
                        <th class="px-4 py-3">Artículos</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($this->pedidos as $pedido)
                        <tr>
                            <td class="px-4 py-3 font-medium">#{{ $pedido->codigo_pedido }}</td>
                            <td class="px-4 py-3">{{ optional($pedido->fecha)->format('d/m/Y') ?? $pedido->fecha }}</td>
                            <td class="px-4 py-3">{{ $pedido->vendedora?->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $pedido->lider?->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $pedido->articulos->count() }}</td>
                            <td class="px-4 py-3 text-right font-semibold">${{ number_format((float) $pedido->total_a_pagar, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                No hay pedidos para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->pedidos->links() }}
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app>
