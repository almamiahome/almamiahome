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
        class="space-y-6 pb-12"
    >
        
<section class="relative min-h-screen w-full p-4 sm:p-5 font-sans flex items-center justify-center overflow-hidden">
    

    <div class="relative z-10 w-full max-w-7xl mx-auto flex flex-col gap-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-zinc-900 dark:text-white tracking-tight">Panel de Gestión</h2>
                <p class="text-zinc-600 dark:text-zinc-400 font-medium">Almamia Home — {{ now()->format('H:i') }}</p>
            </div>
            <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md shadow-sm border border-white/40 dark:border-zinc-800 rounded-2xl px-4 py-2 flex items-center gap-3 w-fit">
                <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-xs font-bold text-zinc-600 dark:text-zinc-300 tracking-widest uppercase">Sistema Online</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <div class="relative overflow-hidden rounded-3xl bg-white/70 dark:bg-zinc-900/70 p-8 border border-white/50 dark:border-zinc-800 shadow-xl lg:col-span-8 flex flex-col justify-center transition-all">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-[#294395]/10 to-[#e91e63]/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-lg bg-[#294395]/10 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-[#294395] dark:text-blue-400 border border-[#294395]/10">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#e91e63] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#e91e63]"></span>
                            </span>
                            Rol Activo: {{ ucfirst($rolActual) }}
                        </div>
                        <div>
                            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight sm:text-4xl">
                                Bienvenido, {{ $usuario?->name }} 👋
                            </h1>
                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400 font-medium max-w-xl">
                                {{ $config['titulo'] }}. {{ $config['subtitulo'] }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0">
                        <div class="inline-flex flex-col rounded-2xl bg-white/50 dark:bg-zinc-800/50 px-5 py-3 border border-white/20">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Tus objetivos actuales</span>
                            <span class="mt-1 text-sm font-bold text-slate-700 dark:text-zinc-200">
                                <span class="text-[#294395] dark:text-blue-400">{{ $metaPedidos }}</span> Pedidos · 
                                <span class="text-[#e91e63]">{{ $metaPuntos }}</span> Pts
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6 lg:col-span-4">
                <div class="flex-1 rounded-3xl bg-white/70 dark:bg-zinc-900/70 p-6 border border-white/50 dark:border-zinc-800 shadow-xl flex flex-col justify-center relative overflow-hidden group transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Progreso Pedidos</span>
                        <span class="text-xl font-black text-[#294395] dark:text-blue-400">{{ $avancePedidos }}%</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200/50 dark:bg-zinc-800">
                        <div class="h-full rounded-full bg-[#294395] transition-all duration-1000 ease-out" style="width: {{ $avancePedidos }}%"></div>
                    </div>
                    <p class="mt-3 text-xs font-medium text-slate-400 text-right">{{ $pedidosTotales }} / {{ $metaPedidos }} meta</p>
                </div>

                <div class="flex-1 rounded-3xl bg-white/70 dark:bg-zinc-900/70 p-6 border border-white/50 dark:border-zinc-800 shadow-xl flex flex-col justify-center relative overflow-hidden group transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Puntos Acumulados</span>
                        <span class="text-xl font-black text-[#e91e63]">{{ $puntosTotal }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200/50 dark:bg-zinc-800">
                        <div class="h-full rounded-full bg-[#e91e63] transition-all duration-1000 ease-out" style="width: {{ $avancePuntos }}%"></div>
                    </div>
                    <p class="mt-3 text-xs font-medium text-slate-400 text-right">Sugerido: {{ $metaPuntos }} pts</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-x-4 gap-y-10 py-4">
            @php
                $user = auth()->user();
                $apps = [
                    ['titulo' => 'Escritorio', 'ruta' => '/dashboard', 'icon' => 'phosphor-house-duotone', 'color' => 'bg-blue-500', 'show' => true],
                    ['titulo' => 'Crear Pedido', 'ruta' => '/crearpedido', 'icon' => 'phosphor-plus-circle-duotone', 'color' => 'bg-emerald-500', 'show' => true],
                    ['titulo' => 'Catálogo', 'ruta' => '/catalogo', 'icon' => 'phosphor-book-duotone', 'color' => 'bg-pink-500', 'show' => true],
                    ['titulo' => 'Mis Pedidos', 'ruta' => '/mis-pedidos', 'icon' => 'phosphor-list-checks-duotone', 'color' => 'bg-violet-600', 'show' => $user->hasAnyRole(['lider', 'vendedora'])],
                    ['titulo' => 'Vendedoras', 'ruta' => '/vendedoras', 'icon' => 'phosphor-handshake-duotone', 'color' => 'bg-orange-500', 'show' => $user->hasAnyRole(['lider', 'admin'])],
                    ['titulo' => 'Pedidos', 'ruta' => '/pedidos', 'icon' => 'phosphor-shopping-cart-duotone', 'color' => 'bg-rose-500', 'show' => $user->hasRole('admin')],
                    ['titulo' => 'Líderes', 'ruta' => '/lideres', 'icon' => 'phosphor-user-circle-duotone', 'color' => 'bg-indigo-600', 'show' => $user->hasAnyRole(['coordinadora', 'admin'])],
                    ['titulo' => 'Coordinadoras', 'ruta' => '/coordinadoras', 'icon' => 'phosphor-user-switch-duotone', 'color' => 'bg-cyan-600', 'show' => $user->hasRole('admin')],
                    ['titulo' => 'Incorporar', 'ruta' => '/incorporar', 'icon' => 'phosphor-user-plus-duotone', 'color' => 'bg-lime-600', 'show' => $user->hasAnyRole(['lider', 'coordinadora', 'admin'])],
                    ['titulo' => 'Puntaje Reglas', 'ruta' => '/puntaje-reglas', 'icon' => 'phosphor-target-duotone', 'color' => 'bg-amber-500', 'show' => $user->hasRole('admin')],
                    ['titulo' => 'Rangos', 'ruta' => '/rangos', 'icon' => 'phosphor-medal-duotone', 'color' => 'bg-purple-500', 'show' => $user->hasRole('admin')],
                    ['titulo' => 'Cierre Gral', 'ruta' => '/crecimiento-cierre-general', 'icon' => 'phosphor-chart-pie-duotone', 'color' => 'bg-zinc-800', 'show' => $user->hasRole('admin')],
                    ['titulo' => 'Gastos', 'ruta' => '/gastos', 'icon' => 'phosphor-coins-duotone', 'color' => 'bg-red-500', 'show' => $user->hasRole('admin')],
                    ['titulo' => 'Agente AI', 'ruta' => '/agente', 'icon' => 'phosphor-robot-duotone', 'color' => 'bg-violet-500', 'show' => $user->hasRole('admin')],
                    ['titulo' => 'Perfil', 'ruta' => '/perfil', 'icon' => 'phosphor-user-duotone', 'color' => 'bg-zinc-500', 'show' => true],
                ];
            @endphp

            @foreach($apps as $app)
                @if($app['show'])
                    <a href="{{ $app['ruta'] }}" wire:navigate class="app-item group flex flex-col items-center outline-none">
                        <div class="relative">
                            <div class="absolute inset-0 {{ $app['color'] }} opacity-20 blur-xl group-hover:opacity-50 transition-opacity rounded-full"></div>
                            <div class="{{ $app['color'] }} w-16 h-16 sm:w-[72px] sm:h-[72px] rounded-[1.5rem] shadow-lg flex items-center justify-center text-white relative overflow-hidden transition-all duration-500 group-hover:scale-105 group-hover:-translate-y-2 group-active:scale-90 border-t border-white/30 shadow-{{ str_replace('bg-', '', $app['color']) }}/40">
                                <x-dynamic-component :component="$app['icon']" class="w-8 h-8 sm:w-9 sm:h-9 text-white drop-shadow-md z-10" />
                                <div class="absolute top-0 inset-x-0 h-1/2 bg-gradient-to-b from-white/20 to-transparent"></div>
                            </div>
                        </div>
                        <span class="mt-3 text-[11px] sm:text-xs font-bold text-zinc-800 dark:text-zinc-200 text-center leading-tight line-clamp-2 px-1 transition-colors group-hover:text-zinc-900 dark:group-hover:text-white drop-shadow-sm">
                            {{ $app['titulo'] }}
                        </span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</section>

<style>
    @keyframes appEntrance {
        from { opacity: 0; transform: scale(0.8) translateY(20px); filter: blur(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
    }

    .app-item {
        animation: appEntrance 0.7s cubic-bezier(0.23, 1, 0.32, 1) backwards;
    }

    @for ($i = 1; $i <= 30; $i++)
        .app-item:nth-child({{ $i }}) {
            animation-delay: {{ 0.1 + ($i * 0.05) }}s;
        }
    @endfor
</style>


  <!-- <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach($config['acciones'] as $accion)
                <a href="{{ url($accion['ruta']) }}" class="group flex flex-col justify-between rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md hover:ring-[#294395]/30">
                    <div>
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-[#294395] ring-1 ring-slate-100 group-hover:bg-[#294395] group-hover:text-white transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75H5.25" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">{{ $accion['titulo'] }}</h3>
                        <p class="mt-2 text-xs font-medium text-slate-500 leading-relaxed">{{ $accion['descripcion'] }}</p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#e91e63] group-hover:gap-2 transition-all">
                        Abrir
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </div>
                </a>
            @endforeach
        </section> -->

        @if($rolActual !== 'admin')
            <section class="grid gap-6 lg:grid-cols-12">
                <div class="rounded-3xl bg-white p-8 ring-1 ring-slate-200/60 shadow-sm lg:col-span-7 flex flex-col">
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Rendimiento e Insignias</h2>
                            <p class="mt-1 text-sm text-slate-500 font-medium">Sigue sumando para desbloquear nuevos niveles.</p>
                        </div>
                        <span class="rounded-lg bg-indigo-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-indigo-600 ring-1 ring-indigo-100">Gamificación</span>
                    </div>

                    <div class="grid gap-6 flex-1 content-start">
                        <div class="space-y-6">
                            <div class="group relative">
                                <div class="flex items-end justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Insignia de constancia</p>
                                        <p class="text-xs text-slate-500">{{ $metaPedidos }} pedidos para lograrlo</p>
                                    </div>
                                    <span class="text-sm font-black text-[#294395]">{{ $avancePedidos }}%</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-[#294395] to-[#4b68c9]" style="width: {{ $avancePedidos }}%"></div>
                                </div>
                            </div>
                            
                            <div class="group relative">
                                <div class="flex items-end justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Racha de puntos</p>
                                        <p class="text-xs text-slate-500">{{ $metaPuntos }} puntos para nuevo rango</p>
                                    </div>
                                    <span class="text-sm font-black text-[#e91e63]">{{ $avancePuntos }}%</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-[#e91e63] to-[#ff6b9d]" style="width: {{ $avancePuntos }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto pt-6 grid grid-cols-3 gap-4 border-t border-slate-100">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Pedidos</p>
                                <p class="mt-1 text-2xl font-black text-slate-900">{{ $pedidosTotales }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Puntos Netos</p>
                                <p class="mt-1 text-2xl font-black text-[#e91e63]">{{ $puntosTotal }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Unidades</p>
                                <p class="mt-1 text-2xl font-black text-[#294395]">{{ $unidadesTotales }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-0 ring-1 ring-slate-200/60 shadow-sm lg:col-span-5 flex flex-col overflow-hidden">
                    <div class="flex items-center justify-between p-6 pb-4 border-b border-slate-100">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Actividad Reciente</h2>
                        <a href="{{ url('/mis-pedidos') }}" class="text-xs font-bold uppercase tracking-wider text-[#294395] hover:text-[#e91e63] transition-colors">Ver historial</a>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto divide-y divide-slate-100 p-2">
                        @forelse($pedidosRecientes as $pedido)
                            <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-full bg-[#294395]/10 text-[#294395]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Pedido #{{ $pedido->codigo_pedido ?? $pedido->id }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $pedido->fecha ?? $pedido->created_at?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-[#e91e63]">{{ $pedido->total_puntos ?? 0 }} <span class="text-[10px] uppercase text-slate-400">pts</span></p>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">{{ ucfirst($pedido->estado ?? 'Revisión') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 flex flex-col items-center justify-center text-center h-full">
                                <div class="h-16 w-16 rounded-full bg-slate-50 flex items-center justify-center mb-4 ring-1 ring-slate-100">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Sin movimientos</p>
                                <p class="text-xs text-slate-500 mt-1 max-w-[200px]">Carga tu primer pedido para empezar a sumar puntos.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif

        @if($rolActual === 'admin')
            <section class="space-y-6">
                <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                                <svg class="w-6 h-6 text-[#294395]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Rendimiento Global
                            </h2>
                            <p class="mt-1 text-sm text-slate-500 font-medium">Indicadores comerciales del sistema.</p>
                        </div>

                        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-end gap-3 rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-100">
                            <label class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-1">Filtro</span>
                                <select name="filtro" class="h-10 rounded-xl border-0 bg-white px-4 text-sm font-bold text-slate-700 ring-1 ring-slate-200 focus:ring-2 focus:ring-[#294395] cursor-pointer">
                                    <option value="mes" @selected($filtroAdmin === 'mes')>Mensual</option>
                                    <option value="rango" @selected($filtroAdmin === 'rango')>Rango personalizado</option>
                                </select>
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-1">Mes</span>
                                <input name="mes" type="month" value="{{ $mesAdmin }}" class="h-10 rounded-xl border-0 bg-white px-4 text-sm font-bold text-slate-700 ring-1 ring-slate-200 focus:ring-2 focus:ring-[#294395]" />
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-1">Desde</span>
                                <input name="fecha_inicio" type="date" value="{{ $fechaInicioAdmin }}" class="h-10 rounded-xl border-0 bg-white px-4 text-sm font-bold text-slate-700 ring-1 ring-slate-200 focus:ring-2 focus:ring-[#294395]" />
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-1">Hasta</span>
                                <input name="fecha_fin" type="date" value="{{ $fechaFinAdmin }}" class="h-10 rounded-xl border-0 bg-white px-4 text-sm font-bold text-slate-700 ring-1 ring-slate-200 focus:ring-2 focus:ring-[#294395]" />
                            </label>

                            <button type="submit" class="h-10 rounded-xl bg-slate-900 px-6 text-xs font-bold uppercase tracking-wider text-white transition-colors hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                                Aplicar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm border-l-4 border-l-[#294395]">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Unidades Vendidas</p>
                        <p class="text-3xl font-black text-slate-900">{{ number_format($resumenAdmin['unidades_vendidas'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm border-l-4 border-l-indigo-400">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Total Catálogo Vendido</p>
                        <p class="text-3xl font-black text-slate-900"><span class="text-slate-400 text-2xl mr-1">$</span>{{ number_format($resumenAdmin['total_catalogo_vendido'], 2, ',', '.') }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm border-l-4 border-l-[#e91e63]">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Total Facturado</p>
                        <p class="text-3xl font-black text-[#e91e63]"><span class="text-[#e91e63]/50 text-2xl mr-1">$</span>{{ number_format($resumenAdmin['total_facturado'], 2, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    
                    <div class="rounded-3xl bg-white ring-1 ring-slate-200/60 shadow-sm overflow-hidden flex flex-col">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">Últimos Pedidos</h3>
                        </div>
                        <div class="overflow-x-auto p-2">
                            <table class="w-full text-left text-sm whitespace-nowrap">
                                <thead class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    <tr>
                                        <th class="px-4 py-3">Cód.</th>
                                        <th class="px-4 py-3">Fecha</th>
                                        <th class="px-4 py-3">Vendedora</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ultimosPedidosAdmin as $pedido)
                                        <tr class="hover:bg-slate-50 rounded-xl transition-colors">
                                            <td class="px-4 py-3 font-bold text-[#294395]">#{{ $pedido->codigo_pedido }}</td>
                                            <td class="px-4 py-3 text-slate-500">{{ $pedido->fecha ? Carbon::parse($pedido->fecha)->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-4 py-3 font-medium text-slate-700">{{ $pedido->vendedora?->name ?? 'Sin vendedora' }}</td>
                                            <td class="px-4 py-3 text-right font-black text-slate-900">${{ number_format((float) $pedido->total_a_pagar, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-4 py-8 text-center text-xs text-slate-400">No hay pedidos registrados en este período.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white ring-1 ring-slate-200/60 shadow-sm overflow-hidden flex flex-col">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">Nuevos Registros</h3>
                        </div>
                        <div class="overflow-x-auto p-2">
                            <table class="w-full text-left text-sm whitespace-nowrap">
                                <thead class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    <tr>
                                        <th class="px-4 py-3">Usuario</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3 text-right">Alta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ultimosRegistrosAdmin as $registro)
                                        <tr class="hover:bg-slate-50 rounded-xl transition-colors">
                                            <td class="px-4 py-3 font-bold text-slate-900">{{ $registro->name }}</td>
                                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $registro->email }}</td>
                                            <td class="px-4 py-3 text-right text-slate-500 text-xs">{{ $registro->created_at?->format('d/m/y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-4 py-8 text-center text-xs text-slate-400">No hay registros recientes.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm">
                        <h3 class="mb-5 text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-100 text-amber-600">🏆</span>
                            Top 5 Vendedoras
                        </h3>
                        <div class="space-y-3">
                            @forelse($topVendedorasAdmin as $index => $vendedora)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100/50">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-[10px] font-black text-slate-400 ring-1 ring-slate-200 shadow-sm">{{ $index + 1 }}</span>
                                        <span class="font-bold text-slate-700 text-sm">{{ $vendedora->name }}</span>
                                    </div>
                                    <span class="font-black text-[#e91e63] text-sm">${{ number_format((float) $vendedora->total_ventas, 2, ',', '.') }}</span>
                                </div>
                            @empty
                                <div class="p-4 text-center text-xs text-slate-400">Sin datos para mostrar.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm">
                        <h3 class="mb-5 text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">⭐</span>
                            Top 5 Líderes
                        </h3>
                        <div class="space-y-3">
                            @forelse($topLideresAdmin as $index => $lider)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100/50">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-[10px] font-black text-slate-400 ring-1 ring-slate-200 shadow-sm">{{ $index + 1 }}</span>
                                        <span class="font-bold text-slate-700 text-sm">{{ $lider->name }}</span>
                                    </div>
                                    <span class="font-black text-[#294395] text-sm">${{ number_format((float) $lider->total_ventas, 2, ',', '.') }}</span>
                                </div>
                            @empty
                                <div class="p-4 text-center text-xs text-slate-400">Sin datos para mostrar.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div x-data="dashboardAccesosRapidos()" x-init="init()" class="rounded-3xl bg-slate-900 p-6 shadow-sm">
                    <div class="mb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-white">Herramientas Rápidas</h3>
                            <p class="mt-1 text-xs text-slate-400">Arrastra las tarjetas para ordenar tu panel de control.</p>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-white/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <template x-for="(item, index) in items" :key="item.href + index">
                            <a
                                :href="item.href"
                                draggable="true"
                                @dragstart="dragStart(index)"
                                @dragover.prevent
                                @drop="drop(index)"
                                class="group flex cursor-move items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10 transition-all hover:bg-white hover:ring-white"
                            >
                                <span class="text-xs font-bold text-slate-300 group-hover:text-slate-900" x-text="item.label"></span>
                                <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                            </a>
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