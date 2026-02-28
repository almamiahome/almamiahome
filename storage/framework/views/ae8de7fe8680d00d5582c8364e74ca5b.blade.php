<?php

use function Laravel\Folio\{middleware, name};
use App\Models\User;
use Livewire\Volt\Component;

?>


        <x-app.container class="relative space-y-8 py-8">
            
            {{-- Fondo decorativo (Liquid Glow) --}}
            <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
                <div class="absolute -top-[5%] left-[20%] w-[35%] h-[35%] rounded-full bg-pink-500/10 blur-[110px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[25%] h-[25%] rounded-full bg-blue-500/10 blur-[90px]"></div>
            </div>

            {{-- Header --}}
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                        Líderes de Equipo
                    </h1>
                    <p class="mt-1 text-slate-500 font-medium">
                        Gestiona el alto mando y asigna nuevas vendedoras al rol de liderazgo.
                    </p>
                </div>
                <div>
                    <button
                        type="button"
                        wire:click="openAddModal"
                        class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 font-bold text-white transition-all duration-300 bg-pink-600 from-pink-500 to-rose-500 rounded-xl hover:shadow-lg hover:shadow-pink-200 focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 active:scale-95"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Agregar líder
                    </button>
                </div>
            </div>

            {{-- Toast Message --}}
            @if (session()->has('message'))
                <div class="animate-in fade-in zoom-in duration-300 p-4 mx-2 rounded-2xl bg-white/40 backdrop-blur-md border border-emerald-200/50 text-emerald-700 flex items-center gap-3 shadow-sm">
                    <div class="bg-emerald-500 text-white p-1 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-bold text-sm">{{ session('message') }}</span>
                </div>
            @endif

            {{-- Main Glass Card --}}
            @if($lideres->isEmpty())
                <div class="flex flex-col items-center justify-center p-20 bg-white/40 backdrop-blur-xl border border-white/60 rounded-[2.5rem] shadow-xl text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mb-6 shadow-inner text-slate-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Sin líderes asignados</h3>
                    <p class="text-slate-500 mt-2 max-w-sm font-medium">No hay usuarios con este rol. Promociona a una vendedora para comenzar a organizar tu equipo.</p>
                </div>
            @else
                <div class="overflow-hidden bg-white/60 backdrop-blur-2xl border border-white/80 rounded-[2.5rem] shadow-2xl shadow-slate-200/40">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-0">
                            <thead>
                                <tr class="text-slate-500">
                                    <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em]">Perfil</th>
                                    <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em]">Dependencia</th>
                                    <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em]">Ecosistema</th>
                                    <th class="px-6 py-5 text-right px-8"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/30">
                                @foreach($lideres as $lider)
                                    @php
                                        $colors = ['bg-pink-500', 'bg-violet-500', 'bg-blue-500', 'bg-emerald-500', 'bg-orange-500', 'bg-indigo-500', 'bg-cyan-500'];
                                        $randomColor = $colors[$lider->id % count($colors)];
                                    @endphp
                                    <tr class="group hover:bg-white/50 transition-all duration-300">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="relative">
                                                    {{-- Avatar derecho (sin rotación) --}}
                                                    <div class="h-12 w-12 rounded-2xl {{ $randomColor }} flex items-center justify-center text-white font-black text-lg shadow-md transition-transform duration-300 group-hover:scale-105">
                                                        {{ strtoupper(substr($lider->name ?? '?', 0, 1)) }}
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-emerald-400 border-2 border-white rounded-full"></div>
                                                </div>
                                                <div>
                                                    <div class="font-black text-slate-800 tracking-tight">{{ $lider->name ?? 'Sin nombre' }}</div>
                                                    <div class="text-xs text-slate-400 font-medium">{{ $lider->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            @if($lider->coordinadora_id)
                                                @php $coordinadora = \App\Models\User::find($lider->coordinadora_id) @endphp
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-slate-700">{{ $coordinadora->name ?? 'N/A' }}</span>
                                                    <span class="text-[10px] font-bold text-slate-400">ID #{{ $lider->coordinadora_id }}</span>
                                                </div>
                                            @else
                                                <span class="px-3 py-1 rounded-lg bg-slate-100/50 text-slate-400 text-[10px] font-bold uppercase tracking-tight">Autónoma</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($lider->roles as $role)
                                                    @if($role->name !== 'lider')
                                                        <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-md bg-white/80 border border-slate-200 text-slate-500 shadow-sm">
                                                            {{ $role->name }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                                @if($lider->roles->where('name', '!=', 'lider')->isEmpty())
                                                    <span class="text-[10px] italic text-slate-300">Único rol</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-right px-8">
                                            <button
                                                type="button"
                                                x-data
                                                @click="if (confirm('¿Quitar rol de líder?')) { $wire.removeLider({{ $lider->id }}) }"
                                                class="opacity-0 group-hover:opacity-100 transition-all duration-300 inline-flex items-center px-4 py-2 rounded-xl text-xs font-black text-red-500 bg-red-50 hover:bg-red-500 hover:text-white border border-red-100 shadow-sm"
                                            >
                                                Remover Liderazgo
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Modal: Agregar Líder --}}
            @if($showAddModal)
                <div
                    x-data="{ open: true }"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                    @keydown.window.escape="open = false; $wire.closeModal()"
                >
                    <div
                        class="w-full max-w-lg bg-white/90 backdrop-blur-2xl border border-white rounded-[2.5rem] shadow-2xl overflow-hidden"
                        @click.outside="open = false; $wire.closeModal()"
                    >
                        <div class="p-8 pb-4 flex justify-between items-start">
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tighter">Nueva Líder</h2>
                                <p class="text-sm text-slate-500 font-medium">Asigna una vendedora activa al rol de coordinación.</p>
                            </div>
                            <button @click="open = false; $wire.closeModal()" class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors">✕</button>
                        </div>

                        <div class="px-8 py-6">
                            @if($usuariosDisponibles->isEmpty())
                                <div class="p-6 rounded-3xl bg-indigo-50 border border-indigo-100 text-indigo-700 text-center">
                                    <p class="text-sm font-bold">No hay vendedoras disponibles</p>
                                    <p class="text-xs opacity-80 mt-1">Todas las vendedoras actuales ya poseen rango de líder.</p>
                                </div>
                            @else
                                <form wire:submit.prevent="addLider" id="formLider" class="space-y-6">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Seleccionar vendedora</label>
                                        <select
                                            wire:model="form.user_id"
                                            class="w-full h-14 pl-4 pr-10 bg-white border-slate-200 rounded-2xl text-slate-700 font-bold focus:ring-4 focus:ring-pink-500/10 focus:border-pink-500 transition-all appearance-none shadow-inner"
                                        >
                                            <option value="">Buscar en la lista...</option>
                                            @foreach($usuariosDisponibles as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name ?? $user->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('form.user_id')
                                            <span class="mt-2 block text-xs font-bold text-red-500 ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </form>
                            @endif
                        </div>

                        <div class="p-8 bg-slate-50/50 flex flex-col sm:flex-row gap-3">
                            <button @click="open = false; $wire.closeModal()" class="flex-1 px-6 py-4 text-sm font-black text-slate-500 hover:text-slate-700 transition-colors uppercase tracking-widest">Cancelar</button>
                            @if(!$usuariosDisponibles->isEmpty())
                                <button
                                    form="formLider"
                                    wire:click="addLider"
                                    class="flex-1 px-6 py-4 bg-pink-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg shadow-pink-200 hover:bg-pink-600 active:scale-95 transition-all"
                                >
                                    Confirmar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </x-app.container>
    