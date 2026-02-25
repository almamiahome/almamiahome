<?php

use function Laravel\Folio\{middleware, name};
use App\Models\User;
use Livewire\Volt\Component;

middleware('auth');
name('coordinadoras');

new class extends Component {
    public $coordinadoras;
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
        // Solo usuarios con rol coordinadora
        $this->coordinadoras = User::role('coordinadora')
            ->with('roles')
            ->orderBy('name')
            ->get();

        // Solo lideres que NO tienen rol coordinadora (para asignar)
        $this->usuariosDisponibles = User::role('lider')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'coordinadora');
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

    public function addCoordinadora(): void
    {
        $validated = $this->validate();

        /** @var \App\Models\User $user */
        $user = User::findOrFail($validated['form']['user_id']);

        // Asignar rol coordinadora
        $user->assignRole('coordinadora');

        session()->flash('message', 'Coordinadora asignada correctamente.');

        $this->showAddModal = false;
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    public function removeCoordinadora(int $userId): void
    {
        /** @var \App\Models\User $user */
        $user = User::findOrFail($userId);

        // Quitar rol coordinadora
        $user->removeRole('coordinadora');

        session()->flash('message', 'Coordinadora eliminada correctamente.');

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
    @volt('coordinadoras')
        <x-app.container class="space-y-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <x-app.heading
                    title="Coordinadoras"
                    description="Gestiona los usuarios con rol coordinadora y asigna lideres como coordinadoras."
                    :border="false"
                />
                <div class="flex justify-end">
                    <x-button
                        type="button"
                        class="w-full md:w-auto"
                        wire:click="openAddModal"
                    >
                        Agregar coordinadora
                    </x-button>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if($coordinadoras->isEmpty())
                <div class="p-10 text-center bg-white border border-dashed rounded-xl text-slate-500">
                    Todavia no hay usuarios con rol coordinadora. Usa el boton "Agregar coordinadora" para asignar una lider.
                </div>
            @else
                <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm">
                    <table class="min-w-full text-left text-sm">
    <thead class="text-xs uppercase tracking-wide bg-slate-50 text-slate-500">
        <tr>
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">Nombre</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Otros roles</th>
            <th class="px-4 py-3">Creado</th>
            <th class="px-4 py-3 text-right">Acciones</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        @foreach($coordinadoras as $coordinadora)
            <tr class="align-middle">
                <td class="px-4 py-3 text-slate-600 text-xs">
                    {{ $coordinadora->id }}
                </td>
                <td class="px-4 py-3 font-medium text-slate-900">
                    {{ $coordinadora->name ?? 'Sin nombre' }}
                </td>
                <td class="px-4 py-3 text-slate-700">
                    {{ $coordinadora->email }}
                </td>
                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-1">
                        @foreach($coordinadora->roles as $role)
                            @if($role->name !== 'coordinadora')
                                <span class="inline-flex px-2 py-0.5 text-[11px] rounded-full bg-slate-100 text-slate-700">
                                    {{ $role->name }}
                                </span>
                            @endif
                        @endforeach
                        @if($coordinadora->roles->where('name', '!=', 'coordinadora')->isEmpty())
                            <span class="text-[11px] text-slate-400">
                                Sin otros roles
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3 text-slate-600 text-xs">
                    {{ $coordinadora->created_at?->format('d/m/Y H:i') }}
                </td>
                <td class="px-4 py-3 text-right">
                    <button
                        type="button"
                        x-data
                        @click="if (confirm('Seguro que queres quitar el rol de coordinadora a este usuario?')) { $wire.removeCoordinadora({{ $coordinadora->id }}) }"
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 transition-colors"
                    >
                        Quitar rol coordinadora
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
                </div>
            @endif

            {{-- Modal: agregar coordinadora --}}
            @if($showAddModal)
                <div
                    x-data="{ open: true }"
                    x-show="open"
                    x-transition.opacity
                    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8 bg-black/40"
                    @keydown.window.escape="open = false; $wire.closeModal()"
                    @click.self="open = false; $wire.closeModal()"
                >
                    <div
                        class="w-full max-w-lg overflow-hidden bg-white border border-slate-100 rounded-2xl shadow-2xl"
                        @click.stop
                    >
                        <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">
                                    Agregar coordinadora
                                </h2>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    Solo se muestran lideres que todavia no tienen rol coordinadora.
                                </p>
                            </div>
                            <button
                                class="inline-flex items-center justify-center w-8 h-8 text-slate-400 rounded-full hover:bg-slate-200 hover:text-slate-700 transition-colors"
                                type="button"
                                @click="open = false; $wire.closeModal()"
                            >
                                ✕
                            </button>
                        </div>

                        <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                            @if($usuariosDisponibles->isEmpty())
                                <p class="text-sm text-slate-600">
                                    No hay lideres disponibles sin rol coordinadora. Primero crea o asigna lideres desde la seccion correspondiente.
                                </p>
                            @else
                                <form wire:submit.prevent="addCoordinadora" class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Lider
                                        </label>
                                        <select
                                            wire:model="form.user_id"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        >
                                            <option value="">Selecciona una lider</option>
                                            @foreach($usuariosDisponibles as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name ?? 'Sin nombre' }} — {{ $user->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('form.user_id')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                        <p class="mt-1 text-xs text-slate-500">
                                            Esta lider pasara a formar parte del equipo de coordinadoras.
                                        </p>
                                    </div>
                                </form>
                            @endif
                        </div>

                        <div class="flex flex-col gap-2 px-6 py-4 border-t bg-slate-50 sm:flex-row sm:justify-end">
                            <x-button
                                type="button"
                                class="w-full sm:w-auto bg-slate-200 text-slate-700 hover:bg-slate-300"
                                @click="open = false; $wire.closeModal()"
                            >
                                Cancelar
                            </x-button>

                            @if(!$usuariosDisponibles->isEmpty())
                                <x-button
                                    type="button"
                                    class="w-full sm:w-auto"
                                    wire:click="addCoordinadora"
                                >
                                    Guardar
                                </x-button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
