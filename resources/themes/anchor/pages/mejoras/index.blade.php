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
        'descripcion' => 'Creación, edición y seguimiento de pedidos para vendedoras, líderes y coordinadoras.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Catálogo de productos',
        'descripcion' => 'Administración de productos, variantes, precios y disponibilidad comercial.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Categorías y organización comercial',
        'descripcion' => 'Estructura de categorías para clasificación, navegación y reglas por tipo de producto.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Stock y control interno',
        'descripcion' => 'Control de inventario interno, movimientos y alertas de faltantes.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Campañas comerciales',
        'descripcion' => 'Gestión de campañas activas, objetivos y períodos de vigencia.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Reglas de puntaje y bonificaciones',
        'descripcion' => 'Definición de reglas de puntaje, premios y bonificaciones por desempeño.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Jerarquía comercial',
        'descripcion' => 'Modelo de relaciones Vendedora → Líder → Coordinadora y asignaciones automáticas.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Onboarding y gestión de usuarias',
        'descripcion' => 'Altas, edición de perfiles y procesos de incorporación operativa.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Finanzas operativas',
        'descripcion' => 'Registro de gastos, cobros, pagos y control administrativo diario.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Reportes y métricas',
        'descripcion' => 'Visualización de indicadores clave para ventas, campañas y crecimiento.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Panel administrativo interno',
        'descripcion' => 'Gestión centralizada de operaciones, permisos y configuración del sistema.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Autenticación, roles y permisos',
        'descripcion' => 'Control de acceso por rol, seguridad de sesiones y autorización por módulo.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Páginas Folio/Volt (Anchor)',
        'descripcion' => 'Capa de páginas y vistas del tema Anchor para panel autenticado y secciones públicas.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Agentes internos y flujos IA',
        'descripcion' => 'Lineamientos y operación de asistentes internos mediante AGENTS.md para trabajo asistido.',
        'estado' => 'instalado',
    ],
    [
        'titulo' => 'Documentación técnica y operativa',
        'descripcion' => 'Documentación del proyecto, módulos y guías internas para trazabilidad funcional.',
        'estado' => 'instalado',
    ],
];

    public array $dataOperativa = [
        'notas' => [],
        'kanban' => [],
        'reportes' => [],
        'tickets' => [],
    ];

    public array $notaForm = [
        'titulo' => '',
        'contenido' => '',
    ];

    public ?string $notaEditandoId = null;

    public array $kanbanForm = [
        'modulo' => '',
        'titulo' => '',
        'estado' => 'pendiente',
        'subtareas' => '',
    ];

    public array $reporteForm = [
        'modulo' => '',
        'tipo' => 'operativo',
        'resumen' => '',
    ];

    public array $ticketForm = [
        'modulo' => '',
        'titulo' => '',
        'descripcion' => '',
        'estado' => 'abierto',
        'prioridad' => 'media',
    ];

    public string $mensajeOperativo = '';

    public function mount(): void
    {
        $this->cargarDataOperativa();
    }

    public function getRutaDataOperativa(): string
    {
        return resource_path('themes/anchor/pages/mejoras/items/operaciones.json');
    }

    public function cargarDataOperativa(): void
    {
        $ruta = $this->getRutaDataOperativa();

        if (! File::exists($ruta)) {
            $this->guardarDataOperativa();

            return;
        }

        $decodificado = json_decode(File::get($ruta), true);

        if (! is_array($decodificado)) {
            return;
        }

        foreach (['notas', 'kanban', 'reportes', 'tickets'] as $clave) {
            if (is_array($decodificado[$clave] ?? null)) {
                $this->dataOperativa[$clave] = $decodificado[$clave];
            }
        }
    }

    public function guardarDataOperativa(): void
    {
        $json = json_encode($this->dataOperativa, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            return;
        }

        $ruta = $this->getRutaDataOperativa();
        $tmp = $ruta.'.tmp';

        File::put($tmp, $json.PHP_EOL);
        File::move($tmp, $ruta);
    }

    public function guardarNota(): void
    {
        $titulo = trim($this->notaForm['titulo']);
        $contenido = trim($this->notaForm['contenido']);

        if ($titulo === '' || $contenido === '') {
            $this->mensajeOperativo = 'Completa el título y el contenido de la nota.';

            return;
        }

        if ($this->notaEditandoId !== null) {
            foreach ($this->dataOperativa['notas'] as &$nota) {
                if (($nota['id'] ?? '') === $this->notaEditandoId) {
                    $nota['titulo'] = $titulo;
                    $nota['contenido'] = $contenido;
                    $nota['actualizado_en'] = now()->toDateTimeString();
                }
            }
            unset($nota);
            $this->mensajeOperativo = 'Nota actualizada correctamente.';
        } else {
            $this->dataOperativa['notas'][] = [
                'id' => (string) Str::uuid(),
                'titulo' => $titulo,
                'contenido' => $contenido,
                'creado_en' => now()->toDateTimeString(),
                'actualizado_en' => now()->toDateTimeString(),
            ];
            $this->mensajeOperativo = 'Nota creada correctamente.';
        }

        $this->guardarDataOperativa();
        $this->notaEditandoId = null;
        $this->notaForm = ['titulo' => '', 'contenido' => ''];
    }

    public function editarNota(string $id): void
    {
        $nota = collect($this->dataOperativa['notas'])->firstWhere('id', $id);

        if (! is_array($nota)) {
            return;
        }

        $this->notaEditandoId = $id;
        $this->notaForm = [
            'titulo' => (string) ($nota['titulo'] ?? ''),
            'contenido' => (string) ($nota['contenido'] ?? ''),
        ];
    }

    public function crearTareaKanban(): void
    {
        $modulo = trim($this->kanbanForm['modulo']);
        $titulo = trim($this->kanbanForm['titulo']);

        if ($modulo === '' || $titulo === '') {
            $this->mensajeOperativo = 'Define módulo y título para crear la tarea.';

            return;
        }

        $subtareas = collect(explode("\n", (string) $this->kanbanForm['subtareas']))
            ->map(fn (string $linea): string => trim($linea))
            ->filter(fn (string $linea): bool => $linea !== '')
            ->map(fn (string $linea): array => ['titulo' => $linea, 'completada' => false])
            ->values()
            ->all();

        $this->dataOperativa['kanban'][] = [
            'id' => (string) Str::uuid(),
            'modulo' => $modulo,
            'titulo' => $titulo,
            'estado' => (string) ($this->kanbanForm['estado'] ?? 'pendiente'),
            'subtareas' => $subtareas,
            'creado_en' => now()->toDateTimeString(),
        ];

        $this->guardarDataOperativa();
        $this->mensajeOperativo = 'Tarea kanban registrada.';
        $this->kanbanForm = ['modulo' => '', 'titulo' => '', 'estado' => 'pendiente', 'subtareas' => ''];
    }

    public function moverTareaKanban(string $id, string $estado): void
    {
        foreach ($this->dataOperativa['kanban'] as &$tarea) {
            if (($tarea['id'] ?? '') === $id) {
                $tarea['estado'] = $estado;
            }
        }
        unset($tarea);

        $this->guardarDataOperativa();
    }

    public function crearReporte(): void
    {
        $modulo = trim($this->reporteForm['modulo']);
        $resumen = trim($this->reporteForm['resumen']);

        if ($modulo === '' || $resumen === '') {
            $this->mensajeOperativo = 'Completa módulo y resumen para guardar el reporte.';

            return;
        }

        $this->dataOperativa['reportes'][] = [
            'id' => (string) Str::uuid(),
            'modulo' => $modulo,
            'tipo' => (string) ($this->reporteForm['tipo'] ?? 'operativo'),
            'resumen' => $resumen,
            'fecha' => now()->toDateTimeString(),
        ];

        $this->guardarDataOperativa();
        $this->mensajeOperativo = 'Reporte guardado.';
        $this->reporteForm = ['modulo' => '', 'tipo' => 'operativo', 'resumen' => ''];
    }

    public function crearTicket(): void
    {
        $modulo = trim($this->ticketForm['modulo']);
        $titulo = trim($this->ticketForm['titulo']);

        if ($modulo === '' || $titulo === '') {
            $this->mensajeOperativo = 'Completa módulo y título del ticket.';

            return;
        }

        $this->dataOperativa['tickets'][] = [
            'id' => (string) Str::uuid(),
            'modulo' => $modulo,
            'titulo' => $titulo,
            'descripcion' => trim((string) ($this->ticketForm['descripcion'] ?? '')),
            'estado' => (string) ($this->ticketForm['estado'] ?? 'abierto'),
            'prioridad' => (string) ($this->ticketForm['prioridad'] ?? 'media'),
            'creado_en' => now()->toDateTimeString(),
        ];

        $this->guardarDataOperativa();
        $this->mensajeOperativo = 'Ticket creado correctamente.';
        $this->ticketForm = [
            'modulo' => '',
            'titulo' => '',
            'descripcion' => '',
            'estado' => 'abierto',
            'prioridad' => 'media',
        ];
    }

    public function actualizarEstadoTicket(string $id, string $estado): void
    {
        foreach ($this->dataOperativa['tickets'] as &$ticket) {
            if (($ticket['id'] ?? '') === $id) {
                $ticket['estado'] = $estado;
            }
        }
        unset($ticket);

        $this->guardarDataOperativa();
    }

    public function getMejorasProperty(): Collection
    {
        $files = File::glob(resource_path('themes/anchor/pages/mejoras/items/*.json')) ?: [];
        $files = array_values(array_filter($files, fn (string $file): bool => basename($file) !== 'activos.json'));

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

    public function getActivosProperty(): Collection
    {
        $path = resource_path('themes/anchor/pages/mejoras/items/activos.json');

        if (! File::exists($path)) {
            return collect();
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded)) {
            return collect();
        }

        $items = array_is_list($decoded) ? $decoded : [$decoded];

        return collect($items)
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(fn (array $item): array => $this->normalizarActivo($item, basename($path)))
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
            'descripcion' => (string) ($item['descripcion'] ?? 'Sin descripción'),
            'estado' => (string) ($item['estado'] ?? 'no instalado'),
            'etiquetas' => $this->normalizarTextoLista($item['etiquetas'] ?? []),
            'categorias' => $this->normalizarTextoLista($item['categorias'] ?? []),
            'ejemplos_html' => $this->normalizarTextoLista($item['ejemplos_html'] ?? []),
            'precios' => $this->normalizarPrecios($item['precios'] ?? []),
            'gastos_externos' => $item['gastos_externos'] ?? null,
            'licencia' => (string) ($item['licencia'] ?? 'Definir tipo de licencia'),
            'degradado' => trim((string) ($item['degradado'] ?? '')) ?: 'from-indigo-600 via-fuchsia-500 to-cyan-400',
            'portada_html' => (string) ($item['portada_html'] ?? ''),
            'icono' => $this->resolverIcono((string) ($item['icono'] ?? '')),
        ];
    }

    protected function normalizarActivo(array $item, string $origen): array
    {
        $titulo = trim((string) ($item['titulo'] ?? 'Activo sin título')) ?: 'Activo sin título';

        return [
            'id' => (string) ($item['id'] ?? Str::slug($titulo.'-'.$origen)),
            'titulo' => $titulo,
            'descripcion' => trim((string) ($item['descripcion'] ?? '')) ?: 'Sin descripción',
            'precio' => trim((string) ($item['precio'] ?? '')) ?: 'A definir',
            'moneda' => trim((string) ($item['moneda'] ?? '')) ?: 'A definir',
            'estado' => trim((string) ($item['estado'] ?? 'pendiente')) ?: 'pendiente',
            'meta' => is_array($item['meta'] ?? null) ? $item['meta'] : [],
            'degradado' => trim((string) ($item['degradado'] ?? '')) ?: 'from-indigo-600 via-fuchsia-500 to-cyan-400',
            'portada_html' => (string) ($item['portada_html'] ?? ''),
            'icono' => $this->resolverIcono((string) ($item['icono'] ?? '')),
        ];
    }

    protected function resolverIcono(string $icono): string
    {
        $icono = trim($icono);

        if ($icono === '') {
            return 'heroicon-o-puzzle-piece';
        }

        if (str_starts_with($icono, 'heroicon-')) {
            return $icono;
        }

        $normalizado = Str::of($icono)->trim()->replace('_', '-')->lower()->toString();
        $candidatos = [
            'heroicon-o-'.$normalizado,
            'heroicon-s-'.$normalizado,
            'heroicon-m-'.$normalizado,
        ];

        foreach ($candidatos as $candidato) {
            if (view()->exists('components.'.$candidato)) {
                return $candidato;
            }
        }

        return 'heroicon-o-puzzle-piece';
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

<x-layouts.empty>
 @volt('mejoras')
<x-app.container class="py-4 max-w-[1600px] antialiased">
    {{-- Estilos de Sistema Operativo de Alto Nivel --}}
    <style>
        .os-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(203, 213, 225, 0.4);
        }
        .dark .os-glass {
            background: rgba(15, 15, 20, 0.85);
            border: 1px solid rgba(63, 63, 70, 0.4);
        }
        .os-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .os-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.1);
        }
        .filter-chip-active {
            background-color: #4f46e5;
            color: white;
            box-shadow: 0 4px 10px -2px rgba(79, 70, 229, 0.4);
        }
        
        .fixed-wallpaper {
    background-image: url('{{ asset('storage/bg.jpg') }}');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
}
.overlay-wallpaper {
    background: rgba(255, 255, 255, 0.5);
}
.dark .overlay-wallpaper {
    background: rgba(9, 9, 11, 0.5);
}

    </style>
    
    

    <div class="fixed-wallpaper rounded-[2rem] p-2">
        <div class="rounded-[2rem] p-4 flex flex-col gap-4 bg-white/50 dark:bg-zinc-950/50">
        {{-- BARRA SUPERIOR DE SISTEMA --}}
        <div class="os-glass rounded-3xl p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <x-heroicon-s-cpu-chip class="h-7 w-7 text-white" />
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Administrador de Extensiones</h1>
                    <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-tighter opacity-70">Sistema Operativo Central • Alma Mía</p>
                </div>
            </div>

            {{-- BUSCADOR Y HERRAMIENTAS --}}
            <div class="flex items-center gap-3">
                <div class="relative group flex-1 lg:flex-none">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                    <input type="text" placeholder="Buscar módulo o función..." 
                           class="w-full lg:w-72 pl-10 pr-4 py-2.5 text-sm bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                </div>
                <div class="h-10 w-px bg-slate-200 dark:bg-zinc-800 mx-2 hidden lg:block"></div>
                <button class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-xl transition-colors">
                    <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                    Reporte Técnico
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 mt-2">
            {{-- PANEL DE NAVEGACIÓN Y FILTROS --}}
            <aside class="w-full lg:w-72 space-y-6">
                {{-- Vistas Principales --}}
                <nav class="os-glass rounded-[2rem] p-3 space-y-1">
                    @foreach([
                        'mejoras' => ['nombre' => 'Explorar Catálogo', 'icon' => 'heroicon-o-rectangle-group'],
                        'modulos-activos' => ['nombre' => 'Sistemas Activos', 'icon' => 'heroicon-o-check-badge'],
                        'en-curso' => ['nombre' => 'En Desarrollo', 'icon' => 'heroicon-o-beaker'],
                        'activos' => ['nombre' => 'Activos', 'icon' => 'heroicon-o-cube'],
                        'notas' => ['nombre' => 'Notas', 'icon' => 'heroicon-o-document-text'],
                        'kanban' => ['nombre' => 'Kanban', 'icon' => 'heroicon-o-squares-2x2'],
                        'reportes' => ['nombre' => 'Reportes', 'icon' => 'heroicon-o-chart-bar-square'],
                        'tickets' => ['nombre' => 'Tickets', 'icon' => 'heroicon-o-lifebuoy']
                    ] as $id => $item)
                        <button wire:click="$set('tabActiva', '{{ $id }}')"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ $tabActiva === $id 
                                ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' 
                                : 'text-slate-500 hover:bg-slate-50 dark:text-zinc-400 dark:hover:bg-zinc-800/50' }}">
                            <x-dynamic-component :component="$item['icon']" class="h-5 w-5" />
                            {{ $item['nombre'] }}
                        </button>
                    @endforeach
                </nav>

                {{-- Filtros por Categoría (Solo visible en Catálogo) --}}
                @if($tabActiva === 'mejoras')
                <div class="px-2">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-2">Filtrar por Categoría</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Ventas', 'Finanzas', 'Logística', 'IA', 'Marketing', 'Estrategia'] as $cat)
                            <button class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-zinc-800 text-[11px] font-bold text-slate-600 dark:text-zinc-400 hover:border-indigo-500 transition-all">
                                {{ $cat }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>

            {{-- ÁREA DE CONTENIDO --}}
            <main class="flex-1 min-h-[600px]">
                @if($mensajeOperativo !== '')
                    <div class="mb-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200/70 dark:border-indigo-800 px-4 py-3 text-sm font-bold text-indigo-700 dark:text-indigo-200">
                        {{ $mensajeOperativo }}
                    </div>
                @endif

                {{-- VISTA: CATÁLOGO EXPLORADOR --}}
                @if($tabActiva === 'mejoras')
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @forelse($this->mejoras as $mejora)
                            <div class="os-glass os-card rounded-[2rem] p-6 flex flex-col border-transparent hover:border-indigo-500/30">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="p-3.5 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600">
                                        <x-dynamic-component :component="$mejora['icono'] ?? 'heroicon-o-puzzle-piece'" class="h-7 w-7" />
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Versión 1.0</span>
                                        <span class="mt-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-zinc-800 text-[9px] font-bold text-slate-500 uppercase">{{ $mejora['estado'] }}</span>
                                    </div>
                                </div>
                                
                                <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight mb-2 tracking-tight">{{ $mejora['titulo'] }}</h3>
                                <p class="text-xs font-bold text-indigo-600/70 uppercase tracking-wide mb-4">{{ $mejora['subtitulo'] }}</p>
                                
                                <p class="text-sm text-slate-600 dark:text-zinc-400 line-clamp-3 leading-relaxed mb-6">
                                    {{ $mejora['descripcion'] }}
                                </p>

                                <div class="mt-auto pt-5 border-t border-slate-100 dark:border-zinc-800/50 flex items-center justify-between">
                                    <div class="flex gap-1">
                                        @foreach(array_slice($mejora['etiquetas'] ?? [], 0, 2) as $tag)
                                            <span class="text-[9px] font-black text-slate-400 uppercase">#{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                    <button wire:click="verMejora('{{ $mejora['id'] }}')" 
                                            class="px-4 py-2 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-black transition-transform active:scale-95">
                                        Detalles
                                    </button>
                                </div>
                            </div>
                        @empty
                            {{-- Empty state --}}
                        @endforelse
                    </div>
                @endif


                @if($tabActiva === 'activos')
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @forelse($this->activos as $activo)
                            <div class="os-glass os-card rounded-[2rem] p-6 border border-transparent hover:border-indigo-500/30">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="h-12 w-12 rounded-2xl flex items-center justify-center bg-gradient-to-br {{ $activo['degradado'] }} text-white">
                                        <x-dynamic-component :component="$activo['icono']" class="h-6 w-6" />
                                    </div>
                                    <span class="text-[10px] font-black uppercase text-slate-500">{{ $activo['estado'] }}</span>
                                </div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white">{{ $activo['titulo'] }}</h3>
                                <p class="mt-2 text-sm text-slate-600 dark:text-zinc-300">{{ $activo['descripcion'] }}</p>
                                <div class="mt-4 text-xs font-black uppercase text-indigo-600">{{ $activo['precio'] }} · {{ $activo['moneda'] }}</div>
                            </div>
                        @empty
                            <div class="os-glass rounded-3xl p-8 text-sm font-bold text-slate-500">No hay activos cargados.</div>
                        @endforelse
                    </div>
                @endif

                {{-- VISTA: SISTEMAS ACTIVOS (Simplificada y Profesional) --}}
                @if($tabActiva === 'modulos-activos')
                    <div class="os-glass rounded-[2.5rem] overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 dark:bg-zinc-900/50">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Identificador de Sistema</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Estado Operativo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                                @foreach($modulosActivos as $modulo)
                                    <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors cursor-pointer">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="h-10 w-10 rounded-xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center shadow-sm">
                                                    <x-dynamic-component :component="$modulo['icon'] ?? 'heroicon-o-check-badge'" class="h-5 w-5 text-indigo-600" />
                                                </div>
                                                <div>
                                                    <p class="font-black text-slate-900 dark:text-white">{{ $modulo['titulo'] }}</p>
                                                    <p class="text-xs text-slate-500 font-medium">{{ Str::limit($modulo['descripcion'], 60) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-2">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                <span class="text-xs font-bold text-emerald-600 uppercase tracking-tighter">Activo y Seguro</span>
                                            </div>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- VISTA: PROYECTOS EN CURSO --}}
                @if($tabActiva === 'en-curso')
                    <div class="grid gap-4">
                        @foreach($this->mejorasEnCurso as $mejora)
                            <div class="os-glass rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8">
                                <div class="flex-1 w-full text-center md:text-left">
                                    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">{{ $mejora['titulo'] }}</h3>
                                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest">Compilando Módulo</span>
                                    </div>
                                    <p class="text-sm text-slate-500 font-medium mb-6 leading-relaxed">{{ $mejora['descripcion'] }}</p>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase mb-1">
                                            <span>Progreso de implementación</span>
                                            <span>45%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 dark:bg-zinc-800 h-3 rounded-full overflow-hidden p-0.5">
                                            <div class="bg-indigo-600 h-full rounded-full shadow-[0_0_15px_rgba(79,70,229,0.5)] transition-all duration-1000" style="width: 45%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden lg:block w-48 text-center border-l border-slate-100 dark:border-zinc-800 pl-8">
                                    <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Entrega estimada</p>
                                    <p class="text-lg font-black text-slate-900 dark:text-white">Marzo 2024</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($tabActiva === 'notas')
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="os-glass rounded-3xl p-6 space-y-3">
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">Sistema de notas</h3>
                            <input wire:model="notaForm.titulo" type="text" placeholder="Título"
                                class="w-full rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                            <textarea wire:model="notaForm.contenido" rows="5" placeholder="Contenido de la nota"
                                class="w-full rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm"></textarea>
                            <button wire:click="guardarNota" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-black">{{ $notaEditandoId ? 'Actualizar nota' : 'Guardar nota' }}</button>
                        </div>
                        <div class="space-y-3">
                            @forelse($dataOperativa['notas'] as $nota)
                                <div class="os-glass rounded-2xl p-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <h4 class="font-black text-slate-900 dark:text-white">{{ $nota['titulo'] ?? 'Sin título' }}</h4>
                                        <button wire:click="editarNota('{{ $nota['id'] ?? '' }}')" class="text-xs font-black text-indigo-600">Editar</button>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-600 dark:text-zinc-300">{{ $nota['contenido'] ?? '' }}</p>
                                </div>
                            @empty
                                <div class="os-glass rounded-2xl p-4 text-sm font-bold text-slate-500">No hay notas registradas.</div>
                            @endforelse
                        </div>
                    </div>
                @endif

                @if($tabActiva === 'kanban')
                    <div class="space-y-4">
                        <div class="os-glass rounded-3xl p-6 grid gap-3 lg:grid-cols-2">
                            <input wire:model="kanbanForm.modulo" type="text" placeholder="Módulo"
                                class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                            <input wire:model="kanbanForm.titulo" type="text" placeholder="Título de tarea"
                                class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                            <select wire:model="kanbanForm.estado" class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                                <option value="pendiente">Pendiente</option>
                                <option value="en progreso">En progreso</option>
                                <option value="hecho">Hecho</option>
                            </select>
                            <textarea wire:model="kanbanForm.subtareas" rows="3" placeholder="Subtareas (una por línea)"
                                class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm"></textarea>
                            <button wire:click="crearTareaKanban" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-black w-fit">Crear tarea</button>
                        </div>
                        <div class="grid md:grid-cols-3 gap-4">
                            @foreach(['pendiente' => 'Pendiente', 'en progreso' => 'En progreso', 'hecho' => 'Hecho'] as $estadoId => $estadoLabel)
                                <div class="os-glass rounded-3xl p-4">
                                    <h4 class="font-black text-slate-900 dark:text-white mb-3">{{ $estadoLabel }}</h4>
                                    <div class="space-y-3">
                                        @foreach(collect($dataOperativa['kanban'])->where('estado', $estadoId) as $tarea)
                                            <div class="rounded-2xl p-3 bg-white/80 dark:bg-zinc-900/70 border border-slate-200 dark:border-zinc-800">
                                                <p class="text-sm font-black text-slate-900 dark:text-white">{{ $tarea['titulo'] ?? '' }}</p>
                                                <p class="text-xs font-bold text-indigo-600 mt-1">{{ $tarea['modulo'] ?? '' }}</p>
                                                <ul class="mt-2 text-xs text-slate-600 dark:text-zinc-300 list-disc pl-4">
                                                    @foreach(($tarea['subtareas'] ?? []) as $subtarea)
                                                        <li>{{ $subtarea['titulo'] ?? '' }}</li>
                                                    @endforeach
                                                </ul>
                                                <div class="flex gap-2 mt-3">
                                                    @foreach(['pendiente', 'en progreso', 'hecho'] as $estadoMover)
                                                        <button wire:click="moverTareaKanban('{{ $tarea['id'] ?? '' }}', '{{ $estadoMover }}')" class="text-[10px] px-2 py-1 rounded-lg bg-slate-100 dark:bg-zinc-800 font-black">{{ $estadoMover }}</button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($tabActiva === 'reportes')
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="os-glass rounded-3xl p-6 space-y-3">
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">Sistema de reportes</h3>
                            <input wire:model="reporteForm.modulo" type="text" placeholder="Módulo"
                                class="w-full rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                            <select wire:model="reporteForm.tipo" class="w-full rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                                <option value="operativo">Operativo</option>
                                <option value="financiero">Financiero</option>
                                <option value="incidencias">Incidencias</option>
                            </select>
                            <textarea wire:model="reporteForm.resumen" rows="4" placeholder="Resumen del reporte"
                                class="w-full rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm"></textarea>
                            <button wire:click="crearReporte" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-black">Guardar reporte</button>
                        </div>
                        <div class="space-y-3">
                            @forelse($dataOperativa['reportes'] as $reporte)
                                <div class="os-glass rounded-2xl p-4">
                                    <p class="text-xs font-black uppercase text-indigo-600">{{ $reporte['tipo'] ?? 'operativo' }} · {{ $reporte['modulo'] ?? '' }}</p>
                                    <p class="mt-2 text-sm text-slate-600 dark:text-zinc-300">{{ $reporte['resumen'] ?? '' }}</p>
                                </div>
                            @empty
                                <div class="os-glass rounded-2xl p-4 text-sm font-bold text-slate-500">No hay reportes registrados.</div>
                            @endforelse
                        </div>
                    </div>
                @endif

                @if($tabActiva === 'tickets')
                    <div class="space-y-4">
                        <div class="os-glass rounded-3xl p-6 grid gap-3 lg:grid-cols-2">
                            <input wire:model="ticketForm.modulo" type="text" placeholder="Módulo"
                                class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                            <input wire:model="ticketForm.titulo" type="text" placeholder="Título del ticket"
                                class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                            <select wire:model="ticketForm.estado" class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                                <option value="abierto">Abierto</option>
                                <option value="en progreso">En progreso</option>
                                <option value="resuelto">Resuelto</option>
                                <option value="cerrado">Cerrado</option>
                            </select>
                            <select wire:model="ticketForm.prioridad" class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm">
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                            </select>
                            <textarea wire:model="ticketForm.descripcion" rows="3" placeholder="Descripción"
                                class="lg:col-span-2 rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-sm"></textarea>
                            <button wire:click="crearTicket" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-black w-fit">Crear ticket</button>
                        </div>
                        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
                            @forelse($dataOperativa['tickets'] as $ticket)
                                <div class="os-glass rounded-2xl p-4 space-y-2">
                                    <p class="text-xs font-black uppercase text-indigo-600">{{ $ticket['modulo'] ?? '' }} · {{ $ticket['prioridad'] ?? 'media' }}</p>
                                    <h4 class="font-black text-slate-900 dark:text-white">{{ $ticket['titulo'] ?? '' }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-zinc-300">{{ $ticket['descripcion'] ?? '' }}</p>
                                    <select wire:change="actualizarEstadoTicket('{{ $ticket['id'] ?? '' }}', $event.target.value)" class="w-full rounded-xl border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900 px-3 py-2 text-xs font-bold">
                                        @foreach(['abierto', 'en progreso', 'resuelto', 'cerrado'] as $estadoTicket)
                                            <option value="{{ $estadoTicket }}" @selected(($ticket['estado'] ?? '') === $estadoTicket)>{{ ucfirst($estadoTicket) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @empty
                                <div class="os-glass rounded-2xl p-4 text-sm font-bold text-slate-500">No hay tickets cargados.</div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>

    {{-- MODAL SYSTEM OVERLAY (Z-INDEX SUPERIOR) --}}
    @if($this->detalleMejora)
        <div class="fixed inset-0 z-[99099] flex items-center justify-center p-4 md:p-10">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity" wire:click="cerrarModal"></div>
            
            {{-- Ventana de Sistema --}}
            <div class="relative w-full max-w-6xl bg-white dark:bg-zinc-900 rounded-[3rem] shadow-[0_30px_100px_-20px_rgba(0,0,0,0.5)] border border-white/20 overflow-hidden flex flex-col max-h-[95vh] os-card">
                
                {{-- Header con Identidad --}}
                <div class="flex items-center justify-between p-8 bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center gap-6">
                        <div class="h-16 w-16 bg-indigo-600 rounded-[1.5rem] flex items-center justify-center shadow-xl shadow-indigo-500/30">
                            <x-dynamic-component :component="$this->detalleMejora['icono'] ?? 'heroicon-o-cpu-chip'" class="h-9 w-9 text-white" />
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $this->detalleMejora['titulo'] }}</h2>
                                <span class="px-3 py-1 rounded-lg bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-[10px] font-black uppercase">Core v1.0</span>
                            </div>
                            <p class="text-sm font-bold text-indigo-600/70 uppercase mt-1 tracking-widest">{{ $this->detalleMejora['subtitulo'] }}</p>
                            @if(!empty($this->detalleMejora['portada_html']))
                                <div class="mt-4 rounded-2xl p-4 bg-gradient-to-r {{ $this->detalleMejora['degradado'] ?? 'from-indigo-600 via-fuchsia-500 to-cyan-400' }} text-white">
                                    {!! $this->detalleMejora['portada_html'] !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    <button wire:click="cerrarModal" class="p-4 rounded-full hover:bg-slate-200 dark:hover:bg-zinc-800 transition-all text-slate-400 active:scale-90">
                        <x-heroicon-s-x-mark class="h-7 w-7" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-10 lg:p-14">
                    <div class="grid lg:grid-cols-12 gap-16">
                        
                        {{-- DOCUMENTACIÓN TÉCNICA --}}
                        <div class="lg:col-span-8 space-y-12">
                            <section>
                                <label class="text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-4 block">Ficha Descriptiva</label>
                                <p class="text-xl text-slate-600 dark:text-zinc-300 leading-relaxed font-medium">
                                    {{ $this->detalleMejora['descripcion'] }}
                                </p>
                            </section>

                            <section>
                                <label class="text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-6 block">Previsualización del Flujo</label>
                                <div class="grid gap-6">
                                    @foreach($this->detalleMejora['ejemplos_html'] as $html)
                                        <div class="p-8 rounded-[2rem] bg-slate-50 dark:bg-zinc-950/50 border border-slate-100 dark:border-zinc-800 group shadow-sm hover:shadow-md transition-shadow">
                                            <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed">
                                                {!! $html !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>

                        {{-- PANEL DE INVERSIÓN Y MÉTRICAS --}}
                        <div class="lg:col-span-4 space-y-8">
                            <div class="p-8 rounded-[2.5rem] bg-slate-900 dark:bg-black border border-white/5 space-y-8 shadow-2xl">
                                <div>
                                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Arquitectura de Licencia</label>
                                    <p class="mt-2 text-base font-bold text-white flex items-center gap-3">
                                        <x-heroicon-s-check-badge class="h-6 w-6 text-indigo-400" />
                                        {{ $this->detalleMejora['licencia'] }}
                                    </p>
                                </div>

                                <hr class="border-white/10">

                                <div>
                                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4 block">Modelos de Implementación</label>
                                    <div class="space-y-4">
                                        @foreach($this->detalleMejora['precios'] as $p)
                                            <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:border-indigo-500/50 transition-colors group">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-[10px] font-black uppercase text-indigo-300">{{ $p['concepto'] }}</span>
                                                    <span class="text-[9px] px-2 py-0.5 rounded bg-indigo-500 text-white font-black uppercase">{{ $p['periodicidad'] }}</span>
                                                </div>
                                                <p class="text-2xl font-black text-white tracking-tighter">{{ $p['monto'] }}</p>
                                                <p class="text-[10px] mt-2 font-medium text-slate-400 leading-tight">{{ $p['detalle'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm shadow-2xl shadow-indigo-500/40 transition-all active:scale-95 uppercase tracking-widest">
                                    Solicitar Activación
                                </button>
                                
                                <p class="text-[9px] text-center text-slate-500 font-bold uppercase tracking-tighter">Sujeto a validación técnica de infraestructura</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
        </div>
    </div>
</x-app.container>
@endvolt
</x-layouts.empty>
