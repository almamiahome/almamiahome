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
        'imagenes'    => [],
    ];

    public $paginaEditingId = null;

    public $mapForm = [
        'catalogo_pagina_id' => null,
        'producto_id'        => null,
        'producto_ids'       => [],
        'es_grupo'           => false,
        'pos_x'              => 50,
        'pos_y'              => 50,
    ];

    public $mapEditingId = null;
    public $mapPositionSaved = false;

    public function mount(): void
    {
        if (! auth()->user()?->hasRole('admin')) {
            redirect()->to('/catalogo')->send();
        }

        $this->productos = Producto::with('categorias')->orderBy('nombre')->get();
        $this->loadCatalogos();
    }

    public function loadCatalogos(): void
    {
        $this->catalogos = Catalogo::with(['paginas.productos.producto', 'paginas.productos.productosGrupo'])
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
        $catalogo = Catalogo::with(['paginas.productos.producto', 'paginas.productos.productosGrupo'])
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
        $this->paginaForm['imagenes']   = [];
    }

    public function savePagina(): void
    {
        $rules = [
            'paginaForm.catalogo_id' => 'required|exists:catalogos,id',
            'paginaForm.numero'      => 'required|integer|min:1',
            'paginaForm.imagen'      => 'nullable|image|max:8192',
            'paginaForm.imagenes'    => 'nullable|array',
            'paginaForm.imagenes.*'  => 'image|max:8192',
        ];

        $this->validate($rules);

        $imagenesMultiples = collect($this->paginaForm['imagenes'] ?? [])->filter();

        if ($imagenesMultiples->isNotEmpty() && ! $this->paginaEditingId) {
            $numeroInicial = (int) $this->paginaForm['numero'];

            $imagenesMultiples->values()->each(function ($imagen, int $indice) use ($numeroInicial) {
                CatalogoPagina::create([
                    'catalogo_id' => $this->paginaForm['catalogo_id'],
                    'numero' => $numeroInicial + $indice,
                    'imagen' => $imagen->store('catalogo/paginas', 'public'),
                ]);
            });

            $this->resetPaginaForm();
            $this->refreshPaginas();
            $this->loadCatalogos();

            return;
        }

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
        $pivot                           = CatalogoPaginaProducto::with('productosGrupo')->findOrFail($mapId);
        $this->mapEditingId              = $pivot->id;
        $this->mapForm['catalogo_pagina_id'] = $pivot->catalogo_pagina_id;
        $this->mapForm['es_grupo']       = (bool) $pivot->es_grupo;
        $this->mapForm['producto_id']    = $pivot->producto_id;
        $this->mapForm['producto_ids']   = $pivot->productosGrupo->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        $this->mapForm['pos_x']          = $pivot->pos_x;
        $this->mapForm['pos_y']          = $pivot->pos_y;
        $this->mapPositionSaved          = false;
    }


    public function updatedMapFormEsGrupo($value): void
    {
        $esGrupo = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        if ($esGrupo) {
            $this->mapForm['producto_id'] = null;
        } else {
            $this->mapForm['producto_ids'] = [];
        }
    }

    public function saveMap(): void
    {
        $this->persistMap(true);
    }

    public function saveDraggedMap(): void
    {
        $this->persistMap(false);
        $this->mapPositionSaved = true;
    }

    protected function persistMap(bool $resetAfterSave = true): void
    {
        $esGrupo = (bool) ($this->mapForm['es_grupo'] ?? false);

        $rules = [
            'mapForm.catalogo_pagina_id' => 'required|exists:catalogo_paginas,id',
            'mapForm.pos_x'              => 'required|numeric|min:0|max:100',
            'mapForm.pos_y'              => 'required|numeric|min:0|max:100',
        ];

        if ($esGrupo) {
            $rules['mapForm.producto_ids'] = 'required|array|min:1';
            $rules['mapForm.producto_ids.*'] = 'required|exists:productos,id';
        } else {
            $rules['mapForm.producto_id'] = 'required|exists:productos,id';
        }

        $this->validate($rules);

        $pivot = $this->mapEditingId
            ? CatalogoPaginaProducto::with('productosGrupo')->findOrFail($this->mapEditingId)
            : new CatalogoPaginaProducto();

        $pivot->catalogo_pagina_id = $this->mapForm['catalogo_pagina_id'];
        $pivot->es_grupo           = $esGrupo;
        $pivot->producto_id        = $esGrupo ? null : $this->mapForm['producto_id'];
        $pivot->pos_x              = $this->mapForm['pos_x'];
        $pivot->pos_y              = $this->mapForm['pos_y'];
        $pivot->save();

        if ($esGrupo) {
            $pivot->productosGrupo()->sync($this->mapForm['producto_ids']);
        } else {
            $pivot->productosGrupo()->sync([]);
        }

        if ($resetAfterSave) {
            $this->resetMapForm();
        } else {
            $this->mapEditingId = $pivot->id;
        }

        $this->refreshPaginas();
    }

    public function deleteMap(int $mapId): void
    {
        $pivot = CatalogoPaginaProducto::findOrFail($mapId);
        $pivot->delete();
        $this->mapPositionSaved = false;
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
        $this->paginaForm['imagenes']      = [];
        $this->paginaForm['catalogo_id']   = $this->selectedCatalogoId;
    }

    public function resetMapForm(): void
    {
        $this->mapEditingId = null;
        $this->mapForm      = [
            'catalogo_pagina_id' => $this->paginas->first()->id ?? null,
            'producto_id'        => null,
            'producto_ids'       => [],
            'es_grupo'           => false,
            'pos_x'              => 50,
            'pos_y'              => 50,
        ];
        $this->mapPositionSaved = false;
    }
};

