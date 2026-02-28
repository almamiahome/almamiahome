<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Categoria;
use App\Models\PuntajeRegla;
use Livewire\Volt\Component;

middleware('auth');
name('categorias');

new class extends Component {
    public $categorias;

    public $reglas = [];

    public $nombre;
    public $slug;
    public $categoria_ids = [];

    public $editing = false;
    public $categoria_id;

    public function mount()
    {
        $this->reloadCollections();
    }

    protected function reloadCollections(): void
    {
        $this->categorias = Categoria::with('puntajeReglas')->latest()->get();
        $this->reglas = PuntajeRegla::orderBy('descripcion')->get();
    }

    public function deleteCategoria(Categoria $categoria)
    {
        $categoria->delete();
        $this->reloadCollections();
    }

    public function editCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        $this->categoria_id = $categoria->id;
        $this->nombre = $categoria->nombre;
        $this->slug = $categoria->slug;
        $this->puntaje_regla_id = optional($categoria->puntajeRegla()->first())->id;
        $this->editing = true;
    }

    public function saveCategoria()
    {
        $this->validate([
            'nombre' => 'required|max:255',
            'slug' => 'nullable|max:255',
            'categoria_ids' => 'array',
            'categoria_ids.*' => 'exists:puntaje_reglas,id',
        ]);

        $categoria = Categoria::findOrFail($this->categoria_id);
        $categoria->update([
            'nombre' => $this->nombre,
            'slug' => $this->slug ?: null,
        ]);

        $categoria->puntajeRegla()->sync($this->puntaje_regla_id ? [$this->puntaje_regla_id] : []);

        session()->flash('message', 'Categoría actualizada correctamente.');

        $this->editing = false;
        $this->categoria_ids = [];
        $this->reloadCollections();
    }

    public function closeModal()
    {
        $this->editing = false;
        $this->categoria_ids = [];
    }
};
?>

<x-layouts.app>
    @volt('categorias')
        <x-app.container>
            <div class="flex items-center justify-between mb-5">
                <x-app.heading
                    title="Categorías"
                    description="Listado de categorías"
                    :border="false"
                />
                <div class="flex gap-2">
                    <x-button tag="a" href="/categorias/create">Nueva Categoría</x-button>
                    <x-button tag="a" href="/categorias/masivo" class="bg-indigo-500 hover:bg-indigo-600 text-white">
                        Creación masiva
                    </x-button>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @if($categorias->isEmpty())
                <div class="w-full p-20 text-center bg-gray-100 rounded-xl">
                    <p class="text-gray-500">No hay categorías aún.</p>
                </div>
            @else
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full bg-white">
                        <thead class="text-sm bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Nombre</th>
                                <th class="px-4 py-2 text-left">Slug</th>
                                <th class="px-4 py-2 text-left">Reglas</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categorias as $categoria)
                                <tr>
                                    <td class="px-4 py-2">{{ $categoria->nombre }}</td>
                                    <td class="px-4 py-2 text-gray-600">{{ $categoria->slug ?? '—' }}</td>
                                    <td class="px-4 py-2 text-gray-600">
                                        {{ optional($categoria->puntajeRegla->first())->descripcion ? \Illuminate\Support\Str::limit($categoria->puntajeRegla->first()->descripcion, 60) : 'Sin asignar' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <button wire:click="editCategoria({{ $categoria->id }})" class="mr-2 text-blue-500 hover:underline">Editar</button>
                                        <button wire:click="deleteCategoria({{ $categoria->id }})" class="text-red-500 hover:underline">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Modal de edición -->
            @if($editing)
                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg">
                        <h2 class="mb-4 text-xl font-semibold">Editar categoría</h2>
                        <form wire:submit="saveCategoria" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" wire:model.live="nombre" class="w-full border-gray-300 rounded-md shadow-xs focus:ring focus:ring-indigo-200">
                                @error('nombre') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Slug (opcional)</label>
                                <input type="text" wire:model.live="slug" class="w-full border-gray-300 rounded-md shadow-xs focus:ring focus:ring-indigo-200">
                                @error('slug') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reglas de puntaje</label>
                                <select wire:model.live="categoria_ids" multiple class="w-full border-gray-300 rounded-md shadow-xs focus:ring focus:ring-indigo-200">
                                    @foreach($reglas as $regla)
                                        <option value="{{ $regla->id }}">{{ \Illuminate\Support\Str::limit($regla->descripcion ?? 'Regla ' . $regla->id, 60) }}</option>
                                    @endforeach
                                </select>
                                @error('categoria_ids') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                @error('categoria_ids.*') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end mt-5 space-x-3">
                                <x-button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white">
                                    Cancelar
                                </x-button>
                                <x-button type="submit">
                                    Guardar cambios
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
