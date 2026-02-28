<?php

use function Laravel\Folio\{middleware, name};
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

middleware('auth');
name('perfil');

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $dni = '';
    public string $whatsapp = '';
    public string $direccion = '';
    public string $departamento = '';
    public string $zona = '';

    public array $departamentos = [];
    public array $zonas = [];

    public function mount(): void
    {
        $user = auth()->user();

        $this->name = $user?->name ?? '';
        $this->email = $user?->email ?? '';
        $this->dni = (string) ($user?->profile('dni') ?? '');
        $this->whatsapp = (string) ($user?->profile('whatsapp') ?? '');
        $this->direccion = (string) ($user?->profile('direccion') ?? '');
        $this->departamento = (string) ($user?->profile('departamento') ?? '');
        $this->zona = (string) ($user?->profile('zona') ?? '');

        $this->departamentos = $this->decodeOptions(setting('almamia.departamentos.mendoza'));
        $this->zonas = $this->decodeOptions(setting('almamia.zona.mendoza'));
    }

    protected function decodeOptions($value): array
    {
        $decoded = json_decode($value ?? '[]', true);
        if (! is_array($decoded)) return [];
        return array_values(array_filter($decoded, static fn ($item) => filled($item)));
    }

    public function save(): void
    {
        $user = auth()->user();
        if (! $user) return;

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'dni' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'departamento' => ['nullable', 'string', 'max:255'],
            'zona' => ['nullable', 'string', 'max:255'],
        ], [], [
            'name' => 'nombre',
            'email' => 'correo',
            'dni' => 'DNI',
            'whatsapp' => 'WhatsApp',
            'direccion' => 'dirección',
            'departamento' => 'departamento',
            'zona' => 'zona',
        ]);

        $user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
        ])->save();

        $user->setProfileKeyValue('dni', $this->dni);
        $user->setProfileKeyValue('whatsapp', $this->whatsapp);
        $user->setProfileKeyValue('direccion', $this->direccion);
        $user->setProfileKeyValue('departamento', $this->departamento);
        $user->setProfileKeyValue('zona', $this->zona);

        session()->flash('perfil_guardado', '¡Tu perfil ha sido actualizado!');
    }
};
?>

