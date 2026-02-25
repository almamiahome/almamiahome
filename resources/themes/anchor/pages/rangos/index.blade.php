<?php

use function Laravel\Folio\{middleware, name};
use App\Models\RangoLider;
use App\Models\PremioRegla;
use App\Models\CierreCampana;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

middleware([
    'auth',
    function ($request, $next) {
        // Permiso base para acceder a la pantalla
        if (! $request->user() || ! $request->user()->can('crecimiento.ver_rangos')) {
            abort(403, 'No tiene permiso para ver los rangos.');
        }

        return $next($request);
    },
]);

name('rangos');

new class extends Component {
    public $rangos;
    public $cierreCampanas;
    public $premioReglas;

    public $rangoId = null;
    public $nombre;
    public $slug;
    public $posicion = 1;
    public $revendedoras_minimas = 0;
    public $revendedoras_maximas = 0;
    public $unidades_minimas = 0;
    public $premio_actividad = 0;
    public $premio_unidades = 0;
    public $premio_cobranzas = 0;
    public $reparto_referencia = 0;
    public $color = '#f0f5ff';
    public $descripcion = null;

    public $premioId = null;
    public $premio_tipo = 'actividad';
    public $premio_nombre;
    public $premio_descripcion;
    public $premio_umbral_minimo = 0;
    public $premio_umbral_maximo = null;
    public $premio_monto = 0;
    public $premio_cuotas = 1;
    public $premio_compra_orden = null;
    public $premio_campana_id = null;

    public function mount(): void
    {
        $this->rangos       = collect();
        $this->premioReglas = collect();

        $this->cierreCampanas = CierreCampana::orderByDesc('created_at')->get();
        $this->loadRangos();
    }

    public function updatedNombre(): void
    {
        if (! $this->slug) {
            $this->slug = Str::slug($this->nombre, '_');
        }
    }

    public function loadRangos(): void
    {
        $this->rangos = RangoLider::withCount('premioReglas')
            ->orderBy('posicion')
            ->get();

        if (! $this->rangoId && $this->rangos->isNotEmpty()) {
            $this->selectRango($this->rangos->first()->id);
        }
    }

    public function selectRango($rangoId): void
    {
        $this->rangoId = $rangoId;
        $rango         = RangoLider::findOrFail($rangoId);

        $this->nombre               = $rango->nombre;
        $this->slug                 = $rango->slug;
        $this->posicion             = $rango->posicion;
        $this->revendedoras_minimas = $rango->revendedoras_minimas;
        $this->revendedoras_maximas = $rango->revendedoras_maximas;
        $this->unidades_minimas     = $rango->unidades_minimas;
        $this->premio_actividad     = $rango->premio_actividad;
        $this->premio_unidades      = $rango->premio_unidades;
        $this->premio_cobranzas     = $rango->premio_cobranzas;
        $this->reparto_referencia   = $rango->reparto_referencia;
        $this->color                = $rango->color;
        $this->descripcion          = $rango->descripcion;

        $this->loadPremioReglas();
        $this->resetPremioForm();
    }

    public function startCrearRango(): void
    {
        $this->ensurePermission('crecimiento.crear_rangos');

        $this->reset([
            'rangoId',
            'nombre',
            'slug',
            'posicion',
            'revendedoras_minimas',
            'revendedoras_maximas',
            'unidades_minimas',
            'premio_actividad',
            'premio_unidades',
            'premio_cobranzas',
            'reparto_referencia',
            'color',
            'descripcion',
        ]);

        $this->posicion = ($this->rangos->max('posicion') ?? 0) + 1;
        $this->color    = '#f0f5ff';
    }

    public function saveRango(): void
    {
        $this->ensurePermission($this->rangoId ? 'crecimiento.editar_rangos' : 'crecimiento.crear_rangos');

        $validated = $this->validate([
            'nombre'                => 'required|string|max:255',
            'slug'                  => 'required|string|max:255|unique:rangos_lideres,slug,' . ($this->rangoId ?? 'NULL') . ',id',
            'posicion'              => 'required|integer|min:1',
            'revendedoras_minimas'  => 'required|integer|min:0',
            'revendedoras_maximas'  => 'required|integer|min:0',
            'unidades_minimas'      => 'required|integer|min:0',
            'premio_actividad'      => 'required|numeric|min:0',
            'premio_unidades'       => 'required|numeric|min:0',
            'premio_cobranzas'      => 'required|numeric|min:0',
            'reparto_referencia'    => 'required|numeric|min:0',
            'color'                 => 'nullable|string|max:20',
            'descripcion'           => 'nullable|string',
        ]);

        $rango = RangoLider::updateOrCreate(
            ['id' => $this->rangoId],
            $validated
        );

        $this->rangoId = $rango->id;
        $this->loadRangos();

        session()->flash('message', 'Rango guardado correctamente.');
    }

    public function deleteRango($rangoId): void
    {
        $this->ensurePermission('crecimiento.eliminar_rangos');

        $rango = RangoLider::findOrFail($rangoId);
        $rango->delete();

        $this->rangoId      = null;
        $this->premioReglas = collect();

        $this->loadRangos();
    }

    public function loadPremioReglas(): void
    {
        if (! $this->rangoId) {
            $this->premioReglas = collect();
            return;
        }

        $this->premioReglas = PremioRegla::where('rango_lider_id', $this->rangoId)
            ->orderBy('tipo')
            ->orderBy('umbral_minimo')
            ->get();
    }

    public function editPremio($premioId): void
    {
        $this->ensurePermission('crecimiento.configurar_premios_liderazgo');

        $premio                     = PremioRegla::findOrFail($premioId);
        $this->premioId             = $premio->id;
        $this->premio_tipo          = $premio->tipo;
        $this->premio_nombre        = data_get($premio->datos, 'nombre');
        $this->premio_descripcion   = data_get($premio->datos, 'descripcion');
        $this->premio_umbral_minimo = $premio->umbral_minimo;
        $this->premio_umbral_maximo = $premio->umbral_maximo;
        $this->premio_monto         = $premio->monto;
        $this->premio_cuotas        = data_get($premio->datos, 'cuotas', 1);
        $this->premio_compra_orden  = data_get($premio->datos, 'compra_orden');
        $this->premio_campana_id    = $premio->campana_id;
    }

    public function resetPremioForm(): void
    {
        $this->premioId             = null;
        $this->premio_tipo          = 'actividad';
        $this->premio_nombre        = null;
        $this->premio_descripcion   = null;
        $this->premio_umbral_minimo = 0;
        $this->premio_umbral_maximo = null;
        $this->premio_monto         = 0;
        $this->premio_cuotas        = 1;
        $this->premio_compra_orden  = null;
        $this->premio_campana_id    = null;
    }

    public function savePremio(): void
    {
        $this->ensurePermission('crecimiento.configurar_premios_liderazgo');

        $validated = $this->validate([
            'rangoId'              => 'required|exists:rangos_lideres,id',
            'premio_tipo'          => 'required|string|max:100',
            'premio_nombre'        => 'nullable|string|max:255',
            'premio_descripcion'   => 'nullable|string',
            'premio_umbral_minimo' => 'nullable|integer|min:0',
            'premio_umbral_maximo' => 'nullable|integer|min:0',
            'premio_monto'         => 'nullable|numeric|min:0',
            'premio_cuotas'        => 'nullable|integer|min:1',
            'premio_compra_orden'  => 'nullable|integer|min:1',
            'premio_campana_id'    => 'nullable|exists:cierres_campana,id',
        ], [], [
            'rangoId' => 'rango',
        ]);

        $datos = array_filter([
            'nombre'       => $this->premio_nombre,
            'descripcion'  => $this->premio_descripcion,
            'cuotas'       => $this->premio_cuotas,
            'compra_orden' => $this->premio_compra_orden,
        ], fn ($valor) => ! is_null($valor) && $valor !== '');

        PremioRegla::updateOrCreate(
            ['id' => $this->premioId],
            [
                'rango_lider_id' => $this->rangoId,
                'campana_id'     => $validated['premio_campana_id'],
                'tipo'           => $validated['premio_tipo'],
                'umbral_minimo'  => $validated['premio_umbral_minimo'],
                'umbral_maximo'  => $validated['premio_umbral_maximo'],
                'monto'          => $validated['premio_monto'],
                'datos'          => $datos,
            ]
        );

        $this->loadPremioReglas();
        $this->resetPremioForm();

        session()->flash('message', 'Regla de premio guardada correctamente.');
    }

    public function deletePremio($premioId): void
    {
        $this->ensurePermission('crecimiento.configurar_premios_liderazgo');

        $premio = PremioRegla::findOrFail($premioId);
        $premio->delete();

        $this->loadPremioReglas();
        $this->resetPremioForm();
    }

    protected function ensurePermission(string $permission): void
    {
        $user = auth()->user();

        if (! $user || ! $user->can($permission)) {
            abort(403, 'No tiene permiso para realizar esta acción.');
        }
    }
};

