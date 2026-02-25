<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
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
    <?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['xData' => '{
            tab: \'resumen\',
            tabs: [\'resumen\', \'equipo\', \'catalogo\'],
            go(direction) {
                const index = this.tabs.indexOf(this.tab);
                const target = (index + direction + this.tabs.length) % this.tabs.length;
                this.tab = this.tabs[target];
            }
        }','xCloak' => true,'class' => 'space-y-8']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-data' => '{
            tab: \'resumen\',
            tabs: [\'resumen\', \'equipo\', \'catalogo\'],
            go(direction) {
                const index = this.tabs.indexOf(this.tab);
                const target = (index + direction + this.tabs.length) % this.tabs.length;
                this.tab = this.tabs[target];
            }
        }','x-cloak' => true,'class' => 'space-y-8']); ?>
        <section class="relative overflow-hidden rounded-3xl <?php echo e($clases['panel']); ?> text-[#1f2758] shadow-sm <?php echo e($clases['panelRing']); ?>">
            <div class="relative px-6 py-10 sm:px-10 sm:py-12 lg:px-14 lg:py-16">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] <?php echo e($clases['chip']); ?> ring-1">
                            Panel dinámico · Rol <?php echo e(ucfirst($rolActual)); ?>

                        </div>
                        <div>
                            <h1 class="text-3xl font-semibold sm:text-4xl">
                                Hola, <?php echo e($usuario?->name); ?> 👋
                            </h1>
                            <p class="mt-3 text-base <?php echo e($clases['heroText']); ?>">
                                <?php echo e($config['titulo']); ?>. <?php echo e($config['subtitulo']); ?>

                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-sm font-semibold shadow-sm ring-1 <?php echo e($clases['chip']); ?>">
                                <span class="inline-block h-2 w-2 rounded-full <?php echo e($clases['indicador']); ?>"></span>
                                Rol activo: <?php echo e(ucfirst($rolActual)); ?>

                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-sm font-semibold shadow-sm ring-1 <?php echo e($clases['chip']); ?>">
                                🎯 Objetivo de pedidos: <?php echo e($metaPedidos); ?> · Puntos: <?php echo e($metaPuntos); ?>

                            </span>
                        </div>
                    </div>
                    <div class="grid w-full max-w-xl grid-cols-2 gap-4 rounded-2xl bg-white/80 p-4 shadow-sm ring-1 <?php echo e($clases['panelRing']); ?> md:w-auto">
                        <div class="flex flex-col gap-1 rounded-xl <?php echo e($clases['panelSuave']); ?> p-4 ring-1">
                            <span class="text-sm font-semibold">Progreso de pedidos</span>
                            <span class="text-2xl font-bold"><?php echo e($avancePedidos); ?>%</span>
                            <div class="mt-1 h-2 overflow-hidden rounded-full <?php echo e($clases['progresoFondo']); ?>">
                                <div class="h-full <?php echo e($clases['progresoColor']); ?>" style="width: <?php echo e($avancePedidos); ?>%"></div>
                            </div>
                            <p class="text-xs <?php echo e($clases['textoSuave']); ?>">Llevás <?php echo e($pedidosTotales); ?> de <?php echo e($metaPedidos); ?> pedidos meta.</p>
                        </div>
                        <div class="flex flex-col gap-1 rounded-xl <?php echo e($clases['panelSuave']); ?> p-4 ring-1">
                            <span class="text-sm font-semibold">Puntos acumulados</span>
                            <span class="text-2xl font-bold"><?php echo e($puntosTotal); ?></span>
                            <div class="mt-1 h-2 overflow-hidden rounded-full <?php echo e($clases['progresoFondo']); ?>">
                                <div class="h-full <?php echo e($clases['progresoColor']); ?>" style="width: <?php echo e($avancePuntos); ?>%"></div>
                            </div>
                            <p class="text-xs <?php echo e($clases['textoSuave']); ?>">Objetivo sugerido: <?php echo e($metaPuntos); ?> pts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <?php $__currentLoopData = $config['acciones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a
                    href="<?php echo e(url($accion['ruta'])); ?>"
                    class="group relative overflow-hidden rounded-2xl border bg-white p-5 shadow-sm transition hover:-translate-y-1 <?php echo e($clases['ctaBorder']); ?> hover:shadow-lg"
                >
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full <?php echo e($clases['icono']); ?> ring-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75H5.25" />
                        </svg>
                    </span>
                    <h3 class="mt-4 text-lg font-semibold text-[#1f2758]"><?php echo e($accion['titulo']); ?></h3>
                    <p class="mt-2 text-sm text-[#3d4c7c]"><?php echo e($accion['descripcion']); ?></p>
                    <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold <?php echo e($clases['ctaTexto']); ?>">
                        Abrir
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border <?php echo e($clases['borde']); ?> bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#1f2758]">Progreso e insignias</h2>
                        <p class="text-sm text-[#3d4c7c]">La barra sube a medida que sumás pedidos y puntos.</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($clases['badge']); ?> ring-1">Gamificación</span>
                </div>
                <div class="mt-6 space-y-5">
                    <div class="rounded-xl <?php echo e($clases['panel']); ?> p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2758]">Insignia de constancia</p>
                                <p class="text-xs text-[#3d4c7c]">Completá <?php echo e($metaPedidos); ?> pedidos para lograr la insignia.</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold <?php echo e($clases['textoFuerte']); ?> ring-1 <?php echo e($clases['borde']); ?>"><?php echo e($avancePedidos); ?>%</span>
                        </div>
                        <div class="mt-3 h-2 rounded-full bg-white/70">
                            <div class="h-full rounded-full <?php echo e($clases['progresoColor']); ?>" style="width: <?php echo e($avancePedidos); ?>%"></div>
                        </div>
                    </div>
                    <div class="rounded-xl <?php echo e($clases['panel']); ?> p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2758]">Racha de puntos</p>
                                <p class="text-xs text-[#3d4c7c]">Sumá <?php echo e($metaPuntos); ?> puntos para desbloquear el siguiente rango.</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold <?php echo e($clases['textoFuerte']); ?> ring-1 <?php echo e($clases['borde']); ?>"><?php echo e($avancePuntos); ?>%</span>
                        </div>
                        <div class="mt-3 h-2 rounded-full bg-white/70">
                            <div class="h-full rounded-full <?php echo e($clases['progresoColor']); ?>" style="width: <?php echo e($avancePuntos); ?>%"></div>
                        </div>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-semibold text-slate-600">Pedidos totales</p>
                            <p class="text-2xl font-bold text-slate-900"><?php echo e($pedidosTotales); ?></p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-semibold text-slate-600">Puntos</p>
                            <p class="text-2xl font-bold text-slate-900"><?php echo e($puntosTotal); ?></p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                            <p class="text-xs font-semibold text-slate-600">Unidades</p>
                            <p class="text-2xl font-bold text-slate-900"><?php echo e($unidadesTotales); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border <?php echo e($clases['borde']); ?> bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#1f2758]">Pedidos recientes</h2>
                        <p class="text-sm text-[#3d4c7c]">Últimos movimientos relevantes para tu rol.</p>
                    </div>
                    <a href="<?php echo e(url('/mis-pedidos')); ?>" class="text-sm font-semibold <?php echo e($clases['link']); ?>">Ver todos</a>
                </div>
                <div class="mt-4 divide-y divide-slate-100 border border-slate-100 rounded-xl">
                    <?php $__empty_1 = true; $__currentLoopData = $pedidosRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-[#1f2758]">Pedido #<?php echo e($pedido->codigo_pedido ?? $pedido->id); ?></p>
                                <p class="text-xs text-[#3d4c7c]"><?php echo e($pedido->fecha ?? $pedido->created_at?->format('d/m/Y') ?? 'Fecha sin registrar'); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold <?php echo e($clases['textoFuerte']); ?>"><?php echo e($pedido->total_puntos ?? 0); ?> pts</p>
                                <p class="text-xs text-[#3d4c7c]"><?php echo e(ucfirst($pedido->estado ?? 'en revisión')); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-4 py-6 text-center text-sm text-[#3d4c7c]">
                            Todavía no hay pedidos cargados para este rol. Creá el primero y desbloqueá tu progreso.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal08db35abc15b88d7e891883ef0dd6bed)): ?>
<?php $attributes = $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed; ?>
<?php unset($__attributesOriginal08db35abc15b88d7e891883ef0dd6bed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal08db35abc15b88d7e891883ef0dd6bed)): ?>
<?php $component = $__componentOriginal08db35abc15b88d7e891883ef0dd6bed; ?>
<?php unset($__componentOriginal08db35abc15b88d7e891883ef0dd6bed); ?>
<?php endif; ?>
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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/dashboard/index.blade.php ENDPATH**/ ?>