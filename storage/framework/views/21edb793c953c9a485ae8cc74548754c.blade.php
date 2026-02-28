<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Livewire\Volt\Component;

?>


<x-app.container>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Mis pedidos</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Pedidos vinculados a tu cuenta Almamia Home</p>
        </div>
        <x-button tag="a" href="/crearpedido" class="shadow-lg">Nuevo Pedido</x-button>
    </div>

    @if(session()->has('message'))
        <div class="p-4 mb-6 text-emerald-800 bg-emerald-500/20 border border-emerald-500/30 rounded-xl backdrop-blur-sm">
            {{ session('message') }}
        </div>
    @endif

    {{-- CARD PRINCIPAL TRASLÚCIDA --}}
    <div class="bg-white/50 dark:bg-zinc-900/50 border border-white/40 dark:border-zinc-700/30 rounded-[2rem] shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-white/20 dark:border-zinc-700/30 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-zinc-800 dark:text-zinc-100">
                    Pedidos registrados
                </p>
                <p class="text-xs text-zinc-600 dark:text-zinc-400">
                    @if($pedidos->count())
                        Mostrando {{ $pedidos->count() }} pedido(s).
                    @else
                        No hay pedidos cargados todavía.
                    @endif
                </p>
            </div>
        </div>

        @if($pedidos->count())
            <div class="overflow-x-auto scrollbar-hidden">
                <table class="min-w-full divide-y divide-white/10 dark:divide-zinc-700/30 text-sm">
                    <thead>
                        <tr class="text-xs font-black uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
                            <th class="px-6 py-4 text-left">Código</th>
                            <th class="px-6 py-4 text-left">Vendedora</th>
                            <th class="px-6 py-4 text-left">Estado</th>
                            <th class="px-6 py-4 text-right">Total</th>
                            <th class="px-6 py-4 text-left">Fecha</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 dark:divide-zinc-700/30">
                        @foreach($pedidos as $pedido)
                            <tr class="hover:bg-white/30 dark:hover:bg-zinc-800/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-zinc-900 dark:text-zinc-200">
                                    #{{ $pedido->codigo_pedido }}
                                </td>
                                <td class="px-6 py-4 text-zinc-700 dark:text-zinc-300">
                                    {{ optional($pedido->vendedora)->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border shadow-sm',
                                        $pedido->estado === 'Nuevo'
                                            ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border-emerald-500/20'
                                            : 'bg-zinc-500/20 text-zinc-700 dark:text-zinc-400 border-zinc-500/20',
                                    ])>
                                        {{ $pedido->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-zinc-900 dark:text-white font-black">
                                    ${{ number_format($pedido->total_a_pagar, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">
                                    {{ $pedido->fecha instanceof \Carbon\Carbon ? $pedido->fecha->format('d/m/Y') : $pedido->fecha }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-1">
                                    <button wire:click="editPedido({{ $pedido->id }})" 
                                            class="p-2 rounded-lg bg-white/40 dark:bg-zinc-800/50 hover:bg-white/60 dark:hover:bg-zinc-700 border border-white/50 dark:border-zinc-600/50 transition-all">
                                        <x-phosphor-pencil-simple-duotone class="w-4 h-4 text-zinc-700 dark:text-zinc-300" />
                                    </button>

                                    <a href="{{ url('/pedidos/'.$pedido->id.'/factura') }}" 
                                       class="p-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 shadow-md inline-block">
                                        <x-phosphor-eye-duotone class="w-4 h-4" />
                                    </a>

                                    <button wire:click="deletePedido({{ $pedido->id }})" 
                                            class="p-2 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-600 hover:text-white border border-red-500/20 transition-all">
                                        <x-phosphor-trash-duotone class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <x-phosphor-shopping-cart-light class="w-12 h-12 mx-auto text-zinc-300 mb-4" />
                <p class="text-zinc-500 dark:text-zinc-400">No hay pedidos cargados todavía.</p>
            </div>
        @endif
    </div>

    {{-- MODAL EDITAR ESTILO OS (FLOTANTE Y TRASLÚCIDO) --}}
    @if($editing)
        <div class="fixed inset-0 flex items-center justify-center z-[60] px-4">
            <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <div class="relative w-full max-w-lg p-8 bg-white/80 dark:bg-zinc-900/90 backdrop-blur-2xl rounded-[2.5rem] border border-white dark:border-zinc-700 shadow-2xl">
                <h2 class="mb-6 text-xl font-black text-zinc-900 dark:text-white">Editar Pedido</h2>
                
                <form wire:submit="savePedido" class="space-y-5">
                    <div>
                        <label class="block mb-1.5 text-xs font-bold uppercase tracking-widest text-zinc-500">Código de Pedido</label>
                        <input type="text" wire:model="codigo_pedido" readonly
                               class="w-full px-4 py-3 border-none rounded-2xl bg-zinc-100 dark:bg-black/20 text-zinc-500 font-mono">
                    </div>
                    
                    <div>
                        <label class="block mb-1.5 text-xs font-bold uppercase tracking-widest text-zinc-500">Estado del Pedido</label>
                        <select wire:model="estado" class="w-full px-4 py-3 border-zinc-200 dark:border-zinc-700 rounded-2xl bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500">
                            @foreach(['Nuevo','En espera','Procesando','En viaje','Entregado','Completado','Cancelado'] as $estadoOption)
                                <option value="{{ $estadoOption }}">{{ $estadoOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-xs font-bold uppercase tracking-widest text-zinc-500">Observaciones Internas</label>
                        <textarea wire:model="observaciones" rows="3"
                                  class="w-full px-4 py-3 border-zinc-200 dark:border-zinc-700 rounded-2xl bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="closeModal" 
                                class="px-6 py-3 rounded-2xl bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold hover:bg-zinc-300 transition-all">
                            Cancelar
                        </button>
                        <x-button type="submit" class="px-8 py-3 rounded-2xl">
                            Guardar Cambios
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</x-app.container>
