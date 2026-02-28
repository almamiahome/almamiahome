<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

middleware('auth');
name('panel');

$usuario = Auth::user();
$rolesOrdenados = ['admin', 'coordinadora', 'lider', 'vendedora'];
$rolActual = collect($rolesOrdenados)->first(fn ($rol) => $usuario?->hasRole($rol)) ?? 'vendedora';

$pedidosBase = Pedido::query();

match ($rolActual) {
    'coordinadora' => $pedidosBase->where('coordinadora_id', $usuario?->id),
    'lider'        => $pedidosBase->where('lider_id', $usuario?->id),
    'vendedora'    => $pedidosBase->where('vendedora_id', $usuario?->id),
    default        => $pedidosBase,
};

$pedidosTotales   = (clone $pedidosBase)->count();
$puntosTotal      = (clone $pedidosBase)->sum('total_puntos');
$unidadesTotales  = (clone $pedidosBase)->sum('cantidad_unidades');
$pedidosRecientes = (clone $pedidosBase)->latest()->take(5)->get();

$metaPedidos = [
    'admin'        => 30,
    'coordinadora' => 20,
    'lider'        => 15,
    'vendedora'    => 8,
][$rolActual] ?? 10;

$metaPuntos = [
    'admin'        => 1200,
    'coordinadora' => 900,
    'lider'        => 700,
    'vendedora'    => 400,
][$rolActual] ?? 500;

$avancePedidos = $metaPedidos > 0 ? min(100, round(($pedidosTotales / $metaPedidos) * 100)) : 0;
$avancePuntos  = $metaPuntos > 0 ? min(100, round(($puntosTotal / $metaPuntos) * 100)) : 0;

