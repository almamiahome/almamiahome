<?php
use function Laravel\Folio\{middleware, name};
use App\Models\GastoAdministrativo;
use Livewire\Volt\Component;

middleware('auth');
name('gastos');

new class extends Component {
    public $gastos = [];
    public $editing = false;
    public $creating = false;

    public $gasto_id;
    public $concepto;
    public $monto;
    public $tipo;

    public $search = '';

    public function mount()
    {
        $this->loadGastos();
    }

    public function updated($field)
    {
        if ($field === 'search') {
            $this->loadGastos();
        }
    }

    public function loadGastos()
    {
        $query = GastoAdministrativo::query()->latest();

        if (!empty($this->search)) {
            $query->where('concepto', 'like', "%{$this->search}%")
                  ->orWhere('tipo', 'like', "%{$this->search}%");
        }

        $this->gastos = $query->get();
    }

    public function saveGasto()
    {
        $this->validate([
            'concepto' => 'required|string|max:255',
            'monto' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
        ]);

        if ($this->editing && $this->gasto_id) {
            $gasto = GastoAdministrativo::findOrFail($this->gasto_id);
            $gasto->update([
                'concepto' => $this->concepto,
                'monto' => $this->monto,
                'tipo' => $this->tipo,
            ]);
            session()->flash('message', 'Gasto actualizado correctamente.');
        } else {
            GastoAdministrativo::create([
                'concepto' => $this->concepto,
                'monto' => $this->monto,
                'tipo' => $this->tipo,
            ]);
            session()->flash('message', 'Gasto creado correctamente.');
        }

        $this->resetModal();
        $this->loadGastos();
    }

    public function editGasto($id)
    {
        $gasto = GastoAdministrativo::findOrFail($id);
        $this->gasto_id = $gasto->id;
        $this->concepto = $gasto->concepto;
        $this->monto = $gasto->monto;
        $this->tipo = $gasto->tipo;
        $this->editing = true;
    }

    public function deleteGasto(GastoAdministrativo $gasto)
    {
        $gasto->delete();
        $this->loadGastos();
    }

    public function openCreateModal()
    {
        $this->resetFields();
        $this->creating = true;
    }

    public function closeModal()
    {
        $this->resetModal();
    }

    private function resetModal()
    {
        $this->editing = false;
        $this->creating = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->gasto_id = null;
        $this->concepto = '';
        $this->monto = '';
        $this->tipo = '';
    }
};
?>

<x-layouts.app>
@volt('gastos')
<x-app.container>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5 gap-3">
        <x-app.heading
            title="Gastos Administrativos"
            description="Listado y gestión de gastos"
            :border="false"
        />

        <x-button wire:click="openCreateModal" class="bg-indigo-500 hover:bg-indigo-600 text-white">
            Nuevo Gasto
        </x-button>
    </div>

    <!-- Buscador 
    <div class="w-full sm:w-1/2 mb-4">
        <input 
            type="text" 
            wire:model.debounce.300ms="search" 
            placeholder="Buscar por concepto o tipo..." 
            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
        >
    </div>-->

    <!-- Mensaje flash -->
    @if (session()->has('message'))
        <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabla -->
    <div wire:loading.class="opacity-50">
        @if($gastos->isEmpty())
            <div class="w-full p-20 text-center bg-gray-100 rounded-xl">
                <p class="text-gray-500">No hay gastos registrados.</p>
            </div>
        @else
            <div class="overflow-x-auto border rounded-lg shadow-sm">
                <table class="min-w-full bg-white text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Concepto</th>
                            <th class="px-4 py-2 text-left">Monto</th>
                            <th class="px-4 py-2 text-left">Tipo</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gastos as $gasto)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-4 py-2">{{ $gasto->concepto }}</td>
                                <td class="px-4 py-2">{{ $gasto->monto }}</td>
                                <td class="px-4 py-2">{{ $gasto->tipo }}</td>
                                <td class="px-4 py-2">
                                    <button wire:click="editGasto({{ $gasto->id }})" class="mr-2 text-blue-500 hover:underline">Editar</button>
                                    <button wire:click="deleteGasto({{ $gasto->id }})" class="text-red-500 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Loader -->
    <div wire:loading class="flex justify-center mt-4">
        <div class="flex items-center gap-2 text-gray-500 text-sm">
            <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Cargando...
        </div>
    </div>

    <!-- Modal de creación / edición -->
    @if($creating || $editing)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                <h2 class="mb-4 text-xl font-semibold">
                    {{ $editing ? 'Editar Gasto' : 'Nuevo Gasto' }}
                </h2>
                <form wire:submit="saveGasto" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Concepto</label>
                        <input type="text" wire:model.live="concepto" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('concepto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                        <input type="text" wire:model.live="monto" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('monto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <input type="text" wire:model.live="tipo" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('tipo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end mt-5 space-x-3">
                        <x-button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white">
                            Cancelar
                        </x-button>
                        <x-button type="submit">
                            Guardar
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</x-app.container>
@endvolt
</x-layouts.app>
