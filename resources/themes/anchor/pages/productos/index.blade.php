<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Producto;
use App\Models\Categoria;
use Livewire\Volt\Component;

middleware('auth');
name('productos');

new class extends Component {
    public $productos = [];
    public $categorias = [];
    public $editing = false;
    public $producto_id;

    public $nombre;
    public $precio;
    public $puntos_por_unidad;
    public $activo = true;
    public $categoria_id;
    public $sku = null;
    public $descripcion = null;
    public $stock_actual = null;
    public $altura = null;
    public $anchura = null;
    public $profundidad = null;
    public $bulto = null;
    public $imagen = null;

    // Filtros y búsqueda
    public $search = '';
    public $filter_categoria = '';

    public function mount()
    {
        $this->categorias = Categoria::orderBy('nombre')->get();
        $this->loadProductos();
    }

    public function updated($field)
    {
        // Cuando cambian los filtros, se actualiza la tabla dinámicamente
        if (in_array($field, ['search', 'filter_categoria'])) {
            $this->loadProductos();
        }
    }

    public function loadProductos()
    {
        $query = Producto::with('categorias')->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->filter_categoria)) {
            $query->whereHas('categorias', function ($q) {
                $q->where('categorias.id', $this->filter_categoria);
            });
        }

        $this->productos = $query->get();
    }

    public function deleteProducto(Producto $producto)
    {
        $producto->delete();
        $this->loadProductos();
    }

    public function editProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $this->producto_id = $producto->id;
        $this->nombre = $producto->nombre;
        $this->precio = $producto->precio;
        $this->puntos_por_unidad = $producto->puntos_por_unidad;
        $this->activo = $producto->activo;
        $this->categoria_id = optional($producto->categorias->first())->id;
        $this->sku = $producto->sku;
        $this->descripcion = $producto->descripcion;
        $this->stock_actual = $producto->stock_actual;
        $this->altura = $producto->altura;
        $this->anchura = $producto->anchura;
        $this->profundidad = $producto->profundidad;
        $this->bulto = $producto->bulto;
        $this->imagen = $producto->imagen;
        $this->editing = true;
    }

    public function saveProducto()
    {
        $this->validate([
            'nombre' => 'required|max:255',
            'precio' => 'required|numeric|min:0',
            'puntos_por_unidad' => 'required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'sku' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:65535',
            'stock_actual' => 'nullable|integer|min:0',
            'altura' => 'nullable|string|max:255',
            'anchura' => 'nullable|string|max:255',
            'profundidad' => 'nullable|string|max:255',
            'bulto' => 'nullable|string|max:255',
            'imagen' => 'nullable|string|max:255',
            'activo' => 'nullable|boolean'
        ]);

        $producto = Producto::findOrFail($this->producto_id);
        $producto->update([
            'nombre' => $this->nombre,
            'precio' => $this->precio,
            'puntos_por_unidad' => $this->puntos_por_unidad,
            'activo' => $this->activo,
            'sku' => $this->sku,
            'descripcion' => $this->descripcion,
            'stock_actual' => $this->stock_actual,
            'altura' => $this->altura,
            'anchura' => $this->anchura,
            'profundidad' => $this->profundidad,
            'bulto' => $this->bulto,
            'imagen' => $this->imagen,
        ]);

        $producto->categorias()->sync($this->categoria_id ? [$this->categoria_id] : []);

        session()->flash('message', 'Producto actualizado correctamente.');
        $this->editing = false;
        $this->loadProductos();
    }

    public function closeModal()
    {
        $this->editing = false;
    }
};
?>

