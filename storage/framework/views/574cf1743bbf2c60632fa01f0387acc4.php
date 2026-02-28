<?php

use function Laravel\Folio\{middleware, name};
use App\Services\PedidoCartService;
use Livewire\Volt\Component;

middleware('auth');
name('crearpedido');

// Servicio compartido con /catalogo para centralizar cálculos y validaciones del carrito.
new class extends Component {

    public $productos = [];
    public $categorias = [];
    public $vendedoras = [];
    public $lideres = [];
    public $responsable = [];
    public $paginas_catalogo = [];
    public $gastos_administrativos = [];
    public $codigo_pedido;
    public $vendedoraSeleccionadaId = null;
    public $liderSeleccionadoId = null;
    public $esVendedoraAutenticada = false;
    public $esLiderAutenticado = false;

    public function mount(PedidoCartService $pedidoCartService): void
    {
        $usuario = auth()->user();

        $this->codigo_pedido = $pedidoCartService->generarCodigoPedido();
        $this->categorias = $pedidoCartService->obtenerCategorias();
        $this->productos = $pedidoCartService->obtenerProductosConReglas();
        $this->gastos_administrativos = $pedidoCartService->obtenerGastosAdministrativos();

        $contexto = $pedidoCartService->obtenerContextoUsuarios($usuario);

        $this->vendedoras = $contexto['vendedoras'];
        $this->lideres = $contexto['lideres'];
        $this->vendedoraSeleccionadaId = $contexto['vendedoraSeleccionadaId'];
        $this->liderSeleccionadoId = $contexto['liderSeleccionadoId'];
        $this->responsable = $contexto['responsable'];
        $this->esLiderAutenticado = $contexto['esLiderAutenticado'];
        $this->esVendedoraAutenticada = $contexto['esVendedoraAutenticada'];
    }

    public function storePedido($cart, $vendedora_id, $lider_id, $gastosSeleccionados, $observaciones, PedidoCartService $pedidoCartService)
    {
        $resultado = $pedidoCartService->storePedido([
            'cart'          => $cart,
            'gastos'        => $gastosSeleccionados,
            'observaciones' => $observaciones,
            'vendedora_id'  => $vendedora_id,
            'lider_id'      => $lider_id,
        ], $this->codigo_pedido, auth()->user());

        if (isset($resultado['success'])) {
            $this->codigo_pedido = $resultado['codigo_pedido'];
        }

        return $resultado;
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiY3JlYXJwZWRpZG8iLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL2NyZWFycGVkaWRvXC9pbmRleC5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-2931425225-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/crearpedido/index.blade.php ENDPATH**/ ?>