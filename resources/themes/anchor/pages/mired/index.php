<?php

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('mired');

new class extends Component {
    public array $red = [];

    public string $rolVista = 'vendedora';

    public string $mesActual = '';

    public function mount(): void
    {
        $this->mesActual = Carbon::now()->translatedFormat('F Y');
        $this->construirRed();
    }

    protected function construirRed(): void
    {
        $usuario = Auth::user();

        if (! $usuario) {
            $this->red = [];

            return;
        }

        $this->rolVista = $this->detectarRol($usuario);

        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $resumenPedidos = Pedido::query()
            ->selectRaw('vendedora_id, COUNT(*) as pedidos, COALESCE(SUM(total_a_pagar), 0) as monto')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->whereNotNull('vendedora_id')
            ->groupBy('vendedora_id')
            ->get()
            ->keyBy('vendedora_id');

        if ($this->rolVista === 'admin') {
            $this->red = $this->redDeAdmin($resumenPedidos);

            return;
        }

        if ($this->rolVista === 'coordinadora') {
            $this->red = $this->redDeCoordinadora($usuario, $resumenPedidos);

            return;
        }

        if ($this->rolVista === 'lider') {
            $this->red = $this->redDeLider($usuario, $resumenPedidos);

            return;
        }

        $this->red = [
            'titulo' => 'Mi red',
            'descripcion' => 'Tu rol no tiene red comercial asignada para visualizar.',
            'nodos' => [],
        ];
    }

    protected function detectarRol(User $usuario): string
    {
        foreach (['admin', 'coordinadora', 'lider', 'vendedora'] as $rol) {
            if ($usuario->hasRole($rol)) {
                return $rol;
            }
        }

        return 'vendedora';
    }

    protected function redDeAdmin(Collection $resumenPedidos): array
    {
        $coordinadoras = User::query()
            ->role('coordinadora')
            ->orderBy('name')
            ->get(['id', 'name', 'avatar']);

        $lideres = User::query()
            ->role('lider')
            ->orderBy('name')
            ->get(['id', 'name', 'avatar', 'coordinadora_id'])
            ->keyBy('id');

        $pivot = User::query()
            ->from('coordinadora_lider')
            ->get(['coordinadora_id', 'lider_id'])
            ->groupBy('coordinadora_id');

        $vendedoras = User::query()
            ->role('vendedora')
            ->orderBy('name')
            ->get(['id', 'name', 'avatar', 'lider_id'])
            ->groupBy('lider_id');

        $nodos = $coordinadoras->map(function (User $coordinadora) use ($lideres, $pivot, $vendedoras, $resumenPedidos) {
            $idsPorPivot = collect($pivot->get($coordinadora->id))->pluck('lider_id');
            $idsPorCampo = $lideres->filter(fn (User $lider) => (int) $lider->coordinadora_id === (int) $coordinadora->id)->keys();

            $lideresAsignados = $idsPorPivot
                ->merge($idsPorCampo)
                ->unique()
                ->filter(fn ($id) => $lideres->has($id))
                ->values()
                ->map(function ($liderId) use ($lideres, $vendedoras, $resumenPedidos) {
                    $lider = $lideres->get($liderId);
                    $nodosVendedoras = $this->armarVendedoras($vendedoras->get($liderId, collect()), $resumenPedidos);

                    return [
                        'id' => $lider->id,
                        'nombre' => $lider->name,
                        'avatar' => $lider->avatar,
                        'iniciales' => $this->iniciales($lider->name),
                        'tooltip' => $this->resumenTooltip($nodosVendedoras),
                        'vendedoras' => $nodosVendedoras,
                    ];
                });

            return [
                'id' => $coordinadora->id,
                'nombre' => $coordinadora->name,
                'avatar' => $coordinadora->avatar,
                'iniciales' => $this->iniciales($coordinadora->name),
                'tooltip' => $this->resumenTooltip($lideresAsignados->flatMap(fn ($lider) => $lider['vendedoras'])),
                'lideres' => $lideresAsignados,
            ];
        })->values()->all();

        return [
            'titulo' => 'Red completa',
            'descripcion' => 'Vista general de coordinadoras, líderes y vendedoras del mes en curso.',
            'nodos' => $nodos,
        ];
    }

    protected function redDeCoordinadora(User $coordinadora, Collection $resumenPedidos): array
    {
        $lideres = $coordinadora->lideres()->orderBy('name')->get(['users.id', 'users.name', 'users.avatar']);

        $lideresPorCampo = User::query()
            ->role('lider')
            ->where('coordinadora_id', $coordinadora->id)
            ->orderBy('name')
            ->get(['id', 'name', 'avatar']);

        $todosLosLideres = $lideres
            ->merge($lideresPorCampo)
            ->unique('id')
            ->values();

        $vendedoras = User::query()
            ->role('vendedora')
            ->whereIn('lider_id', $todosLosLideres->pluck('id'))
            ->orderBy('name')
            ->get(['id', 'name', 'avatar', 'lider_id'])
            ->groupBy('lider_id');

        $nodosLideres = $todosLosLideres->map(function (User $lider) use ($vendedoras, $resumenPedidos) {
            $nodosVendedoras = $this->armarVendedoras($vendedoras->get($lider->id, collect()), $resumenPedidos);

            return [
                'id' => $lider->id,
                'nombre' => $lider->name,
                'avatar' => $lider->avatar,
                'iniciales' => $this->iniciales($lider->name),
                'tooltip' => $this->resumenTooltip($nodosVendedoras),
                'vendedoras' => $nodosVendedoras,
            ];
        })->values()->all();

        return [
            'titulo' => 'Mi red de coordinación',
            'descripcion' => 'Líderes y vendedoras asignadas a tu coordinación.',
            'nodo_principal' => [
                'id' => $coordinadora->id,
                'nombre' => $coordinadora->name,
                'avatar' => $coordinadora->avatar,
                'iniciales' => $this->iniciales($coordinadora->name),
                'tooltip' => $this->resumenTooltip(collect($nodosLideres)->flatMap(fn ($lider) => $lider['vendedoras'])),
            ],
            'nodos' => $nodosLideres,
        ];
    }

    protected function redDeLider(User $lider, Collection $resumenPedidos): array
    {
        $vendedoras = User::query()
            ->role('vendedora')
            ->where('lider_id', $lider->id)
            ->orderBy('name')
            ->get(['id', 'name', 'avatar']);

        $nodosVendedoras = $this->armarVendedoras($vendedoras, $resumenPedidos);

        return [
            'titulo' => 'Mi red de ventas',
            'descripcion' => 'Vendedoras asignadas a tu liderazgo.',
            'nodo_principal' => [
                'id' => $lider->id,
                'nombre' => $lider->name,
                'avatar' => $lider->avatar,
                'iniciales' => $this->iniciales($lider->name),
                'tooltip' => $this->resumenTooltip($nodosVendedoras),
            ],
            'nodos' => $nodosVendedoras,
        ];
    }

    protected function armarVendedoras(Collection $vendedoras, Collection $resumenPedidos): array
    {
        return $vendedoras->map(function (User $vendedora) use ($resumenPedidos) {
            $resumen = $resumenPedidos->get($vendedora->id);
            $monto = (float) ($resumen->monto ?? 0);
            $pedidos = (int) ($resumen->pedidos ?? 0);

            return [
                'id' => $vendedora->id,
                'nombre' => $vendedora->name,
                'avatar' => $vendedora->avatar,
                'iniciales' => $this->iniciales($vendedora->name),
                'pedidos' => $pedidos,
                'monto' => $monto,
                'tiene_pedido' => $pedidos > 0,
                'resumen_texto' => $pedidos > 0
                    ? "{$pedidos} pedido(s) por $" . number_format($monto, 2, ',', '.')
                    : 'Sin pedidos en el mes',
            ];
        })->values()->all();
    }

    protected function resumenTooltip(Collection|array $vendedoras): array
    {
        $items = collect($vendedoras)->values();

        return [
            'total' => $items->count(),
            'con_pedido' => $items->where('tiene_pedido', true)->count(),
            'sin_pedido' => $items->where('tiene_pedido', false)->count(),
            'monto_total' => (float) $items->sum('monto'),
            'vendedoras' => $items->map(fn ($vendedora) => [
                'nombre' => $vendedora['nombre'],
                'tiene_pedido' => (bool) $vendedora['tiene_pedido'],
                'resumen_texto' => $vendedora['resumen_texto'],
            ])->all(),
        ];
    }

    protected function iniciales(?string $nombre): string
    {
        $partes = collect(explode(' ', trim((string) $nombre)))
            ->filter()
            ->take(2)
            ->map(fn (string $parte) => mb_strtoupper(mb_substr($parte, 0, 1)));

        return $partes->isNotEmpty() ? $partes->implode('') : 'NA';
    }
};
?>