<x-layouts.app>
@volt('productos')
<x-app.container>

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5 gap-3">
        <x-app.heading
            title="Productos"
            description="Listado de productos"
            :border="false"
        />
        <div class="flex flex-wrap gap-2">
            <x-button tag="a" href="/productos/create">Nuevo Producto</x-button>
            <x-button tag="a" href="/productos/masivo" class="bg-indigo-500 hover:bg-indigo-600 text-white">
                Creación masiva
            </x-button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <div class="w-full sm:w-1/2">
            <input 
                type="text" 
                wire:model.debounce.300ms="search" 
                placeholder="Buscar producto o SKU..." 
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
            >
        </div>
        <div class="w-full sm:w-1/3">
            <select 
                wire:model.live="filter_categoria" 
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
            >
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Mensaje flash -->
    @if (session()->has('message'))
        <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabla dinámica -->
    <div wire:loading.class="opacity-50 transition-opacity duration-300">
        @if($productos->isEmpty())
            <div class="w-full p-20 text-center bg-gray-100 rounded-xl">
                <p class="text-gray-500">No hay productos que coincidan con la búsqueda.</p>
            </div>
        @else
            <div class="overflow-x-auto border rounded-lg shadow-sm">
                <table class="min-w-full bg-white text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Precio</th>
                            <th class="px-4 py-2 text-left">Puntos</th>
                            <th class="px-4 py-2 text-left">Activo</th>
                            <th class="px-4 py-2 text-left">Categoría</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-4 py-2">{{ $producto->nombre }}</td>
                                <td class="px-4 py-2">{{ number_format($producto->precio, 2) }}</td>
                                <td class="px-4 py-2">{{ $producto->puntos_por_unidad }}</td>
                                <td class="px-4 py-2">{{ $producto->activo ? 'Sí' : 'No' }}</td>
                                <td class="px-4 py-2">
                                    {{ optional($producto->categorias->first())->nombre ?? 'Sin categoría' }}
                                </td>
                                <td class="px-4 py-2">
                                    <button wire:click="editProducto({{ $producto->id }})" class="mr-2 text-blue-500 hover:underline">Editar</button>
                                    <button wire:click="deleteProducto({{ $producto->id }})" class="text-red-500 hover:underline">Eliminar</button>
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
            Cargando productos...
        </div>
    </div>

    <!-- Modal de edición -->
    @if($editing)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="w-full max-w-2xl p-6 bg-white rounded-lg shadow-lg overflow-y-auto max-h-[90vh]">
                <h2 class="mb-4 text-xl font-semibold">Editar producto</h2>
                <form wire:submit="saveProducto" class="space-y-4">
                    <div>
                        <label for="edit-nombre" class="block mb-2 text-sm font-medium text-gray-700">Nombre</label>
                        <input id="edit-nombre" type="text" wire:model.live="nombre" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('nombre') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit-precio" class="block mb-2 text-sm font-medium text-gray-700">Precio</label>
                            <input id="edit-precio" type="number" step="0.01" wire:model.live="precio" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            @error('precio') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="edit-puntos" class="block mb-2 text-sm font-medium text-gray-700">Puntos por unidad</label>
                            <input id="edit-puntos" type="number" wire:model.live="puntos_por_unidad" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            @error('puntos_por_unidad') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="edit-categoria" class="block mb-2 text-sm font-medium text-gray-700">Categoría</label>
                        <select id="edit-categoria" wire:model="categoria_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            <option value="">Sin categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="edit-sku" class="block mb-2 text-sm font-medium text-gray-700">SKU</label>
                        <input id="edit-sku" type="text" wire:model.live="sku" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('sku') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="edit-descripcion" class="block mb-2 text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="edit-descripcion" rows="3" wire:model.live="descripcion" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"></textarea>
                        @error('descripcion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit-stock" class="block mb-2 text-sm font-medium text-gray-700">Stock actual</label>
                            <input id="edit-stock" type="number" wire:model.live="stock_actual" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            @error('stock_actual') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center mt-6">
                            <input id="edit-activo" type="checkbox" wire:model.live="activo" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="edit-activo" class="ml-2 text-sm text-gray-700">Activo</label>
                        </div>
                        @error('activo') <span class="text-xs text-red-500 col-span-2">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit-altura" class="block mb-2 text-sm font-medium text-gray-700">Altura</label>
                            <input id="edit-altura" type="text" wire:model.live="altura" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            @error('altura') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="edit-anchura" class="block mb-2 text-sm font-medium text-gray-700">Anchura</label>
                            <input id="edit-anchura" type="text" wire:model.live="anchura" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            @error('anchura') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="edit-profundidad" class="block mb-2 text-sm font-medium text-gray-700">Profundidad</label>
                            <input id="edit-profundidad" type="text" wire:model.live="profundidad" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            @error('profundidad') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="edit-bulto" class="block mb-2 text-sm font-medium text-gray-700">Bulto</label>
                            <input id="edit-bulto" type="text" wire:model.live="bulto" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                            @error('bulto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="edit-imagen" class="block mb-2 text-sm font-medium text-gray-700">Imagen (ruta)</label>
                        <input id="edit-imagen" type="text" wire:model.live="imagen" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('imagen') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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
