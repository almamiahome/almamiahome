<?php

declare(strict_types=1);

use function Laravel\Folio\name;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

\Laravel\Folio\middleware('auth');
name('mejoras');

new class extends Component {
    public string $tabActiva = 'mejoras';

    public ?string $mejoraSeleccionada = null;

    public array $modulosActivos = [
        [
            'titulo' => 'Gestión de pedidos multinivel',
            'descripcion' => 'Creación y seguimiento de pedidos para vendedoras, líderes y coordinadoras.',
            'estado' => 'instalado',
        ],
        [
            'titulo' => 'Catálogo y stock interno',
            'descripcion' => 'Administración de productos, categorías, stock y rótulos desde panel interno.',
            'estado' => 'instalado',
        ],
        [
            'titulo' => 'Campañas y puntaje comercial',
            'descripcion' => 'Control de campañas, reglas de puntaje y reportes de crecimiento.',
            'estado' => 'instalado',
        ],
        [
            'titulo' => 'Finanzas operativas',
            'descripcion' => 'Registro de gastos, pagos y cobros para control administrativo.',
            'estado' => 'instalado',
        ],
    ];

    public function getMejorasProperty(): Collection
    {
        $files = File::glob(resource_path('themes/anchor/pages/mejoras/items/*.json')) ?: [];

        return collect($files)
            ->flatMap(function (string $path): array {
                $decoded = json_decode(File::get($path), true);

                if (! is_array($decoded)) {
                    return [];
                }

                $items = array_is_list($decoded) ? $decoded : [$decoded];

                return collect($items)
                    ->filter(fn ($item) => is_array($item))
                    ->map(fn (array $item) => $this->normalizarMejora($item, basename($path)))
                    ->all();
            })
            ->values();
    }

    public function getMejorasEnCursoProperty(): Collection
    {
        return $this->mejoras
            ->filter(function (array $mejora): bool {
                $estado = Str::lower((string) ($mejora['estado'] ?? ''));

                return ! in_array($estado, ['instalado', 'no instalado'], true);
            })
            ->values();
    }

    public function verMejora(string $id): void
    {
        $this->mejoraSeleccionada = $id;
    }

    public function cerrarModal(): void
    {
        $this->mejoraSeleccionada = null;
    }

    public function getDetalleMejoraProperty(): ?array
    {
        return $this->mejoras
            ->firstWhere('id', $this->mejoraSeleccionada);
    }

    protected function normalizarMejora(array $item, string $origen): array
    {
        $titulo = (string) ($item['titulo'] ?? 'Mejora sin título');

        return [
            'id' => (string) ($item['id'] ?? Str::slug($titulo.'-'.$origen)),
            'titulo' => $titulo,
            'subtitulo' => (string) ($item['subtitulo'] ?? ''),
            'descripcion' => (string) ($item['descripcion'] ?? ''),
            'estado' => (string) ($item['estado'] ?? 'no instalado'),
            'etiquetas' => $this->normalizarTextoLista($item['etiquetas'] ?? []),
            'categorias' => $this->normalizarTextoLista($item['categorias'] ?? []),
            'ejemplos_html' => $this->normalizarTextoLista($item['ejemplos_html'] ?? []),
            'precios' => $this->normalizarPrecios($item['precios'] ?? []),
            'gastos_externos' => $item['gastos_externos'] ?? null,
            'licencia' => (string) ($item['licencia'] ?? 'Definir tipo de licencia'),
        ];
    }

    protected function normalizarTextoLista(mixed $valor): array
    {
        if (is_string($valor)) {
            return [$valor];
        }

        if (! is_array($valor)) {
            return [];
        }

        return collect($valor)
            ->filter(fn ($item) => is_string($item) && trim($item) !== '')
            ->values()
            ->all();
    }

    protected function normalizarPrecios(mixed $valor): array
    {
        if (! is_array($valor)) {
            return [];
        }

        return collect($valor)
            ->filter(fn ($precio) => is_array($precio))
            ->map(function (array $precio): array {
                return [
                    'concepto' => (string) ($precio['concepto'] ?? 'Precio'),
                    'monto' => (string) ($precio['monto'] ?? 'A definir'),
                    'periodicidad' => (string) ($precio['periodicidad'] ?? 'No especificada'),
                    'detalle' => (string) ($precio['detalle'] ?? ''),
                ];
            })
            ->values()
            ->all();
    }
};
?>

