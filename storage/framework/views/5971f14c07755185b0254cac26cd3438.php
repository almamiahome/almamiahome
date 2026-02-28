<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Livewire\Volt\Component;
use Barryvdh\DomPDF\Facade\Pdf;

middleware('auth');
name('rotulos');

new class extends Component {

    public $mes;
    public $anio;
    public $rotulos = [];
    public $limiteBulto;

    public function mount(): void
    {
        $now = now();

        // Mes actual y año actual
        $this->mes  = $now->format('m');
        $this->anio = $now->format('Y');

        // Límite de bulto desde settings o 9 por defecto
        $this->limiteBulto = (float) (setting('almamia.cantidad.bulto') ?? 9);

        $this->loadRotulos();
    }

    public function updatedMes(): void
    {
        $this->loadRotulos();
    }

    public function updatedAnio(): void
    {
        $this->loadRotulos();
    }

    /**
     * Carga todos los pedidos filtrados por mes/año y genera los rótulos
     */
    public function loadRotulos(): void
    {
        $this->rotulos = [];

        $pedidos = Pedido::with(['vendedora', 'lider', 'articulos'])
            ->whereYear('fecha', $this->anio)
            ->whereMonth('fecha', $this->mes)
            ->orderBy('fecha')
            ->get();

        foreach ($pedidos as $pedido) {

            // Suma del bulto de todos los artículos del pedido
            $totalBulto = (float) $pedido->articulos->sum('bulto');

            // Si el pedido no tiene bulto, de todas formas generamos al menos 1 rótulo
            $cantidadRotulos = max(
                1,
                (int) ceil($totalBulto / $this->limiteBulto)
            );

            for ($i = 1; $i <= $cantidadRotulos; $i++) {
                $this->rotulos[] = [
                    'vendedora' => $pedido->vendedora?->name ?? '',
                    'lider'     => $pedido->lider?->name ?? '',
                    'numero'    => $i,
                ];
            }
        }
    }

    /**
     * Exporta los rótulos actuales a PDF
     */
    public function exportPdf()
    {
        $rotulos = $this->rotulos;
        $limiteBulto = $this->limiteBulto;
        $mes = $this->mes;
        $anio = $this->anio;
    
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'pages.rotulos.imprimir', // ← ahora apunta a tu nueva vista
            compact('rotulos', 'limiteBulto', 'mes', 'anio')
        )->setPaper('a4', 'portrait');
    
        return response()->streamDownload(
            fn () => print($pdf->output()),
            "rotulos_{$anio}_{$mes}.pdf"
        );
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicm90dWxvcyIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvcm90dWxvc1wvaW5kZXguYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-1658816158-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/rotulos/index.blade.php ENDPATH**/ ?>