<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Catalogo;
use App\Models\CatalogoPagina;
use App\Models\CatalogoPaginaProducto;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

middleware([
    'auth',
    function ($request, $next) {
        if (! $request->user() || ! $request->user()->hasRole('admin')) {
            return redirect('/catalogo');
          
        }

        return $next($request);
    },
]);

name('catalogo.admin');

new class extends Component {
    use WithFileUploads;

    public $catalogos = [];
    public $paginas = [];
    public $productos = [];

    public $selectedCatalogoId = null;

    public $catalogoForm = [
        'nombre'         => '',
        'descripcion'    => '',
        'imagen_portada' => null,
    ];

    public $catalogoEditingId = null;

    public $paginaForm = [
        'catalogo_id' => null,
        'numero'      => 1,
        'imagen'      => null,
    ];

    public $paginaEditingId = null;

    public $mapForm = [
        'catalogo_pagina_id' => null,
        'producto_id'        => null,
        'pos_x'              => 50,
        'pos_y'              => 50,
    ];

    public $mapEditingId = null;

    public function mount(): void
    {
        if (! auth()->user()?->hasRole('admin')) {
            redirect()->to('/catalogo')->send();
        }

        $this->productos = Producto::orderBy('nombre')->get();
        $this->loadCatalogos();
    }

    public function loadCatalogos(): void
    {
        $this->catalogos = Catalogo::with(['paginas.productos.producto'])
            ->orderByDesc('id')
            ->get();

        if (! $this->selectedCatalogoId) {
            $this->selectedCatalogoId = $this->catalogos->first()?->id;
        }

        $this->paginaForm['catalogo_id'] = $this->selectedCatalogoId;
        $this->refreshPaginas();
    }

    public function selectCatalogo($catalogoId): void
    {
        $catalogoId = $catalogoId ? (int) $catalogoId : null;

        $this->selectedCatalogoId           = $catalogoId;
        $this->paginaForm['catalogo_id']    = $catalogoId;
        $this->mapForm['catalogo_pagina_id'] = null;

        $this->refreshPaginas();
    }

    public function refreshPaginas(): void
    {
        $catalogo = Catalogo::with(['paginas.productos.producto'])
            ->find($this->selectedCatalogoId);

        $this->paginas = $catalogo
            ? $catalogo->paginas->sortBy('numero')->values()
            : collect();

        if (! $this->mapForm['catalogo_pagina_id']) {
            $this->mapForm['catalogo_pagina_id'] = $this->paginas->first()->id ?? null;
        }
    }

    public function editCatalogo(int $catalogoId): void
    {
        $catalogo                     = Catalogo::findOrFail($catalogoId);
        $this->catalogoEditingId      = $catalogo->id;
        $this->catalogoForm['nombre'] = $catalogo->nombre;
        $this->catalogoForm['descripcion'] = $catalogo->descripcion;
        $this->catalogoForm['imagen_portada'] = null;
    }

    public function saveCatalogo(): void
    {
        $rules = [
            'catalogoForm.nombre'         => 'required|string|max:255',
            'catalogoForm.descripcion'    => 'nullable|string|max:1000',
            'catalogoForm.imagen_portada' => 'nullable|image|max:4096',
        ];

        $this->validate($rules);

        $catalogo = $this->catalogoEditingId
            ? Catalogo::findOrFail($this->catalogoEditingId)
            : new Catalogo();

        $catalogo->nombre      = $this->catalogoForm['nombre'];
        $catalogo->descripcion = $this->catalogoForm['descripcion'];

        if ($this->catalogoForm['imagen_portada']) {
            if ($catalogo->imagen_portada) {
                Storage::disk('public')->delete($catalogo->imagen_portada);
            }

            $catalogo->imagen_portada = $this->catalogoForm['imagen_portada']
                ->store('catalogo/portadas', 'public');
        }

        $catalogo->save();

        $this->resetCatalogoForm();
        $this->loadCatalogos();
    }

    public function deleteCatalogo(int $catalogoId): void
    {
        $catalogo = Catalogo::findOrFail($catalogoId);

        foreach ($catalogo->paginas as $pagina) {
            $this->deletePagina($pagina->id, false);
        }

        if ($catalogo->imagen_portada) {
            Storage::disk('public')->delete($catalogo->imagen_portada);
        }

        $catalogo->delete();

        if ($this->selectedCatalogoId === $catalogoId) {
            $this->selectedCatalogoId = null;
        }

        $this->loadCatalogos();
    }

    public function editPagina(int $paginaId): void
    {
        $pagina                         = CatalogoPagina::findOrFail($paginaId);
        $this->paginaEditingId          = $pagina->id;
        $this->paginaForm['catalogo_id'] = $pagina->catalogo_id;
        $this->paginaForm['numero']     = $pagina->numero;
        $this->paginaForm['imagen']     = null;
    }

    public function savePagina(): void
    {
        $rules = [
            'paginaForm.catalogo_id' => 'required|exists:catalogos,id',
            'paginaForm.numero'      => 'required|integer|min:1',
            'paginaForm.imagen'      => 'nullable|image|max:8192',
        ];

        $this->validate($rules);

        $pagina = $this->paginaEditingId
            ? CatalogoPagina::findOrFail($this->paginaEditingId)
            : new CatalogoPagina();

        $pagina->catalogo_id = $this->paginaForm['catalogo_id'];
        $pagina->numero      = $this->paginaForm['numero'];

        if ($this->paginaForm['imagen']) {
            if ($pagina->imagen) {
                Storage::disk('public')->delete($pagina->imagen);
            }

            $pagina->imagen = $this->paginaForm['imagen']
                ->store('catalogo/paginas', 'public');
        }

        $pagina->save();

        $this->resetPaginaForm();
        $this->refreshPaginas();
        $this->loadCatalogos();
    }

    public function deletePagina(int $paginaId, bool $refresh = true): void
    {
        $pagina = CatalogoPagina::findOrFail($paginaId);

        foreach ($pagina->productos as $pivot) {
            $pivot->delete();
        }

        if ($pagina->imagen) {
            Storage::disk('public')->delete($pagina->imagen);
        }

        $pagina->delete();

        if ($refresh) {
            if ($this->mapForm['catalogo_pagina_id'] === $paginaId) {
                $this->mapForm['catalogo_pagina_id'] = null;
            }

            $this->refreshPaginas();
            $this->loadCatalogos();
        }
    }

    public function editMap(int $mapId): void
    {
        $pivot                           = CatalogoPaginaProducto::findOrFail($mapId);
        $this->mapEditingId              = $pivot->id;
        $this->mapForm['catalogo_pagina_id'] = $pivot->catalogo_pagina_id;
        $this->mapForm['producto_id']    = $pivot->producto_id;
        $this->mapForm['pos_x']          = $pivot->pos_x;
        $this->mapForm['pos_y']          = $pivot->pos_y;
    }

    public function saveMap(): void
    {
        $rules = [
            'mapForm.catalogo_pagina_id' => 'required|exists:catalogo_paginas,id',
            'mapForm.producto_id'        => 'required|exists:productos,id',
            'mapForm.pos_x'              => 'required|numeric|min:0|max:100',
            'mapForm.pos_y'              => 'required|numeric|min:0|max:100',
        ];

        $this->validate($rules);

        $pivot = $this->mapEditingId
            ? CatalogoPaginaProducto::findOrFail($this->mapEditingId)
            : new CatalogoPaginaProducto();

        $pivot->catalogo_pagina_id = $this->mapForm['catalogo_pagina_id'];
        $pivot->producto_id        = $this->mapForm['producto_id'];
        $pivot->pos_x              = $this->mapForm['pos_x'];
        $pivot->pos_y              = $this->mapForm['pos_y'];
        $pivot->save();

        $this->resetMapForm();
        $this->refreshPaginas();
    }

    public function deleteMap(int $mapId): void
    {
        $pivot = CatalogoPaginaProducto::findOrFail($mapId);
        $pivot->delete();
        $this->refreshPaginas();
    }

    // ðŸ‘‡ Estos mÃ©todos deben ser públicos porque se llaman con wire:click

    public function resetCatalogoForm(): void
    {
        $this->catalogoEditingId = null;
        $this->catalogoForm      = [
            'nombre'         => '',
            'descripcion'    => '',
            'imagen_portada' => null,
        ];
    }

    public function resetPaginaForm(): void
    {
        $this->paginaEditingId             = null;
        $this->paginaForm['numero']        = 1;
        $this->paginaForm['imagen']        = null;
        $this->paginaForm['catalogo_id']   = $this->selectedCatalogoId;
    }

    public function resetMapForm(): void
    {
        $this->mapEditingId = null;
        $this->mapForm      = [
            'catalogo_pagina_id' => $this->paginas->first()->id ?? null,
            'producto_id'        => null,
            'pos_x'              => 50,
            'pos_y'              => 50,
        ];
    }
};

