<?php
use function Laravel\Folio\{middleware, name};
use App\Http\Controllers\Crecimiento\CierreCampanaController;
use App\Models\Catalogo;
use App\Models\CierreCampana;
use Livewire\Volt\Component;

middleware([
    'auth',
    function ($request, $next) {
        if (! $request->user() || ! $request->user()->can('crecimiento.ver_cierres_campana')) {
            abort(403, 'No tiene permisos para acceder al cierre general.');
        }

        return $next($request);
    },
]);

name('crecimiento-cierre-general');

new class extends Component {
    public $cierres = [];
    public $catalogos = [];
    public $selectedCierreId = null;
    public $totales = [];
    public $resumen = [];
    public $estadoMensaje = null;

    public $nuevo = [
        'nombre' => null,
        'codigo' => null,
        'catalogo_id' => null,
        'numero_cierre' => 1,
        'fecha_inicio' => null,
        'fecha_cierre' => null,
        'fecha_liquidacion' => null,
        'estado' => CierreCampana::ESTADO_PLANIFICADO,
        'datos' => null,
    ];

    public function mount()
    {
        $this->catalogos = Catalogo::orderByDesc('anio')->orderBy('numero')->get();
        $this->nuevo['catalogo_id'] = $this->catalogos->first()?->id;

        $this->loadCierres();
    }

    public function loadCierres()
    {
        $this->cierres = CierreCampana::orderByDesc('created_at')->get();

        if ($this->cierres->isNotEmpty()) {
            $this->selectedCierreId = $this->selectedCierreId ?? $this->cierres->first()->id;
            $this->refrescarResumen();
        }
    }

    public function refrescarResumen()
    {
        if (! $this->selectedCierreId) {
            return;
        }

        $cierre = CierreCampana::findOrFail($this->selectedCierreId);
        $controlador = app(CierreCampanaController::class);

        $this->totales = $controlador->totalesPorLider($cierre, auth()->user());
        $this->resumen = $controlador->planResumen($cierre, auth()->user());
    }

    public function registrarCierre()
    {
        $this->validate([
            'nuevo.nombre' => 'required|string|max:255',
            'nuevo.codigo' => 'required|string|max:50|unique:cierres_campana,codigo',
            'nuevo.catalogo_id' => 'required|exists:catalogos,id',
            'nuevo.numero_cierre' => 'required|integer|min:1|max:3',
            'nuevo.fecha_inicio' => 'nullable|date',
            'nuevo.fecha_cierre' => 'nullable|date|after_or_equal:nuevo.fecha_inicio',
            'nuevo.fecha_liquidacion' => 'nullable|date|after_or_equal:nuevo.fecha_cierre',
            'nuevo.estado' => 'required|in:' . implode(',', CierreCampana::ESTADOS_VALIDOS),
        ]);

        $controlador = app(CierreCampanaController::class);
        $cierre = $controlador->registrarCampana($this->nuevo, auth()->user());

        $this->estadoMensaje = 'Campaña registrada correctamente.';
        $this->nuevo = [
            'nombre' => null,
            'codigo' => null,
            'catalogo_id' => $this->catalogos->first()?->id,
            'numero_cierre' => 1,
            'fecha_inicio' => null,
            'fecha_cierre' => null,
            'fecha_liquidacion' => null,
            'estado' => CierreCampana::ESTADO_PLANIFICADO,
            'datos' => null,
        ];

        $this->selectedCierreId = $cierre->id;
        $this->loadCierres();
    }

    public function cerrarCierre()
    {
        if (! $this->selectedCierreId) {
            return;
        }

        $controlador = app(CierreCampanaController::class);
        $cierre = CierreCampana::findOrFail($this->selectedCierreId);
        $controlador->cerrarCampana($cierre, auth()->user());

        $this->estadoMensaje = 'Cierre actualizado a estado "cerrado".';
        $this->loadCierres();
    }
};
?>

