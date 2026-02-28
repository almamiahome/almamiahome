<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Volt\Component;

middleware('auth');
name('pedidos');

new class extends Component {
    use WithFileUploads;
    public $pedidos = [];
    public $lideres = [];
    public $editing = false;
    public $pedido_id;
    public $activeTab = 'lista'; // 'lista' o 'kanban'
    public $liderFilter = '';

    public $estado;
    public $codigo_pedido;
    public $observaciones;
    public $comprobante_pago;
    public $estado_pago;

    public function mount() {
        $this->lideres = User::role('lider')->orderBy('name')->get();
        $this->loadPedidos();
    }

    public function updatedLiderFilter() {
        $this->loadPedidos();
    }

    public function loadPedidos() {
        $query = Pedido::with(['vendedora', 'lider', 'responsable'])->latest();

        if (! empty($this->liderFilter)) {
            $query->where('lider_id', (int) $this->liderFilter);
        }

        $this->pedidos = $query->get();
    }

    public function setTab($tab) { $this->activeTab = $tab; }

    public function updateEstadoKanban($id, $nuevoEstado) {
        $pedido = Pedido::findOrFail($id);
        $pedido->update(['estado' => $nuevoEstado]);
        $this->loadPedidos();
    }

    public function editPedido($id) {
        $pedido = Pedido::findOrFail($id);
        $this->pedido_id = $pedido->id;
        $this->codigo_pedido = $pedido->codigo_pedido;
        $this->estado = $pedido->estado;
        $this->observaciones = $pedido->observaciones;
        $this->estado_pago = $pedido->estado_pago ?? 'sin_pago';
        $this->comprobante_pago = null;
        $this->editing = true;
    }

    public function savePedido() {
        $this->validate([
            'estado' => 'required',
            'observaciones' => 'nullable|string|max:500',
            'comprobante_pago' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'estado_pago' => 'required|in:sin_pago,pendiente_verificacion_lider,verificado,rechazado',
        ]);

        $pedido = Pedido::findOrFail($this->pedido_id);

        $datosActualizar = [
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'estado_pago' => $this->estado_pago,
        ];

        if ($this->comprobante_pago) {
            if (filled($pedido->comprobante_pago_path)) {
                Storage::disk('public')->delete($pedido->comprobante_pago_path);
            }

            $datosActualizar['comprobante_pago_path'] = $this->comprobante_pago->store('comprobantes/pedidos', 'public');
            $datosActualizar['comprobante_pago_subido_en'] = now();
            $datosActualizar['estado_pago'] = 'pendiente_verificacion_lider';
        }

        $pedido->update($datosActualizar);
        session()->flash('message', 'Pedido actualizado.');
        $this->editing = false;
        $this->loadPedidos();
    }

    public function closeModal() { $this->editing = false; }
};

?>

