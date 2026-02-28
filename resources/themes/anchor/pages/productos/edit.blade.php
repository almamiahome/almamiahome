<?php
use function Laravel\Folio\{middleware, name};
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use App\Models\Producto;
use App\Models\Categoria;

middleware('auth');
name('productos.edit');

new class extends Component
{
    public Producto $producto;

    #[Validate('required|max:255')]
    public $nombre;

    #[Validate('required|numeric|min:0')]
    public $precio;

    #[Validate('required|integer|min:0')]
    public $puntos_por_unidad;

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

    #[Validate('nullable|string|max:255')]
    public $imagen = null;

    public $categorias = [];

    #[Validate('nullable|integer|exists:categorias,id')]
    public $categoria_id = null;

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
        $this->categorias = Categoria::orderBy('nombre')->get();

        $this->fill($producto->only([
            'nombre','precio','puntos_por_unidad','sku','descripcion',
            'stock_actual','activo','altura','anchura','profundidad','bulto','imagen'
        ]));

        $this->categoria_id = $producto->categorias()->pluck('id')->first();
    }

    public function save()
    {
        $validated = $this->validate();

        $this->producto->update($validated);

        $this->producto->categorias()->sync($this->categoria_id ? [$this->categoria_id] : []);

        session()->flash('message', 'Producto actualizado exitosamente.');

        $this->redirect(route('productos'));
    }
}
?>

<x-layouts.app>
    @volt('productos.edit')
        <x-app.container>
            <x-elements.back-button
                class="max-w-full mx-auto mb-3"
                text="Volver a Productos"
                :href="route('productos')"
            />
            <div class="flex items-center justify-between mb-3">
                <x-app.heading
                    title="Editar Producto"
                    description=""
                    :border="false"
                />
            </div>

            <form wire:submit="save" class="space-y-4 max-w-2xl">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block mb-2 text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="nombre" wire:model.live="nombre"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('nombre') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Precio -->
                <div>
                    <label for="precio" class="block mb-2 text-sm font-medium text-gray-700">Precio</label>
                    <input type="number" step="0.01" id="precio" wire:model.live="precio"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('precio') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Puntos por unidad -->
                <div>
                    <label for="puntos_por_unidad" class="block mb-2 text-sm font-medium text-gray-700">Puntos por unidad</label>
                    <input type="number" id="puntos_por_unidad" wire:model.live="puntos_por_unidad"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('puntos_por_unidad') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="categoria_id" class="block mb-2 text-sm font-medium text-gray-700">Categoría</label>
                    <select id="categoria_id" wire:model="categoria_id"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Sin categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Campos opcionales -->
                <div>
                    <label for="sku" class="block mb-2 text-sm font-medium text-gray-700">SKU</label>
                    <input type="text" id="sku" wire:model.live="sku"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200">
                    @error('sku') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="descripcion" wire:model.live="descripcion" rows="3"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200"></textarea>
                    @error('descripcion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="stock_actual" class="block mb-2 text-sm font-medium text-gray-700">Stock actual</label>
                    <input type="number" id="stock_actual" wire:model.live="stock_actual"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200">
                    @error('stock_actual') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="activo" wire:model.live="activo"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="activo" class="ml-2 text-sm text-gray-700">Activo</label>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="altura" class="block mb-2 text-sm font-medium text-gray-700">Altura</label>
                        <input type="text" id="altura" wire:model.live="altura"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200">
                        @error('altura') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="anchura" class="block mb-2 text-sm font-medium text-gray-700">Anchura</label>
                        <input type="text" id="anchura" wire:model.live="anchura"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200">
                        @error('anchura') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="profundidad" class="block mb-2 text-sm font-medium text-gray-700">Profundidad</label>
                        <input type="text" id="profundidad" wire:model.live="profundidad"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200">
                        @error('profundidad') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="bulto" class="block mb-2 text-sm font-medium text-gray-700">Bulto</label>
                        <input type="text" id="bulto" wire:model.live="bulto"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200">
                        @error('bulto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="imagen" class="block mb-2 text-sm font-medium text-gray-700">Imagen (ruta)</label>
                    <input type="text" id="imagen" wire:model.live="imagen"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:ring-indigo-200">
                    @error('imagen') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <x-button type="submit">
                    Guardar Cambios
                </x-button>
            </form>
        </x-app.container>
    @endvolt
</x-layouts.app>
