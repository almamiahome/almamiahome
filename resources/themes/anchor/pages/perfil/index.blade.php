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

        if (! is_array($decoded)) {
            return [];
        }

        return array_values(array_filter($decoded, static fn ($item) => filled($item)));
    }

    public function save(): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

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

        session()->flash('perfil_guardado', 'Guardamos tus datos correctamente.');
    }
};
?>

<x-layouts.app>
    @volt('perfil')
        <div class="max-w-5xl mx-auto py-6 lg:py-10 space-y-6">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#294395]">Tu perfil</p>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white sm:text-3xl">Actualizá tus datos</h1>
                <p class="text-sm text-slate-600 dark:text-slate-300 sm:text-base">
                    Conservamos este perfil para personalizar tu experiencia y ubicarte en la red correcta.
                    Los campos siguen el mismo estilo que usamos durante el onboarding para que te resulte familiar.
                </p>
            </div>

            @if (session()->has('perfil_guardado'))
                <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 shadow-sm dark:border-emerald-500/60 dark:bg-emerald-900/30 dark:text-emerald-100">
                    {{ session('perfil_guardado') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-zinc-900">
                        <div class="space-y-1">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Identidad y contacto</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Datos básicos para mantenerte identificada dentro de AlmaMia.</p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200" for="perfil-name">Nombre completo</label>
                            <input
                                id="perfil-name"
                                type="text"
                                wire:model.defer="name"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Ej: Ana Pérez"
                            >
                            @error('name')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200" for="perfil-email">Correo electrónico</label>
                            <input
                                id="perfil-email"
                                type="email"
                                wire:model.defer="email"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="tucorreo@ejemplo.com"
                            >
                            @error('email')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200" for="perfil-whatsapp">WhatsApp</label>
                            <input
                                id="perfil-whatsapp"
                                type="text"
                                wire:model.defer="whatsapp"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Número con código de área"
                            >
                            @error('whatsapp')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200" for="perfil-dni">DNI</label>
                            <input
                                id="perfil-dni"
                                type="text"
                                wire:model.defer="dni"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Sin puntos ni espacios"
                            >
                            @error('dni')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-zinc-900">
                        <div class="space-y-1">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Ubicación y zona</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Usamos estos datos para asignarte la red y logística correcta.</p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200" for="perfil-direccion">Dirección</label>
                            <input
                                id="perfil-direccion"
                                type="text"
                                wire:model.defer="direccion"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Calle y número"
                            >
                            @error('direccion')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200" for="perfil-departamento">Departamento</label>
                            <select
                                id="perfil-departamento"
                                wire:model.defer="departamento"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                            >
                                <option value="">Elegí un departamento</option>
                                @foreach($departamentos as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('departamento')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-200" for="perfil-zona">Zona</label>
                            <select
                                id="perfil-zona"
                                wire:model.defer="zona"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                            >
                                <option value="">Elegí una zona</option>
                                @foreach($zonas as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('zona')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm dark:border-slate-700 dark:bg-zinc-900">
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">Mantén tu perfil al día</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Así podremos contactarte y asignarte al equipo correcto.</p>
                    </div>
                    <x-button type="submit" class="px-6" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar cambios</span>
                        <span wire:loading class="flex items-center gap-2">
                            <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/60 border-t-white"></span>
                            Guardando...
                        </span>
                    </x-button>
                </div>
            </form>
        </div>
    @endvolt
</x-layouts.app>
