<?php

use App\Models\Cobro;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('cobros');

new class extends Component {
    public $cobros = [];
    public $pedidos = [];
    public $usuarios = [];

    public $form = [
        'pedido_id' => '',
        'lider_id' => '',
        'coordinadora_id' => '',
        'mes_campana' => '',
        'monto' => '',
        'estado' => 'pendiente',
        'concepto' => '',
        'fecha_pago' => '',
    ];

    public function mount(): void
    {
        $this->loadCobros();
        $this->pedidos = Pedido::orderByDesc('fecha')->get(['id', 'codigo_pedido']);
        $this->usuarios = User::orderBy('name')->get(['id', 'name']);
    }

    public function loadCobros(): void
    {
        $this->cobros = Cobro::with(['pedido', 'lider', 'coordinadora'])
            ->latest()
            ->get();
    }

    public function saveCobro(): void
    {
        $validated = $this->validate([
            'form.pedido_id' => 'nullable|exists:pedidos,id',
            'form.lider_id' => 'nullable|exists:users,id',
            'form.coordinadora_id' => 'nullable|exists:users,id',
            'form.mes_campana' => 'required|date_format:Y-m',
            'form.monto' => 'required|numeric|min:0',
            'form.estado' => 'required|string|max:50',
            'form.concepto' => 'nullable|string|max:255',
            'form.fecha_pago' => 'nullable|date',
        ])['form'];

        $validated['mes_pago_programado'] = Cobro::calcularMesPago($validated['mes_campana']);

        Cobro::create($validated);

        session()->flash('message', 'Cobro registrado correctamente.');
        $this->reset('form');
        $this->form['estado'] = 'pendiente';
        $this->loadCobros();
    }

    public function marcarCobrado(Cobro $cobro): void
    {
        $cobro->update([
            'estado' => 'cobrado',
            'fecha_pago' => Carbon::now(),
        ]);

        $this->loadCobros();
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiY29icm9zIiwicGF0aCI6InJlc291cmNlc1wvdGhlbWVzXC9hbmNob3JcL3BhZ2VzXC9jb2Jyb3NcL2luZGV4LmJsYWRlLnBocCJ9", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-4245234300-1', $__slots ?? [], get_defined_vars());

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
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/cobros/index.blade.php ENDPATH**/ ?>