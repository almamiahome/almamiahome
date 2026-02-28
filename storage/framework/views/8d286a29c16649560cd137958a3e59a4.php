<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Volt\Component;

middleware('auth');
name('zona-coordinadora');

new class extends Component {
    public string $startDate;

    public string $endDate;

    public string $estado = '';

    public array $resumen = [];

    public Collection $lideres;

    public Collection $estados;

    public array $estadosDisponibles = [];

    public bool $hasCoordinadoraColumn = false;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->hasCoordinadoraColumn = Schema::hasColumn('pedidos', 'coordinadora_id');
        $this->estadosDisponibles = Pedido::query()
            ->whereNotNull('estado')
            ->distinct()
            ->pluck('estado')
            ->filter()
            ->values()
            ->all();

        $this->lideres = collect();
        $this->estados = collect();
        $this->loadData();
    }

    public function updated($property): void
    {
        if (in_array($property, ['startDate', 'endDate', 'estado'], true)) {
            $this->loadData();
        }
    }

    protected function filteredPedidos(): Collection
    {
        $query = Pedido::query()
            ->with(['vendedora', 'lider'])
            ->whereNotNull('vendedora_id')
            ->whereNotNull('lider_id')
            ->when($this->hasCoordinadoraColumn, fn ($q) => $q->where('coordinadora_id', Auth::id()))
            ->when(! $this->hasCoordinadoraColumn, fn ($q) => $q->whereNotNull('responsable_id'));

        if ($this->startDate) {
            $query->whereDate('fecha', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('fecha', '<=', $this->endDate);
        }

        if ($this->estado !== '') {
            $query->where('estado', $this->estado);
        }

        return $query->get();
    }

    protected function loadData(): void
    {
        $pedidos = $this->filteredPedidos();

        $this->resumen = [
            'pedidos' => $pedidos->count(),
            'unidades' => $pedidos->sum('cantidad_unidades'),
            'monto' => $pedidos->sum('total_a_pagar'),
        ];

        $this->estados = $pedidos
            ->groupBy('estado')
            ->map(fn ($items, $estado) => [
                'estado' => $estado ?? 'Sin estado',
                'pedidos' => $items->count(),
                'unidades' => $items->sum('cantidad_unidades'),
            ])
            ->values()
            ->sortByDesc('pedidos');

        $this->lideres = $pedidos
            ->groupBy('lider_id')
            ->map(function ($items) {
                $estadoFrecuente = $items
                    ->groupBy('estado')
                    ->sortByDesc(fn ($group) => $group->count())
                    ->keys()
                    ->first();

                $vendedoras = $items->groupBy('vendedora_id')->map(fn ($g) => $g->first()?->vendedora?->name ?? 'Sin nombre');

                return [
                    'nombre' => $items->first()?->lider?->name ?? 'Sin nombre',
                    'vendedoras' => $vendedoras->filter()->unique()->values(),
                    'pedidos' => $items->count(),
                    'unidades' => $items->sum('cantidad_unidades'),
                    'monto' => $items->sum('total_a_pagar'),
                    'estado' => $estadoFrecuente ?? 'Sin estado',
                    'ultimo_pedido' => $items->max('fecha'),
                ];
            })
            ->values()
            ->sortBy('nombre');
    }

    public function exportLideres()
    {
        $filename = 'lideres-zona-coordinadora-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Líder', 'Vendedoras', 'Pedidos', 'Unidades', 'Monto', 'Estado más frecuente', 'Último pedido']);
            foreach ($this->lideres as $fila) {
                fputcsv($handle, [
                    $fila['nombre'],
                    $fila['vendedoras']->implode(' | '),
                    $fila['pedidos'],
                    $fila['unidades'],
                    $fila['monto'],
                    $fila['estado'],
                    $fila['ultimo_pedido'],
                ]);
            }
            fclose($handle);
        }, $filename, $headers);
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiem9uYS1jb29yZGluYWRvcmEiLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL3pvbmEtY29vcmRpbmFkb3JhXC9pbmRleC5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-3838792948-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/zona-coordinadora/index.blade.php ENDPATH**/ ?>