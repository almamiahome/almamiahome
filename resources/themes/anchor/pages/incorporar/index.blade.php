<?php

use function Laravel\Folio\{middleware, name};
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Volt\Component;

middleware('auth');
name('incorporar');

new class extends Component {
    public ?User $currentUser = null;
    public string $currentRole = 'registered';

    public array $form = [];
    public bool $isEditing = false;
    public ?int $editingUserId = null;
    public ?string $editingUserName = null;

    /** @var Collection|array */
    public $usuarios;
    /** @var Collection|array */
    public $coordinadoras;
    /** @var Collection|array */
    public $lideres;
    /** @var Collection|array */
    public $coordinadorasDelLider;

    public array $tiposPermitidos = [];
    public array $departamentosDisponibles = [];
    public array $zonasDisponibles = [];

    public function mount(): void
    {
        $this->currentUser = auth()->user();
        $this->currentRole = $this->currentUser?->getRoleNames()->first() ?? 'registered';
        $this->tiposPermitidos = $this->resolveAllowedTipos();
        $this->departamentosDisponibles = $this->resolveSettingOptions('almamia.departamentos.mendoza');
        $this->zonasDisponibles = $this->resolveSettingOptions('almamia.zona.mendoza');
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    protected function resolveSettingOptions(string $key): array
    {
        $items = json_decode(setting($key) ?? '[]', true) ?? [];

        if (! is_array($items)) {
            return [];
        }

        $items = array_values(array_filter(array_map(function ($item) {
            if (! is_scalar($item)) {
                return null;
            }

            $normalized = trim((string) $item);

            return $normalized === '' ? null : $normalized;
        }, $items)));

        return array_values(array_unique($items));
    }

    protected function resolveAllowedTipos(): array
    {
        return match ($this->currentRole) {
            'admin' => ['vendedora', 'lider', 'coordinadora'],
            'coordinadora' => ['lider'],
            'lider' => ['vendedora'],
            default => [],
        };
    }

    protected function defaultForm(): array
    {
        $tipo = $this->tiposPermitidos[0] ?? 'vendedora';

        return [
            'tipo' => $tipo,
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'dni' => '',
            'whatsapp' => '',
            'direccion' => '',
            'zona' => '',
            'departamento' => '',
            'lider_id' => $this->currentRole === 'lider' ? $this->currentUser?->id : null,
            'coordinadora_id' => $this->currentRole === 'coordinadora' ? $this->currentUser?->id : null,
        ];
    }

    protected function loadData(): void
    {
        $this->coordinadoras = User::role('coordinadora')->orderBy('name')->get();
        $this->lideres = User::role('lider')->orderBy('name')->get();
        $this->coordinadorasDelLider = $this->currentRole === 'lider'
            ? $this->currentUser?->coordinadoras()->orderBy('name')->get()
            : collect();

        if ($this->coordinadorasDelLider instanceof Collection
            && $this->coordinadorasDelLider->count() === 1
            && empty($this->form['coordinadora_id'])) {
            $this->form['coordinadora_id'] = $this->coordinadorasDelLider->first()->id;
        }

        $this->usuarios = $this->resolveUsuarios();
    }

    protected function resolveUsuarios()
    {
        return match ($this->currentRole) {
            'admin' => User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['vendedora', 'lider', 'coordinadora']);
                })
                ->with(['roles', 'lideres', 'coordinadoras'])
                ->orderBy('name')
                ->get(),
            'lider' => User::role('vendedora')
                ->where('lider_id', $this->currentUser?->id)
                ->with('roles')
                ->orderBy('name')
                ->get(),
            'coordinadora' => User::role('lider')
                ->where('coordinadora_id', $this->currentUser?->id)
                ->with('roles')
                ->orderBy('name')
                ->get(),
            default => collect(),
        };
    }

    public function updatedFormTipo(): void
    {
        if (($this->form['tipo'] ?? null) === 'coordinadora') {
            $this->form['lider_id'] = null;
            $this->form['coordinadora_id'] = null;
        }

        if (($this->form['tipo'] ?? null) === 'lider') {
            $this->form['lider_id'] = null;
        }

        $this->resetValidation();
    }

    protected function rules(): array
    {
        $passwordRules = $this->isEditing
            ? ['nullable', 'string', 'confirmed', Password::defaults()]
            : ['required', 'string', 'confirmed', Password::defaults()];

        $rules = [
            'form.tipo' => ['required', 'string', Rule::in($this->tiposPermitidos)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingUserId)],
            'form.password' => $passwordRules,
            'form.password_confirmation' => $this->isEditing ? ['nullable'] : ['required'],
            'form.dni' => ['nullable', 'string', 'max:255'],
            'form.whatsapp' => ['nullable', 'string', 'max:255'],
            'form.direccion' => ['nullable', 'string', 'max:255'],
            'form.zona' => ['nullable', 'string', 'max:255', Rule::in($this->zonasDisponibles)],
            'form.departamento' => ['nullable', 'string', 'max:255', Rule::in($this->departamentosDisponibles)],
            'form.lider_id' => ['nullable', 'integer', 'exists:users,id'],
            'form.coordinadora_id' => ['nullable', 'integer', 'exists:users,id'],
        ];

        if (($this->form['tipo'] ?? null) === 'vendedora') {
            $rules['form.lider_id'] = ['required', 'integer', 'exists:users,id'];
            $rules['form.coordinadora_id'] = ['required', 'integer', 'exists:users,id'];
        }

        if (($this->form['tipo'] ?? null) === 'lider') {
            $rules['form.coordinadora_id'] = ['required', 'integer', 'exists:users,id'];
        }

        return $rules;
    }

    public function save()
    {
        if (empty($this->tiposPermitidos)) {
            $this->addError('form.tipo', 'No tenés permisos para incorporar usuarios.');
            return null;
        }

        if ($this->currentRole === 'lider'
            && ($this->form['tipo'] ?? null) === 'vendedora'
            && ($this->coordinadorasDelLider instanceof Collection && $this->coordinadorasDelLider->isEmpty())) {
            $this->addError('form.coordinadora_id', 'Necesitás vincular una coordinadora antes de poder incorporar vendedoras.');
            return null;
        }

        $validated = $this->validate($this->rules());
        $data = $validated['form'];
        $data['lider_id'] = $data['lider_id'] ?: null;
        $data['coordinadora_id'] = $data['coordinadora_id'] ?: null;

        $this->enforceRoleLimits($data);

        if ($this->isEditing) {
            if ($this->updateExistingUser($data)) {
                session()->flash('message', 'Usuario actualizado correctamente.');
            }
        } else {
            $this->createNewUser($data);
            session()->flash('message', 'Usuario creado correctamente.');
        }

        return redirect()->route('incorporar');
    }

    protected function enforceRoleLimits(array &$data): void
    {
        if ($this->currentRole === 'lider') {
            $data['tipo'] = 'vendedora';
            $data['lider_id'] = $this->currentUser?->id;
        }

        if ($this->currentRole === 'coordinadora') {
            $data['tipo'] = 'lider';
            $data['coordinadora_id'] = $this->currentUser?->id;
        }

        if ($data['tipo'] === 'coordinadora') {
            $data['lider_id'] = null;
            $data['coordinadora_id'] = null;
        }
    }

    protected function createNewUser(array $data): void
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $this->applyVinculos($user, $data);
        $user->save();

        $this->syncRoleByTipo($user, $data['tipo']);
        $this->syncCoordinadoraPivot($user, $data['tipo']);
        $this->persistProfileValues($user, $data);
    }

    protected function updateExistingUser(array $data): bool
    {
        if (! $this->editingUserId) {
            session()->flash('error', 'No se encontró el usuario seleccionado.');
            return false;
        }

        $user = $this->findUserForEdit($this->editingUserId);

        if (! $user) {
            session()->flash('error', 'No tenés permisos para editar este usuario.');
            return false;
        }

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $this->applyVinculos($user, $data);
        $user->save();

        $this->syncRoleByTipo($user, $data['tipo']);
        $this->syncCoordinadoraPivot($user, $data['tipo']);
        $this->persistProfileValues($user, $data);

        return true;
    }

    protected function applyVinculos(User $user, array $data): void
    {
        if ($data['tipo'] === 'vendedora') {
            $user->lider_id = $data['lider_id'];
            $user->coordinadora_id = $data['coordinadora_id'];
        } elseif ($data['tipo'] === 'lider') {
            $user->lider_id = null;
            $user->coordinadora_id = $data['coordinadora_id'];
        } else {
            $user->lider_id = null;
            $user->coordinadora_id = null;
        }
    }

    protected function syncRoleByTipo(User $user, string $tipo): void
    {
        $roleName = match ($tipo) {
            'coordinadora' => 'coordinadora',
            'lider' => 'lider',
            default => 'vendedora',
        };

        $user->syncRoles([$roleName]);
    }

    protected function syncCoordinadoraPivot(User $user, string $tipo): void
    {
        if ($tipo === 'lider' && $user->coordinadora_id) {
            $user->coordinadoras()->sync([$user->coordinadora_id]);
        } else {
            $user->coordinadoras()->detach();
        }
    }

    protected function persistProfileValues(User $user, array $data): void
    {
        foreach (['dni', 'whatsapp', 'direccion', 'zona', 'departamento'] as $key) {
            $user->setProfileKeyValue($key, $data[$key] ?? '');
        }
    }

    protected function findUserForEdit(int $userId): ?User
    {
        $query = User::query()->with('roles');

        if ($this->currentRole === 'lider') {
            $query->where('lider_id', $this->currentUser?->id);
        }

        if ($this->currentRole === 'coordinadora') {
            $query->where('coordinadora_id', $this->currentUser?->id);
        }

        return $query->find($userId);
    }

    public function startEdit(int $userId): void
    {
        if ($this->currentRole !== 'admin') {
            session()->flash('error', 'Solo el admin puede editar usuarios.');
            return;
        }

        $user = $this->findUserForEdit($userId);

        if (! $user) {
            session()->flash('error', 'No tenés permisos para editar este usuario.');
            return;
        }

        $tipo = $this->inferTipoDesdeRoles($user);

        if (! in_array($tipo, $this->tiposPermitidos, true)) {
            session()->flash('error', 'No podés editar este tipo de usuario.');
            return;
        }

        $this->isEditing = true;
        $this->editingUserId = $user->id;
        $this->editingUserName = $user->name;

        $this->form = [
            'tipo' => $tipo,
            'name' => $user->name,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
            'dni' => optional($user->profileKeyValue('dni'))->value ?? '',
            'whatsapp' => optional($user->profileKeyValue('whatsapp'))->value ?? '',
            'direccion' => optional($user->profileKeyValue('direccion'))->value ?? '',
            'zona' => optional($user->profileKeyValue('zona'))->value ?? '',
            'departamento' => optional($user->profileKeyValue('departamento'))->value ?? '',
            'lider_id' => $user->lider_id,
            'coordinadora_id' => $user->coordinadora_id,
        ];

        $this->resetValidation();
    }

    protected function inferTipoDesdeRoles(User $user): string
    {
        if ($user->hasRole('coordinadora')) {
            return 'coordinadora';
        }

        if ($user->hasRole('lider')) {
            return 'lider';
        }

        return 'vendedora';
    }

    public function cancelEdit(): void
    {
        $this->resetFormState();
    }

    protected function resetFormState(): void
    {
        $this->form = $this->defaultForm();
        $this->isEditing = false;
        $this->editingUserId = null;
        $this->editingUserName = null;
        $this->resetValidation();
        $this->resetErrorBag();
    }
};
?>

