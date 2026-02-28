<?php

use App\Models\Cobro;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

?>


    <x-app.container class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <x-app.heading
                title="Cobros"
                description="Sigue los cobros de bonos a líderes y coordinadoras."
                :border="false"
            />

            <div class="flex flex-wrap gap-2">
                <div class="flex items-center gap-2 px-3 py-2 text-sm bg-white border rounded-lg shadow-sm">
                    <span class="text-gray-500">Total registrados:</span>
                    <span class="font-semibold text-gray-900">{{ $cobros->count() }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-2 text-sm bg-white border rounded-lg shadow-sm">
                    <span class="text-gray-500">Pendientes:</span>
                    <span class="font-semibold text-amber-600">{{ $cobros->where('estado', 'pendiente')->count() }}</span>
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
                        <p class="text-sm font-semibold text-gray-800">Cobros programados</p>
                        <p class="text-xs text-gray-500">Listado de bonos registrados y su estado.</p>
                    </div>
                </div>

                @if($cobros->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-4 py-3">Pedido</th>
                                    <th class="px-4 py-3">Líder</th>
                                    <th class="px-4 py-3">Coordinadora</th>
                                    <th class="px-4 py-3">Monto</th>
                                    <th class="px-4 py-3">Campaña</th>
                                    <th class="px-4 py-3">Pago programado</th>
                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($cobros as $cobro)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-gray-800">
                                            {{ optional($cobro->pedido)->codigo_pedido ?? 'Pedido #'.$cobro->pedido_id }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-800">
                                            {{ optional($cobro->lider)->name ?? 'Sin líder' }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-800">
                                            {{ optional($cobro->coordinadora)->name ?? 'Sin coordinadora' }}
                                        </td>
                                        <td class="px-4 py-2 font-semibold text-right text-gray-900">
                                            ${{ number_format($cobro->monto, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-700">{{ $cobro->mes_campana }}</td>
                                        <td class="px-4 py-2 text-gray-700">{{ $cobro->mes_pago_programado }}</td>
                                        <td class="px-4 py-2">
                                            <span
                                                @class([
                                                    'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border',
                                                    $cobro->estado === 'cobrado'
                                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                                        : 'bg-amber-50 text-amber-700 border-amber-200',
                                                ])
                                            >
                                                {{ ucfirst($cobro->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-right space-x-2">
                                            @if($cobro->estado !== 'cobrado')
                                                <button
                                                    wire:click="marcarCobrado({{ $cobro->id }})"
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-md bg-emerald-600 text-xs font-medium text-white hover:bg-emerald-700"
                                                >
                                                    Marcar cobrado
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Todavía no hay cobros registrados.</p>
                @endif
            </div>

            <div class="p-4 space-y-4 bg-white border rounded-2xl shadow-sm">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">Registrar nuevo cobro</p>
                    <span class="text-xs text-gray-500">Campos obligatorios *</span>
                </div>

                <form wire:submit="saveCobro" class="space-y-3">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Pedido</label>
                        <select wire:model="form.pedido_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">Sin pedido asociado</option>
                            @foreach($pedidos as $pedido)
                                <option value="{{ $pedido->id }}">{{ $pedido->codigo_pedido }}</option>
                            @endforeach
                        </select>
                        @error('form.pedido_id')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Líder</label>
                            <select wire:model="form.lider_id" class="w-full border-gray-300 rounded-lg">
                                <option value="">Sin líder</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                            @error('form.lider_id')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Coordinadora</label>
                            <select wire:model="form.coordinadora_id" class="w-full border-gray-300 rounded-lg">
                                <option value="">Sin coordinadora</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                            @error('form.coordinadora_id')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
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
                                @foreach(['pendiente', 'cobrado', 'observado'] as $estado)
                                    <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                                @endforeach
                            </select>
                            @error('form.estado')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Fecha de cobro</label>
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
                        <label class="text-sm font-medium text-gray-700">Concepto</label>
                        <input
                            type="text"
                            wire:model="form.concepto"
                            class="w-full border-gray-300 rounded-lg"
                        >
                        @error('form.concepto')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-button type="submit" class="w-full justify-center">Guardar cobro</x-button>
                </form>
            </div>
        </div>
    </x-app.container>
    