<?php

use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use App\Models\RevendedoraPunto;
use App\Models\RevendedoraRacha;
use App\Models\TiendaPremio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$usuario = Auth::user();
$rolesOrdenados = ['admin', 'coordinadora', 'lider', 'vendedora'];
$rolActual = collect($rolesOrdenados)->first(fn ($rol) => $usuario?->hasRole($rol)) ?? 'vendedora';

$metricasBase = MetricaLiderCampana::query()->with(['lider.zona', 'lider.departamento', 'cierreCampana']);

if ($rolActual === 'lider') {
    $metricasBase->where('lider_id', $usuario?->id);
}

if ($rolActual === 'coordinadora') {
    $metricasBase->whereHas('lider', fn ($query) => $query->where('coordinadora_id', $usuario?->id));
}

$metricas = $metricasBase->orderByDesc('cierre_campana_id')->get();
$ultimaMetricaLider = $rolActual === 'lider' ? $metricas->first() : null;

$saldoPuntos = 0;
$rachaActual = 0;
$pedidosConfirmados = 0;
$premiosCanjeables = collect();

if ($rolActual === 'vendedora' && $usuario) {
    $saldoPuntos = (int) RevendedoraPunto::query()->where('user_id', $usuario->id)->sum('puntos');

    $rachaActual = (int) RevendedoraRacha::query()
        ->where('user_id', $usuario->id)
        ->latest('id')
        ->value('racha_actual');

    $pedidosConfirmados = (int) Pedido::query()
        ->where('vendedora_id', $usuario->id)
        ->whereIn('estado', ['Confirmado', 'Facturado', 'Entregado'])
        ->count();

    $premiosCanjeables = TiendaPremio::query()
        ->where('estado', '!=', 'inactivo')
        ->where('stock', '>', 0)
        ->where('puntos_requeridos', '<=', $saldoPuntos)
        ->orderBy('puntos_requeridos')
        ->limit(4)
        ->get(['id', 'nombre', 'puntos_requeridos', 'stock']);
}

$avanceRacha = min(100, (int) round(($pedidosConfirmados % 3) / 3 * 100));

$resumenLider = [
    'actividad' => [
        'cumple' => (int) $metricas->where('actividad_ok', true)->count(),
        'total' => (int) $metricas->count(),
    ],
    'retencion' => [
        'cumple' => (int) $metricas->where('retencion_ok', true)->count(),
        'total' => (int) $metricas->count(),
    ],
    'altas' => (int) $metricas->sum(fn ($metrica) => (int) data_get($metrica->datos, 'altas_mes', 0)),
    'cobranzas_ok' => (int) $metricas->where('cobranzas_ok', true)->count(),
    'crecimiento_ok' => (int) $metricas->where('crecimiento_ok', true)->count(),
    'reparto' => (float) $metricas->sum('monto_reparto_total'),
    'plus' => (float) $metricas->sum('premio_plus_crecimiento'),
    'unidades' => (int) $metricas->sum(fn ($metrica) => (int) data_get($metrica->datos, 'unidades', 0)),
];

$consolidadoTerritorial = collect();
$alertasLideres = collect();

if (in_array($rolActual, ['admin', 'coordinadora'], true)) {
    $consolidadoTerritorial = $metricas
        ->groupBy(function ($metrica) {
            $zona = $metrica->lider?->zona?->nombre ?? 'Sin zona';
            $depto = $metrica->lider?->departamento?->nombre ?? 'Sin departamento';

            return $zona . '||' . $depto;
        })
        ->map(function ($items, $clave) {
            [$zona, $departamento] = explode('||', $clave);

            return [
                'zona' => $zona,
                'departamento' => $departamento,
                'lideres' => $items->pluck('lider_id')->unique()->count(),
                'actividad_promedio' => round($items->avg(fn ($i) => $i->actividad_ok ? 100 : 0), 1),
                'retencion_promedio' => round($items->avg(fn ($i) => $i->retencion_ok ? 100 : 0), 1),
                'premio_total' => (float) $items->sum('premio_total'),
            ];
        })
        ->values()
        ->sortByDesc('premio_total');

    $alertasLideres = $metricas
        ->filter(fn ($metrica) => ! $metrica->actividad_ok || ! $metrica->retencion_ok)
        ->map(fn ($metrica) => [
            'lider' => $metrica->lider?->name ?? 'Sin líder',
            'zona' => $metrica->lider?->zona?->nombre ?? 'Sin zona',
            'actividad_ok' => (bool) $metrica->actividad_ok,
            'retencion_ok' => (bool) $metrica->retencion_ok,
            'cierre' => $metrica->cierreCampana?->codigo ?? 'Sin cierre',
        ])
        ->take(8)
        ->values();
}

$widgetsOperativos = [
    ['titulo' => 'Marketplace', 'ruta' => '/marketplace', 'roles' => ['vendedora', 'lider', 'coordinadora', 'admin']],
    ['titulo' => 'Resumen líderes', 'ruta' => '/resumen-lideres', 'roles' => ['coordinadora', 'admin']],
    ['titulo' => 'Zona coordinadora', 'ruta' => '/zona-coordinadora', 'roles' => ['coordinadora', 'admin']],
    ['titulo' => 'Reglas de puntaje', 'ruta' => '/puntaje-reglas', 'roles' => ['admin']],
    ['titulo' => 'Mis pedidos', 'ruta' => '/mis-pedidos', 'roles' => ['vendedora', 'lider', 'coordinadora', 'admin']],
];
?>

