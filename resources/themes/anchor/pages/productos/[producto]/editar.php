<?php

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use function Laravel\Folio\name;

\Laravel\Folio\middleware('auth');
name('productos.editar');

new class extends Component
{
    use WithFileUploads;

    public Producto $producto;

    #[Validate('required|max:255')]
    public $nombre = '';

    #[Validate('required|numeric|min:0')]
    public $precio = 0;

    #[Validate('required|integer|min:0')]
    public $puntos_por_unidad = 0;

    #[Validate('nullable|string|max:255')]
    public $sku = null;

    #[Validate('nullable|string|max:65535')]
    public $descripcion = null;

    #[Validate('nullable|integer|min:0')]
    public $stock_actual = null;

    #[Validate('nullable|boolean')]
    public $activo = true;

    #[Validate('nullable|string|max:255')]
    public $altura = null;

    #[Validate('nullable|string|max:255')]
    public $anchura = null;

    #[Validate('nullable|string|max:255')]
    public $profundidad = null;

    #[Validate('nullable|string|max:255')]
    public $bulto = null;

    #[Validate('nullable|image|max:2048')]
    public $imagen = null;

    public $imagen_actual = null;

    #[Validate('nullable|integer|exists:categorias,id')]
    public $categoria_id = null;

    public $categorias = [];

    public function mount(Producto $producto): void
    {
        $this->producto = $producto;
        $this->categorias = Categoria::orderBy('nombre')->get();

        $this->fill($producto->only([
            'nombre',
            'precio',
            'puntos_por_unidad',
            'sku',
            'descripcion',
            'stock_actual',
            'activo',
            'altura',
            'anchura',
            'profundidad',
            'bulto',
        ]));

        $this->imagen_actual = $producto->imagen;
        $this->categoria_id = $producto->categorias()->pluck('id')->first();
    }

    public function save(): void
    {
        $validated = $this->validate();
        unset($validated['imagen']);

        if ($this->imagen) {
            $validated['imagen'] = $this->imagen->store('productos', 'public');
        } else {
            $validated['imagen'] = $this->imagen_actual;
        }

        $this->producto->update($validated);
        $this->producto->categorias()->sync($this->categoria_id ? [$this->categoria_id] : []);

        session()->flash('message', 'Producto actualizado exitosamente.');
        $this->redirect(route('productos'));
    }
}
?>

<x-layouts.app>
    @volt('productos.editar')
        <x-app.container>
            <div class="max-w-4xl mx-auto">
                <x-elements.back-button
                    class="mb-6"
                    text="Volver a Productos"
                    :href="route('productos')"
                />

                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Editar Producto</h1>
                    <p class="text-sm text-gray-500">Actualiza la información del producto con el mismo formato de creación.</p>
                </div>

                <form wire:submit="save" class="space-y-8">
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h2 class="text-lg font-semibold mb-4 text-gray-800">Información General</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre del Producto</label>
                                <input type="text" wire:model="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('nombre') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría</label>
                                <select wire:model="categoria_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Selecciona una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('categoria_id') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">SKU (Código Interno)</label>
                                <input type="text" wire:model="sku" placeholder="Ej: PROD-001" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('sku') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea wire:model="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                @error('descripcion') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <h2 class="text-lg font-semibold mb-4 text-gray-800">Precios y Puntos</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Precio</label>
                                    <input type="number" step="0.01" wire:model="precio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('precio') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Puntos por Unidad</label>
                                    <input type="number" wire:model="puntos_por_unidad" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('puntos_por_unidad') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <h2 class="text-lg font-semibold mb-4 text-gray-800">Inventario y Estado</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stock Actual</label>
                                    <input type="number" wire:model="stock_actual" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('stock_actual') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="pt-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="activo" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-700">Producto Visible/Activo</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h2 class="text-lg font-semibold mb-4 text-gray-800">Dimensiones</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-bold">Altura</label>
                                        <input type="text" wire:model="altura" placeholder="cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-bold">Anchura</label>
                                        <input type="text" wire:model="anchura" placeholder="cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-bold">Profundidad</label>
                                        <input type="text" wire:model="profundidad" placeholder="cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-bold">Bulto/Peso</label>
                                        <input type="text" wire:model="bulto" placeholder="kg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold mb-4 text-gray-800">Imagen del Producto</h2>
                                @if ($imagen_actual)
                                    <p class="mb-3 text-xs text-slate-500">Imagen actual: {{ $imagen_actual }}</p>
                                @endif
                                <div class="flex items-center justify-center w-full">
                                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative">
                                        @if ($imagen)
                                            <img src="{{ $imagen->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover opacity-50">
                                        @endif
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                            <p class="text-sm text-gray-500"><span class="font-semibold">Haz clic para actualizar</span></p>
                                        </div>
                                        <input type="file" wire:model="imagen" class="hidden" />
                                    </label>
                                </div>
                                @error('imagen') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <x-button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-lg transition-all transform hover:scale-105">
                            Guardar Cambios
                        </x-button>
                    </div>
                </form>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
