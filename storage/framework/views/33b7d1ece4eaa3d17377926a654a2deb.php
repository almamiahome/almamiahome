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

<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiY2F0YWxvZ28uYWRtaW4iLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL2NhdGFsb2dvXC9hZG1pblwvaW5kZXguYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-2204567326-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/catalogo/admin/index.blade.php ENDPATH**/ ?>