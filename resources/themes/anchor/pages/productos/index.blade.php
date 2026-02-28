<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Producto;
use App\Models\Categoria;
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // Importante para la imagen
use Illuminate\Support\Facades\Storage;

middleware('auth');
name('productos');

new class extends Component {
    use WithFileUploads; // Habilitar subida de archivos

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
    public $imagen = null; // Ruta actual en BD
    public $nueva_imagen;   // Propiedad temporal para el upload

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
        $this->nueva_imagen = null; // Resetear el dropzone
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
            'nueva_imagen' => 'nullable|image|max:2048', // Validación de imagen
            'activo' => 'nullable|boolean'
        ]);

        $producto = Producto::findOrFail($this->producto_id);

        // Lógica de guardado de imagen
        $rutaImagen = $this->imagen;
        if ($this->nueva_imagen) {
            $rutaImagen = $this->nueva_imagen->store('productos', 'public');
        }

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
            'imagen' => $rutaImagen,
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

  <div class="flex flex-col md:flex-row items-center gap-4 mb-6 p-4 bg-white/40 dark:bg-black/20 backdrop-blur-md rounded-[2rem] border border-white/40 shadow-sm">
    <div class="relative w-full md:flex-1 group">
        <x-phosphor-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" />
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search" 
            placeholder="Buscar producto o SKU..." 
            class="w-full pl-12 pr-4 py-3 bg-white/50 dark:bg-zinc-900/50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/50 backdrop-blur-sm placeholder-gray-400 text-sm transition-all"
        >
    </div>
    
    <div class="w-full md:w-64">
        <select 
            wire:model.live="filter_categoria" 
            class="w-full py-3 px-4 bg-white/50 dark:bg-zinc-900/50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/50 backdrop-blur-sm text-sm cursor-pointer transition-all"
        >
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
            @endforeach
        </select>
    </div>
</div>

@if (session()->has('message'))
    <div class="flex items-center p-4 mb-6 text-emerald-800 bg-emerald-500/20 backdrop-blur-md border border-emerald-500/30 rounded-2xl shadow-lg animate-in fade-in slide-in-from-top-4 duration-300">
        <x-phosphor-check-circle-duotone class="w-6 h-6 mr-3 text-emerald-600" />
        <span class="text-sm font-medium">{{ session('message') }}</span>
    </div>
@endif

