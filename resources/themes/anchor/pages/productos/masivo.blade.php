<?php
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Str;

middleware('auth');
name('productos.masivo');

new class extends Component {
    public $input_text = '';

    public function saveMasivo()
    {
        $lineas = collect(explode("\n", $this->input_text))
            ->map(fn($l) => trim($l))
            ->filter();

        foreach ($lineas as $linea) {
            [$nombre, $precio, $puntos, $categoria_nombre] = array_pad(explode('|', $linea), 4, null);

            $nombre = trim($nombre);
            $precio = $precio ? (float) trim($precio) : 0;
            $puntos = $puntos ? (int) trim($puntos) : 0;
            $categoria_nombre = trim($categoria_nombre);

            if (!$nombre) continue;

            $producto = Producto::create([
                'nombre' => $nombre,
                'precio' => $precio,
                'puntos_por_unidad' => $puntos,
                'activo' => true,
            ]);

            if ($categoria_nombre) {
                $categoria = Categoria::firstOrCreate(
                    ['slug' => Str::slug($categoria_nombre)],
                    ['nombre' => $categoria_nombre]
                );
                $producto->categorias()->sync([$categoria->id]);
            }
        }

        session()->flash('message', 'Productos creados correctamente.');
        $this->input_text = '';
    }
};
?>

<x-layouts.app>
    @volt('productos.masivo')
        <x-app.container>
            <x-elements.back-button text="Volver al listado" :href="route('productos')" class="mb-4" />

            <x-app.heading
                title="Creación masiva de productos"
                description="Pegá una lista de productos. Formato: nombre | precio | puntos | categoría (opcional)"
                :border="false"
            />

            @if (session()->has('message'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="saveMasivo" class="space-y-4 max-w-3xl">
                <div>
                    <label for="input_text" class="block mb-2 text-sm font-medium text-gray-700">
                        Lista de productos
                    </label>
                    <textarea
                        id="input_text"
                        rows="10"
                        wire:model.defer="input_text"
                        placeholder="Ejemplo:
Camisa Azul | 25000 | 50 | Ropa
Pantalón Jeans | 35000 | 60 | Ropa
Perfume Floral | 12000 | 30 | Perfumería"
                        class="w-full border-gray-300 rounded-md shadow-xs focus:ring focus:ring-indigo-200"
                    ></textarea>
                </div>

                <x-button type="submit">
                    Crear productos
                </x-button>
            </form>
        </x-app.container>
    @endvolt
</x-layouts.app>
