<?php
use function Laravel\Folio\name;
use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Volt\Component;

\Laravel\Folio\middleware('auth');
name('productos');

new class extends Component {
    public $productos = [];
    public $categorias = [];

    public $search = '';
    public $filter_categoria = '';
    public $vista = 'lista';

    public function mount(): void
    {
        $this->categorias = Categoria::orderBy('nombre')->get();
        $this->loadProductos();
    }

    public function updated($field): void
    {
        if (in_array($field, ['search', 'filter_categoria'], true)) {
            $this->loadProductos();
        }
    }

    public function setVista(string $vista): void
    {
        if (! in_array($vista, ['lista', 'grilla'], true)) {
            return;
        }

        $this->vista = $vista;
    }

    public function getSearchSuggestionsProperty()
    {
        if (mb_strlen(trim($this->search)) < 2) {
            return collect();
        }

        return Producto::query()
            ->select('id', 'nombre', 'sku')
            ->where(function ($query) {
                $query->where('nombre', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%');
            })
            ->orderBy('nombre')
            ->limit(8)
            ->get();
    }

    public function loadProductos(): void
    {
        $query = Producto::with('categorias')->latest();

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->filter_categoria)) {
            $query->whereHas('categorias', function ($q) {
                $q->where('categorias.id', $this->filter_categoria);
            });
        }

        $this->productos = $query->get();
    }

    public function deleteProducto(Producto $producto): void
    {
        $producto->delete();
        $this->loadProductos();

        session()->flash('message', 'Producto eliminado correctamente.');
    }
};
?>

<x-layouts.app>
@volt('productos')
<x-app.container>
    <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Catálogo de productos</h1>
            <p class="text-sm text-slate-500">Administra tu catálogo con una experiencia más visual y ordenada.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <x-button tag="a" :href="route('productos.create')">Nuevo producto</x-button>
            <x-button tag="a" :href="url('/productos/masivo')" class="bg-indigo-500 hover:bg-indigo-600 text-white">
                Creación masiva
            </x-button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-3 mb-4 text-green-700 border border-green-300 rounded-xl bg-green-50">
            {{ session('message') }}
        </div>
    @endif

    <div class="p-4 mb-5 bg-white border shadow-sm rounded-2xl border-slate-200">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <label for="buscador-productos" class="block mb-2 text-xs font-semibold tracking-wide uppercase text-slate-500">Buscar</label>
                <input
                    id="buscador-productos"
                    type="text"
                    list="sugerencias-productos"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre o SKU"
                    class="w-full border rounded-xl border-slate-300 focus:border-indigo-400 focus:ring-indigo-200"
                >
                <datalist id="sugerencias-productos">
                    @foreach($this->searchSuggestions as $suggestion)
                        <option value="{{ $suggestion->nombre }}">{{ $suggestion->sku ? $suggestion->sku : 'Sin SKU' }}</option>
                        @if($suggestion->sku)
                            <option value="{{ $suggestion->sku }}">{{ $suggestion->nombre }}</option>
                        @endif
                    @endforeach
                </datalist>
            </div>

            <div class="lg:col-span-3">
                <label for="filtro-categoria" class="block mb-2 text-xs font-semibold tracking-wide uppercase text-slate-500">Categoría</label>
                <select
                    id="filtro-categoria"
                    wire:model.live="filter_categoria"
                    class="w-full border rounded-xl border-slate-300 focus:border-indigo-400 focus:ring-indigo-200"
                >
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3">
                <span class="block mb-2 text-xs font-semibold tracking-wide uppercase text-slate-500">Visualización</span>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="setVista('lista')" class="px-3 py-2 text-sm font-medium border rounded-xl {{ $vista === 'lista' ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-slate-300 text-slate-600 bg-white' }}">Lista</button>
                    <button type="button" wire:click="setVista('grilla')" class="px-3 py-2 text-sm font-medium border rounded-xl {{ $vista === 'grilla' ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-slate-300 text-slate-600 bg-white' }}">Grilla</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
        @if($productos->isEmpty())
            <div class="p-12 text-center bg-white border border-dashed rounded-2xl border-slate-300">
                <p class="text-slate-500">No hay productos que coincidan con la búsqueda.</p>
            </div>
        @elseif($vista === 'lista')
            <div class="overflow-x-auto bg-white border shadow-sm rounded-2xl border-slate-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Producto</th>
                            <th class="px-4 py-3 text-left">SKU</th>
                            <th class="px-4 py-3 text-left">Precio</th>
                            <th class="px-4 py-3 text-left">Puntos</th>
                            <th class="px-4 py-3 text-left">Categoría</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr class="border-t border-slate-100 hover:bg-slate-50/70">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $producto->nombre }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $producto->sku ?: '—' }}</td>
                                <td class="px-4 py-3">${{ number_format($producto->precio, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $producto->puntos_por_unidad }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ optional($producto->categorias->first())->nombre ?? 'Sin categoría' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $producto->activo ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-3">
                                        <a href="{{ route('productos.editar', ['producto' => $producto->id]) }}" class="font-medium text-indigo-600 hover:text-indigo-700">Editar</a>
                                        <button wire:click="deleteProducto({{ $producto->id }})" type="button" class="font-medium text-rose-600 hover:text-rose-700">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($productos as $producto)
                    <article class="p-4 bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <h3 class="font-semibold text-slate-900">{{ $producto->nombre }}</h3>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $producto->activo ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span>
                        </div>
                        <p class="mb-1 text-sm text-slate-500">SKU: {{ $producto->sku ?: 'Sin SKU' }}</p>
                        <p class="mb-1 text-sm text-slate-600">Categoría: {{ optional($producto->categorias->first())->nombre ?? 'Sin categoría' }}</p>
                        <div class="mt-3 mb-4">
                            <p class="text-lg font-bold text-slate-800">${{ number_format($producto->precio, 2, ',', '.') }}</p>
                            <p class="text-sm text-slate-500">{{ $producto->puntos_por_unidad }} puntos por unidad</p>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ route('productos.editar', ['producto' => $producto->id]) }}" class="font-medium text-indigo-600 hover:text-indigo-700">Editar</a>
                            <button wire:click="deleteProducto({{ $producto->id }})" type="button" class="font-medium text-rose-600 hover:text-rose-700">Eliminar</button>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-app.container>
@endvolt
</x-layouts.app>