<x-layouts.app>
@volt('pedidos')
<x-app.container>
    <style>
        /* SCROLLBAR DECORADA Y GRANDE PARA MÓVILES */
        .custom-scroll::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #6366f1, #a855f7);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }
    </style>

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-zinc-900 dark:text-white tracking-tight">Gestión de Pedidos</h1>
            <p class="text-zinc-600 dark:text-zinc-400 font-medium">Administra y organiza tus ventas de Almamia</p>
        </div>
        <x-button tag="a" href="/crearpedido" class="shadow-xl bg-indigo-600 hover:bg-indigo-700 py-3 px-6 rounded-2xl">
            + Nuevo Pedido
        </x-button>
    </div>

    {{-- SISTEMA DE TABS --}}
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <div class="flex p-1.5 space-x-2 bg-white/30 dark:bg-black/20 border border-white/40 dark:border-white/10 rounded-[2rem] w-fit shadow-inner backdrop-blur-sm">
        <button wire:click="setTab('lista')" 
            class="flex items-center px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ $activeTab == 'lista' ? 'bg-white dark:bg-zinc-800 shadow-md text-indigo-600' : 'text-zinc-600 dark:text-zinc-400 hover:bg-white/40' }}">
            <x-phosphor-list-bullets-bold class="w-4 h-4 mr-2" /> Lista
        </button>
        <button wire:click="setTab('kanban')" 
            class="flex items-center px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ $activeTab == 'kanban' ? 'bg-white dark:bg-zinc-800 shadow-md text-indigo-600' : 'text-zinc-600 dark:text-zinc-400 hover:bg-white/40' }}">
            <x-phosphor-columns-bold class="w-4 h-4 mr-2" /> Kanban
        </button>
        </div>

        <div class="min-w-[230px]">
            <select wire:model.live="liderFilter" class="w-full rounded-2xl border border-white/40 bg-white/70 px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-zinc-900/70 dark:text-zinc-200">
                <option value="">Todas las líderes</option>
                @foreach($lideres as $lider)
                    <option value="{{ $lider->id }}">{{ $lider->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="p-4 mb-6 text-indigo-900 bg-indigo-500/20 border border-indigo-500/30 rounded-2xl animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    {{-- CONTENIDO SEGÚN TAB --}}
    <div class="relative min-h-[500px]">
        
        {{-- VISTA LISTA --}}
        @if($activeTab == 'lista')
        <div class="bg-white/50 dark:bg-zinc-900/50 border border-white/50 dark:border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden transition-all duration-500">
            <div class="overflow-x-auto custom-scroll">
                <table class="min-w-full divide-y divide-white/20">
                    <thead class="bg-zinc-950/5">
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">
                            <th class="px-6 py-5 text-left">Código</th>
                            <th class="px-6 py-5 text-left">Vendedora</th>
                            <th class="px-6 py-5 text-left">Estado</th>
                            <th class="px-6 py-5 text-left">Pago</th>
                            <th class="px-6 py-5 text-right">Total</th>
                            <th class="px-6 py-5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($pedidos as $pedido)
                        <tr class="hover:bg-white/40 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-zinc-900 dark:text-white">#{{ $pedido->codigo_pedido }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ optional($pedido->vendedora)->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-[10px] font-black uppercase border rounded-full bg-white/50 dark:bg-black/20">
                                    {{ $pedido->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php($estadoPago = $pedido->estado_pago ?? 'sin_pago')
                                <span class="px-3 py-1 text-[10px] font-black uppercase border rounded-full {{ $estadoPago === 'verificado' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : ($estadoPago === 'pendiente_verificacion_lider' ? 'bg-amber-100 text-amber-700 border-amber-200' : ($estadoPago === 'rechazado' ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-zinc-100 text-zinc-600 border-zinc-200')) }}">
                                    {{ str_replace('_', ' ', $estadoPago) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-indigo-600 dark:text-indigo-400">${{ number_format($pedido->total_a_pagar, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <button wire:click="editPedido({{ $pedido->id }})" class="p-2.5 bg-white/60 dark:bg-zinc-800 rounded-xl shadow-sm hover:scale-110 transition-transform"><x-phosphor-pencil-duotone class="w-4 h-4 text-zinc-700 dark:text-zinc-300" /></button>
                                <a href="{{ url('/pedidos/'.$pedido->id.'/factura') }}" class="p-2.5 bg-indigo-600 text-white rounded-xl shadow-lg inline-block hover:scale-110 transition-transform"><x-phosphor-printer-duotone class="w-4 h-4" /></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- VISTA KANBAN --}}
        @if($activeTab == 'kanban')
        <div class="flex gap-6 overflow-x-auto pb-8 custom-scroll snap-x">
            @foreach(['Nuevo', 'Procesando', 'En viaje', 'Entregado'] as $col)
            <div class="flex-shrink-0 w-80 snap-center">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="font-black uppercase tracking-widest text-xs text-zinc-700 dark:text-zinc-300 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></span>
                        {{ $col }}
                    </h3>
                    <span class="bg-white/40 px-2 py-0.5 rounded-lg text-[10px] font-bold">{{ $pedidos->where('estado', $col)->count() }}</span>
                </div>
                
                <div class="space-y-4 min-h-[400px] p-4 bg-white/20 dark:bg-black/20 border border-white/30 dark:border-white/5 rounded-[2rem] backdrop-blur-sm">
                    @foreach($pedidos->where('estado', $col) as $p)
                    <div class="group p-4 bg-white/80 dark:bg-zinc-800/90 rounded-2xl shadow-xl border border-white transition-all hover:-rotate-1 cursor-grab active:cursor-grabbing">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-mono text-[10px] font-bold text-indigo-600">#{{ $p->codigo_pedido }}</span>
                            <button wire:click="editPedido({{ $p->id }})"><x-phosphor-dots-three-vertical-bold class="w-4 h-4 text-zinc-400" /></button>
                        </div>
                        <p class="text-sm font-black text-zinc-800 dark:text-white leading-tight mb-3">{{ optional($p->vendedora)->name }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-zinc-500">${{ number_format($p->total_a_pagar, 0, ',', '.') }}</span>
                            <div class="flex -space-x-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-[8px] font-bold">AH</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- MODAL EDICIÓN (Traslúcido estilo iPhone) --}}
    @if($editing)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-zinc-950/40 backdrop-blur-md" wire:click="closeModal"></div>
        <div class="relative w-full max-w-lg bg-white/90 dark:bg-zinc-900/90 rounded-[3rem] p-10 shadow-2xl border border-white">
            <h2 class="text-2xl font-black mb-6 dark:text-white">Detalles del Pedido</h2>
            <form wire:submit="savePedido" class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Estado</label>
                        <select wire:model="estado" class="w-full bg-zinc-100 dark:bg-zinc-800 border-none rounded-2xl py-3 px-4 font-bold">
                            @foreach(['Nuevo','En espera','Procesando','En viaje','Entregado','Completado','Cancelado'] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Estado de pago</label>
                        <select wire:model="estado_pago" class="w-full bg-zinc-100 dark:bg-zinc-800 border-none rounded-2xl py-3 px-4 font-bold">
                            <option value="sin_pago">Sin pago</option>
                            <option value="pendiente_verificacion_lider">Pendiente verificación líder</option>
                            <option value="verificado">Verificado</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Comprobante de pago</label>
                        <input type="file" wire:model="comprobante_pago" accept=".jpg,.jpeg,.png,.pdf" class="w-full bg-zinc-100 dark:bg-zinc-800 border-none rounded-2xl py-3 px-4 font-medium">
                        @error('comprobante_pago') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                        @if($pedido_id)
                            @php($pedidoEditando = $pedidos->firstWhere('id', $pedido_id))
                            @if(optional($pedidoEditando)->comprobante_pago_path)
                                <a href="{{ Storage::disk('public')->url($pedidoEditando->comprobante_pago_path) }}" target="_blank" class="inline-flex mt-2 text-xs font-bold text-indigo-600 hover:text-indigo-500">Ver comprobante actual</a>
                            @endif
                        @endif
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Observaciones</label>
                        <textarea wire:model="observaciones" rows="4" class="w-full bg-zinc-100 dark:bg-zinc-800 border-none rounded-2xl py-3 px-4 font-medium"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="closeModal" class="flex-1 py-4 font-bold text-zinc-500 hover:text-zinc-800 transition-colors">Cerrar</button>
                    <x-button type="submit" class="flex-[2] py-4 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30">Actualizar Registro</x-button>
                </div>
            </form>
        </div>
    </div>
    @endif

</x-app.container>
@endvolt
</x-layouts.app>