<?php

use App\Models\Pago;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('pagos');

new class extends Component {
    public $pagos = [];
    public $pedidos = [];
    public $vendedoras = [];

    public $form = [
        'pedido_id' => '',
        'vendedora_id' => '',
        'mes_campana' => '',
        'monto' => '',
        'estado' => 'pendiente',
        'fecha_pago' => '',
        'detalle' => '',
    ];

    public function mount(): void
    {
        $this->loadPagos();
        $this->pedidos = Pedido::orderByDesc('fecha')->get(['id', 'codigo_pedido', 'vendedora_id']);
        $this->vendedoras = User::orderBy('name')->get(['id', 'name']);
    }

    public function loadPagos(): void
    {
        $this->pagos = Pago::with(['pedido', 'vendedora'])
            ->latest()
            ->get();
    }

    public function savePago(): void
    {
        $validated = $this->validate([
            'form.pedido_id' => 'required|exists:pedidos,id',
            'form.vendedora_id' => 'required|exists:users,id',
            'form.mes_campana' => 'required|date_format:Y-m',
            'form.monto' => 'required|numeric|min:0',
            'form.estado' => 'required|string|max:50',
            'form.fecha_pago' => 'nullable|date',
            'form.detalle' => 'nullable|string|max:500',
        ])['form'];

        $validated['mes_pago_programado'] = Pago::calcularMesPago($validated['mes_campana']);

        Pago::create($validated);

        session()->flash('message', 'Pago registrado correctamente.');
        $this->reset('form');
        $this->form['estado'] = 'pendiente';
        $this->loadPagos();
    }

    public function marcarPagado(Pago $pago): void
    {
        $pago->update([
            'estado' => 'pagado',
            'fecha_pago' => Carbon::now(),
        ]);

        $this->loadPagos();
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicGFnb3MiLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL3BhZ29zXC9pbmRleC5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-608955971-0', $__slots ?? [], get_defined_vars());

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
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/pagos/index.blade.php ENDPATH**/ ?>