<x-layouts.app>
    @volt('mejoras')
        <x-app.container>
            <div class="space-y-6">
                <x-app.heading
                    title="Mejoras y módulos"
                    description="Listado de mejoras y servicios gestionados por archivos JSON, sin conexión a base de datos."
                    :border="false"
                />

                <div class="flex flex-wrap gap-2 rounded-xl border border-zinc-200 bg-white p-2 dark:border-zinc-700 dark:bg-zinc-900">
                    @php
                        $tabs = [
                            'mejoras' => 'Catálogo de mejoras',
                            'modulos-activos' => 'Módulos Activos',
                            'en-curso' => 'En curso',
                        ];
                    @endphp

                    @foreach($tabs as $tabId => $tabLabel)
                        <button
                            type="button"
                            wire:click="$set('tabActiva', '{{ $tabId }}')"
                            class="rounded-lg px-4 py-2 text-sm font-medium transition {{ $tabActiva === $tabId
                                ? 'bg-primary-600 text-white shadow-sm'
                                : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}"
                        >
                            {{ $tabLabel }}
                        </button>
                    @endforeach
                </div>

                @if($tabActiva === 'mejoras')
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @forelse($this->mejoras as $mejora)
                            <article class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                                <div class="mb-4 flex items-start justify-between gap-3">
                                    <div>
                                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $mejora['titulo'] }}</h2>
                                        @if($mejora['subtitulo'] !== '')
                                            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $mejora['subtitulo'] }}</p>
                                        @endif
                                    </div>
                                    <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold uppercase text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                        {{ $mejora['estado'] }}
                                    </span>
                                </div>

                                <p class="line-clamp-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $mejora['descripcion'] }}</p>

                                @if(!empty($mejora['etiquetas']))
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach($mejora['etiquetas'] as $etiqueta)
                                            <span class="rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 dark:bg-primary-950/40 dark:text-primary-300">
                                                #{{ $etiqueta }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-5">
                                    <button
                                        type="button"
                                        wire:click="verMejora('{{ $mejora['id'] }}')"
                                        class="w-full rounded-lg border border-primary-600 px-4 py-2 text-sm font-semibold text-primary-600 transition hover:bg-primary-600 hover:text-white"
                                    >
                                        Ver detalle completo
                                    </button>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-400">
                                No hay mejoras registradas en <code>resources/themes/anchor/pages/mejoras/items</code>.
                            </div>
                        @endforelse
                    </div>
                @endif

                @if($tabActiva === 'modulos-activos')
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($modulosActivos as $modulo)
                            <article class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900 dark:bg-emerald-950/30">
                                <h2 class="text-base font-semibold text-emerald-900 dark:text-emerald-200">{{ $modulo['titulo'] }}</h2>
                                <p class="mt-2 text-sm text-emerald-800 dark:text-emerald-300">{{ $modulo['descripcion'] }}</p>
                                <span class="mt-3 inline-flex rounded-full bg-emerald-200 px-2.5 py-1 text-xs font-semibold uppercase text-emerald-900 dark:bg-emerald-900 dark:text-emerald-200">
                                    {{ $modulo['estado'] }}
                                </span>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if($tabActiva === 'en-curso')
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @forelse($this->mejorasEnCurso as $mejora)
                            <article class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900 dark:bg-amber-950/30">
                                <h2 class="text-lg font-semibold text-amber-900 dark:text-amber-200">{{ $mejora['titulo'] }}</h2>
                                <p class="mt-2 text-sm text-amber-800 dark:text-amber-300">{{ $mejora['descripcion'] }}</p>
                                <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-400">
                                    Estado: {{ $mejora['estado'] }}
                                </p>
                            </article>
                        @empty
                            <div class="col-span-full rounded-xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-400">
                                No hay mejoras en curso por el momento.
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>

            @if($this->detalleMejora)
                <div class="fixed inset-0 z-50 overflow-y-auto bg-black/70 p-4" wire:click="cerrarModal">
                    <div class="mx-auto min-h-full max-w-6xl rounded-2xl bg-white p-6 shadow-xl dark:bg-zinc-900" wire:click.stop>
                        <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $this->detalleMejora['titulo'] }}</h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $this->detalleMejora['subtitulo'] }}</p>
                            </div>
                            <button type="button" wire:click="cerrarModal" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                                Cerrar
                            </button>
                        </div>

                        <div class="grid gap-6 lg:grid-cols-3">
                            <section class="space-y-4 lg:col-span-2">
                                <div>
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Descripción</h3>
                                    <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $this->detalleMejora['descripcion'] }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Ejemplos</h3>
                                    @forelse($this->detalleMejora['ejemplos_html'] as $ejemplo)
                                        <div class="prose mt-3 max-w-none rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:prose-invert dark:border-zinc-700 dark:bg-zinc-800/70">
                                            {!! $ejemplo !!}
                                        </div>
                                    @empty
                                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Sin ejemplos cargados.</p>
                                    @endforelse
                                </div>
                            </section>

                            <aside class="space-y-4 rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/60">
                                <div>
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Categorías</h3>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($this->detalleMejora['categorias'] as $categoria)
                                            <span class="rounded-full bg-zinc-200 px-2.5 py-1 text-xs font-medium text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200">{{ $categoria }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Precio</h3>
                                    <ul class="mt-2 space-y-2">
                                        @forelse($this->detalleMejora['precios'] as $precio)
                                            <li class="rounded-lg border border-zinc-200 bg-white p-3 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                                                <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $precio['concepto'] }}: {{ $precio['monto'] }}</p>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $precio['periodicidad'] }}</p>
                                                @if($precio['detalle'] !== '')
                                                    <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-300">{{ $precio['detalle'] }}</p>
                                                @endif
                                            </li>
                                        @empty
                                            <li class="text-sm text-zinc-500 dark:text-zinc-400">Sin información de precios.</li>
                                        @endforelse
                                    </ul>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Licencia</h3>
                                    <p class="mt-1 text-sm text-zinc-700 dark:text-zinc-300">{{ $this->detalleMejora['licencia'] }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Gastos externos</h3>
                                    <p class="mt-1 text-sm text-zinc-700 dark:text-zinc-300">
                                        {{ is_string($this->detalleMejora['gastos_externos']) ? $this->detalleMejora['gastos_externos'] : 'No informado' }}
                                    </p>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
