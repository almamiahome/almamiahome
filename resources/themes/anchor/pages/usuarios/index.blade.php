<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('usuarios');

new class extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingUserId = null;

    public array $form = [
        'name' => '',
        'email' => '',
        'dni' => '',
        'whatsapp' => '',
        'direccion' => '',
        'departamento' => '',
        'zona' => '',
    ];

    public array $departamentos = [];
    public array $zonas = [];

    public function mount(): void
    {
        $this->departamentos = $this->decodeOptions(setting('almamia.departamentos.mendoza') ?? '[]');
        $this->zonas = $this->decodeOptions(setting('almamia.zona.mendoza') ?? '[]');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function decodeOptions($value): array
    {
        $decoded = json_decode($value ?? '[]', true);
        return is_array($decoded) ? array_values(array_filter($decoded, fn($item) => filled($item))) : [];
    }

    public function getUsuariosProperty()
    {
        $search = trim($this->search);
        return User::query()
            ->with(['profileKeyValues'])
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->where(function (Builder $b) use ($search) {
                    $b->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereRaw('CAST(id AS CHAR) LIKE ?', ["%{$search}%"]);
                });
            })
            ->orderBy('name')
            ->paginate(10);
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($userId): void
    {
        $user = User::find($userId);
        if (!$user) return;

        $this->resetValidation();
        $this->isEditing = true;
        $this->editingUserId = $userId;
        $this->form = [
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'dni' => (string)($user->profile('dni') ?? ''),
            'whatsapp' => (string)($user->profile('whatsapp') ?? ''),
            'direccion' => (string)($user->profile('direccion') ?? ''),
            'departamento' => (string)($user->profile('departamento') ?? ''),
            'zona' => (string)($user->profile('zona') ?? ''),
        ];
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function saveUser(): void
    {
        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingUserId)],
        ]);

        if ($this->isEditing) {
            $user = User::find($this->editingUserId);
            $user->update(['name' => $this->form['name'], 'email' => $this->form['email']]);
        } else {
            $user = User::create([
                'name' => $this->form['name'],
                'email' => $this->form['email'],
                'password' => Hash::make(Str::random(12)),
            ]);
        }

        foreach (['dni', 'whatsapp', 'direccion', 'departamento', 'zona'] as $key) {
            $user->setProfileKeyValue($key, $this->form[$key] ?? '');
        }

        $this->showModal = false;
        session()->flash('success', 'Operación exitosa.');
    }

    private function resetForm(): void
    {
        $this->form = ['name' => '', 'email' => '', 'dni' => '', 'whatsapp' => '', 'direccion' => '', 'departamento' => '', 'zona' => ''];
        $this->editingUserId = null;
    }
};
?>

<x-layouts.app>
    @volt('usuarios')
        <div class="max-w-6xl mx-auto py-8 px-4">
            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h1>
                    <p class="text-sm text-gray-500">Administra los perfiles de la plataforma.</p>
                </div>
                <button wire:click="openCreateModal" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition">
                    Nuevo Usuario
                </button>
            </div>

            {{-- Buscador --}}
            <div class="mb-6">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre, email o ID..." class="w-full max-w-md rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            @if (session()->has('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabla --}}
            <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($this->usuarios as $user)
                            <tr wire:key="user-row-{{ $user->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->profile('dni') ?: '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="openEditModal({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold">Editar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">No se encontraron resultados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $this->usuarios->links() }}
            </div>

            {{-- Modal --}}
            @if($showModal)
                <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div wire:click="closeModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                            <form wire:submit.prevent="saveUser">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">{{ $isEditing ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                            <input type="text" wire:model="form.name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('form.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" wire:model="form.email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('form.email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">DNI</label>
                                            <input type="text" wire:model="form.dni" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                            <input type="text" wire:model="form.whatsapp" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Departamento</label>
                                            <select wire:model="form.departamento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Seleccionar...</option>
                                                @foreach($departamentos as $dep) <option value="{{ $dep }}">{{ $dep }}</option> @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Zona</label>
                                            <select wire:model="form.zona" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Seleccionar...</option>
                                                @foreach($zonas as $z) <option value="{{ $z }}">{{ $z }}</option> @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                        Guardar
                                    </button>
                                    <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endvolt
</x-layouts.app>