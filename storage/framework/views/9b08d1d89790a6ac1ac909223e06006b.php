<?php

use function Laravel\Folio\{middleware, name};
use App\Models\RangoLider;
use App\Models\PremioRegla;
use App\Models\CierreCampana;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

middleware([
    'auth',
    function ($request, $next) {
        // Permiso base para acceder a la pantalla
        if (! $request->user() || ! $request->user()->can('crecimiento.ver_rangos')) {
            abort(403, 'No tiene permiso para ver los rangos.');
        }

        return $next($request);
    },
]);

name('rangos');

new class extends Component {
    public $rangos;
    public $cierreCampanas;
    public $premioReglas;

    public $rangoId = null;
    public $nombre;
    public $slug;
    public $posicion = 1;
    public $revendedoras_minimas = 0;
    public $revendedoras_maximas = 0;
    public $unidades_minimas = 0;
    public $premio_actividad = 0;
    public $premio_unidades = 0;
    public $premio_cobranzas = 0;
    public $reparto_referencia = 0;
    public $color = '#f0f5ff';
    public $descripcion = null;

    public $premioId = null;
    public $premio_tipo = 'actividad';
    public $premio_nombre;
    public $premio_descripcion;
    public $premio_umbral_minimo = 0;
    public $premio_umbral_maximo = null;
    public $premio_monto = 0;
    public $premio_cuotas = 1;
    public $premio_compra_orden = null;
    public $premio_campana_id = null;

    public function mount(): void
    {
        $this->rangos       = collect();
        $this->premioReglas = collect();

        $this->cierreCampanas = CierreCampana::orderByDesc('created_at')->get();
        $this->loadRangos();
    }

    public function updatedNombre(): void
    {
        if (! $this->slug) {
            $this->slug = Str::slug($this->nombre, '_');
        }
    }

    public function loadRangos(): void
    {
        $this->rangos = RangoLider::withCount('premioReglas')
            ->orderBy('posicion')
            ->get();

        if (! $this->rangoId && $this->rangos->isNotEmpty()) {
            $this->selectRango($this->rangos->first()->id);
        }
    }

    public function selectRango($rangoId): void
    {
        $this->rangoId = $rangoId;
        $rango         = RangoLider::findOrFail($rangoId);

        $this->nombre               = $rango->nombre;
        $this->slug                 = $rango->slug;
        $this->posicion             = $rango->posicion;
        $this->revendedoras_minimas = $rango->revendedoras_minimas;
        $this->revendedoras_maximas = $rango->revendedoras_maximas;
        $this->unidades_minimas     = $rango->unidades_minimas;
        $this->premio_actividad     = $rango->premio_actividad;
        $this->premio_unidades      = $rango->premio_unidades;
        $this->premio_cobranzas     = $rango->premio_cobranzas;
        $this->reparto_referencia   = $rango->reparto_referencia;
        $this->color                = $rango->color;
        $this->descripcion          = $rango->descripcion;

        $this->loadPremioReglas();
        $this->resetPremioForm();
    }

    public function startCrearRango(): void
    {
        $this->ensurePermission('crecimiento.crear_rangos');

        $this->reset([
            'rangoId',
            'nombre',
            'slug',
            'posicion',
            'revendedoras_minimas',
            'revendedoras_maximas',
            'unidades_minimas',
            'premio_actividad',
            'premio_unidades',
            'premio_cobranzas',
            'reparto_referencia',
            'color',
            'descripcion',
        ]);

        $this->posicion = ($this->rangos->max('posicion') ?? 0) + 1;
        $this->color    = '#f0f5ff';
    }

    public function saveRango(): void
    {
        $this->ensurePermission($this->rangoId ? 'crecimiento.editar_rangos' : 'crecimiento.crear_rangos');

        $validated = $this->validate([
            'nombre'                => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:rangos_lideres,slug,' . ($this->rangoId ?? 'NULL') . ',id',
            'posicion'              => 'required|integer|min:1',
            'revendedoras_minimas'  => 'required|integer|min:0',
            'revendedoras_maximas'  => 'required|integer|min:0',
            'unidades_minimas'      => 'required|integer|min:0',
            'premio_actividad'      => 'required|numeric|min:0',
            'premio_unidades'       => 'required|numeric|min:0',
            'premio_cobranzas'      => 'required|numeric|min:0',
            'reparto_referencia'    => 'required|numeric|min:0',
            'color'                 => 'nullable|string|max:20',
            'descripcion'           => 'nullable|string',
        ]);

        $rango = RangoLider::updateOrCreate(
            ['id' => $this->rangoId],
            $validated
        );

        $this->rangoId = $rango->id;
        $this->loadRangos();

        session()->flash('message', 'Rango guardado correctamente.');
    }

    public function deleteRango($rangoId): void
    {
        $this->ensurePermission('crecimiento.eliminar_rangos');

        $rango = RangoLider::findOrFail($rangoId);
        $rango->delete();

        $this->rangoId      = null;
        $this->premioReglas = collect();

        $this->loadRangos();
    }

    public function loadPremioReglas(): void
    {
        if (! $this->rangoId) {
            $this->premioReglas = collect();
            return;
        }

        $this->premioReglas = PremioRegla::where('rango_lider_id', $this->rangoId)
            ->orderBy('tipo')
            ->orderBy('umbral_minimo')
            ->get();
    }

    public function editPremio($premioId): void
    {
        $this->ensurePermission('crecimiento.configurar_premios_liderazgo');

        $premio                     = PremioRegla::findOrFail($premioId);
        $this->premioId             = $premio->id;
        $this->premio_tipo          = $premio->tipo;
        $this->premio_nombre        = data_get($premio->datos, 'nombre');
        $this->premio_descripcion   = data_get($premio->datos, 'descripcion');
        $this->premio_umbral_minimo = $premio->umbral_minimo;
        $this->premio_umbral_maximo = $premio->umbral_maximo;
        $this->premio_monto         = $premio->monto;
        $this->premio_cuotas        = data_get($premio->datos, 'cuotas', 1);
        $this->premio_compra_orden  = data_get($premio->datos, 'compra_orden');
        $this->premio_campana_id    = $premio->campana_id;
    }

    public function resetPremioForm(): void
    {
        $this->premioId             = null;
        $this->premio_tipo          = 'actividad';
        $this->premio_nombre        = null;
        $this->premio_descripcion   = null;
        $this->premio_umbral_minimo = 0;
        $this->premio_umbral_maximo = null;
        $this->premio_monto         = 0;
        $this->premio_cuotas        = 1;
        $this->premio_compra_orden  = null;
        $this->premio_campana_id    = null;
    }

    public function savePremio(): void
    {
        $this->ensurePermission('crecimiento.configurar_premios_liderazgo');

        $validated = $this->validate([
            'rangoId'              => 'required|exists:rangos_lideres,id',
            'premio_tipo'          => 'required|string|max:100',
            'premio_nombre'        => 'nullable|string|max:255',
            'premio_descripcion'   => 'nullable|string',
            'premio_umbral_minimo' => 'nullable|integer|min:0',
            'premio_umbral_maximo' => 'nullable|integer|min:0',
            'premio_monto'         => 'nullable|numeric|min:0',
            'premio_cuotas'        => 'nullable|integer|min:1',
            'premio_compra_orden'  => 'nullable|integer|min:1',
            'premio_campana_id'    => 'nullable|exists:cierres_campana,id',
        ], [], [
            'rangoId' => 'rango',
        ]);

        $datos = array_filter([
            'nombre'       => $this->premio_nombre,
            'descripcion'  => $this->premio_descripcion,
            'cuotas'       => $this->premio_cuotas,
            'compra_orden' => $this->premio_compra_orden,
        ], fn ($valor) => ! is_null($valor) && $valor !== '');

        PremioRegla::updateOrCreate(
            ['id' => $this->premioId],
            [
                'rango_lider_id' => $this->rangoId,
                'campana_id'     => $validated['premio_campana_id'],
                'tipo'           => $validated['premio_tipo'],
                'umbral_minimo'  => $validated['premio_umbral_minimo'],
                'umbral_maximo'  => $validated['premio_umbral_maximo'],
                'monto'          => $validated['premio_monto'],
                'datos'          => $datos,
            ]
        );

        $this->loadPremioReglas();
        $this->resetPremioForm();

        session()->flash('message', 'Regla de premio guardada correctamente.');
    }

    public function deletePremio($premioId): void
    {
        $this->ensurePermission('crecimiento.configurar_premios_liderazgo');

        $premio = PremioRegla::findOrFail($premioId);
        $premio->delete();

        $this->loadPremioReglas();
        $this->resetPremioForm();
    }

    protected function ensurePermission(string $permission): void
    {
        $user = auth()->user();

        if (! $user || ! $user->can($permission)) {
            abort(403, 'No tiene permiso para realizar esta acción.');
        }
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicmFuZ29zIiwicGF0aCI6InJlc291cmNlc1wvdGhlbWVzXC9hbmNob3JcL3BhZ2VzXC9yYW5nb3NcL2luZGV4LmJsYWRlLnBocCJ9", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-1860668182-0', $__slots ?? [], get_defined_vars());

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
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/rangos/index.blade.php ENDPATH**/ ?>