<x-layouts.app>
    @volt('mired')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Mi red"
                :description="'Visualización de red para el rol ' . ucfirst($rolVista) . ' · ' . ucfirst($mesActual)"
                :border="false"
            />

            <div class="rounded-2xl border border-slate-200 bg-white/90 p-5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/70">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-50">{{ $red['titulo'] ?? 'Mi red' }}</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $red['descripcion'] ?? '' }}</p>
                </div>

                @if($rolVista === 'admin')
                    <div class="space-y-6">
                        @forelse(($red['nodos'] ?? []) as $coordinadora)
                            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                                <div class="group relative inline-flex items-center gap-3">
                                    <div class="h-12 w-12 overflow-hidden rounded-full bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-900/40">
                                        @if(!empty($coordinadora['avatar']))
                                            <img src="{{ $coordinadora['avatar'] }}" alt="{{ $coordinadora['nombre'] }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-sm font-bold">{{ $coordinadora['iniciales'] }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $coordinadora['nombre'] }}</p>
                                        <p class="text-xs text-slate-500">Coordinadora</p>
                                    </div>
                                    <div class="pointer-events-none absolute left-0 top-full z-20 hidden w-96 rounded-lg border border-slate-200 bg-white p-3 text-xs shadow-lg group-hover:block dark:border-slate-700 dark:bg-slate-900">
                                        <p class="font-semibold text-slate-900 dark:text-slate-100">Resumen de vendedoras</p>
                                        <p class="mt-1 text-slate-600 dark:text-slate-300">Total: {{ $coordinadora['tooltip']['total'] }} · Con pedido: {{ $coordinadora['tooltip']['con_pedido'] }} · Sin pedido: {{ $coordinadora['tooltip']['sin_pedido'] }} · Monto: ${{ number_format($coordinadora['tooltip']['monto_total'], 2, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 space-y-4 border-l-2 border-dashed border-indigo-200 pl-4 dark:border-indigo-900/50">
                                    @forelse($coordinadora['lideres'] as $lider)
                                        <div>
                                            <div class="group relative inline-flex items-center gap-3">
                                                <div class="h-10 w-10 overflow-hidden rounded-full bg-blue-600 text-white ring-2 ring-blue-100 dark:ring-blue-900/40">
                                                    @if(!empty($lider['avatar']))
                                                        <img src="{{ $lider['avatar'] }}" alt="{{ $lider['nombre'] }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-xs font-bold">{{ $lider['iniciales'] }}</div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $lider['nombre'] }}</p>
                                                    <p class="text-xs text-slate-500">Líder</p>
                                                </div>
                                                <div class="pointer-events-none absolute left-0 top-full z-20 hidden w-96 rounded-lg border border-slate-200 bg-white p-3 text-xs shadow-lg group-hover:block dark:border-slate-700 dark:bg-slate-900">
                                                    <p class="font-semibold text-slate-900 dark:text-slate-100">Resumen de vendedoras</p>
                                                    <p class="mt-1 text-slate-600 dark:text-slate-300">Total: {{ $lider['tooltip']['total'] }} · Con pedido: {{ $lider['tooltip']['con_pedido'] }} · Sin pedido: {{ $lider['tooltip']['sin_pedido'] }} · Monto: ${{ number_format($lider['tooltip']['monto_total'], 2, ',', '.') }}</p>
                                                    <ul class="mt-2 space-y-1">
                                                        @foreach($lider['tooltip']['vendedoras'] as $fila)
                                                            <li class="flex items-center gap-2">
                                                                <span class="inline-flex h-2.5 w-2.5 rounded-full {{ $fila['tiene_pedido'] ? 'bg-emerald-500' : 'bg-orange-500' }}"></span>
                                                                <span class="text-slate-700 dark:text-slate-200">{{ $fila['nombre'] }} · {{ $fila['resumen_texto'] }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="mt-3 flex flex-wrap gap-2 border-l border-slate-300 pl-3 dark:border-slate-700">
                                                @forelse($lider['vendedoras'] as $vendedora)
                                                    <div class="group relative inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs {{ $vendedora['tiene_pedido'] ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200' : 'border-orange-200 bg-orange-50 text-orange-700 dark:border-orange-800 dark:bg-orange-950/40 dark:text-orange-200' }}">
                                                        <div class="h-6 w-6 overflow-hidden rounded-full bg-white/70">
                                                            @if(!empty($vendedora['avatar']))
                                                                <img src="{{ $vendedora['avatar'] }}" alt="{{ $vendedora['nombre'] }}" class="h-full w-full object-cover">
                                                            @else
                                                                <div class="flex h-full w-full items-center justify-center text-[10px] font-bold text-slate-700">{{ $vendedora['iniciales'] }}</div>
                                                            @endif
                                                        </div>
                                                        <span>{{ $vendedora['nombre'] }}</span>
                                                        <div class="pointer-events-none absolute left-0 top-full z-20 hidden w-72 rounded-lg border border-slate-200 bg-white p-3 text-xs shadow-lg group-hover:block dark:border-slate-700 dark:bg-slate-900">
                                                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $vendedora['nombre'] }}</p>
                                                            <p class="mt-1 text-slate-600 dark:text-slate-300">{{ $vendedora['resumen_texto'] }}</p>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-xs text-slate-500">Sin vendedoras asignadas.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500">Sin líderes asignadas.</p>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No hay coordinadoras con red disponible.</p>
                        @endforelse
                    </div>
                @elseif($rolVista === 'coordinadora')
                    <div class="space-y-4">
                        @if(!empty($red['nodo_principal']))
                            <div class="group relative inline-flex items-center gap-3 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 dark:border-indigo-800 dark:bg-indigo-950/40">
                                <div class="h-12 w-12 overflow-hidden rounded-full bg-indigo-600 text-white">
                                    @if(!empty($red['nodo_principal']['avatar']))
                                        <img src="{{ $red['nodo_principal']['avatar'] }}" alt="{{ $red['nodo_principal']['nombre'] }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-sm font-bold">{{ $red['nodo_principal']['iniciales'] }}</div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $red['nodo_principal']['nombre'] }}</p>
                                    <p class="text-xs text-slate-500">Coordinadora</p>
                                </div>
                                <div class="pointer-events-none absolute left-0 top-full z-20 hidden w-96 rounded-lg border border-slate-200 bg-white p-3 text-xs shadow-lg group-hover:block dark:border-slate-700 dark:bg-slate-900">
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">Resumen de vendedoras</p>
                                    <p class="mt-1 text-slate-600 dark:text-slate-300">Total: {{ $red['nodo_principal']['tooltip']['total'] }} · Con pedido: {{ $red['nodo_principal']['tooltip']['con_pedido'] }} · Sin pedido: {{ $red['nodo_principal']['tooltip']['sin_pedido'] }} · Monto: ${{ number_format($red['nodo_principal']['tooltip']['monto_total'], 2, ',', '.') }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-4 border-l-2 border-dashed border-indigo-200 pl-4 dark:border-indigo-900/50">
                            @forelse(($red['nodos'] ?? []) as $lider)
                                <div>
                                    <div class="group relative inline-flex items-center gap-3">
                                        <div class="h-10 w-10 overflow-hidden rounded-full bg-blue-600 text-white">
                                            @if(!empty($lider['avatar']))
                                                <img src="{{ $lider['avatar'] }}" alt="{{ $lider['nombre'] }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-xs font-bold">{{ $lider['iniciales'] }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $lider['nombre'] }}</p>
                                            <p class="text-xs text-slate-500">Líder</p>
                                        </div>
                                        <div class="pointer-events-none absolute left-0 top-full z-20 hidden w-96 rounded-lg border border-slate-200 bg-white p-3 text-xs shadow-lg group-hover:block dark:border-slate-700 dark:bg-slate-900">
                                            <p class="font-semibold text-slate-900 dark:text-slate-100">Resumen de vendedoras</p>
                                            <ul class="mt-2 space-y-1">
                                                @foreach($lider['tooltip']['vendedoras'] as $fila)
                                                    <li class="flex items-center gap-2">
                                                        <span class="inline-flex h-2.5 w-2.5 rounded-full {{ $fila['tiene_pedido'] ? 'bg-emerald-500' : 'bg-orange-500' }}"></span>
                                                        <span class="text-slate-700 dark:text-slate-200">{{ $fila['nombre'] }} · {{ $fila['resumen_texto'] }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2 border-l border-slate-300 pl-3 dark:border-slate-700">
                                        @forelse($lider['vendedoras'] as $vendedora)
                                            <div class="group relative inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs {{ $vendedora['tiene_pedido'] ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200' : 'border-orange-200 bg-orange-50 text-orange-700 dark:border-orange-800 dark:bg-orange-950/40 dark:text-orange-200' }}">
                                                <div class="h-6 w-6 overflow-hidden rounded-full bg-white/70">
                                                    @if(!empty($vendedora['avatar']))
                                                        <img src="{{ $vendedora['avatar'] }}" alt="{{ $vendedora['nombre'] }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-[10px] font-bold text-slate-700">{{ $vendedora['iniciales'] }}</div>
                                                    @endif
                                                </div>
                                                <span>{{ $vendedora['nombre'] }}</span>
                                                <div class="pointer-events-none absolute left-0 top-full z-20 hidden w-72 rounded-lg border border-slate-200 bg-white p-3 text-xs shadow-lg group-hover:block dark:border-slate-700 dark:bg-slate-900">
                                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $vendedora['nombre'] }}</p>
                                                    <p class="mt-1 text-slate-600 dark:text-slate-300">{{ $vendedora['resumen_texto'] }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-500">Sin vendedoras asignadas.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No tenés líderes asignadas.</p>
                            @endforelse
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @if(!empty($red['nodo_principal']))
                            <div class="group relative inline-flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 dark:border-blue-800 dark:bg-blue-950/40">
                                <div class="h-12 w-12 overflow-hidden rounded-full bg-blue-600 text-white">
                                    @if(!empty($red['nodo_principal']['avatar']))
                                        <img src="{{ $red['nodo_principal']['avatar'] }}" alt="{{ $red['nodo_principal']['nombre'] }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-sm font-bold">{{ $red['nodo_principal']['iniciales'] }}</div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $red['nodo_principal']['nombre'] }}</p>
                                    <p class="text-xs text-slate-500">Líder</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-2 border-l-2 border-dashed border-blue-200 pl-4 dark:border-blue-900/50">
                            @forelse(($red['nodos'] ?? []) as $vendedora)
                                <div class="group relative inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs {{ $vendedora['tiene_pedido'] ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200' : 'border-orange-200 bg-orange-50 text-orange-700 dark:border-orange-800 dark:bg-orange-950/40 dark:text-orange-200' }}">
                                    <div class="h-6 w-6 overflow-hidden rounded-full bg-white/70">
                                        @if(!empty($vendedora['avatar']))
                                            <img src="{{ $vendedora['avatar'] }}" alt="{{ $vendedora['nombre'] }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[10px] font-bold text-slate-700">{{ $vendedora['iniciales'] }}</div>
                                        @endif
                                    </div>
                                    <span>{{ $vendedora['nombre'] }}</span>
                                    <div class="pointer-events-none absolute left-0 top-full z-20 hidden w-72 rounded-lg border border-slate-200 bg-white p-3 text-xs shadow-lg group-hover:block dark:border-slate-700 dark:bg-slate-900">
                                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $vendedora['nombre'] }}</p>
                                        <p class="mt-1 text-slate-600 dark:text-slate-300">{{ $vendedora['resumen_texto'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No hay vendedoras asignadas.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
