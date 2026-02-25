<?php

use function Laravel\Folio\{middleware, name};
use App\Models\PuntajeRegla;
use App\Models\Categoria;
use Livewire\Volt\Component;

middleware('auth');
name('puntaje-reglas');

new class extends Component {
    public $reglas;
    public $categorias;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;

    public ?int $editingId = null;

    public array $form = [];

    public function mount(): void
    {
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    protected function loadData(): void
    {
        $this->reglas = PuntajeRegla::with('categorias')->latest()->get();
        $this->categorias = Categoria::orderBy('nombre')->get();
    }

    protected function defaultForm(): array
    {
        return [
            'categoria_ids' => [],
            'min_unidades' => null,
            'max_unidades' => null,
            'descripcion' => '',
            'bonificacion' => null,
            'porcentaje' => null,
            'beneficios' => '',
            'puntaje_minimo' => null,
            'puntaje_minimo_descripcion' => '',
            'puntos_mensuales' => null,
            'puntos_por_campania' => null,
            'datos' => [],
        ];
    }

    public function openCreateModal(): void
    {
        $this->form = $this->defaultForm();
        $this->editingId = null;
        $this->showEditModal = false;
        $this->showCreateModal = true;
    }

    public function openEditModal(int $id): void
    {
        $regla = PuntajeRegla::with('categorias')->findOrFail($id);

        $this->form = [
            'categoria_ids' => $regla->categorias
                ->pluck('id')
                ->map(fn ($value) => (string) $value)
                ->toArray(),
            'min_unidades' => $regla->min_unidades,
            'max_unidades' => $regla->max_unidades,
            'descripcion' => $regla->descripcion ?? '',
            'bonificacion' => $regla->bonificacion,
            'porcentaje' => $regla->porcentaje,
            'beneficios' => $regla->beneficios ?? '',
            'puntaje_minimo' => $regla->puntaje_minimo,
            'puntaje_minimo_descripcion' => $regla->puntaje_minimo_descripcion ?? '',
            'puntos_mensuales' => $regla->puntos_mensuales,
            'puntos_por_campania' => $regla->puntos_por_campania,
            'datos' => collect($regla->datos ?? [])
                ->map(fn ($value, $key) => [
                    'key' => (string) $key,
                    'value' => is_scalar($value) ? (string) $value : json_encode($value),
                ])
                ->values()
                ->toArray(),
        ];

        $this->editingId = $regla->id;
        $this->showCreateModal = false;
        $this->showEditModal = true;
    }

    protected function rules(): array
    {
        return [
            'form.categoria_ids' => ['array'],
            'form.categoria_ids.*' => ['exists:categorias,id'],
            'form.min_unidades' => ['nullable', 'integer', 'min:0'],
            'form.max_unidades' => ['nullable', 'integer', 'min:0'],
            'form.descripcion' => ['nullable', 'string'],
            'form.bonificacion' => ['nullable', 'numeric'],
            'form.porcentaje' => ['nullable', 'numeric'],
            'form.beneficios' => ['nullable', 'string'],
            'form.puntaje_minimo' => ['nullable', 'integer', 'min:0'],
            'form.puntaje_minimo_descripcion' => ['nullable', 'string'],
            'form.puntos_mensuales' => ['nullable', 'integer', 'min:0'],
            'form.puntos_por_campania' => ['nullable', 'integer', 'min:0'],
            'form.datos' => ['nullable', 'array'],
            'form.datos.*.key' => ['nullable', 'string'],
            'form.datos.*.value' => ['nullable', 'string'],
        ];
    }

    protected function extractPayload(): array
    {
        $validated = $this->validate();
        $rawForm = $validated['form'];

        $categoriaIds = collect($rawForm['categoria_ids'] ?? [])
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->all();

        $datosRows = collect($rawForm['datos'] ?? [])
            ->filter(fn ($row) =>
                (($row['key'] ?? '') !== '') || (($row['value'] ?? '') !== '')
            )
            ->mapWithKeys(fn ($row) => [
                $row['key'] ?? '' => $row['value'] ?? '',
            ])
            ->toArray();

        $attributes = collect($rawForm)
            ->except(['categoria_ids', 'datos'])
            ->map(fn ($value) => $value === '' ? null : $value)
            ->toArray();

        $attributes['datos'] = ! empty($datosRows) ? $datosRows : null;

        return [
            'attributes' => $attributes,
            'categoria_ids' => $categoriaIds,
        ];
    }

    public function saveRegla(): void
    {
        $payload = $this->extractPayload();

        $regla = PuntajeRegla::create($payload['attributes']);
        $this->syncCategorias($regla, $payload['categoria_ids']);

        session()->flash('message', 'Regla creada correctamente.');

        $this->redirectRoute('puntaje-reglas', navigate: true);
    }

    public function updateRegla(): void
    {
        if (! $this->editingId) {
            return;
        }

        $payload = $this->extractPayload();

        $regla = PuntajeRegla::findOrFail($this->editingId);
        $regla->update($payload['attributes']);
        $this->syncCategorias($regla, $payload['categoria_ids']);

        session()->flash('message', 'Regla actualizada correctamente.');

        $this->redirectRoute('puntaje-reglas', navigate: true);
    }

    public function deleteRegla(int $id): void
    {
        $regla = PuntajeRegla::findOrFail($id);

        // Muchos-a-muchos: limpiar pivot y borrar regla
        $regla->categorias()->detach();
        $regla->delete();

        session()->flash('message', 'Regla eliminada correctamente.');

        $this->redirectRoute('puntaje-reglas', navigate: true);
    }

    protected function syncCategorias(PuntajeRegla $regla, array $categoriaIds): void
    {
        // Muchos-a-muchos: sincronizar tabla pivot
        $regla->categorias()->sync($categoriaIds);
    }

    public function addDatoRow(): void
    {
        $this->form['datos'][] = ['key' => '', 'value' => ''];
    }

    public function removeDatoRow(int $index): void
    {
        if (! isset($this->form['datos'][$index])) {
            return;
        }

        unset($this->form['datos'][$index]);
        $this->form['datos'] = array_values($this->form['datos']);
    }

    public function closeModals(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editingId = null;
        $this->form = $this->defaultForm();
    }
};
?>

<x-layouts.app>
    @volt('puntaje-reglas')
        <x-app.container class="space-y-6" x-data="{}">
          
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <x-app.heading
                    title="Puntaje y Reglas"
                    description="Configura las reglas de puntaje, bonificaciones y beneficios para tus categorias."
                    :border="false"
                />
                <div class="flex justify-end">
                    <x-button
                        type="button"
                        class="w-full md:w-auto bg-blue-900 text-blue-700 hover:bg-blue-100 transition-colors "
                        wire:click="openCreateModal"
                    >
                        Nueva regla
                    </x-button>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if($reglas->isEmpty())
                <div class="p-10 text-center bg-white border border-dashed rounded-xl text-slate-500">
                    No hay reglas configuradas todavia. Crea la primera para comenzar.
                </div>
            @else
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($reglas as $regla)
                        <div class="flex flex-col h-full bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4 space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900">
                                            {{ $regla->descripcion ?: 'Regla sin descripcion' }}
                                        </h3>
                                        @if($regla->puntaje_minimo_descripcion)
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $regla->puntaje_minimo_descripcion }}
                                            </p>
                                        @endif
                                    </div>
                                    @if($regla->puntaje_minimo !== null)
                                        <span class="inline-flex items-center px-2 py-1 text-[11px] font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Puntaje min: {{ $regla->puntaje_minimo }}
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-2">
                                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                                        Categorias
                                    </p>
                                    @if($regla->categorias->isEmpty())
                                        <span class="inline-flex px-2 py-1 text-[11px] font-medium rounded-full bg-slate-100 text-slate-500">
                                            Sin asignar
                                        </span>
                                    @else
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($regla->categorias as $categoria)
                                                <span class="inline-flex px-2 py-1 text-[11px] font-medium rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                    {{ $categoria->nombre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                                    <div class="space-y-1">
                                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                                            Rango unidades
                                        </p>
                                        <p class="text-slate-800">
                                            Min: {{ $regla->min_unidades ?? '—' }}
                                        </p>
                                        <p class="text-slate-800">
                                            Max: {{ $regla->max_unidades ?? '—' }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                                            Bonificacion
                                        </p>
                                        <p class="text-slate-800">
                                            $ {{ $regla->bonificacion !== null ? number_format($regla->bonificacion, 2, ',', '.') : '—' }}
                                        </p>
                                        <p class="text-slate-800">
                                            {{ $regla->porcentaje !== null ? number_format($regla->porcentaje, 2, ',', '.') . '%' : '—' }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                                            Puntos
                                        </p>
                                        <p class="text-slate-800">
                                            Mensual: {{ $regla->puntos_mensuales ?? '—' }}
                                        </p>
                                        <p class="text-slate-800">
                                            Campania: {{ $regla->puntos_por_campania ?? '—' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                                        Beneficios
                                    </p>
                                    <p class="text-xs text-slate-700">
                                        {{ $regla->beneficios ? \Illuminate\Support\Str::limit($regla->beneficios, 120) : '—' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between px-4 py-3 mt-auto text-xs border-t bg-slate-50/80 rounded-b-2xl">
                                <div class="text-[11px] text-slate-400">
                                    ID #{{ $regla->id }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors"
                                        wire:click="openEditModal({{ $regla->id }})"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        x-data
                                        @click="if (confirm('Seguro que queres eliminar esta regla?')) { $wire.deleteRegla({{ $regla->id }}) }"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 transition-colors"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Modal creacion --}}
            @if($showCreateModal)
                <div
                    x-data="{ open: true }"
                    x-show="open"
                    x-transition.opacity
                    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8 bg-black/40"
                    @keydown.window.escape="open = false; $wire.closeModals()"
                    @click.self="open = false; $wire.closeModals()"
                >
                    <div
                        class="w-full max-w-3xl bg-white border border-slate-100 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden"
                        @click.stop
                    >
                        <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">
                                    Nueva regla de puntaje
                                </h2>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    Defini la regla y asignala a una o varias categorias.
                                </p>
                            </div>
                            <button
                                class="inline-flex items-center justify-center w-8 h-8 text-slate-400 rounded-full hover:bg-slate-200 hover:text-slate-700 transition-colors"
                                type="button"
                                @click="open = false; $wire.closeModals()"
                            >
                                ✕
                            </button>
                        </div>

                        <div class="px-6 py-5 overflow-y-auto min-h-0 flex-1">
                            <form wire:submit.prevent="saveRegla" class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">
                                        Categorias relacionadas
                                    </label>
                                    <select
                                        multiple
                                        wire:model="form.categoria_ids"
                                        class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        size="6"
                                    >
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">
                                                {{ $categoria->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Selecciona todas las categorias que compartan esta regla.
                                    </p>
                                    @error('form.categoria_ids')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    @error('form.categoria_ids.*')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Minimo de unidades
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.min_unidades"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.min_unidades')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Maximo de unidades
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.max_unidades"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.max_unidades')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">
                                        Descripcion
                                    </label>
                                    <textarea
                                        wire:model="form.descripcion"
                                        rows="3"
                                        class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                    ></textarea>
                                    @error('form.descripcion')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Bonificacion fija
                                        </label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.bonificacion"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.bonificacion')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Porcentaje
                                        </label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.porcentaje"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.porcentaje')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Puntaje minimo
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.puntaje_minimo"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.puntaje_minimo')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Descripcion del puntaje minimo
                                        </label>
                                        <textarea
                                            wire:model="form.puntaje_minimo_descripcion"
                                            rows="3"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        ></textarea>
                                        @error('form.puntaje_minimo_descripcion')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Puntos mensuales
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.puntos_mensuales"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.puntos_mensuales')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Puntos por campania
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.puntos_por_campania"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.puntos_por_campania')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">
                                        Beneficios
                                    </label>
                                    <textarea
                                        wire:model="form.beneficios"
                                        rows="3"
                                        class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                    ></textarea>
                                    @error('form.beneficios')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Repeater datos (solo en formulario, no se muestra en las tarjetas) --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label class="block text-sm font-medium text-slate-700">
                                            Datos adicionales (clave / valor)
                                        </label>
                                        <button
                                            type="button"
                                            wire:click="addDatoRow"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md bg-slate-100 text-slate-700 hover:bg-slate-200"
                                        >
                                            + Agregar dato
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Usa este espacio para guardar informacion extra de la regla en formato clave / valor.
                                    </p>

                                    <div class="mt-3 space-y-2">
                                        @forelse($form['datos'] as $index => $dato)
                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="text"
                                                    wire:model="form.datos.{{ $index }}.key"
                                                    placeholder="Clave (ej: tipo, nivel, color)"
                                                    class="w-1/3 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs"
                                                />
                                                <input
                                                    type="text"
                                                    wire:model="form.datos.{{ $index }}.value"
                                                    placeholder="Valor"
                                                    class="w-2/3 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs"
                                                />
                                                <button
                                                    type="button"
                                                    wire:click="removeDatoRow({{ $index }})"
                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs bg-red-50 text-red-600 hover:bg-red-100"
                                                >
                                                    ✕
                                                </button>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400">
                                                No hay datos adicionales cargados.
                                            </p>
                                        @endforelse
                                    </div>

                                    @error('form.datos')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    @error('form.datos.*.key')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    @error('form.datos.*.value')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </form>
                        </div>

                        <div class="flex flex-col gap-2 px-6 py-4 border-t bg-slate-50 sm:flex-row sm:justify-end">
                            <x-button
                                type="button"
                                class="w-full sm:w-auto bg-slate-200 text-slate-700 hover:bg-slate-300"
                                @click="open = false; $wire.closeModals()"
                            >
                                Cancelar
                            </x-button>
                            <x-button
                                type="button"
                                class="w-full sm:w-auto"
                                wire:click="saveRegla"
                            >
                                Guardar
                            </x-button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Modal edicion --}}
            @if($showEditModal)
                <div
                    x-data="{ open: true }"
                    x-show="open"
                    x-transition.opacity
                    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8 bg-black/40"
                    @keydown.window.escape="open = false; $wire.closeModals()"
                    @click.self="open = false; $wire.closeModals()"
                >
                    <div
                        class="w-full max-w-3xl bg-white border border-slate-100 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden"
                        @click.stop
                    >
                        <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">
                                    Editar regla de puntaje
                                </h2>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    Actualiza los valores y las categorias asociadas.
                                </p>
                            </div>
                            <button
                                class="inline-flex items-center justify-center w-8 h-8 text-slate-400 rounded-full hover:bg-slate-200 hover:text-slate-700 transition-colors"
                                type="button"
                                @click="open = false; $wire.closeModals()"
                            >
                                ✕
                            </button>
                        </div>

                        <div class="px-6 py-5 overflow-y-auto min-h-0 flex-1">
                            <form wire:submit.prevent="updateRegla" class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">
                                        Categorias relacionadas
                                    </label>
                                    <select
                                        multiple
                                        wire:model="form.categoria_ids"
                                        class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        size="6"
                                    >
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">
                                                {{ $categoria->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Las categorias elegidas quedaran vinculadas a esta regla.
                                    </p>
                                    @error('form.categoria_ids')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    @error('form.categoria_ids.*')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Minimo de unidades
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.min_unidades"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.min_unidades')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Maximo de unidades
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.max_unidades"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.max_unidades')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">
                                        Descripcion
                                    </label>
                                    <textarea
                                        wire:model="form.descripcion"
                                        rows="3"
                                        class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                    ></textarea>
                                    @error('form.descripcion')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Bonificacion fija
                                        </label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.bonificacion"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.bonificacion')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Porcentaje
                                        </label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.porcentaje"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.porcentaje')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Puntaje minimo
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.puntaje_minimo"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.puntaje_minimo')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Descripcion del puntaje minimo
                                        </label>
                                        <textarea
                                            wire:model="form.puntaje_minimo_descripcion"
                                            rows="3"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        ></textarea>
                                        @error('form.puntaje_minimo_descripcion')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Puntos mensuales
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.puntos_mensuales"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.puntos_mensuales')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Puntos por campania
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            wire:model="form.puntos_por_campania"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        />
                                        @error('form.puntos_por_campania')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">
                                        Beneficios
                                    </label>
                                    <textarea
                                        wire:model="form.beneficios"
                                        rows="3"
                                        class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                    ></textarea>
                                    @error('form.beneficios')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Repeater datos en edicion --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label class="block text-sm font-medium text-slate-700">
                                            Datos adicionales (clave / valor)
                                        </label>
                                        <button
                                            type="button"
                                            wire:click="addDatoRow"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md bg-slate-100 text-slate-700 hover:bg-slate-200"
                                        >
                                            + Agregar dato
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Edita los datos extra asociados a esta regla. No se muestran en las tarjetas, solo aca.
                                    </p>

                                    <div class="mt-3 space-y-2">
                                        @forelse($form['datos'] as $index => $dato)
                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="text"
                                                    wire:model="form.datos.{{ $index }}.key"
                                                    placeholder="Clave"
                                                    class="w-1/3 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs"
                                                />
                                                <input
                                                    type="text"
                                                    wire:model="form.datos.{{ $index }}.value"
                                                    placeholder="Valor"
                                                    class="w-2/3 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs"
                                                />
                                                <button
                                                    type="button"
                                                    wire:click="removeDatoRow({{ $index }})"
                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs bg-red-50 text-red-600 hover:bg-red-100"
                                                >
                                                    ✕
                                                </button>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400">
                                                No hay datos adicionales cargados.
                                            </p>
                                        @endforelse
                                    </div>

                                    @error('form.datos')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    @error('form.datos.*.key')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    @error('form.datos.*.value')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </form>
                        </div>

                        <div class="flex flex-col gap-2 px-6 py-4 border-t bg-slate-50 sm:flex-row sm:justify-end">
                            <x-button
                                type="button"
                                class="w-full sm:w-auto bg-slate-200 text-slate-700 hover:bg-slate-300"
                                @click="open = false; $wire.closeModals()"
                            >
                                Cancelar
                            </x-button>
                            <x-button
                                type="button"
                                class="w-full sm:w-auto"
                                wire:click="updateRegla"
                            >
                                Actualizar
                            </x-button>
                        </div>
                    </div>
                </div>
            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
