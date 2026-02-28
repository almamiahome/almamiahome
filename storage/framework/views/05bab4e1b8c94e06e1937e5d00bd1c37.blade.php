<?php

use function Laravel\Folio\{middleware, name};
use App\Models\RangoLider;
use App\Models\PremioRegla;
use App\Models\CierreCampana;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

?>


    <x-app.container class="space-y-6 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 bg-white/40 dark:bg-zinc-900/30 backdrop-blur-xl rounded-[2.5rem] border border-white/50 shadow-xl">
            <x-app.heading
                title="Rangos y Premios"
                description="Configura niveles de red y sus reglas de bonificación."
                :border="false"
            />
            <div class="flex flex-wrap gap-2">
                <button wire:click="resetPremioForm" class="px-5 py-2.5 bg-white/50 hover:bg-white text-zinc-700 rounded-2xl border border-white/60 shadow-sm transition-all text-sm font-bold flex items-center gap-2">
                    <x-phosphor-plus-circle class="w-5 h-5" /> Nueva Regla
                </button>
                <button wire:click="startCrearRango" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-lg shadow-indigo-500/30 transition-all text-sm font-bold flex items-center gap-2">
                    <x-phosphor-shield-plus-bold class="w-5 h-5" /> Nuevo Rango
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-emerald-500/20 backdrop-blur-md border border-emerald-500/30 rounded-2xl text-emerald-700 dark:text-emerald-400 text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                <x-phosphor-check-circle-fill class="w-5 h-5" />
                {{ session('message') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-3 space-y-4">
                <div class="p-5 bg-white/30 dark:bg-zinc-900/20 backdrop-blur-md rounded-[2rem] border border-white/40 shadow-sm">
                    <h3 class="mb-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest px-2">Rangos Configurados</h3>
                    <div class="space-y-2">
                        @forelse($rangos as $rango)
                            <button
                                wire:click="selectRango({{ $rango->id }})"
                                class="w-full text-left p-3.5 rounded-2xl transition-all duration-300 border flex items-center justify-between group
                                {{ $rangoId === $rango->id 
                                    ? 'bg-white dark:bg-zinc-800 shadow-lg border-zinc-200' 
                                    : 'bg-white/40 border-transparent hover:bg-white/60' }}"
                                @if($rangoId === $rango->id) style="border-left: 4px solid {{ $rango->color }};" @endif
                            >
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full shadow-sm" style="background: {{ $rango->color }}"></span>
                                    <div>
                                        <p class="font-bold text-zinc-800 dark:text-zinc-100 text-sm">{{ $rango->nombre }}</p>
                                        <p class="text-[10px] text-zinc-500 font-medium">POS. {{ $rango->posicion }} • {{ $rango->premio_reglas_count }} REGLAS</p>
                                    </div>
                                </div>
                                <x-phosphor-caret-right-bold class="w-3 h-3 text-zinc-300 group-hover:text-indigo-500" />
                            </button>
                        @empty
                            <p class="text-xs text-zinc-500 text-center py-4 italic">No hay rangos definidos.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="lg:col-span-9 space-y-6">
                
                <div class="p-8 bg-white/60 dark:bg-zinc-900/40 backdrop-blur-2xl rounded-[2.5rem] border border-white/60 shadow-xl relative transition-all duration-500"
                     style="border-top: 6px solid {{ $color ?? '#6366f1' }}">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg transition-colors duration-500" 
                                 style="background: {{ $color ?? '#6366f1' }}">
                                <x-phosphor-medal-bold class="w-7 h-7" />
                            </div>
                            <h3 class="text-xl font-black text-zinc-800 dark:text-zinc-100 tracking-tight">Ficha del Rango</h3>
                        </div>
                        @if($rangoId)
                            <button onclick="confirm('¿Eliminar este rango?') || event.stopImmediatePropagation()" wire:click="deleteRango({{ $rangoId }})" class="p-2.5 text-red-500 bg-red-500/10 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                                <x-phosphor-trash-bold class="w-5 h-5" />
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[11px] font-bold text-zinc-400 uppercase ml-2">Nombre</label>
                            <input wire:model.live="nombre" class="w-full px-5 py-3 bg-white border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 shadow-sm" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-zinc-400 uppercase ml-2">Slug</label>
                            <input wire:model.live="slug" class="w-full px-5 py-3 bg-white border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 shadow-sm text-xs font-mono" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-zinc-400 uppercase ml-2">Color</label>
                            <input type="color" wire:model.live="color" class="w-full h-[48px] p-1 bg-white border-none rounded-2xl cursor-pointer shadow-sm" />
                        </div>

                        <div class="p-4 bg-indigo-500/5 rounded-3xl border border-indigo-500/10 grid grid-cols-3 gap-4 md:col-span-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-indigo-400 uppercase text-center block">Posición</label>
                                <input type="number" wire:model.live="posicion" class="w-full text-center bg-white border-none rounded-xl py-2 font-bold" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-indigo-400 uppercase text-center block">Rev. Mínimas</label>
                                <input type="number" wire:model.live="revendedoras_minimas" class="w-full text-center bg-white border-none rounded-xl py-2 font-bold" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-indigo-400 uppercase text-center block">Rev. Máximas</label>
                                <input type="number" wire:model.live="revendedoras_maximas" class="w-full text-center bg-white border-none rounded-xl py-2 font-bold" />
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Unidades Mínimas</label>
                            <input type="number" wire:model.live="unidades_minimas" class="w-full px-5 py-3 bg-white/50 border-none rounded-2xl shadow-sm font-bold" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Premio Actividad ($)</label>
                            <input type="number" step="0.01" wire:model.live="premio_actividad" class="w-full px-5 py-3 bg-white border-none rounded-2xl shadow-sm text-emerald-600 font-bold" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Premio Unidades ($)</label>
                            <input type="number" step="0.01" wire:model.live="premio_unidades" class="w-full px-5 py-3 bg-white border-none rounded-2xl shadow-sm text-emerald-600 font-bold" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Premio Cobranzas ($)</label>
                            <input type="number" step="0.01" wire:model.live="premio_cobranzas" class="w-full px-5 py-3 bg-white border-none rounded-2xl shadow-sm text-emerald-600 font-bold" />
                        </div>

                        <div class="space-y-1 md:col-span-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Reparto Ref. (%)</label>
                            <input type="number" step="0.01" wire:model.live="reparto_referencia" class="w-full px-5 py-3 bg-indigo-600 text-white border-none rounded-2xl shadow-sm font-black" />
                        </div>
                        <div class="md:col-span-3 space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Descripción Interna</label>
                            <textarea wire:model.live="descripcion" rows="1" class="w-full px-5 py-3 bg-white border-none rounded-2xl shadow-sm text-sm" placeholder="Notas sobre este rango..."></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button wire:click="saveRango" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-95">
                            GUARDAR RANGO
                        </button>
                    </div>
                </div>

                <div class="p-8 bg-white/40 dark:bg-zinc-900/30 backdrop-blur-xl rounded-[2.5rem] border border-white/40 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-zinc-800 dark:text-zinc-100 flex items-center gap-2">
                            <span class="w-2 h-6 bg-pink-500 rounded-full"></span>
                            Reglas de Premios Especiales
                        </h3>
                        <button wire:click="resetPremioForm" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">Limpiar Formulario</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-white/50 rounded-[2rem] border border-white/60 mb-8">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Tipo de Regla</label>
                            <select wire:model.live="premio_tipo" class="w-full px-4 py-2.5 bg-white border-none rounded-xl text-sm shadow-sm font-bold">
                                <option value="actividad">Actividad</option>
                                <option value="altas">Altas</option>
                                <option value="unidades">Unidades</option>
                                <option value="cobranzas">Cobranzas</option>
                                <option value="crecimiento">Crecimiento</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Nombre Interno</label>
                            <input wire:model.live="premio_nombre" class="w-full px-4 py-2.5 bg-white border-none rounded-xl text-sm shadow-sm" placeholder="Ej: Bono Especial Navidad" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Cierre Asociado</label>
                            <select wire:model.live="premio_campana_id" class="w-full px-4 py-2.5 bg-white border-none rounded-xl text-sm shadow-sm">
                                <option value="">Plan Base</option>
                                @foreach($cierreCampanas as $cierre)
                                    <option value="{{ $cierre->id }}">{{ $cierre->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:col-span-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Umbral Mín</label>
                                <input type="number" wire:model.live="premio_umbral_minimo" class="w-full px-4 py-2.5 bg-white border-none rounded-xl text-sm shadow-sm font-bold" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Umbral Máx</label>
                                <input type="number" wire:model.live="premio_umbral_maximo" class="w-full px-4 py-2.5 bg-white border-none rounded-xl text-sm shadow-sm font-bold" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Monto ($)</label>
                                <input type="number" step="0.01" wire:model.live="premio_monto" class="w-full px-4 py-2.5 bg-pink-500 text-white border-none rounded-xl text-sm shadow-lg font-black" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Cuotas</label>
                                <input type="number" step="1" wire:model.live="premio_cuotas" class="w-full px-4 py-2.5 bg-white border-none rounded-xl text-sm shadow-sm font-bold" />
                            </div>
                        </div>

                        <div class="space-y-1 md:col-span-3">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Descripción / Condiciones</label>
                            <textarea wire:model.live="premio_descripcion" rows="1" class="w-full px-4 py-2.5 bg-white border-none rounded-xl text-xs shadow-sm" placeholder="Detalla cómo se gana este premio..."></textarea>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase ml-2">Orden Compra</label>
                            <input type="number" wire:model.live="premio_compra_orden" class="w-full px-4 py-2.5 bg-white border-none rounded-xl text-sm shadow-sm font-bold" />
                        </div>

                        <div class="md:col-span-4 flex justify-end gap-2 pt-2">
                            @if($premioId)
                                <button wire:click="deletePremio({{ $premioId }})" class="px-6 py-2.5 bg-red-50 text-red-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">Eliminar Regla</button>
                            @endif
                            <button wire:click="savePremio" class="px-8 py-2.5 bg-zinc-800 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all">
                                {{ $premioId ? 'Actualizar Regla' : 'Agregar Regla' }}
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-[1.5rem] border border-white/20">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/50 text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em]">
                                <tr>
                                    <th class="px-6 py-4 text-left">Tipo / Nombre</th>
                                    <th class="px-6 py-4 text-left">Umbrales</th>
                                    <th class="px-6 py-4 text-left">Premio</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/20">
                                @forelse($premioReglas as $regla)
                                    <tr class="hover:bg-white/40 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-600 text-[9px] font-black uppercase">{{ $regla->tipo }}</span>
                                                <span class="font-bold text-zinc-700">{{ $regla->datos['nombre'] ?? 'Sin nombre' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-[10px] font-medium text-zinc-500">
                                                MIN: {{ $regla->umbral_minimo ?? '—' }} <br> MAX: {{ $regla->umbral_maximo ?? '—' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-black text-pink-600">${{ number_format($regla->monto, 0, ',', '.') }}</div>
                                            @if(!empty($regla->datos['compra_orden']))
                                                <div class="text-[9px] font-bold text-zinc-400 uppercase">{{ $regla->datos['compra_orden'] }}ª Compra</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button wire:click="editPremio({{ $regla->id }})" class="p-2 bg-white text-zinc-400 hover:text-indigo-600 rounded-lg shadow-sm border border-zinc-100 transition-all">
                                                <x-phosphor-pencil-simple-bold class="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-xs text-zinc-400 italic">No hay reglas de premios para este rango.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-app.container>