<x-layouts.app>
    @volt('incorporar')
        <x-app.container class="space-y-8">
            

            @if(empty($tiposPermitidos))
                <div class="mx-auto max-w-2xl rounded-2xl border border-amber-300 bg-amber-50/90 p-6 text-sm text-amber-900 shadow-sm dark:border-amber-500/60 dark:bg-amber-900/30 dark:text-amber-100">
                    No tenés permisos para incorporar nuevas usuarias.
                </div>
            @else
                <div class="space-y-8">
                    {{-- FORMULARIO PRINCIPAL - INSPIRADO EN ONBOARDING --}}
<div class="mx-auto w-full max-w-3xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-slate-200/60 dark:border-zinc-800 dark:bg-zinc-950 dark:shadow-none">
    {{-- Header con Identidad de Rol --}}
    <div class="bg-slate-50/80 px-6 py-8 text-center dark:bg-zinc-900/50 border-b border-slate-100 dark:border-zinc-800 sm:px-10">
        @if($currentRole === 'admin')
            <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                Gestión de red AlmaMia
            </span>
        @elseif($currentRole === 'lider')
            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                Incorporá nuevas vendedoras
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Incorporá nuevos líderes
            </span>
        @endif

        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-zinc-100 sm:text-4xl">
            {{ $isEditing ? 'Actualizar Perfil' : 'Completá los datos' }}
        </h2>
        
        @if($isEditing && $currentRole === 'admin')
            <div class="mt-6 flex items-center justify-center">
                <div class="flex items-center gap-2 rounded-full border border-indigo-200 bg-white pl-2 pr-4 py-1.5 shadow-sm dark:border-indigo-900/50 dark:bg-zinc-900">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-600"></span>
                    </span>
                    <span class="text-xs font-bold text-slate-700 dark:text-indigo-300">Editando a: {{ $editingUserName }}</span>
                    <button type="button" wire:click="cancelEdit" class="ml-2 text-[10px] font-black uppercase text-rose-500 hover:text-rose-700 transition-colors">
                        [ Cancelar ]
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="p-6 sm:p-10">
        {{-- Alertas de Feedback --}}
        @if (session()->has('message'))
            <div class="mb-8 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-400 animate-in fade-in slide-in-from-top-4">
                <svg class="h-6 w-6 flex-shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="text-sm font-bold">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-10">
            
            {{-- SECCIÓN 1: ROL Y JERARQUÍA --}}
            <section class="space-y-6">
                @if($currentRole === 'admin')
                    <div>
                        <label class="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-slate-800 dark:text-zinc-200 mb-4">
                            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-600 text-[10px] text-white">01</span>
                            Tipo de usuaria
                        </label>
                        <div class="grid gap-4 sm:grid-cols-3">
                            @foreach(['vendedora' => 'Vendedora', 'lider' => 'Líder', 'coordinadora' => 'Coordinadora'] as $val => $label)
                                <label @class([
                                    'relative flex cursor-pointer flex-col rounded-2xl border-2 p-4 transition-all shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/20',
                                    'border-indigo-600 bg-indigo-50/50 dark:border-indigo-500 dark:bg-indigo-500/10' => ($form['tipo'] ?? '') === $val,
                                    'border-slate-200 bg-white hover:border-slate-300 dark:border-zinc-800 dark:bg-zinc-900' => ($form['tipo'] ?? '') !== $val,
                                ])>
                                    <input type="radio" value="{{ $val }}" wire:model.live="form.tipo" class="sr-only">
                                    <span @class([
                                        'text-sm font-black',
                                        'text-indigo-700 dark:text-indigo-400' => ($form['tipo'] ?? '') === $val,
                                        'text-slate-900 dark:text-zinc-200' => ($form['tipo'] ?? '') !== $val,
                                    ])>{{ $label }}</span>
                                    <span class="mt-1 text-[11px] leading-tight text-slate-500 dark:text-zinc-500">
                                        {{ $val === 'vendedora' ? 'Venta directa y pedidos.' : ($val === 'lider' ? 'Gestión de vendedoras.' : 'Control total de red.') }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('form.tipo') <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                @else
                    <input type="hidden" wire:model="form.tipo">
                    <div class="flex items-center gap-3 rounded-2xl bg-indigo-50 p-4 border border-indigo-100 dark:bg-indigo-900/20 dark:border-indigo-800">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm font-medium text-indigo-900 dark:text-indigo-300">
                            Alta de nueva <span class="font-black underline">{{ $currentRole === 'lider' ? 'Vendedora' : 'Líder' }}</span> vinculada a tu estructura.
                        </p>
                    </div>
                @endif

                {{-- Vínculos Jerárquicos --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-5 dark:border-zinc-800 dark:bg-zinc-900/20 space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-400">Vinculación Estructural</h4>
                    
                    <div class="grid gap-4 sm:grid-cols-2">
                        @if($currentRole === 'lider')
                            <input type="hidden" wire:model="form.lider_id">
                            <div class="col-span-full flex items-center gap-3 py-2">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs uppercase">TU</div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase">Líder a Cargo</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-zinc-100">Tu cuenta personal</p>
                                </div>
                            </div>
                        @elseif($currentRole === 'admin')
                            @if(($form['tipo'] ?? '') === 'vendedora')
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Asignar Líder</label>
                                    <div class="relative">
                                        <select wire:model="form.lider_id" class="w-full appearance-none rounded-xl border-slate-300 bg-white py-3 pl-4 pr-10 text-sm font-bold shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                                            <option value="">Seleccioná un líder...</option>
                                            @foreach($lideres as $lider) <option value="{{ $lider->id }}">{{ $lider->name }}</option> @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg></div>
                                    </div>
                                </div>
                            @endif

                            @if(in_array(($form['tipo'] ?? ''), ['vendedora', 'lider']))
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Asignar Coordinadora</label>
                                    <div class="relative">
                                        <select wire:model="form.coordinadora_id" class="w-full appearance-none rounded-xl border-slate-300 bg-white py-3 pl-4 pr-10 text-sm font-bold shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                                            <option value="">Seleccioná coordinadora...</option>
                                            @foreach($coordinadoras as $coord) <option value="{{ $coord->id }}">{{ $coord->name }}</option> @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg></div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </section>

            {{-- SECCIÓN 2: ACCESO --}}
            <section class="space-y-5">
                <label class="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-slate-800 dark:text-zinc-200">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-600 text-[10px] text-white">02</span>
                    Credenciales de Acceso
                </label>
                
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-bold text-slate-600 dark:text-zinc-400 ml-1">Nombre Completo</label>
                        <div class="relative group">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <input type="text" wire:model.defer="form.name" placeholder="Ej: Lucía Martínez" 
                                class="w-full rounded-xl border-slate-300 bg-white py-3.5 pl-11 pr-4 text-sm font-semibold shadow-sm transition-all focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-bold text-slate-600 dark:text-zinc-400 ml-1">Correo Electrónico</label>
                        <div class="relative group">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <input type="email" wire:model.defer="form.email" placeholder="email@alma-mia.com" 
                                class="w-full rounded-xl border-slate-300 bg-white py-3.5 pl-11 pr-4 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-600 dark:text-zinc-400 ml-1">Contraseña</label>
                        <div class="relative group">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input type="password" wire:model.defer="form.password" placeholder="••••••••" 
                                class="w-full rounded-xl border-slate-300 bg-white py-3.5 pl-11 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-600 dark:text-zinc-400 ml-1">Confirmar</label>
                        <div class="relative group">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <input type="password" wire:model.defer="form.password_confirmation" placeholder="••••••••" 
                                class="w-full rounded-xl border-slate-300 bg-white py-3.5 pl-11 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950">
                        </div>
                    </div>
                </div>
            </section>

            {{-- SECCIÓN 3: DATOS PERSONALES --}}
            <section class="rounded-3xl border border-slate-200 bg-slate-50/50 p-6 dark:border-zinc-800 dark:bg-zinc-900/40 space-y-6">
                <label class="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-slate-800 dark:text-zinc-200">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-600 text-[10px] text-white">03</span>
                    Información Personal
                </label>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase text-slate-500 ml-1">DNI / Identificación</label>
                        <input type="text" wire:model.defer="form.dni" placeholder="Sin puntos" 
                            class="w-full rounded-xl border-slate-300 bg-white py-3 px-4 text-sm font-bold shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase text-slate-500 ml-1">WhatsApp</label>
                        <div class="relative group">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-emerald-500 group-focus-within:animate-pulse">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.224-3.82c1.516.903 3.125 1.378 4.77 1.379 5.093 0 9.248-4.154 9.25-9.248.001-2.47-.961-4.79-2.708-6.539-1.748-1.748-4.068-2.71-6.538-2.71-5.093 0-9.248 4.154-9.25 9.249-.001 1.777.469 3.511 1.359 5.021l-.918 3.355 3.435-.902z" /></svg>
                            </div>
                            <input type="text" wire:model.defer="form.whatsapp" placeholder="+54 9..." 
                                class="w-full rounded-xl border-slate-300 bg-white py-3 pl-11 pr-4 text-sm font-bold shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950">
                        </div>
                    </div>

                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Dirección Residencial</label>
                        <div class="relative group">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                            </div>
                            <input type="text" wire:model.defer="form.direccion" placeholder="Calle, número, barrio" 
                                class="w-full rounded-xl border-slate-300 bg-white py-3 pl-11 pr-4 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Localidad / Depto</label>
                        <select wire:model.defer="form.departamento"
                            class="w-full rounded-xl border-slate-300 bg-white py-3 px-4 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950">
                            <option value="">Seleccioná un departamento</option>
                            @foreach($departamentosDisponibles as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase text-slate-500 ml-1">Zona</label>
                        <select wire:model.defer="form.zona"
                            class="w-full rounded-xl border-slate-300 bg-white py-3 px-4 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-zinc-700 dark:bg-zinc-950">
                            <option value="">Seleccioná una zona</option>
                            @foreach($zonasDisponibles as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            {{-- Botón de Acción Final --}}
            <div class="pt-6">
                <button type="submit" wire:loading.attr="disabled"
                    class="group relative flex w-full items-center justify-center overflow-hidden rounded-2xl bg-indigo-600 px-8 py-5 text-white transition-all hover:bg-indigo-700 hover:shadow-2xl hover:shadow-indigo-500/40 active:scale-[0.98] disabled:opacity-70">
                    
                    <div wire:loading.remove class="flex items-center gap-3">
                        <span class="text-sm font-black uppercase tracking-widest">
                            {{ $isEditing ? 'Guardar Cambios' : 'Finalizar Registro' }}
                        </span>
                        <svg class="h-5 w-5 transition-transform group-hover:translate-x-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                    
                    <div wire:loading class="flex items-center gap-3">
                        <svg class="h-6 w-6 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="text-sm font-black uppercase tracking-widest">Sincronizando...</span>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
                    {{-- RESUMEN DE PERMISOS --}}
<div class="mx-auto w-full max-w-3xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-950">
    <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 dark:bg-zinc-900/50 dark:border-zinc-800">
        <h3 class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-900 dark:text-zinc-100">
            <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Guía de alcance de tu rol
        </h3>
    </div>
    
    <div class="p-6">
        @php
            $config = [
                'admin' => ['color' => 'indigo', 'items' => ['Podés crear vendedoras, líderes y coordinadoras.', 'Definís la relación entre cada nivel de la red.', 'Podés editar cualquier usuaria desde esta vista.']],
                'lider' => ['color' => 'emerald', 'items' => ['Solo podés crear vendedoras.', 'Quedan vinculadas a vos como líder.', 'También quedan asociadas a tu coordinadora.']],
                'coordinadora' => ['color' => 'amber', 'items' => ['Solo podés crear líderes.', 'Quedan vinculados directamente a vos.', 'Desde aquí podés gestionar tu red de líderes.']]
            ];
            $current = $config[$currentRole] ?? $config['admin'];
        @endphp

        <div class="grid gap-4 sm:grid-cols-1">
            @foreach($current['items'] as $item)
                <div class="flex items-center gap-3 rounded-2xl border border-{{ $current['color'] }}-100 bg-{{ $current['color'] }}-50/30 p-4 dark:border-{{ $current['color'] }}-900/30 dark:bg-{{ $current['color'] }}-950/10">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-{{ $current['color'] }}-100 text-{{ $current['color'] }}-600 dark:bg-{{ $current['color'] }}-900/50">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-{{ $current['color'] }}-900 dark:text-{{ $current['color'] }}-300">{{ $item }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
                    {{-- LISTADOS SEGÚN ROL (SOLO ADMIN TIENE ACCIONES) --}}
                    @if($currentRole === 'admin')
                        <div class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white/95 p-4 text-sm shadow-md shadow-slate-200/80 dark:border-slate-700 dark:bg-slate-900/95 dark:text-slate-100 dark:shadow-black/40">
                            <h3 class="mb-3 text-sm font-semibold text-slate-900 dark:text-slate-50">Usuarias actuales</h3>
                            @if($usuarios->isEmpty())
                                <p class="text-sm text-slate-600 dark:text-slate-300">Todavía no hay usuarias cargadas.</p>
                            @else
                                <div class="max-h-96 overflow-auto rounded-xl border border-slate-200 dark:border-slate-700">
                                    <table class="min-w-full divide-y divide-slate-200 text-xs dark:divide-slate-700">
                                        <thead class="bg-slate-100 dark:bg-slate-800">
                                            <tr>
                                                <th class="px-3 py-2 text-left font-semibold text-slate-700 dark:text-slate-100">Nombre</th>
                                                <th class="px-3 py-2 text-left font-semibold text-slate-700 dark:text-slate-100">Email</th>
                                                <th class="px-3 py-2 text-left font-semibold text-slate-700 dark:text-slate-100">Rol</th>
                                                <th class="px-3 py-2 text-left font-semibold text-slate-700 dark:text-slate-100">Líderes</th>
                                                <th class="px-3 py-2 text-left font-semibold text-slate-700 dark:text-slate-100">Coordinadoras</th>
                                                <th class="px-3 py-2 text-right font-semibold text-slate-700 dark:text-slate-100">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-900">
                                            @foreach($usuarios as $user)
                                                <tr>
                                                    <td class="px-3 py-2 text-slate-900 dark:text-slate-50 whitespace-normal break-words">
                                                        {{ $user->name }}
                                                    </td>
                                                    <td class="px-3 py-2 text-slate-700 dark:text-slate-200 whitespace-normal break-words">
                                                        {{ $user->email }}
                                                    </td>
                                                    <td class="px-3 py-2 text-slate-700 dark:text-slate-200 whitespace-normal break-words">
                                                        {{ $user->getRoleNames()->implode(', ') }}
                                                    </td>
                                                    <td class="px-3 py-2 text-slate-700 dark:text-slate-200 whitespace-normal break-words">
                                                        {{ $user->lideres->pluck('name')->implode(' / ') ?: '—' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-slate-700 dark:text-slate-200 whitespace-normal break-words">
                                                        {{ $user->coordinadoras->pluck('name')->implode(' / ') ?: '—' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-right">
                                                        <button
                                                            type="button"
                                                            class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-100 dark:bg-indigo-900/40 dark:text-indigo-100 dark:hover:bg-indigo-800/70"
                                                            wire:click="startEdit({{ $user->id }})"
                                                        >
                                                            Editar
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-300">Seleccioná "Editar" para modificar datos o vínculos.</p>
                            @endif
                        </div>
                    @elseif($currentRole === 'lider')
                        <div class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white/95 p-4 text-sm shadow-md shadow-slate-200/80 dark:border-slate-700 dark:bg-slate-900/95 dark:text-slate-100 dark:shadow-black/40">
                            <h3 class="mb-3 text-sm font-semibold text-slate-900 dark:text-slate-50">Tus vendedoras</h3>
                            @if($usuarios->isEmpty())
                                <p class="text-sm text-slate-600 dark:text-slate-300">Todavía no incorporaste vendedoras.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($usuarios as $user)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 text-sm shadow-sm dark:border-slate-600 dark:bg-slate-800/90">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <p class="font-semibold text-slate-900 dark:text-slate-50 whitespace-normal break-words">{{ $user->name }}</p>
                                                    <p class="text-xs text-slate-700 dark:text-slate-200 whitespace-normal break-words">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @elseif($currentRole === 'coordinadora')
                        <div class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white/95 p-4 text-sm shadow-md shadow-slate-200/80 dark:border-slate-700 dark:bg-slate-900/95 dark:text-slate-100 dark:shadow-black/40">
                            <h3 class="mb-3 text-sm font-semibold text-slate-900 dark:text-slate-50">Tus líderes</h3>
                            @if($usuarios->isEmpty())
                                <p class="text-sm text-slate-600 dark:text-slate-300">Todavía no incorporaste líderes.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($usuarios as $user)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 text-sm shadow-sm dark:border-slate-600 dark:bg-slate-800/90">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <p class="font-semibold text-slate-900 dark:text-slate-50 whitespace-normal break-words">{{ $user->name }}</p>
                                                    <p class="text-xs text-slate-700 dark:text-slate-200 whitespace-normal break-words">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