<div wire:loading.class="opacity-50 transition-opacity duration-300" class="relative">
    @if($productos->isEmpty())
        <div class="w-full p-20 text-center bg-white/30 backdrop-blur-xl rounded-[3rem] border border-white/50 shadow-xl">
            <x-phosphor-ghost class="w-16 h-16 mx-auto mb-4 text-gray-400 opacity-50" />
            <p class="text-gray-500 font-medium">No hay productos que coincidan con la búsqueda.</p>
        </div>
    @else
        <div class="overflow-hidden bg-white/40 dark:bg-black/10 backdrop-blur-2xl rounded-[2.5rem] border border-white/40 dark:border-white/10 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-white/30 dark:bg-white/5 text-gray-600 dark:text-gray-300 uppercase text-[10px] tracking-widest font-bold">
                            <th class="px-7 py-5 text-left">Producto</th>
                            <th class="px-7 py-5 text-left">Precio</th>
                            <th class="px-7 py-5 text-left text-center">Puntos</th>
                            <th class="px-7 py-5 text-left text-center">Estado</th>
                            <th class="px-7 py-5 text-left">Categoría</th>
                            <th class="px-7 py-5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/20">
                        @foreach($productos as $producto)
                            <tr class="group hover:bg-white/40 dark:hover:bg-white/5 transition-all duration-300">
                                <td class="px-7 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 mr-3 flex items-center justify-center overflow-hidden border border-white/50 shadow-sm">
                                            @if($producto->imagen)
                                                <img src="{{ asset('storage/' . $producto->imagen) }}" class="w-full h-full object-contain bg-white p-1">
                                            @else
                                                <x-phosphor-package class="w-5 h-5 text-indigo-400" />
                                            @endif
                                        </div>
                                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $producto->nombre }}</span>
                                    </div>
                                </td>
                                <td class="px-7 py-4 font-semibold text-gray-600 dark:text-gray-300">
                                    ${{ number_format($producto->precio, 2) }}
                                </td>
                                <td class="px-7 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-500/10 text-indigo-600 text-xs font-bold border border-indigo-500/20">
                                        {{ $producto->puntos_por_unidad }} pts
                                    </span>
                                </td>
                                <td class="px-7 py-4 text-center">
                                    @if($producto->activo)
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
                                    @endif
                                </td>
                                <td class="px-7 py-4">
                                    <span class="text-xs px-3 py-1 rounded-full bg-gray-500/10 text-gray-500 border border-gray-500/20">
                                        {{ optional($producto->categorias->first())->nombre ?? 'Sin categoría' }}
                                    </span>
                                </td>
                                <td class="px-7 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="editProducto({{ $producto->id }})" class="p-2 rounded-xl bg-blue-500/10 text-blue-600 hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                            <x-phosphor-pencil-simple-bold class="w-4 h-4" />
                                        </button>
                                        <button 
                                            onclick="confirm('¿Seguro?') || event.stopImmediatePropagation()" 
                                            wire:click="deleteProducto({{ $producto->id }})" 
                                            class="p-2 rounded-xl bg-red-500/10 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                        >
                                            <x-phosphor-trash-bold class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

    @if($editing)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50">
            <div class="w-full max-w-2xl p-6 bg-white rounded-2xl shadow-2xl overflow-y-auto max-h-[95vh]">
                <h2 class="mb-4 text-xl font-bold text-gray-800">Editar producto</h2>
                
                <form wire:submit="saveProducto" class="space-y-4">
                    
                    {{-- Dropzone para Imagen --}}
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Imagen del Producto</label>
                        <div 
                            x-data="{ isUploading: false, progress: 0 }"
                            x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            class="relative"
                        >
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden">
                                    
                                    {{-- Previsualización --}}
                                    @if($nueva_imagen)
                                        <img src="{{ $nueva_imagen->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-contain p-2">
                                    @elseif($imagen)
                                        <img src="{{ asset('storage/' . $imagen) }}" class="absolute inset-0 w-full h-full object-contain p-2 opacity-50">
                                    @endif

                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ ($nueva_imagen || $imagen) ? 'bg-white/80 rounded-lg px-4 z-10' : '' }}">
                                        <x-phosphor-cloud-arrow-up class="w-8 h-8 mb-2 text-gray-500" />
                                        <p class="mb-2 text-sm text-gray-700 font-semibold text-center">Haz clic o arrastra una imagen</p>
                                        <p class="text-xs text-gray-500">PNG, JPG (Máx. 2MB)</p>
                                    </div>
                                    <input type="file" wire:model="nueva_imagen" class="hidden" accept="image/*" />
                                </label>
                            </div>

                            {{-- Barra de Progreso --}}
                            <div x-show="isUploading" class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-indigo-600 h-1.5 rounded-full transition-all" :style="`width: ${progress}%` text-align: center"></div>
                                </div>
                            </div>
                        </div>
                        @error('nueva_imagen') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Nombre --}}
                    <div>
                        <label for="edit-nombre" class="block mb-2 text-sm font-medium text-gray-700">Nombre</label>
                        <input id="edit-nombre" type="text" wire:model.live="nombre" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        @error('nombre') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Precio y Puntos --}}
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

                    {{-- Categoría y SKU --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="edit-descripcion" class="block mb-2 text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="edit-descripcion" rows="3" wire:model.live="descripcion" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"></textarea>
                        @error('descripcion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Stock y Activo --}}
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
                    </div>

                    {{-- Medidas --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500">Altura</label>
                            <input type="text" wire:model.live="altura" class="block w-full border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-200">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500">Anchura</label>
                            <input type="text" wire:model.live="anchura" class="block w-full border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-200">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500">Profundidad</label>
                            <input type="text" wire:model.live="profundidad" class="block w-full border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-200">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-500">Bulto</label>
                            <input type="text" wire:model.live="bulto" class="block w-full border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-200">
                        </div>
                    </div>

                    <div class="flex justify-end mt-8 space-x-3 sticky bottom-0 bg-white py-4 border-t">
                        <x-button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white">
                            Cancelar
                        </x-button>
                        <x-button type="submit" wire:loading.attr="disabled">
                            <span wire:loading.remove>Guardar cambios</span>
                            <span wire:loading>Guardando...</span>
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</x-app.container>
@endvolt
</x-layouts.app>