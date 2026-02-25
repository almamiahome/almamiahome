<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

middleware('auth');
name('dashboard');

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

<x-layouts.app>
    <x-app.container
        x-data="{
            tab: 'resumen',
            tabs: ['resumen', 'equipo', 'catalogo'],
            go(direction) {
                const index = this.tabs.indexOf(this.tab);
                const target = (index + direction + this.tabs.length) % this.tabs.length;
                this.tab = this.tabs[target];
            }
        }"
        x-cloak
        class="space-y-8"
    >
        <section class="relative overflow-hidden rounded-3xl {{ $clases['panel'] }} text-[#1f2758] shadow-sm {{ $clases['panelRing'] }}">
            <div class="relative px-6 py-10 sm:px-10 sm:py-12 lg:px-14 lg:py-16">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] {{ $clases['chip'] }} ring-1">
                            Panel dinámico · Rol {{ ucfirst($rolActual) }}
                        </div>
                        <div>
                            <h1 class="text-3xl font-semibold sm:text-4xl">
                                Hola, {{ $usuario?->name }} 👋
                            </h1>
                            <p class="mt-3 text-base {{ $clases['heroText'] }}">
                                {{ $config['titulo'] }}. {{ $config['subtitulo'] }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-sm font-semibold shadow-sm ring-1 {{ $clases['chip'] }}">
                                <span class="inline-block h-2 w-2 rounded-full {{ $clases['indicador'] }}"></span>
                                Rol activo: {{ ucfirst($rolActual) }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-sm font-semibold shadow-sm ring-1 {{ $clases['chip'] }}">
                                🎯 Objetivo de pedidos: {{ $metaPedidos }} · Puntos: {{ $metaPuntos }}
                            </span>
                        </div>
                    </div>
                    <div class="grid w-full max-w-xl grid-cols-2 gap-4 rounded-2xl bg-white/80 p-4 shadow-sm ring-1 {{ $clases['panelRing'] }} md:w-auto">
                        <div class="flex flex-col gap-1 rounded-xl {{ $clases['panelSuave'] }} p-4 ring-1">
                            <span class="text-sm font-semibold">Progreso de pedidos</span>
                            <span class="text-2xl font-bold">{{ $avancePedidos }}%</span>
                            <div class="mt-1 h-2 overflow-hidden rounded-full {{ $clases['progresoFondo'] }}">
                                <div class="h-full {{ $clases['progresoColor'] }}" style="width: {{ $avancePedidos }}%"></div>
                            </div>
                            <p class="text-xs {{ $clases['textoSuave'] }}">Llevás {{ $pedidosTotales }} de {{ $metaPedidos }} pedidos meta.</p>
                        </div>
                        <div class="flex flex-col gap-1 rounded-xl {{ $clases['panelSuave'] }} p-4 ring-1">
                            <span class="text-sm font-semibold">Puntos acumulados</span>
                            <span class="text-2xl font-bold">{{ $puntosTotal }}</span>
                            <div class="mt-1 h-2 overflow-hidden rounded-full {{ $clases['progresoFondo'] }}">
                                <div class="h-full {{ $clases['progresoColor'] }}" style="width: {{ $avancePuntos }}%"></div>
                            </div>
                            <p class="text-xs {{ $clases['textoSuave'] }}">Objetivo sugerido: {{ $metaPuntos }} pts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach($config['acciones'] as $accion)
                <a
                    href="{{ url($accion['ruta']) }}"
                    class="group relative overflow-hidden rounded-2xl border bg-white p-5 shadow-sm transition hover:-translate-y-1 {{ $clases['ctaBorder'] }} hover:shadow-lg"
                >
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full {{ $clases['icono'] }} ring-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75H5.25" />
                        </svg>
                    </span>
                    <h3 class="mt-4 text-lg font-semibold text-[#1f2758]">{{ $accion['titulo'] }}</h3>
                    <p class="mt-2 text-sm text-[#3d4c7c]">{{ $accion['descripcion'] }}</p>
                    <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold {{ $clases['ctaTexto'] }}">
                        Abrir
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                </a>
            @endforeach
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border {{ $clases['borde'] }} bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#1f2758]">Progreso e insignias</h2>
                        <p class="text-sm text-[#3d4c7c]">La barra sube a medida que sumás pedidos y puntos.</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $clases['badge'] }} ring-1">Gamificación</span>
                </div>
                <div class="mt-6 space-y-5">
                    <div class="rounded-xl {{ $clases['panel'] }} p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2758]">Insignia de constancia</p>
                                <p class="text-xs text-[#3d4c7c]">Completá {{ $metaPedidos }} pedidos para lograr la insignia.</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold {{ $clases['textoFuerte'] }} ring-1 {{ $clases['borde'] }}">{{ $avancePedidos }}%</span>
                        </div>
                        <div class="mt-3 h-2 rounded-full bg-white/70">
                            <div class="h-full rounded-full {{ $clases['progresoColor'] }}" style="width: {{ $avancePedidos }}%"></div>
                        </div>
                    </div>
                    <div class="rounded-xl {{ $clases['panel'] }} p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2758]">Racha de puntos</p>
                                <p class="text-xs text-[#3d4c7c]">Sumá {{ $metaPuntos }} puntos para desbloquear el siguiente rango.</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold {{ $clases['textoFuerte'] }} ring-1 {{ $clases['borde'] }}">{{ $avancePuntos }}%</span>
                        </div>
                        <div class="mt-3 h-2 rounded-full bg-white/70">
                            <div class="h-full rounded-full {{ $clases['progresoColor'] }}" style="width: {{ $avancePuntos }}%"></div>
                        </div>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-semibold text-slate-600">Pedidos totales</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $pedidosTotales }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-semibold text-slate-600">Puntos</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $puntosTotal }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-semibold text-slate-600">Unidades</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $unidadesTotales }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border {{ $clases['borde'] }} bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#1f2758]">Pedidos recientes</h2>
                        <p class="text-sm text-[#3d4c7c]">Últimos movimientos relevantes para tu rol.</p>
                    </div>
                    <a href="{{ url('/mis-pedidos') }}" class="text-sm font-semibold {{ $clases['link'] }}">Ver todos</a>
                </div>
                <div class="mt-4 divide-y divide-slate-100 border border-slate-100 rounded-xl">
                    @forelse($pedidosRecientes as $pedido)
                        <div class="flex items-center justify-between px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2758]">Pedido #{{ $pedido->codigo_pedido ?? $pedido->id }}</p>
                                <p class="text-xs text-[#3d4c7c]">{{ $pedido->fecha ?? $pedido->created_at?->format('d/m/Y') ?? 'Fecha sin registrar' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $clases['textoFuerte'] }}">{{ $pedido->total_puntos ?? 0 }} pts</p>
                                <p class="text-xs text-[#3d4c7c]">{{ ucfirst($pedido->estado ?? 'en revisión') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-6 text-center text-sm text-[#3d4c7c]">
                            Todavía no hay pedidos cargados para este rol. Creá el primero y desbloqueá tu progreso.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        @if($rolActual === 'admin')
            <section class="space-y-6 rounded-2xl border border-indigo-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-[#1f2758]">Panel comercial de administración</h2>
                        <p class="text-sm text-[#3d4c7c]">Indicadores globales de ventas con filtros por período.</p>
                    </div>

                    <form method="GET" action="{{ route('dashboard') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <label class="flex flex-col gap-1 text-xs font-medium text-slate-600">
                            Tipo de filtro
                            <select name="filtro" class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700">
                                <option value="mes" @selected($filtroAdmin === 'mes')>Por mes</option>
                                <option value="rango" @selected($filtroAdmin === 'rango')>Rango personalizado</option>
                            </select>
                        </label>

                        <label class="flex flex-col gap-1 text-xs font-medium text-slate-600">
                            Mes
                            <input name="mes" type="month" value="{{ $mesAdmin }}" class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700" />
                        </label>

                        <label class="flex flex-col gap-1 text-xs font-medium text-slate-600">
                            Desde
                            <input name="fecha_inicio" type="date" value="{{ $fechaInicioAdmin }}" class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700" />
                        </label>

                        <div class="flex items-end gap-2">
                            <label class="flex w-full flex-col gap-1 text-xs font-medium text-slate-600">
                                Hasta
                                <input name="fecha_fin" type="date" value="{{ $fechaFinAdmin }}" class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700" />
                            </label>
                            <button type="submit" class="h-10 rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white hover:bg-indigo-700">Aplicar</button>
                        </div>
                    </form>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase text-slate-500">Unidades vendidas</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($resumenAdmin['unidades_vendidas'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase text-slate-500">Total catálogo vendido</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">${{ number_format($resumenAdmin['total_catalogo_vendido'], 2, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase text-slate-500">Total facturado</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">${{ number_format($resumenAdmin['total_facturado'], 2, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Últimos pedidos</h3>
                        <div class="overflow-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-xs uppercase text-slate-500">
                                    <tr>
                                        <th class="pb-2">Código</th>
                                        <th class="pb-2">Fecha</th>
                                        <th class="pb-2">Vendedora</th>
                                        <th class="pb-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($ultimosPedidosAdmin as $pedido)
                                        <tr>
                                            <td class="py-2 font-medium text-slate-900">{{ $pedido->codigo_pedido }}</td>
                                            <td class="py-2 text-slate-600">{{ $pedido->fecha ? Carbon::parse($pedido->fecha)->format('d/m/Y') : 'Sin fecha' }}</td>
                                            <td class="py-2 text-slate-600">{{ $pedido->vendedora?->name ?? 'Sin vendedora' }}</td>
                                            <td class="py-2 text-right font-semibold text-slate-900">${{ number_format((float) $pedido->total_a_pagar, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 text-center text-sm text-slate-500">No hay pedidos para el filtro seleccionado.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 p-4">
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Últimos registros</h3>
                        <div class="overflow-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-xs uppercase text-slate-500">
                                    <tr>
                                        <th class="pb-2">Nombre</th>
                                        <th class="pb-2">Correo</th>
                                        <th class="pb-2">Alta</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($ultimosRegistrosAdmin as $registro)
                                        <tr>
                                            <td class="py-2 font-medium text-slate-900">{{ $registro->name }}</td>
                                            <td class="py-2 text-slate-600">{{ $registro->email }}</td>
                                            <td class="py-2 text-slate-600">{{ $registro->created_at?->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-sm text-slate-500">No hay registros recientes.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Top 5 vendedoras (por ventas)</h3>
                        <ol class="space-y-2 text-sm">
                            @forelse($topVendedorasAdmin as $vendedora)
                                <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2">
                                    <span class="font-medium text-slate-900">{{ $vendedora->name }}</span>
                                    <span class="text-slate-600">${{ number_format((float) $vendedora->total_ventas, 2, ',', '.') }}</span>
                                </li>
                            @empty
                                <li class="text-slate-500">Sin datos para el período seleccionado.</li>
                            @endforelse
                        </ol>
                    </div>

                    <div class="rounded-2xl border border-slate-100 p-4">
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Top 5 líderes (por ventas)</h3>
                        <ol class="space-y-2 text-sm">
                            @forelse($topLideresAdmin as $lider)
                                <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2">
                                    <span class="font-medium text-slate-900">{{ $lider->name }}</span>
                                    <span class="text-slate-600">${{ number_format((float) $lider->total_ventas, 2, ',', '.') }}</span>
                                </li>
                            @empty
                                <li class="text-slate-500">Sin datos para el período seleccionado.</li>
                            @endforelse
                        </ol>
                    </div>
                </div>

                <div
                    x-data="dashboardAccesosRapidos()"
                    x-init="init()"
                    class="space-y-3"
                >
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Accesos rápidos arrastrables</h3>
                        <p class="text-xs text-slate-500">Accedé al mismo menú del sidebar y ordená los accesos según tu uso.</p>
                    </div>

                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        <template x-for="(item, index) in items" :key="item.href + index">
                            <a
                                :href="item.href"
                                draggable="true"
                                @dragstart="dragStart(index)"
                                @dragover.prevent
                                @drop="drop(index)"
                                class="cursor-move rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-indigo-500 hover:text-indigo-600"
                                x-text="item.label"
                            ></a>
                        </template>
                    </div>
                </div>
            </section>
        @endif

        <script>
            function dashboardAccesosRapidos() {
                return {
                    items: [],
                    dragIndex: null,
                    storageKey: 'dashboard-accesos-rapidos-anchor',
                    init() {
                        const links = Array.from(document.querySelectorAll('aside a[href], nav a[href]'))
                            .map((link) => ({
                                href: link.getAttribute('href'),
                                label: (link.textContent || '').trim(),
                            }))
                            .filter((item) => item.href && item.label && !item.href.startsWith('#'))
                            .filter((item, index, arr) => arr.findIndex((el) => el.href === item.href) === index)
                            .slice(0, 12);

                        const guardado = localStorage.getItem(this.storageKey);

                        if (!guardado) {
                            this.items = links;
                            return;
                        }

                        try {
                            const guardados = JSON.parse(guardado);
                            const porHref = new Map(links.map((item) => [item.href, item]));
                            const restaurados = guardados.map((item) => porHref.get(item.href)).filter(Boolean);
                            const nuevos = links.filter((item) => !restaurados.some((r) => r.href === item.href));
                            this.items = [...restaurados, ...nuevos];
                        } catch (error) {
                            this.items = links;
                        }
                    },
                    dragStart(index) {
                        this.dragIndex = index;
                    },
                    drop(index) {
                        if (this.dragIndex === null || this.dragIndex === index) {
                            return;
                        }

                        const movido = this.items.splice(this.dragIndex, 1)[0];
                        this.items.splice(index, 0, movido);
                        this.dragIndex = null;
                        localStorage.setItem(this.storageKey, JSON.stringify(this.items));
                    },
                };
            }
        </script>

    </x-app.container>
</x-layouts.app>