<x-layouts.app>
    @volt('perfil')
        <div class="max-w-4xl mx-auto py-10 px-4 space-y-8">
            
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="inline-block px-4 py-1.5 bg-[#294395]/10 text-[#294395] text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-4">
                        Mi Cuenta AlmaMia
                    </span>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Datos del Perfil</h1>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-lg leading-relaxed">
                        Personaliza tu información para recibir catálogos, premios y coordinar entregas en tu zona.
                    </p>
                </div>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-[#e91e63]/10 dark:bg-[#e91e63]/5 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-700"></div>
                <div class="absolute right-12 top-12 w-12 h-12 border-4 border-[#294395]/5 rounded-full"></div>
            </div>

            @if (session()->has('perfil_guardado'))
                <div class="flex items-center p-5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-[1.5rem] text-emerald-800 dark:text-emerald-400 text-sm animate-in zoom-in-95 duration-300">
                    <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center mr-4 shadow-lg shadow-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="font-bold uppercase tracking-tight">{{ session('perfil_guardado') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid gap-8 md:grid-cols-2">
                    
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm space-y-6 relative">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-[#294395] text-white rounded-xl shadow-md shadow-[#294395]/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Contacto y DNI</h2>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-600 dark:text-slate-400 ml-1 uppercase" for="name">Nombre Completo</label>
                            <input type="text" wire:model.defer="name" id="name" class="w-full bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-[#294395]/30 focus:bg-white rounded-2xl px-5 py-4 text-sm font-medium transition-all dark:text-white outline-none" placeholder="Ana Pérez">
                            @error('name') <p class="text-[10px] text-[#e91e63] font-bold ml-2 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-600 dark:text-slate-400 ml-1 uppercase" for="email">E-mail</label>
                            <input type="email" wire:model.defer="email" id="email" class="w-full bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-[#294395]/30 focus:bg-white rounded-2xl px-5 py-4 text-sm font-medium transition-all dark:text-white outline-none">
                            @error('email') <p class="text-[10px] text-[#e91e63] font-bold ml-2 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-600 dark:text-slate-400 ml-1 uppercase" for="dni">DNI</label>
                                <input type="text" wire:model.defer="dni" id="dni" class="w-full bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-[#294395]/30 focus:bg-white rounded-2xl px-5 py-4 text-sm font-medium transition-all dark:text-white outline-none" placeholder="8 dígitos">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-600 dark:text-slate-400 ml-1 uppercase" for="whatsapp">WhatsApp</label>
                                <input type="text" wire:model.defer="whatsapp" id="whatsapp" class="w-full bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-[#294395]/30 focus:bg-white rounded-2xl px-5 py-4 text-sm font-medium transition-all dark:text-white outline-none" placeholder="Sin el 0 ni el 15">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-[#e91e63] text-white rounded-xl shadow-md shadow-[#e91e63]/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Logística</h2>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-black text-slate-600 dark:text-slate-400 ml-1 uppercase" for="direccion">Dirección de Entrega</label>
                            <input type="text" wire:model.defer="direccion" id="direccion" class="w-full bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent focus:border-[#e91e63]/30 focus:bg-white rounded-2xl px-5 py-4 text-sm font-medium transition-all dark:text-white outline-none" placeholder="Calle y número">
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
    {{-- Selector: Localidad / Departamento --}}
    <div class="space-y-1.5">
        <label class="text-[10px] font-black uppercase text-slate-500 ml-1 tracking-widest">Localidad / Depto</label>
        <div class="relative group">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
            <select wire:model.defer="form.departamento" 
                class="w-full appearance-none rounded-xl border-slate-300 bg-white py-3.5 pl-11 pr-10 text-sm font-bold shadow-sm transition-all focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 outline-none">
                <option value="">Seleccioná de la lista...</option>
                @foreach($departamentos as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
        @error('form.departamento') <p class="mt-1 text-[10px] font-bold text-rose-500 ml-1">{{ $message }}</p> @enderror
    </div>

    {{-- Selector: Zona --}}
    <div class="space-y-1.5">
        <label class="text-[10px] font-black uppercase text-slate-500 ml-1 tracking-widest">Zona de Red</label>
        <div class="relative group">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.487V6a2 2 0 011.106-1.789l5.447-2.724a2 2 0 011.894 0l5.447 2.724A2 2 0 0118 6v9.487a2 2 0 01-1.106 1.789L11.447 20a2 2 0 01-1.894 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v17M12 9l-3 3 3 3" /></svg>
            </div>
            <select wire:model.defer="form.zona" 
                class="w-full appearance-none rounded-xl border-slate-300 bg-white py-3.5 pl-11 pr-10 text-sm font-bold shadow-sm transition-all focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 outline-none">
                <option value="">Seleccioná zona...</option>
                @foreach($zonas as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
        @error('form.zona') <p class="mt-1 text-[10px] font-bold text-rose-500 ml-1">{{ $message }}</p> @enderror
    </div>
</div>
                    </div>
                </div>

                <div class="bg-[#294395] p-8 rounded-[2.5rem] shadow-2xl shadow-[#294395]/30 flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden">
                    <div class="relative z-10 text-center sm:text-left">
                        <p class="text-white font-black text-lg tracking-tight">Confirmar Datos</p>
                    </div>
                    
                    <button type="submit" wire:loading.attr="disabled" class="fi-btn fi-color-primary bg-slate-50 !text-white w-full sm:w-auto relative z-10 px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-105 active:scale-95 disabled:opacity-70 shadow-xl shadow-black/10">
                        <span wire:loading.remove>Guardar Cambios</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-[#294395]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Guardando...
                        </span>
                    </button>

                    <div class="absolute left-0 top-0 w-full h-full opacity-5 pointer-events-none">
                        <svg width="100%" height="100%"><pattern id="pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" fill="white"/></pattern><rect width="100%" height="100%" fill="url(#pattern)"/></svg>
                    </div>
                </div>
            </form>
        </div>
    @endvolt
</x-layouts.app>