?>

<x-layouts.app>
    @volt('catalogo.admin')
    <x-app.container class="relative space-y-10 py-8">
        
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] left-[15%] w-[40%] h-[40%] rounded-full bg-pink-500/10 blur-[120px]"></div>
            <div class="absolute top-[20%] right-[5%] w-[30%] h-[30%] rounded-full bg-blue-500/10 blur-[100px]"></div>
        </div>

        <div class="px-2">
            <h1 class="text-4xl font-black tracking-tighter text-slate-900">
                Editor de Catalogos <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600">Catálogo</span>
            </h1>
            <p class="mt-2 text-slate-500 font-medium max-w-2xl">
                Gestiona la experiencia visual. Vincula productos a tus páginas de forma facil.
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-1 gap-8">
            
            <div class="xl:col-span-4 space-y-6">
                <section class="bg-white/60 backdrop-blur-2xl border border-white/80 rounded-[2.5rem] shadow-xl p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-pink-500 flex items-center justify-center text-white shadow-lg shadow-pink-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Catálogos</h3>
                    </div>

                    <form wire:submit.prevent="saveCatalogo" class="space-y-5">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nombre</label>
                            <input type="text" wire:model="catalogoForm.nombre" class="w-full h-12 px-4 bg-white border-slate-200 rounded-2xl font-bold focus:border-pink-500 transition-all shadow-inner" placeholder="Ej: Nueva Colección">
                            @error('catalogoForm.nombre') <span class="text-xs font-bold text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Descripción</label>
                            <textarea wire:model="catalogoForm.descripcion" rows="2" class="w-full p-4 bg-white border-slate-200 rounded-2xl font-bold focus:border-pink-500 transition-all shadow-inner"></textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Portada</label>
                            <input type="file" wire:model="catalogoForm.imagen_portada" class="w-full text-xs">
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 h-12 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:shadow-pink-200 transition-all">
                                @if($this->catalogoEditingId) Actualizar @else Crear Catálogo @endif
                            </button>
                            @if($this->catalogoEditingId)
                                <button type="button" wire:click="resetCatalogoForm" class="px-4 h-12 bg-slate-100 text-slate-500 rounded-2xl font-bold text-xs">✕</button>
                            @endif
                        </div>
                    </form>

                    <div class="mt-8 space-y-3">
                        @foreach($catalogos as $catalogo)
                            <div class="group p-4 bg-white/40 border border-white rounded-3xl hover:bg-white transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="cursor-pointer" wire:click="selectCatalogo({{ $catalogo->id }})">
                                        <p class="font-black text-slate-800">{{ $catalogo->nombre }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $catalogo->paginas->count() }} Páginas</p>
                                    </div>
                                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="editCatalogo({{ $catalogo->id }})" class="p-2 text-slate-400 hover:text-indigo-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                        <button wire:click="deleteCatalogo({{ $catalogo->id }})" class="p-2 text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="xl:col-span-8 space-y-8">
                
                <section class="bg-white/60 backdrop-blur-2xl border border-white/80 rounded-[2.5rem] shadow-xl p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-violet-500 flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Galería de Páginas</h3>
                        </div>

                        <form wire:submit.prevent="savePagina" class="flex flex-wrap items-end gap-3">
                            <div class="w-20">
                                <input type="number" wire:model="paginaForm.numero" class="w-full h-10 bg-white border-slate-200 rounded-xl font-bold text-sm" placeholder="Nº">
                            </div>
                            <div class="w-40">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Imagen de página</label>
                                <input type="file" wire:model="paginaForm.imagen" class="w-full text-[10px]">
                            </div>
                            @if(!$this->paginaEditingId)
                                <div class="w-52">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Carga múltiple</label>
                                    <input type="file" multiple wire:model="paginaForm.imagenes" class="w-full text-[10px]">
                                </div>
                            @endif
                            <button type="submit" class="h-10 px-6 bg-violet-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest">
                                @if($this->paginaEditingId) Actualizar @else Añadir @endif
                            </button>
                            @if(!$this->paginaEditingId)
                                <p class="text-[10px] font-bold text-slate-400">Si seleccionás varias imágenes, se crean páginas consecutivas desde el número indicado.</p>
                            @endif
                        </form>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-h-[300px] overflow-y-auto pr-2">
                        @forelse($paginas as $pagina)
                            <div class="group relative aspect-[3/4] rounded-2xl overflow-hidden border-2 border-white shadow-sm cursor-pointer hover:scale-[1.02] transition-transform" 
                                 wire:click="$set('mapForm.catalogo_pagina_id', {{ $pagina->id }})">
                                @if($pagina->imagen)
                                    <img src="{{ asset('storage/'.$pagina->imagen) }}" class="w-full h-full object-cover">
                                @endif
                                <div class="absolute bottom-2 left-2 bg-black/50 backdrop-blur-md px-2 py-1 rounded-lg text-white text-[10px] font-black">
                                    Pág {{ $pagina->numero }}
                                </div>
                                <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" wire:click.stop="editPagina({{ $pagina->id }})" class="p-1.5 rounded-lg bg-white/90 text-indigo-600 shadow">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button type="button" wire:click.stop="deletePagina({{ $pagina->id }})" class="p-1.5 rounded-lg bg-white/90 text-red-500 shadow">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-8 text-center text-slate-400 font-bold border-2 border-dashed border-slate-100 rounded-3xl">
                                Sube la primera página para comenzar.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="bg-white/60 backdrop-blur-2xl border border-white/80 rounded-[2.5rem] shadow-xl p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Mapeo de Productos</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-slate-100 min-h-[400px] flex items-center justify-center"
                             x-data="mapaPuntosInteractivo({
                                posX: $wire.entangle('mapForm.pos_x').live,
                                posY: $wire.entangle('mapForm.pos_y').live,
                                mapaId: $wire.entangle('mapEditingId').live
                             })">
                            @php 
                                $paginaActiva = $paginas->firstWhere('id', (int)($mapForm['catalogo_pagina_id'] ?? 0));
                            @endphp

                            @if($paginaActiva && $paginaActiva->imagen)
                                <div class="relative w-full" x-ref="mapa">
                                    <img src="{{ asset('storage/'.$paginaActiva->imagen) }}" class="w-full pointer-events-none select-none">
                                    
                                    @foreach($paginaActiva->productos as $p)
                                        <div class="absolute h-4 w-4 rounded-full border-2 border-white shadow-lg cursor-move"
                                             :class="Number(mapaId) === {{ $p->id }} ? 'bg-emerald-500 ring-4 ring-emerald-200' : 'bg-indigo-600'"
                                             @pointerdown.prevent="iniciarArrastreExistente($event, {{ $p->id }})"
                                             style="transform: translate(-50%, -50%);"
                                             :style="Number(mapaId) === {{ $p->id }}
                                                ? `left: ${normalizar(posX)}%; top: ${normalizar(posY)}%; transform: translate(-50%, -50%);`
                                                : 'left: {{ $p->pos_x }}%; top: {{ $p->pos_y }}%; transform: translate(-50%, -50%);'">
                                        </div>
                                    @endforeach

                                    <div class="absolute h-6 w-6 bg-emerald-500 rounded-full border-4 border-white shadow-xl animate-pulse cursor-move"
                                         @pointerdown.prevent="iniciarArrastreNuevo($event)"
                                         style="transform: translate(-50%, -50%);"
                                         :style="`left: ${normalizar(posX)}%; top: ${normalizar(posY)}%; transform: translate(-50%, -50%);`">
                                    </div>
                                </div>
                            @else
                                <p class="text-slate-400 font-bold p-8 text-center uppercase text-[10px] tracking-widest">Selecciona una página de la galería</p>
                            @endif
                        </div>

                        <div class="space-y-6">
                            <form wire:submit.prevent="saveMap" class="space-y-4">
                                <div class="space-y-3" x-data="{ modalGrupoAbierto: false }">
                                    <label class="inline-flex items-center gap-3 text-xs font-black text-slate-700">
                                        <input type="checkbox" wire:model.live="mapForm.es_grupo" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                        Grupo de productos
                                    </label>

                                    @if($mapForm['es_grupo'])
                                        <button type="button" @click="modalGrupoAbierto = true" class="w-full h-12 px-4 bg-white border border-slate-200 rounded-2xl font-bold text-left text-slate-700">
                                            Seleccionar productos del grupo
                                        </button>

                                        <p class="text-[11px] font-semibold text-slate-500">
                                            Seleccionados: {{ count($mapForm['producto_ids'] ?? []) }}
                                        </p>

                                        <div x-show="modalGrupoAbierto" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" @click.self="modalGrupoAbierto = false">
                                            <div class="w-full max-w-xl rounded-3xl bg-white p-6 shadow-2xl space-y-4">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Seleccionar productos</h4>
                                                    <button type="button" @click="modalGrupoAbierto = false" class="text-slate-500 font-bold">Cerrar</button>
                                                </div>

                                                <div class="max-h-80 overflow-y-auto space-y-2 pr-2">
                                                    @foreach($productos as $prod)
                                                        <label class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50">
                                                            <input type="checkbox" value="{{ $prod->id }}" wire:model="mapForm.producto_ids" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                                            <span class="text-xs font-bold text-slate-700">
                                                                {{ $prod->nombre }} - {{ $prod->categorias->first()?->nombre ?? 'Sin categoría' }} (SKU: {{ $prod->sku ?? 'N/D' }})
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $productosBusqueda = $productos->map(fn ($prod) => [
                                                'id' => (string) $prod->id,
                                                'nombre' => $prod->nombre,
                                                'sku' => $prod->sku,
                                                'categoria' => $prod->categorias->first()?->nombre,
                                                'etiqueta' => $prod->nombre.' - '.($prod->categorias->first()?->nombre ?? 'Sin categoría').' (SKU: '.($prod->sku ?? 'N/D').')',
                                            ])->values();
                                        @endphp

                                        <div
                                            class="space-y-1"
                                            x-data="{
                                                abierto: false,
                                                query: '',
                                                seleccionado: @entangle('mapForm.producto_id').live,
                                                productos: @js($productosBusqueda),
                                                get filtrados() {
                                                    const termino = this.query.trim().toLowerCase();

                                                    if (!termino) {
                                                        return this.productos.slice(0, 30);
                                                    }

                                                    return this.productos.filter((producto) => {
                                                        return (producto.nombre ?? '').toLowerCase().includes(termino)
                                                            || (producto.sku ?? '').toLowerCase().includes(termino)
                                                            || (producto.categoria ?? '').toLowerCase().includes(termino);
                                                    }).slice(0, 30);
                                                },
                                                get etiquetaSeleccionada() {
                                                    const encontrado = this.productos.find((producto) => String(producto.id) === String(this.seleccionado));
                                                    return encontrado ? encontrado.etiqueta : '';
                                                },
                                                seleccionar(producto) {
                                                    this.seleccionado = producto.id;
                                                    this.query = producto.etiqueta;
                                                    this.abierto = false;
                                                },
                                            }"
                                            x-init="query = etiquetaSeleccionada"
                                            x-effect="if (!abierto) { query = etiquetaSeleccionada }"
                                            @click.outside="abierto = false"
                                        >
                                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Producto</label>
                                            <input
                                                type="text"
                                                x-model="query"
                                                @focus="abierto = true"
                                                @input="abierto = true"
                                                placeholder="Buscar por nombre, SKU o categoría"
                                                class="w-full h-12 px-4 bg-white border-slate-200 rounded-2xl font-bold"
                                            >
                                            <input type="hidden" wire:model.live="mapForm.producto_id" :value="seleccionado">

                                            <div x-show="abierto" x-cloak class="max-h-56 overflow-y-auto rounded-2xl border border-slate-200 bg-white shadow-lg">
                                                <template x-for="producto in filtrados" :key="producto.id">
                                                    <button
                                                        type="button"
                                                        class="w-full px-4 py-3 text-left text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                                        @click="seleccionar(producto)"
                                                        x-text="producto.etiqueta"
                                                    ></button>
                                                </template>

                                                <p x-show="filtrados.length === 0" class="px-4 py-3 text-xs font-semibold text-slate-400">
                                                    No se encontraron productos para la búsqueda.
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black uppercase text-slate-400 text-center block">X (%)</label>
                                        <input type="number" step="0.1" wire:model="mapForm.pos_x" class="w-full h-12 text-center bg-white border-slate-200 rounded-2xl font-black text-emerald-600">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black uppercase text-slate-400 text-center block">Y (%)</label>
                                        <input type="number" step="0.1" wire:model="mapForm.pos_y" class="w-full h-12 text-center bg-white border-slate-200 rounded-2xl font-black text-emerald-600">
                                    </div>
                                </div>

                                <button type="button" wire:click="saveDraggedMap" class="w-full h-11 bg-emerald-50 text-emerald-700 rounded-2xl font-black text-[10px] uppercase tracking-widest border border-emerald-200">
                                    Guardar nueva posición
                                </button>

                                @if($mapPositionSaved)
                                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 text-center">Posición guardada correctamente.</p>
                                @endif

                                <button type="submit" class="w-full h-14 bg-emerald-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-100">
                                    @if($this->mapEditingId) Actualizar Hotspot @else Crear Hotspot @endif
                                </button>
                            </form>

                            @if($paginaActiva)
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black uppercase text-slate-400">Vínculos en esta página:</p>
                                    @foreach($paginaActiva->productos as $p)
                                        <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                            <span class="text-xs font-black text-slate-700">{{ $p->es_grupo ? 'Grupo: '. $p->productosGrupo->pluck('nombre')->implode(', ') : $p->producto?->nombre }}</span>
                                            <div class="flex gap-1">
                                                <button wire:click="editMap({{ $p->id }})" class="p-1 text-indigo-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                                <button wire:click="deleteMap({{ $p->id }})" class="p-1 text-red-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mapaPuntosInteractivo', ({ posX, posY, mapaId }) => ({
            posX,
            posY,
            mapaId,
            arrastrando: false,
            normalizar(valor) {
                const numero = Number(valor ?? 0);
                return Math.min(100, Math.max(0, Number.isFinite(numero) ? numero : 0)).toFixed(1);
            },
            iniciarArrastreExistente(evento, idMapa) {
                this.mapaId = idMapa;
                this.$wire.editMap(idMapa);
                this.iniciarArrastre(evento);
            },
            iniciarArrastreNuevo(evento) {
                this.iniciarArrastre(evento);
            },
            iniciarArrastre(evento) {
                this.arrastrando = true;
                this.actualizarPosicion(evento);

                const mover = (ev) => this.actualizarPosicion(ev);
                const finalizar = () => {
                    this.arrastrando = false;
                    window.removeEventListener('pointermove', mover);
                    window.removeEventListener('pointerup', finalizar);
                };

                window.addEventListener('pointermove', mover);
                window.addEventListener('pointerup', finalizar, { once: true });
            },
            actualizarPosicion(evento) {
                const mapa = this.$refs.mapa;

                if (!mapa) {
                    return;
                }

                const rect = mapa.getBoundingClientRect();
                if (!rect.width || !rect.height) {
                    return;
                }

                const x = ((evento.clientX - rect.left) / rect.width) * 100;
                const y = ((evento.clientY - rect.top) / rect.height) * 100;

                this.posX = this.normalizar(x);
                this.posY = this.normalizar(y);
            },
        }));
    });
</script>