<x-layouts.app>
@volt('crecimiento-cierre-general')
    <x-app.container class="space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <x-app.heading
                title="Cierre General de Crecimiento"
                description="Consolida actividad, altas, unidades y cobranzas por líder."
                :border="false"
            />
            <div class="flex gap-2">
                <x-button wire:click="cerrarCierre" class="bg-red-100 text-red-700">Cerrar campaña</x-button>
            </div>
        </div>

        @if($estadoMensaje)
            <div class="p-3 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                {{ $estadoMensaje }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="space-y-4">
                <div class="p-4 bg-white border rounded-xl shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-700">Campañas</h3>
                    <div class="space-y-2">
                        @forelse($cierres as $cierre)
                            <button
                                wire:click="selectedCierreId = {{ $cierre->id }}; refrescarResumen();"
                                class="w-full text-left p-3 border rounded-lg {{ $selectedCierreId === $cierre->id ? 'bg-indigo-50 border-indigo-200' : 'bg-slate-50 hover:bg-slate-100' }}"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $cierre->nombre }}</p>
                                        <p class="text-xs text-slate-500">Código {{ $cierre->codigo }} • {{ $cierre->estado }} • Cierre {{ $cierre->numero_cierre }}</p>
                                        <p class="text-xs text-slate-500">{{ $cierre->catalogo?->nombre ?? 'Sin catálogo' }}</p>
                                    </div>
                                    <span class="text-xs text-slate-500">{{ optional($cierre->fecha_cierre)->format('d/m') }}</span>
                                </div>
                            </button>
                        @empty
                            <p class="text-sm text-slate-500">Aún no hay cierres registrados.</p>
                        @endforelse
                    </div>
                </div>

                <div class="p-4 bg-white border rounded-xl shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-700">Registrar campaña</h3>
                    <div class="space-y-3">
                        <x-input label="Nombre" wire:model.live="nuevo.nombre" />
                        <x-input label="Código" wire:model.live="nuevo.codigo" />

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Catálogo</label>
                            <select wire:model.live="nuevo.catalogo_id" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($catalogos as $catalogo)
                                    <option value="{{ $catalogo->id }}">{{ $catalogo->nombre }} ({{ $catalogo->anio }} · N°{{ $catalogo->numero }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Número de cierre</label>
                                <select wire:model.live="nuevo.numero_cierre" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="1">Cierre 1</option>
                                    <option value="2">Cierre 2</option>
                                    <option value="3">Cierre 3</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Estado inicial</label>
                                <select wire:model.live="nuevo.estado" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="{{ \App\Models\CierreCampana::ESTADO_PLANIFICADO }}">Planificado</option>
                                    <option value="{{ \App\Models\CierreCampana::ESTADO_ABIERTO }}">Abierto</option>
                                    <option value="{{ \App\Models\CierreCampana::ESTADO_LIQUIDACION }}">En liquidación</option>
                                    <option value="{{ \App\Models\CierreCampana::ESTADO_CERRADO }}">Cerrado</option>
                                </select>
                            </div>
                        </div>

                        <x-input label="Fecha de inicio" type="date" wire:model.live="nuevo.fecha_inicio" />
                        <x-input label="Fecha de cierre" type="date" wire:model.live="nuevo.fecha_cierre" />
                        <x-input label="Fecha de liquidación" type="date" wire:model.live="nuevo.fecha_liquidacion" />

                        <div class="flex justify-end">
                            <x-button wire:click="registrarCierre" class="bg-indigo-600 text-white">Registrar</x-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 bg-white border rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-slate-800">Resumen del plan</h3>
                        @if(data_get($resumen, 'estado'))
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">Estado: {{ $resumen['estado'] }}</span>
                        @endif
                    </div>

                    @if(data_get($resumen, 'nota'))
                        <div class="p-3 mb-4 text-sm text-indigo-900 bg-indigo-50 border border-indigo-100 rounded-lg">
                            {{ $resumen['nota'] }}
                        </div>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div class="p-4 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Líderes</p>
                            <p class="text-xl font-bold text-slate-800">{{ data_get($resumen, 'lideres', 0) }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Actividad promedio</p>
                            <p class="text-xl font-bold text-slate-800">{{ number_format(data_get($resumen, 'actividad_promedio', 0), 0) }}%</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Premio total</p>
                            <p class="text-xl font-bold text-slate-800">${{ number_format(data_get($resumen, 'premio_total', 0), 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Actualizado</p>
                            <p class="text-xl font-bold text-slate-800">{{ data_get($resumen, 'actualizado_en', '—') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-slate-800">Totales por líder</h3>
                        <p class="text-sm text-slate-500">Rango, actividad, cobranzas, altas del mes y reparto 1C/2C/3C.</p>
                    </div>

                    @if(empty($totales))
                        <p class="text-sm text-slate-500">Selecciona una campaña para ver sus métricas.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50">
                                    <tr class="text-left text-slate-600">
                                        <th class="p-2">Líder</th>
                                        <th class="p-2">Rango</th>
                                        <th class="p-2">Rev. activas</th>
                                        <th class="p-2">Unidades</th>
                                        <th class="p-2">Cobranzas</th>
                                        <th class="p-2">Altas y pagos</th>
                                        <th class="p-2">Repartos 1C/2C/3C</th>
                                        <th class="p-2">Crecimiento</th>
                                        <th class="p-2">Premio</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($totales as $fila)
                                        <tr>
                                            <td class="p-2 font-semibold text-slate-800">{{ $fila['lider'] }}</td>
                                            <td class="p-2 text-slate-600">{{ $fila['rango'] }}</td>
                                            <td class="p-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-semibold text-slate-800">{{ $fila['revendedoras_activas'] }}</span>
                                                    @if($fila['actividad_ok'])
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">Meta</span>
                                                    @else
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">Pendiente</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-semibold text-slate-800">{{ $fila['unidades'] }}</span>
                                                    @if($fila['unidades_ok'])
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">OK</span>
                                                    @else
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">Falta</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <div class="flex items-center gap-2">
                                                    @if($fila['cobranzas_ok'])
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">Al día</span>
                                                    @else
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-rose-100 text-rose-700">Fuera de plazo</span>
                                                    @endif
                                                    <span class="text-xs text-slate-500">Pago: {{ $fila['fecha_pago_equipo'] ?? '—' }}</span>
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <p class="font-semibold text-slate-800">{{ $fila['altas_mes'] }} altas</p>
                                                <div class="flex flex-wrap gap-2 mt-1">
                                                    @foreach($fila['altas_pagadas_en_cierre'] as $pago)
                                                        <span class="px-2 py-1 text-[10px] rounded-full bg-indigo-50 text-indigo-700">
                                                            C{{ $pago['cuota'] }}: ${{ number_format($pago['monto_pagado'], 0, ',', '.') }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <p class="text-sm text-slate-700">1C: {{ $fila['cantidad_1c'] }} • 2C: {{ $fila['cantidad_2c'] }} • 3C: {{ $fila['cantidad_3c'] }}</p>
                                                <p class="text-xs text-slate-500">Reparto total: ${{ number_format($fila['monto_reparto_total'], 0, ',', '.') }}</p>
                                            </td>
                                            <td class="p-2">
                                                @if($fila['premio_crecimiento'] > 0)
                                                    <span class="px-2 py-1 text-[10px] rounded-full bg-emerald-50 text-emerald-700">${{ number_format($fila['premio_crecimiento'], 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-xs text-slate-500">—</span>
                                                @endif
                                            </td>
                                            <td class="p-2 font-semibold text-slate-800">${{ number_format($fila['premio_total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-app.container>
@endvolt
</x-layouts.app>