?>

<x-layouts.app>
@volt('rangos')
    <x-app.container class="space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <x-app.heading
                title="Rangos"
                description="Administra los rangos y sus reglas de premios."
                :border="false"
            />
            <div class="flex gap-2">
                <x-button wire:click="startCrearRango" class="bg-indigo-600 text-white">Nuevo rango</x-button>
                <x-button wire:click="resetPremioForm" class="bg-slate-100 text-slate-800">Nueva regla</x-button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-3 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-3">
                <div class="p-4 bg-white border rounded-xl shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-700">Rangos configurados</h3>
                    <div class="space-y-2">
                        @forelse($rangos as $rango)
                            <button
                                wire:click="selectRango({{ $rango->id }})"
                                aria-label="Ver rango {{ $rango->nombre }}"
                                class="w-full text-left p-3 border rounded-lg flex items-center justify-between {{ $rangoId === $rango->id ? 'bg-indigo-50 border-indigo-200' : 'bg-slate-50 hover:bg-slate-100' }}"
                            >
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $rango->nombre }}</p>
                                    <p class="text-xs text-slate-500">Posición {{ $rango->posicion }} • {{ $rango->premio_reglas_count }} reglas</p>
                                </div>
                                <span class="w-4 h-4 rounded-full" style="background: {{ $rango->color }}"></span>
                            </button>
                        @empty
                            <p class="text-sm text-slate-500">Aún no hay rangos configurados.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 bg-white border rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-slate-800">Ficha del rango</h3>
                        @if($rangoId)
                            <x-button wire:click="deleteRango({{ $rangoId }})" aria-label="Eliminar rango" class="bg-red-100 text-red-700">Eliminar</x-button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="rango_nombre" class="text-sm font-medium text-slate-700">Nombre</label>
                            <x-input id="rango_nombre" aria-label="Nombre del rango" wire:model.live="nombre" />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_slug" class="text-sm font-medium text-slate-700">Slug</label>
                            <x-input id="rango_slug" aria-label="Slug del rango" wire:model.live="slug" />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_posicion" class="text-sm font-medium text-slate-700">Posición</label>
                            <x-input id="rango_posicion" type="number" aria-label="Posición del rango" wire:model.live="posicion" />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_revendedoras_minimas" class="text-sm font-medium text-slate-700">Revendedoras mínimas</label>
                            <x-input
                                id="rango_revendedoras_minimas"
                                type="number"
                                aria-label="Cantidad mínima de revendedoras"
                                wire:model.live="revendedoras_minimas"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_revendedoras_maximas" class="text-sm font-medium text-slate-700">Revendedoras máximas</label>
                            <x-input
                                id="rango_revendedoras_maximas"
                                type="number"
                                aria-label="Cantidad máxima de revendedoras"
                                wire:model.live="revendedoras_maximas"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_unidades_minimas" class="text-sm font-medium text-slate-700">Unidades mínimas</label>
                            <x-input
                                id="rango_unidades_minimas"
                                type="number"
                                aria-label="Cantidad mínima de unidades"
                                wire:model.live="unidades_minimas"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_premio_actividad" class="text-sm font-medium text-slate-700">Premio por actividad</label>
                            <x-input
                                id="rango_premio_actividad"
                                type="number"
                                step="0.01"
                                aria-label="Premio asignado por actividad"
                                wire:model.live="premio_actividad"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_premio_unidades" class="text-sm font-medium text-slate-700">Premio por unidades</label>
                            <x-input
                                id="rango_premio_unidades"
                                type="number"
                                step="0.01"
                                aria-label="Premio asignado por unidades"
                                wire:model.live="premio_unidades"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_premio_cobranzas" class="text-sm font-medium text-slate-700">Premio por cobranzas</label>
                            <x-input
                                id="rango_premio_cobranzas"
                                type="number"
                                step="0.01"
                                aria-label="Premio asignado por cobranzas"
                                wire:model.live="premio_cobranzas"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_reparto_referencia" class="text-sm font-medium text-slate-700">Reparto de referencia</label>
                            <x-input
                                id="rango_reparto_referencia"
                                type="number"
                                step="0.01"
                                aria-label="Porcentaje de reparto de referencia"
                                wire:model.live="reparto_referencia"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="rango_color" class="text-sm font-medium text-slate-700">Color</label>
                            <x-input id="rango_color" type="color" aria-label="Color distintivo del rango" wire:model.live="color" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="rango_descripcion" class="text-sm font-medium text-slate-700">Descripción</label>
                            <textarea
                                id="rango_descripcion"
                                wire:model.live="descripcion"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-200"
                                rows="3"
                                placeholder="Notas o detalles internos"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <x-button wire:click="saveRango" aria-label="Guardar rango" class="bg-indigo-600 text-white">Guardar rango</x-button>
                    </div>
                </div>

                <div class="p-6 bg-white border rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-slate-800">Reglas de premios</h3>
                        <x-button wire:click="resetPremioForm" class="bg-slate-100 text-slate-800">Limpiar formulario</x-button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="premio_tipo" class="text-sm font-medium text-slate-700">Tipo</label>
                            <select
                                id="premio_tipo"
                                wire:model.live="premio_tipo"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm"
                            >
                                <option value="actividad">Actividad</option>
                                <option value="altas">Altas</option>
                                <option value="unidades">Unidades</option>
                                <option value="cobranzas">Cobranzas</option>
                                <option value="crecimiento">Crecimiento</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label for="premio_nombre" class="text-sm font-medium text-slate-700">Nombre interno</label>
                            <x-input id="premio_nombre" aria-label="Nombre interno del premio" wire:model.live="premio_nombre" />
                        </div>
                        <div class="space-y-1">
                            <label for="premio_compra_orden" class="text-sm font-medium text-slate-700">Orden de compra</label>
                            <x-input
                                id="premio_compra_orden"
                                type="number"
                                aria-label="Orden de compra mínima"
                                wire:model.live="premio_compra_orden"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="premio_umbral_minimo" class="text-sm font-medium text-slate-700">Umbral mínimo</label>
                            <x-input
                                id="premio_umbral_minimo"
                                type="number"
                                aria-label="Umbral mínimo del premio"
                                wire:model.live="premio_umbral_minimo"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="premio_umbral_maximo" class="text-sm font-medium text-slate-700">Umbral máximo</label>
                            <x-input
                                id="premio_umbral_maximo"
                                type="number"
                                aria-label="Umbral máximo del premio"
                                wire:model.live="premio_umbral_maximo"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="premio_monto" class="text-sm font-medium text-slate-700">Monto fijo</label>
                            <x-input
                                id="premio_monto"
                                type="number"
                                step="0.01"
                                aria-label="Monto fijo a otorgar"
                                wire:model.live="premio_monto"
                            />
                        </div>
                        <div class="space-y-1">
                            <label for="premio_cuotas" class="text-sm font-medium text-slate-700">Cuotas</label>
                            <x-input
                                id="premio_cuotas"
                                type="number"
                                step="1"
                                aria-label="Cantidad de cuotas del premio"
                                wire:model.live="premio_cuotas"
                            />
                        </div>
                        <div>
                            <label for="premio_campana_id" class="text-sm font-medium text-slate-700">Cierre asociado</label>
                            <select
                                id="premio_campana_id"
                                wire:model.live="premio_campana_id"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm"
                            >
                                <option value="">Plan base</option>
                                @foreach($cierreCampanas as $cierre)
                                    <option value="{{ $cierre->id }}">{{ $cierre->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label for="premio_descripcion" class="text-sm font-medium text-slate-700">Descripción</label>
                            <textarea
                                id="premio_descripcion"
                                wire:model.live="premio_descripcion"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-200"
                                rows="2"
                                placeholder="Reglas o condiciones del premio"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        @if($premioId)
                            <x-button wire:click="deletePremio({{ $premioId }})" aria-label="Eliminar regla de premio" class="bg-red-100 text-red-700">Eliminar</x-button>
                        @endif
                        <x-button wire:click="savePremio" aria-label="Guardar regla de premio" class="bg-indigo-600 text-white">Guardar regla</x-button>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <h4 class="mb-3 text-sm font-semibold text-slate-700">Reglas cargadas para el rango</h4>
                        @if($premioReglas->isEmpty())
                            <p class="text-sm text-slate-500">No hay reglas cargadas para este rango.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-slate-50">
                                        <tr class="text-left text-slate-600">
                                            <th class="p-2">Tipo</th>
                                            <th class="p-2">Nombre</th>
                                            <th class="p-2">Umbrales</th>
                                            <th class="p-2">Premio</th>
                                            <th class="p-2 text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($premioReglas as $regla)
                                            <tr>
                                                <td class="p-2 capitalize">{{ $regla->tipo }}</td>
                                                @php($datos = $regla->datos ?? [])
                                                <td class="p-2">{{ $datos['nombre'] ?? Str::headline($regla->tipo) }}</td>
                                                <td class="p-2 text-slate-600">
                                                    Mín: {{ $regla->umbral_minimo ?? '—' }}<br>
                                                    Máx: {{ $regla->umbral_maximo ?? '—' }}
                                                </td>
                                                <td class="p-2">
                                                    <div class="text-slate-700">${{ number_format($regla->monto ?? 0, 0, ',', '.') }}</div>
                                                    @if(isset($datos['cuotas']))
                                                        <div class="text-xs text-slate-500">{{ $datos['cuotas'] }} cuota(s)</div>
                                                    @endif
                                                    @if(! empty($datos['compra_orden']))
                                                        <div class="text-xs text-indigo-600">{{ $datos['compra_orden'] }}ª compra</div>
                                                    @endif
                                            </td>
                                            <td class="p-2 text-right">
                                                <x-button
                                                    size="sm"
                                                    wire:click="editPremio({{ $regla->id }})"
                                                    aria-label="Editar regla {{ $datos['nombre'] ?? Str::headline($regla->tipo) }}"
                                                >
                                                    Editar
                                                </x-button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-app.container>
@endvolt
</x-layouts.app>