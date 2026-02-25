<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Livewire\Volt\Component;

middleware('auth');
name('mis-pedidos');

new class extends Component {
    public $pedidos = [];
    public $editing = false;
    public $pedido_id;

    public $estado;
    public $codigo_pedido;
    public $observaciones;

    public function mount()
    {
        $this->loadPedidos();
    }

    public function loadPedidos()
    {
        $userId = auth()->id();

        $this->pedidos = Pedido::with(['vendedora', 'lider', 'responsable'])
            ->when($userId, function ($query, $userId) {
                $query->where(function ($innerQuery) use ($userId) {
                    $innerQuery->where('responsable_id', $userId)
                        ->orWhere('vendedora_id', $userId)
                        ->orWhere('lider_id', $userId)
                        ->orWhere('coordinadora_id', $userId);
                });
            })
            ->latest()
            ->get();
    }

    public function deletePedido(Pedido $pedido)
    {
        $pedido->delete();
        $this->loadPedidos();
    }

    public function editPedido($id)
    {
        $pedido = Pedido::findOrFail($id);
        $this->pedido_id = $pedido->id;
        $this->codigo_pedido = $pedido->codigo_pedido;
        $this->estado = $pedido->estado;
        $this->observaciones = $pedido->observaciones;
        $this->editing = true;
    }

    public function savePedido()
    {
        $this->validate([
            'estado' => 'required',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $pedido = Pedido::findOrFail($this->pedido_id);
        $pedido->update([
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
        ]);

        session()->flash('message', 'Pedido actualizado correctamente.');
        $this->editing = false;
        $this->loadPedidos();
    }

    public function closeModal()
    {
        $this->editing = false;
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoibWlzLXBlZGlkb3MiLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL21pcy1wZWRpZG9zXC9pbmRleC5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-495125762-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/mis-pedidos/index.blade.php ENDPATH**/ ?>