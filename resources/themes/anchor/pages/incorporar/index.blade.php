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

    public function mount(): void
    {
        $this->currentUser = auth()->user();
        $this->currentRole = $this->currentUser?->getRoleNames()->first() ?? 'registered';
        $this->tiposPermitidos = $this->resolveAllowedTipos();
        $this->form = $this->defaultForm();
        $this->loadData();
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
            'form.zona' => ['nullable', 'string', 'max:255'],
            'form.departamento' => ['nullable', 'string', 'max:255'],
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
                    <div class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl shadow-slate-200/80 dark:border-slate-700 dark:bg-zinc-900 dark:shadow-black/40 sm:p-8">
                        <div class="mb-6 text-center">
                            @if($currentRole === 'admin')
                                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 sm:text-sm">
                                    Gestión de red AlmaMia
                                </p>
                            @elseif($currentRole === 'lider')
                                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 sm:text-sm">
                                    Incorporá nuevas vendedoras
                                </p>
                            @else
                                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 sm:text-sm">
                                    Incorporá nuevos líderes
                                </p>
                            @endif

                            <h2 class="mt-3 text-2xl font-bold text-slate-900 dark:text-white sm:text-3xl">
                                Completá los datos de la nueva usuaria
                            </h2>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 sm:text-base">
                                Esta información se usa para ubicarla en la red correcta y mantener todo organizado.
                            </p>

                            @if($isEditing && $currentRole === 'admin')
                                <div class="mt-3 inline-flex items-center rounded-full bg-indigo-50 px-4 py-1.5 text-[11px] font-semibold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-100">
                                    Editando a {{ $editingUserName }}
                                    <button
                                        type="button"
                                        wire:click="cancelEdit"
                                        class="ml-3 text-[11px] font-semibold text-slate-600 underline-offset-2 hover:underline dark:text-slate-200"
                                    >
                                        Cancelar edición
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if (session()->has('message'))
                            <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 shadow-sm dark:border-emerald-500/60 dark:bg-emerald-900/30 dark:text-emerald-100">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-900 shadow-sm dark:border-rose-500/60 dark:bg-rose-900/30 dark:text-rose-100">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="save" class="space-y-6">
                            {{-- Tipo de usuaria --}}
                            @if($currentRole === 'admin')
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Tipo de usuaria
                                    </label>
                                    <p class="mb-2 text-xs text-slate-500 dark:text-slate-400">
                                        Elegí si se suma como vendedora, líder o coordinadora. Podés ajustar esto más adelante.
                                    </p>
                                    <div class="grid gap-3 sm:grid-cols-3">
                                        <label @class([
                                            'flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-indigo-400 dark:text-slate-200',
                                            'border-indigo-500 bg-indigo-50 text-indigo-900 dark:border-indigo-400 dark:bg-indigo-900/40 dark:text-indigo-50' => ($form['tipo'] ?? '') === 'vendedora',
                                            'border-slate-200 dark:border-slate-700' => ($form['tipo'] ?? '') !== 'vendedora',
                                        ])>
                                            <input
                                                type="radio"
                                                class="mt-1"
                                                value="vendedora"
                                                wire:model.live="form.tipo"
                                            >
                                            <span>
                                                Vendedora
                                                <span class="block text-xs font-normal text-slate-500 dark:text-slate-400">
                                                    Carga pedidos y gestiona su cartera de clientas.
                                                </span>
                                            </span>
                                        </label>

                                        <label @class([
                                            'flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-indigo-400 dark:text-slate-200',
                                            'border-indigo-500 bg-indigo-50 text-indigo-900 dark:border-indigo-400 dark:bg-indigo-900/40 dark:text-indigo-50' => ($form['tipo'] ?? '') === 'lider',
                                            'border-slate-200 dark:border-slate-700' => ($form['tipo'] ?? '') !== 'lider',
                                        ])>
                                            <input
                                                type="radio"
                                                class="mt-1"
                                                value="lider"
                                                wire:model.live="form.tipo"
                                            >
                                            <span>
                                                Líder
                                                <span class="block text-xs font-normal text-slate-500 dark:text-slate-400">
                                                    Acompaña a su red de vendedoras y ve sus resultados.
                                                </span>
                                            </span>
                                        </label>

                                        <label @class([
                                            'flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-indigo-400 dark:text-slate-200',
                                            'border-indigo-500 bg-indigo-50 text-indigo-900 dark:border-indigo-400 dark:bg-indigo-900/40 dark:text-indigo-50' => ($form['tipo'] ?? '') === 'coordinadora',
                                            'border-slate-200 dark:border-slate-700' => ($form['tipo'] ?? '') !== 'coordinadora',
                                        ])>
                                            <input
                                                type="radio"
                                                class="mt-1"
                                                value="coordinadora"
                                                wire:model.live="form.tipo"
                                            >
                                            <span>
                                                Coordinadora
                                                <span class="block text-xs font-normal text-slate-500 dark:text-slate-400">
                                                    Coordina varios líderes y ve la red completa.
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    @error('form.tipo')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <input type="hidden" wire:model="form.tipo">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                    @if($currentRole === 'lider')
                                        Vas a incorporar una nueva <span class="font-semibold">vendedora</span> vinculada a tu red.
                                    @elseif($currentRole === 'coordinadora')
                                        Vas a incorporar un nuevo <span class="font-semibold">líder</span> asociado a vos como coordinadora.
                                    @endif
                                </div>
                            @endif
                            
                              {{-- Vínculos en la red --}}
                            <div class="space-y-4 border-t border-slate-200 pt-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                                    Vínculos en la red
                                </h3>

                                @if($currentRole === 'lider')
                                    <input type="hidden" wire:model="form.lider_id">

                                    @if($coordinadorasDelLider->count() === 1)
                                        <input type="hidden" wire:model="form.coordinadora_id">
                                        <p class="text-sm text-slate-600 dark:text-slate-300">
                                            La nueva vendedora quedará vinculada a vos como <span class="font-semibold">Líder</span> y a
                                            <span class="font-semibold">{{ $coordinadorasDelLider->first()->name }}</span> como Coordinadora.
                                        </p>
                                    @elseif($coordinadorasDelLider->count() > 1)
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                                Coordinadora
                                            </label>
                                            <select
                                                wire:model="form.coordinadora_id"
                                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                            >
                                                <option value="">Seleccioná una coordinadora</option>
                                                @foreach($coordinadorasDelLider as $coord)
                                                    <option value="{{ $coord->id }}">{{ $coord->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('form.coordinadora_id')
                                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            La vendedora siempre quedará asociada a vos como líder.
                                        </p>
                                    @else
                                        <p class="text-sm text-amber-700 dark:text-amber-300">
                                            No tenés una coordinadora vinculada. Configurá el vínculo antes de incorporar.
                                        </p>
                                    @endif
                                @elseif($currentRole === 'coordinadora')
                                    <input type="hidden" wire:model="form.coordinadora_id">
                                    <p class="text-sm text-slate-600 dark:text-slate-300">
                                        El líder quedará vinculado directamente a vos como coordinadora.
                                    </p>
                                @elseif($currentRole === 'admin')
                                    <div class="space-y-4">
                                        @if(($form['tipo'] ?? '') === 'vendedora')
                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                                    Líder
                                                </label>
                                                <select
                                                    wire:model="form.lider_id"
                                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                                >
                                                    <option value="">Seleccioná un líder</option>
                                                    @foreach($lideres as $lider)
                                                        <option value="{{ $lider->id }}">{{ $lider->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('form.lider_id')
                                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                                    Coordinadora
                                                </label>
                                                <select
                                                    wire:model="form.coordinadora_id"
                                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                                >
                                                    <option value="">Seleccioná una coordinadora</option>
                                                    @foreach($coordinadoras as $coord)
                                                        <option value="{{ $coord->id }}">{{ $coord->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('form.coordinadora_id')
                                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @elseif(($form['tipo'] ?? '') === 'lider')
                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                                    Coordinadora
                                                </label>
                                                <select
                                                    wire:model="form.coordinadora_id"
                                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                                >
                                                    <option value="">Seleccioná una coordinadora</option>
                                                    @foreach($coordinadoras as $coord)
                                                        <option value="{{ $coord->id }}">{{ $coord->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('form.coordinadora_id')
                                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @else
                                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                                Las coordinadoras no requieren vínculos superiores.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Datos básicos --}}
                            <div class="space-y-5">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Nombre completo
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.defer="form.name"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="Ej: Ana Pérez"
                                    >
                                    @error('form.name')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Correo electrónico
                                    </label>
                                    <input
                                        type="email"
                                        wire:model.defer="form.email"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="Ej: ana@mail.com"
                                    >
                                    @error('form.email')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Contraseña
                                    </label>
                                    <input
                                        type="password"
                                        wire:model.defer="form.password"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="Definí una clave segura"
                                    >
                                    @error('form.password')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Repetir contraseña
                                    </label>
                                    <input
                                        type="password"
                                        wire:model.defer="form.password_confirmation"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="Repetí la misma clave"
                                    >
                                    @error('form.password_confirmation')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Datos personales --}}
                            <div class="space-y-5 border-t border-slate-200 pt-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                                    Datos personales
                                </h3>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        DNI
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.defer="form.dni"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="Sin puntos ni espacios"
                                    >
                                    @error('form.dni')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        WhatsApp
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.defer="form.whatsapp"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="+549..."
                                    >
                                    @error('form.whatsapp')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Dirección
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.defer="form.direccion"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="Calle y número"
                                    >
                                    @error('form.direccion')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Departamento
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.defer="form.departamento"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="Ej: Godoy Cruz, Luján de Cuyo, etc."
                                    >
                                    @error('form.departamento')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Zona
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.defer="form.zona"
                                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder-slate-500"
                                        placeholder="Ej: Norte, Guaymallén, etc."
                                    >
                                    @error('form.zona')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                          

                            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:bg-indigo-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 sm:w-auto"
                                >
                                    <span wire:loading.remove>
                                        {{ $isEditing && $currentRole === 'admin' ? 'Actualizar usuaria' : 'Guardar usuaria' }}
                                    </span>
                                    <span wire:loading>Guardando...</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- RESUMEN DE PERMISOS --}}
                    <div class="mx-auto w-full max-w-3xl rounded-2xl border border-slate-200 bg-slate-50/95 p-4 text-sm text-slate-800 shadow-sm dark:border-slate-700 dark:bg-slate-900/95 dark:text-slate-100">
                        <h3 class="mb-2 text-sm font-semibold text-slate-900 dark:text-slate-50">Resumen de permisos</h3>
                        @if($currentRole === 'admin')
                            <ul class="list-disc space-y-1 pl-4">
                                <li>Podés crear vendedoras, líderes y coordinadoras.</li>
                                <li>Definís la relación entre cada nivel de la red.</li>
                                <li>Podés editar cualquier usuaria desde esta vista.</li>
                            </ul>
                        @elseif($currentRole === 'lider')
                            <ul class="list-disc space-y-1 pl-4">
                                <li>Solo podés crear vendedoras.</li>
                                <li>Quedan vinculadas a vos como líder.</li>
                                <li>También quedan asociadas a tu coordinadora.</li>
                            </ul>
                        @elseif($currentRole === 'coordinadora')
                            <ul class="list-disc space-y-1 pl-4">
                                <li>Solo podés crear líderes.</li>
                                <li>Quedan vinculados directamente a vos.</li>
                                <li>Desde aquí podés ver y gestionar tu red de líderes.</li>
                            </ul>
                        @endif
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
