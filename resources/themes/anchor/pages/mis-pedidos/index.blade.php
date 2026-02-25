<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Livewire\Volt\Component;

middleware('auth');
name('mis-pedidos');

new class extends Component {
    public $pedidos = [];
    public $editing = false;
    public $pedido_id;

    public $estado;
    public $codigo_pedido;
    public $observaciones;

    public function mount()
    {
        $this->loadPedidos();
    }

    public function loadPedidos()
    {
        $userId = auth()->id();

        $this->pedidos = Pedido::with(['vendedora', 'lider', 'responsable'])
            ->when($userId, function ($query, $userId) {
                $query->where(function ($innerQuery) use ($userId) {
                    $innerQuery->where('responsable_id', $userId)
                        ->orWhere('vendedora_id', $userId)
                        ->orWhere('lider_id', $userId)
                        ->orWhere('coordinadora_id', $userId);
                });
            })
            ->latest()
            ->get();
    }

    public function deletePedido(Pedido $pedido)
    {
        $pedido->delete();
        $this->loadPedidos();
    }

    public function editPedido($id)
    {
        $pedido = Pedido::findOrFail($id);
        $this->pedido_id = $pedido->id;
        $this->codigo_pedido = $pedido->codigo_pedido;
        $this->estado = $pedido->estado;
        $this->observaciones = $pedido->observaciones;
        $this->editing = true;
    }

    public function savePedido()
    {
        $this->validate([
            'estado' => 'required',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $pedido = Pedido::findOrFail($this->pedido_id);
        $pedido->update([
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
        ]);

        session()->flash('message', 'Pedido actualizado correctamente.');
        $this->editing = false;
        $this->loadPedidos();
    }

    public function closeModal()
    {
        $this->editing = false;
    }
};

?>

<x-layouts.app>
@volt('mis-pedidos')
<x-app.container>

    <div class="flex items-center justify-between mb-5">
        <x-app.heading title="Mis pedidos" description="Pedidos vinculados a tu cuenta" :border="false" />
        <x-button tag="a" href="/crearpedido">Nuevo Pedido</x-button>
    </div>

    @if(session()->has('message'))
        <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-gray-800">
                    Pedidos registrados
                </p>
                <p class="text-xs text-gray-500">
                    @if($pedidos->count())
                        Mostrando {{ $pedidos->count() }} pedido(s).
                    @else
                        No hay pedidos cargados todavía.
                    @endif
                </p>
            </div>
        </div>

        @if($pedidos->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-4 py-3 text-left">Código</th>
                            <th class="px-4 py-3 text-left">Vendedora</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-right">Total a pagar</th>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pedidos as $pedido)
                            <tr class="hover:bg-gray-50">
                                {{-- Código --}}
                                <td class="px-4 py-2 font-mono text-xs text-gray-800">
                                    {{ $pedido->codigo_pedido }}
                                </td>

                                {{-- Vendedora --}}
                                <td class="px-4 py-2 text-gray-800">
                                    {{ optional($pedido->vendedora)->name ?? '-' }}
                                </td>

                                {{-- Estado --}}
                                <td class="px-4 py-2">
                                    <span
                                        @class([
                                            'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border',
                                            $pedido->estado === 'Nuevo'
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                                : 'bg-gray-100 text-gray-700 border-gray-300',
                                        ])
                                    >
                                        {{ $pedido->estado }}
                                    </span>
                                </td>

                                {{-- Total a pagar --}}
                                <td class="px-4 py-2 text-right text-gray-900 font-semibold">
                                    ${{ number_format($pedido->total_a_pagar, 2, ',', '.') }}
                                </td>

                                {{-- Fecha --}}
                                <td class="px-4 py-2 text-gray-700">
                                    @if($pedido->fecha instanceof \Carbon\Carbon)
                                        {{ $pedido->fecha->format('d/m/Y') }}
                                    @else
                                        {{ $pedido->fecha }}
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="px-4 py-2 text-right space-x-2">
                                    {{-- Editar (Livewire) --}}
                                    <button
                                        wire:click="editPedido({{ $pedido->id }})"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md border border-gray-300 text-xs font-medium text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        Editar
                                    </button>

                                    {{-- Ver (Factura en otra página) --}}
                                    <a
                                        href="{{ url('/pedidos/'.$pedido->id.'/factura') }}"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md bg-indigo-600 text-xs font-medium text-white hover:bg-indigo-700"
                                    >
                                        Ver
                                    </a>

                                    {{-- Eliminar --}}
                                    <button
                                        wire:click="deletePedido({{ $pedido->id }})"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md border border-red-200 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-4 py-10 text-center text-gray-500 text-sm">
                No hay pedidos cargados todavía.
            </div>
        @endif
    </div>

    {{-- MODAL EDITAR (Livewire) --}}
    @if($editing)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg">
                <h2 class="mb-4 text-xl font-semibold">Editar Pedido</h2>
                <form wire:submit="savePedido" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Código</label>
                        <input
                            type="text"
                            wire:model="codigo_pedido"
                            readonly
                            class="w-full border-gray-300 rounded-md bg-gray-100"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select wire:model="estado" class="w-full border-gray-300 rounded-md">
                            @foreach(['Nuevo','En espera','Procesando','En viaje','Entregado','Completado','Cancelado'] as $estadoOption)
                                <option value="{{ $estadoOption }}">{{ $estadoOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea
                            wire:model="observaciones"
                            rows="3"
                            class="w-full border-gray-300 rounded-md"
                        ></textarea>
                    </div>
                    <div class="flex justify-end mt-4 space-x-3">
                        <x-button type="button" wire:click="closeModal" class="bg-gray-500 text-white">
                            Cancelar
                        </x-button>
                        <x-button type="submit">
                            Guardar
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</x-app.container>
@endvolt
</x-layouts.app>