$rolesConfig = [
    'admin' => [
        'titulo'    => 'Administrá el pulso de toda la red',
        'subtitulo' => 'Seguimiento global de coordinadoras, líderes y pedidos.',
        'clases'    => [
            'panel'          => 'bg-indigo-50/60 border border-indigo-100',
            'panelRing'      => 'ring-indigo-100',
            'panelSuave'     => 'bg-indigo-50 text-indigo-900 ring-indigo-100',
            'chip'           => 'text-indigo-700 ring-indigo-200',
            'heroText'       => 'text-indigo-900/90',
            'textoSuave'     => 'text-indigo-800/70',
            'textoFuerte'    => 'text-indigo-800',
            'indicador'      => 'bg-indigo-500',
            'icono'          => 'bg-indigo-50 text-indigo-700 ring-indigo-100',
            'borde'          => 'border-indigo-100',
            'ctaBorder'      => 'border-indigo-200 hover:border-indigo-400/80',
            'ctaTexto'       => 'text-indigo-700 group-hover:text-indigo-900',
            'progresoFondo'  => 'bg-indigo-100',
            'progresoColor'  => 'bg-indigo-500',
            'badge'          => 'bg-indigo-50 text-indigo-800 ring-indigo-100',
            'link'           => 'text-indigo-700 hover:text-indigo-900',
        ],
        'acciones' => [
            [
                'titulo'      => 'Pedidos en curso',
                'descripcion' => 'Supervisá las entregas y detectá demoras críticas.',
                'ruta'        => '/mis-pedidos',
            ],
            [
                'titulo'      => 'Resumen por zona',
                'descripcion' => 'Contrasta el avance de coordinadoras con sus líderes.',
                'ruta'        => '/resumen-coordinadoras',
            ],
            [
                'titulo'      => 'Reglas de puntaje',
                'descripcion' => 'Ajustá incentivos o define metas especiales.',
                'ruta'        => '/puntaje-reglas',
            ],
        ],
    ],
    'coordinadora' => [
        'titulo'    => 'Acompañá a tus líderes',
        'subtitulo' => 'Organizá la zona, detectá alertas y motiva al equipo.',
        'clases'    => [
            'panel'          => 'bg-pink-50/60 border border-pink-100',
            'panelRing'      => 'ring-pink-100',
            'panelSuave'     => 'bg-pink-50 text-pink-900 ring-pink-100',
            'chip'           => 'text-pink-700 ring-pink-200',
            'heroText'       => 'text-pink-900/90',
            'textoSuave'     => 'text-pink-800/70',
            'textoFuerte'    => 'text-pink-800',
            'indicador'      => 'bg-pink-500',
            'icono'          => 'bg-pink-50 text-pink-700 ring-pink-100',
            'borde'          => 'border-pink-100',
            'ctaBorder'      => 'border-pink-200 hover:border-pink-400/80',
            'ctaTexto'       => 'text-pink-700 group-hover:text-pink-900',
            'progresoFondo'  => 'bg-pink-100',
            'progresoColor'  => 'bg-pink-500',
            'badge'          => 'bg-pink-50 text-pink-800 ring-pink-100',
            'link'           => 'text-pink-700 hover:text-pink-900',
        ],
        'acciones' => [
            [
                'titulo'      => 'Zona de coordinadora',
                'descripcion' => 'Visualizá pedidos y cobros asociados a tu zona.',
                'ruta'        => '/zona-coordinadora',
            ],
            [
                'titulo'      => 'Resumen de líderes',
                'descripcion' => 'Identificá quienes necesitan apoyo inmediato.',
                'ruta'        => '/resumen-lideres',
            ],
            [
                'titulo'      => 'Incorporar líderes',
                'descripcion' => 'Sumá nuevas líderes o vendedoras bajo tu seguimiento.',
                'ruta'        => '/incorporar',
            ],
        ],
    ],
    'lider' => [
        'titulo'    => 'Guiá a tus vendedoras',
        'subtitulo' => 'Consolidá pedidos, cobros y oportunidades de incorporación.',
        'clases'    => [
            'panel'          => 'bg-emerald-50/60 border border-emerald-100',
            'panelRing'      => 'ring-emerald-100',
            'panelSuave'     => 'bg-emerald-50 text-emerald-900 ring-emerald-100',
            'chip'           => 'text-emerald-700 ring-emerald-200',
            'heroText'       => 'text-emerald-900/90',
            'textoSuave'     => 'text-emerald-800/70',
            'textoFuerte'    => 'text-emerald-800',
            'indicador'      => 'bg-emerald-500',
            'icono'          => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            'borde'          => 'border-emerald-100',
            'ctaBorder'      => 'border-emerald-200 hover:border-emerald-400/80',
            'ctaTexto'       => 'text-emerald-700 group-hover:text-emerald-900',
            'progresoFondo'  => 'bg-emerald-100',
            'progresoColor'  => 'bg-emerald-500',
            'badge'          => 'bg-emerald-50 text-emerald-800 ring-emerald-100',
            'link'           => 'text-emerald-700 hover:text-emerald-900',
        ],
        'acciones' => [
            [
                'titulo'      => 'Zona de líder',
                'descripcion' => 'Controlá pedidos y cobranzas del equipo inmediato.',
                'ruta'        => '/zona-lider',
            ],
            [
                'titulo'      => 'Incorporar vendedoras',
                'descripcion' => 'Agregá nuevas vendedoras y alineá con su coordinadora.',
                'ruta'        => '/incorporar',
            ],
            [
                'titulo'      => 'Mis pedidos',
                'descripcion' => 'Confirmá estados y registra avances de tu cartera.',
                'ruta'        => '/mis-pedidos',
            ],
        ],
    ],
    'vendedora' => [
        'titulo'    => 'Impulsá tus ventas',
        'subtitulo' => 'Seguimiento simple de pedidos y recordatorios clave.',
        'clases'    => [
            'panel'          => 'bg-amber-50/60 border border-amber-100',
            'panelRing'      => 'ring-amber-100',
            'panelSuave'     => 'bg-amber-50 text-amber-900 ring-amber-100',
            'chip'           => 'text-amber-700 ring-amber-200',
            'heroText'       => 'text-amber-900/90',
            'textoSuave'     => 'text-amber-800/70',
            'textoFuerte'    => 'text-amber-800',
            'indicador'      => 'bg-amber-500',
            'icono'          => 'bg-amber-50 text-amber-700 ring-amber-100',
            'borde'          => 'border-amber-100',
            'ctaBorder'      => 'border-amber-200 hover:border-amber-400/80',
            'ctaTexto'       => 'text-amber-700 group-hover:text-amber-900',
            'progresoFondo'  => 'bg-amber-100',
            'progresoColor'  => 'bg-amber-500',
            'badge'          => 'bg-amber-50 text-amber-800 ring-amber-100',
            'link'           => 'text-amber-700 hover:text-amber-900',
        ],
        'acciones' => [
            [
                'titulo'      => 'Crear pedido',
                'descripcion' => 'Armá tu próximo pedido con catálogo actualizado.',
                'ruta'        => '/crearpedido',
            ],
            [
                'titulo'      => 'Estado de mis pedidos',
                'descripcion' => 'Revisá fechas, totales y próximos pasos.',
                'ruta'        => '/mis-pedidos',
            ],
            [
                'titulo'      => 'Mi perfil',
                'descripcion' => 'Actualizá tus datos y forma de cobro.',
                'ruta'        => '/settings/profile',
            ],
        ],
    ],
];