?>

<x-layouts.app>
    @volt('catalogo.admin')
    <x-app.container>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <x-app.heading
                    title="Administración del catálogo"
                    description="Gestiona catálogos, Páginas e imágenes para el visor de catálogo"
                    :border="false"
                />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                {{-- Columna: Catalogos --}}
                <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold mb-3">Catálogos</h3>
                <p class="text-sm text-gray-600">Agrega varios catalogos, por defecto, dejar solo uno activo o editar el ya creado para mantener solo un catalogo en el sistema</p>

                    <form wire:submit.prevent="saveCatalogo" class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" wire:model="catalogoForm.nombre" class="w-full border-gray-300 rounded-md" required>
                            @error('catalogoForm.nombre')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea wire:model="catalogoForm.descripcion" class="w-full border-gray-300 rounded-md" rows="2"></textarea>
                            @error('catalogoForm.descripcion')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Imagen de portada</label>
                            <input type="file" wire:model="catalogoForm.imagen_portada" accept="image/*" class="w-full text-sm">
                            @error('catalogoForm.imagen_portada')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex gap-2">
                            <x-button type="submit">
                                {{ $this->catalogoEditingId ? 'Actualizar catálogo' : 'Crear catálogo' }}
                            </x-button>

                            @if($this->catalogoEditingId)
                                <x-button type="button" wire:click="resetCatalogoForm" class="bg-gray-200 text-gray-800">
                                    Cancelar
                                </x-button>
                            @endif
                        </div>
                    </form>

                    <div class="mt-4 space-y-2">
                        @forelse($catalogos as $catalogo)
                            <div class="flex items-center justify-between p-3 border rounded-lg">
                                <div>
                                    <p class="font-semibold">{{ $catalogo->nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ $catalogo->descripcion }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <x-button size="sm" wire:click="selectCatalogo({{ $catalogo->id }})" class="bg-indigo-100 text-indigo-700">
                                        Páginas
                                    </x-button>
                                    <x-button size="sm" wire:click="editCatalogo({{ $catalogo->id }})">
                                        Editar
                                    </x-button>
                                    <x-button size="sm" color="danger" wire:click="deleteCatalogo({{ $catalogo->id }})">
                                        Eliminar
                                    </x-button>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Aún no hay catálogos creados.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: Páginas --}}
                <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-200 lg:col-span-1">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-lg font-semibold">Páginas del catálogo</h3>
                            <p class="text-sm text-gray-500">Sube imágenes y define el orden del catálogo seleccionado.</p>
                            <span class="text-sm text-gray-500">Catálogo activo:</span>
                            <select
                                wire:model="selectedCatalogoId"
                                wire:change="selectCatalogo($event.target.value)"
                                class="border-gray-300 rounded-md"
                            >
                                <option value="">Seleccione</option>
                                @foreach($catalogos as $catalogo)
                                    <option value="{{ $catalogo->id }}">{{ $catalogo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2 items-center">
                        </div>
                    </div>

                    <form wire:submit.prevent="savePagina" class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                        <input type="hidden" wire:model="paginaForm.catalogo_id">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número de Página</label>
                            <input type="number" min="1" wire:model="paginaForm.numero" class="w-full border-gray-300 rounded-md" required>
                            @error('paginaForm.numero')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Imagen</label>
                            <input type="file" wire:model="paginaForm.imagen" accept="image/*" class="w-full text-sm">
                            @error('paginaForm.imagen')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="lg:col-span-3 flex gap-2">
                            <x-button type="submit">
                                {{ $this->paginaEditingId ? 'Actualizar Página' : 'Agregar Página' }}
                            </x-button>

                            @if($this->paginaEditingId)
                                <x-button type="button" wire:click="resetPaginaForm" class="bg-gray-200 text-gray-800">
                                    Cancelar
                                </x-button>
                            @endif
                        </div>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                        @forelse($paginas as $pagina)
                            <div class="border rounded-lg p-3 flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold">Página {{ $pagina->numero }}</p>
                                        <p class="text-xs text-gray-500">{{ $pagina->productos->count() }} productos ubicados</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <x-button size="sm" wire:click="editPagina({{ $pagina->id }})">
                                            Editar
                                        </x-button>
                                        <x-button size="sm" color="danger" wire:click="deletePagina({{ $pagina->id }})">
                                            Eliminar
                                        </x-button>
                                    </div>
                                </div>

                                @if($pagina->imagen)
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/'.$pagina->imagen) }}" alt="Página {{ $pagina->numero }}" class="w-full rounded-lg border">

                                        @foreach($pagina->productos as $pivot)
                                            <span
                                                class="absolute h-3 w-3 rounded-full bg-indigo-600 border-2 border-white shadow"
                                                style="left: {{ $pivot->pos_x }}%; top: {{ $pivot->pos_y }}%; transform: translate(-50%, -50%);"
                                                title="{{ $pivot->producto?->sku ?? 'Sin SKU' }} - {{ $pivot->producto?->nombre ?? 'Producto eliminado' }}"
                                            ></span>

                                            <span
                                                class="absolute text-[10px] bg-black/70 text-white px-1.5 py-0.5 rounded"
                                                style="left: {{ $pivot->pos_x }}%; top: {{ $pivot->pos_y }}%; transform: translate(-50%, calc(-100% - 8px));"
                                            >
                                                {{ $pivot->producto?->sku ?? ($pivot->producto?->nombre ?? 'Sin producto') }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($pagina->productos->isNotEmpty())
                                    <div class="text-xs text-gray-600">
                                        @foreach($pagina->productos as $pivot)
                                            <p>
                                                #{{ $pivot->producto?->sku ?? $pivot->producto?->id }}
                                                - {{ $pivot->producto?->nombre }}
                                                ({{ $pivot->pos_x }}%, {{ $pivot->pos_y }}%)
                                            </p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay Páginas cargadas para este catálogo.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Bloque: Mapear productos en Páginas --}}
            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-semibold">Asignar productos a Páginas</h3>
                        <p class="text-sm text-gray-500">
                            Define la posición relativa (0 a 100) para mostrar el botón flotante sobre la imagen.
                        </p>
                    </div>
                    <div class="flex gap-2 items-center">
                        <span class="text-sm text-gray-500">Página:</span>
                        <select wire:model="mapForm.catalogo_pagina_id" class="border-gray-300 rounded-md">
                            <option value="">Seleccione una Página</option>
                            @foreach($paginas as $pagina)
                                <option value="{{ $pagina->id }}">Página {{ $pagina->numero }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <form wire:submit.prevent="saveMap" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Producto</label>
                        <select wire:model="mapForm.producto_id" class="w-full border-gray-300 rounded-md">
                            <option value="">Seleccione un producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">
                                    {{ $producto->nombre }} (SKU: {{ $producto->sku ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('mapForm.producto_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Posición X (%)</label>
                        <input type="number" min="0" max="100" step="0.1" wire:model="mapForm.pos_x" class="w-full border-gray-300 rounded-md">
                        @error('mapForm.pos_x')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Posición Y (%)</label>
                        <input type="number" min="0" max="100" step="0.1" wire:model="mapForm.pos_y" class="w-full border-gray-300 rounded-md">
                        @error('mapForm.pos_y')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-4 flex gap-2">
                        <x-button type="submit">
                            {{ $this->mapEditingId ? 'Actualizar posición' : 'Agregar producto' }}
                        </x-button>

                        @if($this->mapEditingId)
                            <x-button type="button" wire:click="resetMapForm" class="bg-gray-200 text-gray-800">
                                Cancelar
                            </x-button>
                        @endif
                    </div>
                </form>

                @php
                    $paginaSeleccionada = $paginas->firstWhere('id', (int) ($mapForm['catalogo_pagina_id'] ?? 0));
                @endphp

                @if($paginaSeleccionada)
                    <div class="mt-4 p-3 border rounded-lg bg-gray-50">
                        <p class="text-sm font-medium text-gray-700 mb-2">
                            Previsualización de la página {{ $paginaSeleccionada->numero }}
                        </p>

                        @if($paginaSeleccionada->imagen)
                            <div class="relative inline-block max-w-md">
                                <img
                                    src="{{ asset('storage/'.$paginaSeleccionada->imagen) }}"
                                    alt="Previsualización de página {{ $paginaSeleccionada->numero }}"
                                    class="w-full rounded-lg border"
                                >

                                @foreach($paginaSeleccionada->productos as $pivot)
                                    <span
                                        class="absolute h-3 w-3 rounded-full bg-indigo-600 border-2 border-white shadow"
                                        style="left: {{ $pivot->pos_x }}%; top: {{ $pivot->pos_y }}%; transform: translate(-50%, -50%);"
                                        title="{{ $pivot->producto?->sku ?? 'Sin SKU' }} - {{ $pivot->producto?->nombre ?? 'Producto eliminado' }}"
                                    ></span>
                                @endforeach

                                <span
                                    class="absolute h-4 w-4 rounded-full bg-emerald-500/90 border-2 border-white ring-2 ring-emerald-300"
                                    style="left: {{ $mapForm['pos_x'] }}%; top: {{ $mapForm['pos_y'] }}%; transform: translate(-50%, -50%);"
                                    title="Posición actual del formulario ({{ $mapForm['pos_x'] }}%, {{ $mapForm['pos_y'] }}%)"
                                ></span>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">La página seleccionada no tiene imagen para previsualizar.</p>
                        @endif
                    </div>
                @endif

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($paginas as $pagina)
                        <div class="border rounded-lg p-3 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold">Página {{ $pagina->numero }}</p>
                                @if($pagina->imagen)
                                    <a href="{{ asset('storage/'.$pagina->imagen) }}" target="_blank" class="text-sm text-indigo-600">
                                        Ver imagen
                                    </a>
                                @endif
                            </div>

                            @if($pagina->productos->isEmpty())
                                <p class="text-sm text-gray-500">Sin productos asignados.</p>
                            @else
                                <div class="space-y-2 text-sm">
                                    @foreach($pagina->productos as $pivot)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                                            <div>
                                                <p class="font-medium">
                                                    {{ $pivot->producto?->nombre ?? 'Producto eliminado' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    X: {{ $pivot->pos_x }}% Â· Y: {{ $pivot->pos_y }}%
                                                </p>
                                            </div>
                                            <div class="flex gap-2">
                                                <x-button size="sm" wire:click="editMap({{ $pivot->id }})">
                                                    Editar
                                                </x-button>
                                                <x-button size="sm" color="danger" wire:click="deleteMap({{ $pivot->id }})">
                                                    Quitar
                                                </x-button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app>
