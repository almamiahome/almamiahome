<?php

use App\Models\Pago;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('pagos');

new class extends Component {
    public $pagos = [];
    public $pedidos = [];
    public $vendedoras = [];

    public $form = [
        'pedido_id' => '',
        'vendedora_id' => '',
        'mes_campana' => '',
        'monto' => '',
        'estado' => 'pendiente',
        'fecha_pago' => '',
        'detalle' => '',
    ];

    public function mount(): void
    {
        $this->loadPagos();
        $this->pedidos = Pedido::orderByDesc('fecha')->get(['id', 'codigo_pedido', 'vendedora_id']);
        $this->vendedoras = User::orderBy('name')->get(['id', 'name']);
    }

    public function loadPagos(): void
    {
        $this->pagos = Pago::with(['pedido', 'vendedora'])
            ->latest()
            ->get();
    }

    public function savePago(): void
    {
        $validated = $this->validate([
            'form.pedido_id' => 'required|exists:pedidos,id',
            'form.vendedora_id' => 'required|exists:users,id',
            'form.mes_campana' => 'required|date_format:Y-m',
            'form.monto' => 'required|numeric|min:0',
            'form.estado' => 'required|string|max:50',
            'form.fecha_pago' => 'nullable|date',
            'form.detalle' => 'nullable|string|max:500',
        ])['form'];

        $validated['mes_pago_programado'] = Pago::calcularMesPago($validated['mes_campana']);

        Pago::create($validated);

        session()->flash('message', 'Pago registrado correctamente.');
        $this->reset('form');
        $this->form['estado'] = 'pendiente';
        $this->loadPagos();
    }

    public function marcarPagado(Pago $pago): void
    {
        $pago->update([
            'estado' => 'pagado',
            'fecha_pago' => Carbon::now(),
        ]);

        $this->loadPagos();
    }
};

?>

<x-layouts.app>
    @volt('pagos')
    <x-app.container class="space-y-6">
        
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <x-app.heading
                title="Pagos"
                description="Registra los pagos a vendedoras y controla su estado."
                :border="false"
            />


            <div class="flex flex-wrap gap-2">
                <div class="flex items-center gap-2 px-3 py-2 text-sm bg-white border rounded-lg shadow-sm">
                    <span class="text-gray-500">Total registrados:</span>
                    <span class="font-semibold text-gray-900">{{ $pagos->count() }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-2 text-sm bg-white border rounded-lg shadow-sm">
                    <span class="text-gray-500">Pendientes:</span>
                    <span class="font-semibold text-amber-600">{{ $pagos->where('estado', 'pendiente')->count() }}</span>
                </div>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="p-3 text-green-700 bg-green-100 border border-green-300 rounded">
                {{ session('message') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="p-4 space-y-4 bg-white border rounded-2xl shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Pagos programados</p>
                        <p class="text-xs text-gray-500">Listado de pagos registrados y su estado actual.</p>
                    </div>
                </div>

                @if($pagos->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-4 py-3">Pedido</th>
                                    <th class="px-4 py-3">Vendedora</th>
                                    <th class="px-4 py-3">Monto</th>
                                    <th class="px-4 py-3">Campaña</th>
                                    <th class="px-4 py-3">Pago programado</th>
                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pagos as $pago)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-gray-800">
                                            {{ optional($pago->pedido)->codigo_pedido ?? 'Pedido #'.$pago->pedido_id }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-800">
                                            {{ optional($pago->vendedora)->name ?? 'Sin asignar' }}
                                        </td>
                                        <td class="px-4 py-2 font-semibold text-right text-gray-900">
                                            ${{ number_format($pago->monto, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-700">{{ $pago->mes_campana }}</td>
                                        <td class="px-4 py-2 text-gray-700">{{ $pago->mes_pago_programado }}</td>
                                        <td class="px-4 py-2">
                                            <span
                                                @class([
                                                    'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border',
                                                    $pago->estado === 'pagado'
                                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                                        : 'bg-amber-50 text-amber-700 border-amber-200',
                                                ])
                                            >
                                                {{ ucfirst($pago->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-right space-x-2">
                                            @if($pago->estado !== 'pagado')
                                                <button
                                                    wire:click="marcarPagado({{ $pago->id }})"
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-md bg-emerald-600 text-xs font-medium text-white hover:bg-emerald-700"
                                                >
                                                    Marcar pagado
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Todavía no hay pagos registrados.</p>
                @endif
            </div>

            <div class="p-4 space-y-4 bg-white border rounded-2xl shadow-sm">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">Registrar nuevo pago</p>
                    <span class="text-xs text-gray-500">Campos obligatorios *</span>
                </div>

                <form wire:submit="savePago" class="space-y-3">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Pedido *</label>
                        <select wire:model="form.pedido_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">Selecciona un pedido</option>
                            @foreach($pedidos as $pedido)
                                <option value="{{ $pedido->id }}">
                                    {{ $pedido->codigo_pedido }}
                                </option>
                            @endforeach
                        </select>
                        @error('form.pedido_id')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Vendedora *</label>
                        <select wire:model="form.vendedora_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">Selecciona una vendedora</option>
                            @foreach($vendedoras as $vendedora)
                                <option value="{{ $vendedora->id }}">{{ $vendedora->name }}</option>
                            @endforeach
                        </select>
                        @error('form.vendedora_id')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Mes de campaña *</label>
                            <input
                                type="month"
                                wire:model="form.mes_campana"
                                class="w-full border-gray-300 rounded-lg"
                            >
                            @error('form.mes_campana')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Monto *</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="form.monto"
                                class="w-full border-gray-300 rounded-lg"
                            >
                            @error('form.monto')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Estado *</label>
                            <select wire:model="form.estado" class="w-full border-gray-300 rounded-lg">
                                @foreach(['pendiente', 'pagado', 'observado'] as $estado)
                                    <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                                @endforeach
                            </select>
                            @error('form.estado')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Fecha de pago</label>
                            <input
                                type="date"
                                wire:model="form.fecha_pago"
                                class="w-full border-gray-300 rounded-lg"
                            >
                            @error('form.fecha_pago')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Detalle</label>
                        <textarea
                            rows="3"
                            wire:model="form.detalle"
                            class="w-full border-gray-300 rounded-lg"
                        ></textarea>
                        @error('form.detalle')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-button type="submit" class="w-full justify-center">Guardar pago</x-button>
                </form>
            </div>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app>