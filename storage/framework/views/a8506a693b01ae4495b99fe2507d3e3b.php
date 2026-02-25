<?php
use function Laravel\Folio\{middleware, name};
use App\Http\Controllers\Crecimiento\CierreCampanaController;
use App\Models\CierreCampana;
use Livewire\Volt\Component;

name('crecimiento-cierre-general');

new class extends Component {
    public $cierres = [];
    public $selectedCierreId = null;
    public $totales = [];
    public $resumen = [];
    public $estadoMensaje = null;

    public $nuevo = [
        'nombre' => null,
        'codigo' => null,
        'fecha_inicio' => null,
        'fecha_cierre' => null,
        'estado' => 'configurada',
        'datos' => null,
    ];

    public function mount()
    {
        $this->loadCierres();
    }

    public function loadCierres()
    {
        $this->cierres = CierreCampana::orderByDesc('created_at')->get();

        if ($this->cierres->isNotEmpty()) {
            $this->selectedCierreId = $this->selectedCierreId ?? $this->cierres->first()->id;
            $this->refrescarResumen();
        }
    }

    public function refrescarResumen()
    {
        if (! $this->selectedCierreId) {
            return;
        }

        $cierre = CierreCampana::findOrFail($this->selectedCierreId);
        $controlador = app(CierreCampanaController::class);

        $this->totales = $controlador->totalesPorLider($cierre, auth()->user());
        $this->resumen = $controlador->planResumen($cierre, auth()->user());
    }

    public function registrarCierre()
    {
        $this->validate([
            'nuevo.nombre' => 'required|string|max:255',
            'nuevo.codigo' => 'required|string|max:50|unique:cierres_campana,codigo',
            'nuevo.fecha_inicio' => 'nullable|date',
            'nuevo.fecha_cierre' => 'nullable|date|after_or_equal:nuevo.fecha_inicio',
        ]);

        $controlador = app(CierreCampanaController::class);
        $cierre = $controlador->registrarCampana($this->nuevo, auth()->user());

        $this->estadoMensaje = 'Campaña registrada correctamente.';
        $this->nuevo = [
            'nombre' => null,
            'codigo' => null,
            'fecha_inicio' => null,
            'fecha_cierre' => null,
            'estado' => 'configurada',
            'datos' => null,
        ];

        $this->selectedCierreId = $cierre->id;
        $this->loadCierres();
    }

    public function cerrarCierre()
    {
        if (! $this->selectedCierreId) {
            return;
        }

        $controlador = app(CierreCampanaController::class);
        $cierre = CierreCampana::findOrFail($this->selectedCierreId);
        $controlador->cerrarCampana($cierre, auth()->user());

        $this->estadoMensaje = 'Cierre actualizado a estado "cerrada".';
        $this->loadCierres();
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiY3JlY2ltaWVudG8tY2llcnJlLWdlbmVyYWwiLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL2NyZWNpbWllbnRvLWNpZXJyZS1nZW5lcmFsXC9pbmRleC5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-3340314226-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/crecimiento-cierre-general/index.blade.php ENDPATH**/ ?>