<?php

use function Laravel\Folio\{middleware, name};
use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Volt\Component;

middleware('auth');
name('zona-lider');

new class extends Component {
    public string $startDate;

    public string $endDate;

    public string $estado = '';

    public array $resumen = [];

    public Collection $vendedoras;

    public Collection $estados;

    public array $estadosDisponibles = [];

    public bool $hasCoordinadoraColumn = false;

    public array $premios = [];

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

        $this->vendedoras = collect();
        $this->estados = collect();
        $this->loadData();
        $this->loadMetricaCampana();
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
            ->when($this->hasCoordinadoraColumn, fn ($q) => $q->addSelect('coordinadora_id'))
            ->where('lider_id', Auth::id());

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

        $this->vendedoras = $pedidos
            ->groupBy('vendedora_id')
            ->map(function ($items) {
                $estadoFrecuente = $items
                    ->groupBy('estado')
                    ->sortByDesc(fn ($group) => $group->count())
                    ->keys()
                    ->first();

                return [
                    'nombre' => $items->first()?->vendedora?->name ?? 'Sin nombre',
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

    protected function loadMetricaCampana(): void
    {
        $metrica = MetricaLiderCampana::with(['rangoLider', 'cierreCampana'])
            ->where('lider_id', Auth::id())
            ->latest('created_at')
            ->first();

        if (! $metrica) {
            $this->premios = [];

            return;
        }

        $this->premios = [
            'campana' => $metrica->cierreCampana?->nombre ?? 'Sin campaña',
            'rango' => $metrica->rangoLider?->nombre ?? 'Sin rango',
            'revendedoras_activas' => data_get($metrica->datos, 'revendedoras_activas', 0),
            'unidades' => data_get($metrica->datos, 'unidades', 0),
            'cobranzas_ok' => (bool) $metrica->cobranzas_ok,
            'fecha_pago_equipo' => $metrica->fecha_pago_equipo?->format('d/m/Y'),
            'altas_mes' => data_get($metrica->datos, 'altas_mes', 0),
            'altas_pagadas' => $metrica->altas_pagadas_en_cierre ?? [],
            'repartos' => [
                '1c' => $metrica->cantidad_1c,
                '2c' => $metrica->cantidad_2c,
                '3c' => $metrica->cantidad_3c,
                'monto' => $metrica->monto_reparto_total,
            ],
            'premio_crecimiento' => $metrica->premio_crecimiento,
            'premio_total' => $metrica->premio_total,
        ];
    }

    public function exportVendedoras()
    {
        $filename = 'vendedoras-zona-lider-' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Vendedora', 'Pedidos', 'Unidades', 'Monto', 'Estado más frecuente', 'Último pedido']);
            foreach ($this->vendedoras as $fila) {
                fputcsv($handle, [
                    $fila['nombre'],
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiem9uYS1saWRlciIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvem9uYS1saWRlclwvaW5kZXguYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-720412016-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/zona-lider/index.blade.php ENDPATH**/ ?>