<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

middleware(['auth', function ($request, $next) {
    if (! $request->user() || ! $request->user()->hasRole('admin')) {
        abort(403);
    }

    return $next($request);
}]);
name('tareas');

new class extends Component {
    public array $columnas = [];

    public array $nuevaColumna = [
        'titulo' => '',
    ];

    public array $nuevaTarea = [];

    public string $mensaje = '';

    public function mount(): void
    {
        $this->cargarTablero();
    }

    protected function rutaTablero(): string
    {
        return resource_path('themes/anchor/pages/tareas/kanban.json');
    }

    protected function cargarTablero(): void
    {
        $ruta = $this->rutaTablero();

        if (! File::exists($ruta)) {
            $this->columnas = [];

            return;
        }

        $data = json_decode((string) File::get($ruta), true);
        $this->columnas = is_array($data['columnas'] ?? null) ? $data['columnas'] : [];

        foreach ($this->columnas as $columna) {
            $this->nuevaTarea[$columna['id']] = [
                'titulo' => '',
                'descripcion' => '',
                'subtareas' => '',
                'comentarios' => '',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'etiquetas' => '',
            ];
        }
    }

    protected function guardarTablero(): void
    {
        $data = [
            'columnas' => array_values($this->columnas),
            'actualizado_en' => now()->toDateTimeString(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            return;
        }

        File::put($this->rutaTablero(), $json.PHP_EOL);
    }

    public function agregarColumna(): void
    {
        $titulo = trim($this->nuevaColumna['titulo']);

        if ($titulo === '') {
            $this->mensaje = 'Ingresá un título para la columna.';

            return;
        }

        $id = 'col-'.Str::slug($titulo).'-'.Str::lower(Str::random(4));

        $this->columnas[] = [
            'id' => $id,
            'titulo' => $titulo,
            'orden' => count($this->columnas) + 1,
            'tareas' => [],
        ];

        $this->nuevaTarea[$id] = [
            'titulo' => '',
            'descripcion' => '',
            'subtareas' => '',
            'comentarios' => '',
            'fecha_inicio' => '',
            'fecha_fin' => '',
            'etiquetas' => '',
        ];

        $this->nuevaColumna['titulo'] = '';
        $this->mensaje = 'Columna creada.';
        $this->guardarTablero();
    }

    public function eliminarColumna(string $columnaId): void
    {
        $this->columnas = array_values(array_filter($this->columnas, fn (array $columna) => $columna['id'] !== $columnaId));
        unset($this->nuevaTarea[$columnaId]);

        $this->mensaje = 'Columna eliminada.';
        $this->guardarTablero();
    }

    public function agregarTarea(string $columnaId): void
    {
        $form = $this->nuevaTarea[$columnaId] ?? [];
        $titulo = trim((string) ($form['titulo'] ?? ''));

        if ($titulo === '') {
            $this->mensaje = 'La tarea necesita un título.';

            return;
        }

        $subtareas = collect(explode("\n", (string) ($form['subtareas'] ?? '')))
            ->map(fn (string $linea) => trim($linea))
            ->filter()
            ->values()
            ->all();

        $comentarios = collect(explode("\n", (string) ($form['comentarios'] ?? '')))
            ->map(fn (string $linea) => trim($linea))
            ->filter()
            ->values()
            ->all();

        $etiquetas = collect(explode(',', (string) ($form['etiquetas'] ?? '')))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        foreach ($this->columnas as &$columna) {
            if ($columna['id'] !== $columnaId) {
                continue;
            }

            $columna['tareas'][] = [
                'id' => 'task-'.Str::lower(Str::random(10)),
                'titulo' => $titulo,
                'descripcion' => trim((string) ($form['descripcion'] ?? '')),
                'subtareas' => $subtareas,
                'comentarios' => $comentarios,
                'fecha_inicio' => trim((string) ($form['fecha_inicio'] ?? '')),
                'fecha_fin' => trim((string) ($form['fecha_fin'] ?? '')),
                'etiquetas' => $etiquetas,
            ];
            break;
        }
        unset($columna);

        $this->nuevaTarea[$columnaId] = [
            'titulo' => '',
            'descripcion' => '',
            'subtareas' => '',
            'comentarios' => '',
            'fecha_inicio' => '',
            'fecha_fin' => '',
            'etiquetas' => '',
        ];

        $this->mensaje = 'Tarea agregada.';
        $this->guardarTablero();
    }

    public function eliminarTarea(string $columnaId, string $tareaId): void
    {
        foreach ($this->columnas as &$columna) {
            if ($columna['id'] !== $columnaId) {
                continue;
            }

            $columna['tareas'] = array_values(array_filter(
                $columna['tareas'] ?? [],
                fn (array $tarea) => ($tarea['id'] ?? '') !== $tareaId,
            ));
            break;
        }
        unset($columna);

        $this->mensaje = 'Tarea eliminada.';
        $this->guardarTablero();
    }

    public function moverColumna(int $origenIndex, int $destinoIndex): void
    {
        if (! isset($this->columnas[$origenIndex]) || $destinoIndex < 0 || $destinoIndex >= count($this->columnas)) {
            return;
        }

        $columna = $this->columnas[$origenIndex];
        array_splice($this->columnas, $origenIndex, 1);
        array_splice($this->columnas, $destinoIndex, 0, [$columna]);

        $this->guardarTablero();
    }

    public function moverTarea(string $fromColumnId, string $toColumnId, int $fromTaskIndex, int $toTaskIndex): void
    {
        $fromIdx = collect($this->columnas)->search(fn (array $col) => $col['id'] === $fromColumnId);
        $toIdx = collect($this->columnas)->search(fn (array $col) => $col['id'] === $toColumnId);

        if ($fromIdx === false || $toIdx === false) {
            return;
        }

        $origenTareas = $this->columnas[$fromIdx]['tareas'] ?? [];

        if (! isset($origenTareas[$fromTaskIndex])) {
            return;
        }

        $tarea = $origenTareas[$fromTaskIndex];
        array_splice($origenTareas, $fromTaskIndex, 1);
        $this->columnas[$fromIdx]['tareas'] = array_values($origenTareas);

        $destinoTareas = $this->columnas[$toIdx]['tareas'] ?? [];
        $toTaskIndex = max(0, min($toTaskIndex, count($destinoTareas)));
        array_splice($destinoTareas, $toTaskIndex, 0, [$tarea]);
        $this->columnas[$toIdx]['tareas'] = array_values($destinoTareas);

        $this->guardarTablero();
    }
};
?>

<x-layouts.app>
    @volt('tareas')
        <div x-data="{
                dragCol: null,
                dragTask: null,
                iniciarCol(index) { this.dragCol = index; },
                soltarCol(index) { if (this.dragCol !== null) { $wire.moverColumna(this.dragCol, index); this.dragCol = null; } },
                iniciarTask(columnId, taskIndex) { this.dragTask = { columnId, taskIndex }; },
                soltarTask(columnId, taskIndex = 9999) {
                    if (!this.dragTask) return;
                    $wire.moverTarea(this.dragTask.columnId, columnId, this.dragTask.taskIndex, taskIndex);
                    this.dragTask = null;
                }
            }" class="mx-auto max-w-[98%] space-y-6 py-6">
            <div class="rounded-2xl bg-white/90 p-5 shadow ring-1 ring-slate-200">
                <h1 class="text-2xl font-extrabold text-slate-900">Tareas · Kanban administrativo</h1>
                <p class="mt-1 text-sm text-slate-500">Administración de columnas y tareas con arrastre. Incluye historial desde el primer commit.</p>

                @if($mensaje !== '')
                    <div class="mt-3 rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ $mensaje }}</div>
                @endif

                <div class="mt-4 flex flex-col gap-3 md:flex-row">
                    <input wire:model="nuevaColumna.titulo" type="text" placeholder="Nombre de nueva columna"
                        class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 md:max-w-sm">
                    <button wire:click="agregarColumna"
                        class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Agregar columna</button>
                </div>
            </div>

            <div class="flex gap-4 overflow-x-auto pb-4">
                @foreach($columnas as $colIndex => $columna)
                    <section wire:key="columna-{{ $columna['id'] }}" draggable="true"
                        @dragstart="iniciarCol({{ $colIndex }})" @dragover.prevent @drop="soltarCol({{ $colIndex }})"
                        class="min-h-[36rem] w-[24rem] shrink-0 rounded-2xl bg-white/95 p-4 shadow ring-1 ring-slate-200">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <h2 class="text-sm font-black uppercase tracking-wide text-slate-700">{{ $columna['titulo'] }}</h2>
                            <button wire:click="eliminarColumna('{{ $columna['id'] }}')" class="text-xs font-semibold text-rose-600 hover:text-rose-700">Quitar</button>
                        </div>

                        <div class="space-y-3" @dragover.prevent @drop="soltarTask('{{ $columna['id'] }}')">
                            @foreach(($columna['tareas'] ?? []) as $taskIndex => $tarea)
                                <article wire:key="task-{{ $tarea['id'] }}" draggable="true"
                                    @dragstart="iniciarTask('{{ $columna['id'] }}', {{ $taskIndex }})"
                                    @dragover.prevent
                                    @drop="soltarTask('{{ $columna['id'] }}', {{ $taskIndex }})"
                                    class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-sm font-semibold text-slate-800">{{ $tarea['titulo'] }}</h3>
                                        <button wire:click="eliminarTarea('{{ $columna['id'] }}', '{{ $tarea['id'] }}')" class="text-xs text-rose-600">✕</button>
                                    </div>
                                    @if(!empty($tarea['descripcion']))
                                        <p class="mt-2 text-xs text-slate-600">{{ $tarea['descripcion'] }}</p>
                                    @endif
                                    @if(!empty($tarea['subtareas']))
                                        <ul class="mt-2 list-disc space-y-1 pl-5 text-xs text-slate-600">
                                            @foreach($tarea['subtareas'] as $subtarea)
                                                <li>{{ $subtarea }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if(!empty($tarea['comentarios']))
                                        <div class="mt-2 rounded-md bg-white p-2 text-xs text-slate-500">
                                            @foreach($tarea['comentarios'] as $comentario)
                                                <p>• {{ $comentario }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="mt-2 flex flex-wrap gap-1 text-[11px]">
                                        @foreach(($tarea['etiquetas'] ?? []) as $tag)
                                            <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-indigo-700">#{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                    @if(!empty($tarea['fecha_inicio']) || !empty($tarea['fecha_fin']))
                                        <p class="mt-2 text-[11px] text-slate-500">{{ $tarea['fecha_inicio'] ?: 's/f' }} → {{ $tarea['fecha_fin'] ?: 's/f' }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-4 space-y-2 border-t border-slate-200 pt-3">
                            <input wire:model="nuevaTarea.{{ $columna['id'] }}.titulo" type="text" placeholder="Título de tarea"
                                class="w-full rounded-lg border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <textarea wire:model="nuevaTarea.{{ $columna['id'] }}.descripcion" rows="2" placeholder="Descripción (opcional)"
                                class="w-full rounded-lg border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <textarea wire:model="nuevaTarea.{{ $columna['id'] }}.subtareas" rows="2" placeholder="Subtareas o grupos (una por línea)"
                                class="w-full rounded-lg border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <textarea wire:model="nuevaTarea.{{ $columna['id'] }}.comentarios" rows="2" placeholder="Comentarios (uno por línea)"
                                class="w-full rounded-lg border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <div class="grid grid-cols-2 gap-2">
                                <input wire:model="nuevaTarea.{{ $columna['id'] }}.fecha_inicio" type="date" class="rounded-lg border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <input wire:model="nuevaTarea.{{ $columna['id'] }}.fecha_fin" type="date" class="rounded-lg border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <input wire:model="nuevaTarea.{{ $columna['id'] }}.etiquetas" type="text" placeholder="Etiquetas separadas por coma"
                                class="w-full rounded-lg border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button wire:click="agregarTarea('{{ $columna['id'] }}')" class="w-full rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">Agregar tarea</button>
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    @endvolt
</x-layouts.app>
