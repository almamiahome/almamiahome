<?php
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use App\Models\Pedido;
use App\Models\PedidoArticulo;
use App\Models\Producto;
use Carbon\Carbon;

middleware('auth');
name('pedidos.create');

new class extends Component {
    public $vendedora_id;
    public $lider_id;
    public $responsable_id;
    public $productos = [];

    public $articulos = [
        ['producto_id' => null, 'descripcion' => '', 'cantidad' => 1, 'precio_unitario' => 0, 'subtotal' => 0, 'puntos' => 0],
    ];

    public function mount()
    {
        $this->productos = Producto::select('id', 'nombre', 'precio', 'puntos_por_unidad')->orderBy('nombre')->get();
    }

    public function updatedArticulos($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'producto_id' && $value) {
            $producto = $this->productos->firstWhere('id', $value);
            if ($producto) {
                $this->articulos[$index]['descripcion'] = $producto->nombre;
                $this->articulos[$index]['precio_unitario'] = $producto->precio;
                $this->articulos[$index]['puntos'] = $producto->puntos_por_unidad;
                $this->recalcularSubtotal($index);
            }
        }

        if ($field === 'cantidad') {
            $this->recalcularSubtotal($index);
        }
    }

    public function recalcularSubtotal($index)
    {
        $art = &$this->articulos[$index];
        $art['subtotal'] = $art['cantidad'] * $art['precio_unitario'];
    }

    public function addArticulo()
    {
        $this->articulos[] = ['producto_id' => null, 'descripcion' => '', 'cantidad' => 1, 'precio_unitario' => 0, 'subtotal' => 0, 'puntos' => 0];
    }

    public function removeArticulo($index)
    {
        unset($this->articulos[$index]);
        $this->articulos = array_values($this->articulos);
    }

    public function save()
    {
        $this->validate([
            'vendedora_id' => 'nullable|exists:users,id',
            'lider_id' => 'nullable|exists:users,id',
            'responsable_id' => 'nullable|exists:users,id',
            'articulos.*.producto_id' => 'required|exists:productos,id',
            'articulos.*.cantidad' => 'required|integer|min:1',
        ]);

        $fecha = Carbon::now();
        $mes = $fecha->translatedFormat('F');

        // Crea el pedido sin código todavía
        $pedido = Pedido::create([
            'vendedora_id' => $this->vendedora_id,
            'lider_id' => $this->lider_id,
            'responsable_id' => $this->responsable_id,
            'fecha' => $fecha,
            'mes' => ucfirst($mes),
        ]);

        // Actualiza el código con el ID recién generado
        $pedido->update(['codigo_pedido' => 'PED-' . str_pad($pedido->id, 5, '0', STR_PAD_LEFT)]);

        // Guarda los artículos
        foreach ($this->articulos as $articulo) {
            $pedido->articulos()->create([
                'producto' => optional(Producto::find($articulo['producto_id']))->nombre,
                'descripcion' => $articulo['descripcion'],
                'cantidad' => $articulo['cantidad'],
                'precio_unitario' => $articulo['precio_unitario'],
                'subtotal' => $articulo['subtotal'],
                'puntos' => $articulo['puntos'],
            ]);
        }

        session()->flash('message', 'Pedido creado correctamente.');
        $this->redirect(route('pedidos'));
    }
};
?>

<x-layouts.app>
@volt('pedidos.create')
<x-app.container>
    <x-elements.back-button text="Volver a pedidos" :href="route('pedidos')" />
    <x-app.heading title="Nuevo Pedido" :border="false" />

    <form wire:submit="save" class="space-y-4">

        <div class="grid grid-cols-3 gap-4">
            <div><label>Vendedora</label><input type="text" wire:model="vendedora_id" class="w-full border-gray-300 rounded-md"></div>
            <div><label>Líder</label><input type="text" wire:model="lider_id" class="w-full border-gray-300 rounded-md"></div>
            <div><label>Responsable</label><input type="text" wire:model="responsable_id" class="w-full border-gray-300 rounded-md"></div>
        </div>

        <hr class="my-4 border-gray-300">
        <h3 class="text-lg font-semibold mb-2">Artículos</h3>

        <div class="space-y-3">
            @foreach($articulos as $index => $art)
                <div class="p-4 border rounded-md bg-gray-50 relative">
                    <div class="grid grid-cols-6 gap-3">
                        <!-- Producto -->
                        <select wire:model="articulos.{{ $index }}.producto_id" class="col-span-2 border-gray-300 rounded-md">
                            <option value="">Seleccione producto</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>

                        <!-- Descripción -->
                        <input type="text" wire:model="articulos.{{ $index }}.descripcion" class="col-span-2 border-gray-300 rounded-md bg-gray-100" readonly>

                        <!-- Cantidad -->
                        <input type="number" wire:model="articulos.{{ $index }}.cantidad" min="1" class="border-gray-300 rounded-md">

                        <!-- Precio Unitario -->
                        <input type="number" wire:model="articulos.{{ $index }}.precio_unitario" step="0.01" class="border-gray-300 rounded-md bg-gray-100" readonly>

                        <!-- Subtotal -->
                        <input type="number" wire:model="articulos.{{ $index }}.subtotal" step="0.01" class="border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>

                    <button type="button" wire:click="removeArticulo({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            @endforeach
        </div>

        <div>
            <x-button type="button" wire:click="addArticulo" class="bg-indigo-500 hover:bg-indigo-600 text-white">
                + Agregar Artículo
            </x-button>
        </div>

        <x-button type="submit" class="mt-5">Crear Pedido</x-button>
    </form>
</x-app.container>
@endvolt
</x-layouts.app>
