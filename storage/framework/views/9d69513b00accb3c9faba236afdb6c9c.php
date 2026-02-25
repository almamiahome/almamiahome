<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;

middleware('auth');
name('resumen-lideres');

new class extends Component {
    public string $startDate;

    public string $endDate;

    public string $estado = '';

    public string $zona = '';

    public array $resumen = [];

    public Collection $lideres;

    public Collection $estados;

    public array $estadosDisponibles = [];

    public int $metaPedidos = 50;

    public int $metaPuntos = 5000;

    public int $metaMonto = 50000;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
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
        if (in_array($property, ['startDate', 'endDate', 'estado', 'zona'], true)) {
            $this->loadData();
        }
    }

    protected function filteredPedidos(): Collection
    {
        $query = Pedido::query()
            ->with(['lider', 'coordinadora'])
            ->whereNotNull('lider_id');

        if ($this->startDate) {
            $query->whereDate('fecha', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('fecha', '<=', $this->endDate);
        }

        if ($this->estado !== '') {
            $query->where('estado', $this->estado);
        }

        if ($this->zona !== '') {
            $query->whereHas('lider.profileKeyValues', function ($q) {
                $q->where('key', 'zona')
                    ->where('value', 'like', "%{$this->zona}%");
            });
        }

        return $query->get();
    }

    protected function loadData(): void
    {
        $pedidos = $this->filteredPedidos();

        $this->resumen = [
            'pedidos' => $pedidos->count(),
            'monto' => $pedidos->sum('total_a_pagar'),
            'puntos' => $pedidos->sum('total_puntos'),
            'unidades' => $pedidos->sum('cantidad_unidades'),
        ];

        $this->estados = $pedidos
            ->groupBy('estado')
            ->map(fn ($items, $estado) => [
                'estado' => $estado ?? 'Sin estado',
                'pedidos' => $items->count(),
                'monto' => $items->sum('total_a_pagar'),
            ])
            ->values()
            ->sortByDesc('pedidos');

        $this->lideres = $pedidos
            ->groupBy('lider_id')
            ->map(function ($items) {
                $lider = $items->first()?->lider;
                $estadoFrecuente = $items
                    ->groupBy('estado')
                    ->sortByDesc(fn ($group) => $group->count())
                    ->keys()
                    ->first();

                return [
                    'nombre' => $lider?->name ?? 'Sin líder',
                    'zona' => $lider?->profile('zona') ?: 'Sin zona',
                    'pedidos' => $items->count(),
                    'monto' => $items->sum('total_a_pagar'),
                    'puntos' => $items->sum('total_puntos'),
                    'estado' => $estadoFrecuente ?? 'Sin estado',
                    'ultimo_pedido' => $items->max('fecha'),
                ];
            })
            ->values()
            ->sortBy('nombre');
    }

    public function exportLideres()
    {
        $filename = 'resumen-lideres-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Líder', 'Zona', 'Pedidos', 'Monto', 'Puntos', 'Estado más frecuente', 'Último pedido']);
            foreach ($this->lideres as $fila) {
                fputcsv($handle, [
                    $fila['nombre'],
                    $fila['zona'],
                    $fila['pedidos'],
                    $fila['monto'],
                    $fila['puntos'],
                    $fila['estado'],
                    $fila['ultimo_pedido'],
                ]);
            }
            fclose($handle);
        }, $filename, $headers);
    }

    public function exportPedidos()
    {
        $pedidos = $this->filteredPedidos();
        $filename = 'pedidos-lideres-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->streamDownload(function () use ($pedidos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Código', 'Líder', 'Coordinadora', 'Fecha', 'Estado', 'Monto', 'Puntos']);
            foreach ($pedidos as $pedido) {
                fputcsv($handle, [
                    $pedido->codigo_pedido,
                    $pedido->lider?->name,
                    $pedido->coordinadora?->name,
                    $pedido->fecha,
                    $pedido->estado,
                    $pedido->total_a_pagar,
                    $pedido->total_puntos,
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicmVzdW1lbi1saWRlcmVzIiwicGF0aCI6InJlc291cmNlc1wvdGhlbWVzXC9hbmNob3JcL3BhZ2VzXC9yZXN1bWVuLWxpZGVyZXNcL2luZGV4LmJsYWRlLnBocCJ9", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-589568203-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/resumen-lideres/index.blade.php ENDPATH**/ ?>