$config = $rolesConfig[$rolActual] ?? $rolesConfig['vendedora'];
$clases = $config['clases'];

$filtroAdmin = request()->string('filtro')->toString() ?: 'mes';
$mesAdmin = request()->string('mes')->toString() ?: now()->format('Y-m');
$fechaInicioAdmin = request()->string('fecha_inicio')->toString() ?: null;
$fechaFinAdmin = request()->string('fecha_fin')->toString() ?: null;

$pedidosAdminFiltrados = Pedido::query();

if ($filtroAdmin === 'rango') {
    if (! blank($fechaInicioAdmin)) {
        $pedidosAdminFiltrados->whereDate('fecha', '>=', $fechaInicioAdmin);
    }

    if (! blank($fechaFinAdmin)) {
        $pedidosAdminFiltrados->whereDate('fecha', '<=', $fechaFinAdmin);
    }
} elseif (preg_match('/^\d{4}-\d{2}$/', $mesAdmin) === 1) {
    $mes = Carbon::createFromFormat('Y-m', $mesAdmin)->startOfMonth();

    $pedidosAdminFiltrados->whereBetween('fecha', [
        $mes->toDateString(),
        $mes->copy()->endOfMonth()->toDateString(),
    ]);
}

$resumenAdmin = [
    'unidades_vendidas' => (int) (clone $pedidosAdminFiltrados)->sum('cantidad_unidades'),
    'total_catalogo_vendido' => (float) (clone $pedidosAdminFiltrados)->sum('total_precio_catalogo'),
    'total_facturado' => (float) (clone $pedidosAdminFiltrados)->sum('total_a_pagar'),
];

$ultimosPedidosAdmin = (clone $pedidosAdminFiltrados)
    ->with(['vendedora:id,name', 'lider:id,name'])
    ->latest('fecha')
    ->latest('created_at')
    ->limit(10)
    ->get();

$ultimosRegistrosAdmin = User::query()
    ->latest('created_at')
    ->limit(10)
    ->get(['id', 'name', 'email', 'created_at']);

$topVendedorasAdmin = User::query()
    ->select('users.id', 'users.name')
    ->selectSub(
        (clone $pedidosAdminFiltrados)
            ->selectRaw('COALESCE(SUM(total_a_pagar), 0)')
            ->whereColumn('pedidos.vendedora_id', 'users.id'),
        'total_ventas'
    )
    ->role('vendedora')
    ->orderByDesc('total_ventas')
    ->limit(5)
    ->get();

$topLideresAdmin = User::query()
    ->select('users.id', 'users.name')
    ->selectSub(
        (clone $pedidosAdminFiltrados)
            ->selectRaw('COALESCE(SUM(total_a_pagar), 0)')
            ->whereColumn('pedidos.lider_id', 'users.id'),
        'total_ventas'
    )
    ->role('lider')
    ->orderByDesc('total_ventas')
    ->limit(5)
    ->get();
?>

<?php if (isset($component)) { $__componentOriginalf103f87f9e6975b672a2453f5462c100 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf103f87f9e6975b672a2453f5462c100 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::layouts.marketing','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.marketing'); ?>
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicGFuZWwiLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL3BhbmVsXC9pbmRleC5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-962469049-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf103f87f9e6975b672a2453f5462c100)): ?>
<?php $attributes = $__attributesOriginalf103f87f9e6975b672a2453f5462c100; ?>
<?php unset($__attributesOriginalf103f87f9e6975b672a2453f5462c100); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf103f87f9e6975b672a2453f5462c100)): ?>
<?php $component = $__componentOriginalf103f87f9e6975b672a2453f5462c100; ?>
<?php unset($__componentOriginalf103f87f9e6975b672a2453f5462c100); ?>
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/panel/index.blade.php ENDPATH**/ ?>