<x-app.container class="space-y-6 pb-12">
    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60">
        <h1 class="text-2xl font-extrabold text-slate-900">Panel unificado Alma Mía</h1>
        <p class="mt-1 text-sm text-slate-500">Vista consolidada por rol: {{ ucfirst($rolActual) }}.</p>
    </section>

    @if($rolActual === 'vendedora')
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Saldo de puntos</p><p class="text-2xl font-black text-slate-900">{{ $saldoPuntos }}</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Racha actual</p><p class="text-2xl font-black text-slate-900">{{ $rachaActual }} cierres</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200 md:col-span-2">
                <div class="flex items-center justify-between text-xs uppercase text-slate-400"><span>Progreso a 3 pedidos</span><span>{{ $pedidosConfirmados % 3 }}/3</span></div>
                <div class="mt-3 h-3 rounded-full bg-slate-100"><div class="h-3 rounded-full bg-indigo-500" style="width: {{ $avanceRacha }}%"></div></div>
            </article>
        </section>

        <section class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-600">Próximos premios canjeables</h2>
                <a href="{{ url('/marketplace') }}" class="text-xs font-bold text-indigo-600">Ir al marketplace</a>
            </div>
            <div class="mt-4 grid gap-3 md:grid-cols-2">
                @forelse($premiosCanjeables as $premio)
                    <article class="rounded-xl border border-slate-200 p-3">
                        <p class="font-bold text-slate-800">{{ $premio->nombre }}</p>
                        <p class="text-xs text-slate-500">{{ $premio->puntos_requeridos }} pts · Stock {{ $premio->stock }}</p>
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Todavía no hay premios disponibles para tu saldo actual.</p>
                @endforelse
            </div>
        </section>
    @endif

    @if($rolActual === 'lider')
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Actividad</p><p class="text-2xl font-black text-slate-900">{{ $resumenLider['actividad']['cumple'] }}/{{ $resumenLider['actividad']['total'] }}</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Retención</p><p class="text-2xl font-black text-slate-900">{{ $resumenLider['retencion']['cumple'] }}/{{ $resumenLider['retencion']['total'] }}</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Altas</p><p class="text-2xl font-black text-slate-900">{{ $resumenLider['altas'] }}</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Cobranzas OK</p><p class="text-2xl font-black text-slate-900">{{ $resumenLider['cobranzas_ok'] }}</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Crecimiento</p><p class="text-2xl font-black text-slate-900">{{ $resumenLider['crecimiento_ok'] }}</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Reparto</p><p class="text-2xl font-black text-slate-900">${{ number_format($resumenLider['reparto'], 0, ',', '.') }}</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Plus</p><p class="text-2xl font-black text-slate-900">${{ number_format($resumenLider['plus'], 0, ',', '.') }}</p></article>
            <article class="rounded-2xl bg-white p-5 ring-1 ring-slate-200"><p class="text-xs uppercase text-slate-400">Unidades</p><p class="text-2xl font-black text-slate-900">{{ $resumenLider['unidades'] }}</p></article>
        </section>
    @endif

    @if(in_array($rolActual, ['coordinadora', 'admin'], true))
        <section class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-600">Consolidado por zona/departamento</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="text-left text-xs uppercase text-slate-400"><th class="py-2">Zona</th><th>Departamento</th><th>Líderes</th><th>Actividad</th><th>Retención</th><th>Premio total</th></tr></thead>
                    <tbody>
                    @forelse($consolidadoTerritorial as $fila)
                        <tr class="border-t border-slate-100"><td class="py-2">{{ $fila['zona'] }}</td><td>{{ $fila['departamento'] }}</td><td>{{ $fila['lideres'] }}</td><td>{{ $fila['actividad_promedio'] }}%</td><td>{{ $fila['retencion_promedio'] }}%</td><td>${{ number_format($fila['premio_total'], 0, ',', '.') }}</td></tr>
                    @empty
                        <tr><td colspan="6" class="py-4 text-slate-500">Sin métricas cargadas para el período.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl bg-white p-5 ring-1 ring-rose-200">
            <h2 class="text-sm font-bold uppercase tracking-wider text-rose-600">Alertas de actividad/retención</h2>
            <div class="mt-3 space-y-2">
                @forelse($alertasLideres as $alerta)
                    <article class="rounded-xl border border-rose-100 bg-rose-50/60 p-3 text-sm text-rose-900">
                        {{ $alerta['lider'] }} · {{ $alerta['zona'] }} · {{ $alerta['cierre'] }}
                        <span class="ml-2 text-xs">Actividad: {{ $alerta['actividad_ok'] ? 'OK' : 'Caída' }} · Retención: {{ $alerta['retencion_ok'] ? 'OK' : 'Caída' }}</span>
                    </article>
                @empty
                    <p class="text-sm text-slate-500">No se detectaron alertas en el último consolidado.</p>
                @endforelse
            </div>
        </section>
    @endif

    <section class="rounded-2xl bg-white p-5 ring-1 ring-slate-200">
        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-600">Widgets operativos</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-3">
            @foreach($widgetsOperativos as $widget)
                @if(in_array($rolActual, $widget['roles'], true))
                    <a href="{{ url($widget['ruta']) }}" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-700">
                        {{ $widget['titulo'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </section>
</x-app.container>
