<?php

use function Laravel\Folio\{middleware, name};
use App\Models\User;
use Livewire\Volt\Component;

middleware('auth');
name('vendedoras');

new class extends Component {
    public $vendedoras;
    public $usuariosDisponibles;

    public bool $showAddModal = false;

    public array $form = [];

    public function mount(): void
    {
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    protected function loadData(): void
    {
        $user = auth()->user();

        // Usuarios con rol vendedora
        $vendedorasQuery = User::role('vendedora')
            ->with('roles')
            ->orderBy('name');

        if ($user?->hasRole('lider')) {
            $vendedorasQuery->where('lider_id', $user->id);
        }

        $this->vendedoras = $vendedorasQuery->get();

        // Usuarios que NO tienen rol vendedora (para asignar)
        $this->usuariosDisponibles = User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'vendedora');
            })
            ->orderBy('name')
            ->get();
    }

    protected function defaultForm(): array
    {
        return [
            'user_id' => null,
        ];
    }

    public function openAddModal(): void
    {
        $this->form = $this->defaultForm();
        $this->showAddModal = true;
    }

    protected function rules(): array
    {
        return [
            'form.user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function addVendedora(): void
    {
        $validated = $this->validate();

        /** @var \App\Models\User $user */
        $user = User::findOrFail($validated['form']['user_id']);

        // Asignar rol vendedora
        $user->assignRole('vendedora');

        session()->flash('message', 'Vendedora asignada correctamente.');

        $this->showAddModal = false;
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    public function removeVendedora(int $userId): void
    {
        /** @var \App\Models\User $user */
        $user = User::findOrFail($userId);

        // Quitar rol vendedora
        $user->removeRole('vendedora');

        session()->flash('message', 'Rol de vendedora quitado correctamente.');

        $this->loadData();
    }

    public function closeModal(): void
    {
        $this->showAddModal = false;
        $this->form = $this->defaultForm();
    }
};
?>
<x-layouts.app>
    @volt('vendedoras')
    <x-app.container class="relative py-8" id="vendedoras-section-container">
        {{-- CSS Localizado para evitar conflictos --}}
        <style>
            #vendedoras-section-container .custom-scroll-pink::-webkit-scrollbar {
                height: 12px;
                width: 10px;
            }
            #vendedoras-section-container .custom-scroll-pink::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 10px;
            }
            #vendedoras-section-container .custom-scroll-pink::-webkit-scrollbar-thumb {
                background: linear-gradient(90deg, #ec4899 0%, #f43f5e 100%);
                border-radius: 10px;
                border: 3px solid rgba(255, 255, 255, 0.1);
            }

            #vendedoras-section-container .glass-card {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.8);
            }
            .dark #vendedoras-section-container .glass-card {
                background: rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>

        {{-- Fondo decorativo --}}
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[5%] left-[20%] w-[35%] h-[35%] rounded-full bg-pink-500/10 blur-[110px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[25%] h-[25%] rounded-full bg-rose-500/10 blur-[90px]"></div>
        </div>

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6 px-2">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Vendedoras</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium tracking-tight">Gestión y asignación de perfiles comerciales Almamia</p>
            </div>
            
            <button wire:click="openAddModal" class="w-full sm:w-auto px-6 py-4 bg-pink-600 text-white rounded-2xl font-black text-sm hover:bg-pink-700 shadow-xl shadow-pink-500/20 transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Agregar vendedora
            </button>
        </div>

        {{-- Toast Mensaje --}}
        @if (session()->has('message'))
            <div class="animate-in slide-in-from-top-4 duration-500 p-4 mb-8 mx-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 shadow-lg backdrop-blur-md font-bold text-sm">
                <div class="bg-emerald-500 p-1 rounded-full text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                {{ session('message') }}
            </div>
        @endif

        {{-- Tabla / Contenido --}}
        <div class="relative custom-scroll-pink overflow-x-auto rounded-[2.5rem]">
            @if($vendedoras->isEmpty())
                <div class="glass-card rounded-[2.5rem] p-20 flex flex-col items-center text-center shadow-2xl shadow-slate-200/50">
                    <div class="h-20 w-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">No hay vendedoras</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-xs mt-2 font-medium">Asigna el rol a un usuario registrado para que aparezca en esta lista.</p>
                </div>
            @else
                <div class="glass-card rounded-[2.5rem] shadow-2xl overflow-hidden min-w-[800px]">
                    <table class="w-full text-left border-separate border-spacing-0">
                        <thead class="bg-slate-950/5">
                            <tr class="text-slate-500">
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Perfil Vendedora</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Roles Activos</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Fecha Alta</th>
                                <th class="px-8 py-6 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/30">
                            @foreach($vendedoras as $user)
                            <tr class="group hover:bg-white/40 transition-all duration-300">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        {{-- COLOR FIJO ROSADO PARA TODOS --}}
                                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white font-black text-xl shadow-lg group-hover:rotate-3 transition-transform">
                                            {{ strtoupper(substr($user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-800 dark:text-white tracking-tight text-lg leading-tight">{{ $user->name }}</div>
                                            <div class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($user->roles as $role)
                                            <span class="px-2.5 py-1 text-[9px] font-black uppercase rounded-lg {{ $role->name === 'vendedora' ? 'bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400' : 'bg-slate-100 dark:bg-white/5 text-slate-500' }} border border-slate-200 dark:border-white/10 shadow-sm">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm font-bold text-slate-500 dark:text-slate-400">
                                    {{ $user->created_at?->format('d M, Y') }}
                                </td>
                                <td class="px-8 py-5 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button 
                                        type="button"
                                        x-data
                                        @click="if (confirm('¿Quitar el rol de vendedora?')) { $wire.removeVendedora({{ $user->id }}) }"
                                        class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white transition-all border border-rose-100 shadow-sm"
                                    >
                                        Remover
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Modal Agregar --}}
        @if($showAddModal)
            <div 
                x-data="{ open: true }"
                x-show="open"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            >
                <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-md" @click="open = false; $wire.closeModal()"></div>
                
                <div class="relative w-full max-w-lg bg-white/90 dark:bg-zinc-900/90 rounded-[3rem] p-10 shadow-2xl border border-white dark:border-white/10 animate-in zoom-in-95 duration-200">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Vincular Vendedora</h2>
                            <p class="text-sm font-medium text-slate-500 mt-1">Otorga permisos comerciales a un usuario.</p>
                        </div>
                        <button @click="open = false; $wire.closeModal()" class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-400 hover:text-rose-500 transition-colors">✕</button>
                    </div>

                    <div class="space-y-6">
                        @if($usuariosDisponibles->isEmpty())
                            <div class="p-8 rounded-[2rem] bg-pink-50 dark:bg-pink-900/10 border border-pink-100 dark:border-pink-500/20 text-center">
                                <p class="text-sm font-black text-pink-600 uppercase tracking-widest">Sin usuarios libres</p>
                                <p class="text-xs font-bold text-slate-500 mt-2 leading-relaxed">No se encontraron usuarios registrados que no tengan ya el rol de vendedora.</p>
                            </div>
                        @else
                            <form wire:submit.prevent="addVendedora" id="addVendedoraForm" class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-4">Seleccionar Usuario</label>
                                    <select 
                                        wire:model="form.user_id" 
                                        class="w-full bg-slate-100 dark:bg-zinc-800 border-none rounded-2xl py-4 px-6 font-bold text-slate-700 dark:text-white focus:ring-4 focus:ring-pink-500/10 transition-all outline-none appearance-none cursor-pointer"
                                    >
                                        <option value="">Buscar por nombre o email...</option>
                                        @foreach($usuariosDisponibles as $user)
                                            <option value="{{ $user->id }}">{{ $user->name ?? $user->email }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.user_id')
                                        <span class="mt-2 ml-4 block text-[10px] font-black text-rose-500 uppercase tracking-tight">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="w-full py-4 bg-pink-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-pink-500/30 hover:bg-pink-700 transition-all active:scale-95">
                                    Confirmar Asignación
                                </button>
                            </form>
                        @endif
                        
                        <button @click="open = false; $wire.closeModal()" class="w-full py-3 text-sm font-black text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors uppercase tracking-widest">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </x-app.container>
    @endvolt
</x-layouts.app> 