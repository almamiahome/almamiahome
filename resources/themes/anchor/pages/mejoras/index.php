<?php

declare(strict_types=1);

use function Laravel\Folio\name;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
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
        $icono = (string) ($item['icono'] ?? '');
        $iconoRespaldo = (string) ($item['icono_respaldo'] ?? 'heroicon-o-puzzle-piece');

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
            'icono' => $icono,
            'icono_respaldo' => $iconoRespaldo,
            'icono_resuelto' => $this->resolverIconoConRespaldo($icono, $iconoRespaldo),
        ];
    }

    protected function resolverIcono(string $icono): string
    {
        $iconoNormalizado = Str::of($icono)
            ->trim()
            ->lower()
            ->replace('_', '-')
            ->replace('.', '-')
            ->value();

        if ($iconoNormalizado === '') {
            return 'heroicon-o-puzzle-piece';
        }

        if ($this->esIconoFontAwesome($iconoNormalizado)) {
            return preg_replace('/\s+/', ' ', $iconoNormalizado) ?: 'fa-solid fa-puzzle-piece';
        }

        $alias = [
            'chart-pie' => 'heroicon-o-chart-pie',
            'truck' => 'heroicon-o-truck',
            'shopping-bag' => 'heroicon-o-shopping-bag',
            'gift' => 'heroicon-o-gift',
            'rocket' => 'heroicon-o-rocket-launch',
            'graduation-cap' => 'heroicon-o-academic-cap',
            'message-circle' => 'heroicon-o-chat-bubble-left-right',
            'mail' => 'heroicon-o-envelope',
            'shield-check' => 'heroicon-o-shield-check',
            'credit-card' => 'heroicon-o-credit-card',
            'target' => 'heroicon-o-cursor-arrow-rays',
            'refresh-ccw' => 'heroicon-o-arrow-path',
            'file-text' => 'heroicon-o-document-text',
            'star' => 'heroicon-o-star',
            'map-pin' => 'heroicon-o-map-pin',
            'file-check' => 'heroicon-o-document-check',
            'award' => 'heroicon-o-trophy',
            'database' => 'heroicon-o-circle-stack',
            'help-circle' => 'heroicon-o-question-mark-circle',
            'user' => 'heroicon-o-user',
        ];

        $candidato = $alias[$iconoNormalizado] ?? $iconoNormalizado;

        if (! Str::startsWith($candidato, 'heroicon-')) {
            $candidato = 'heroicon-o-'.$candidato;
        }

        return $this->existeComponenteBlade($candidato)
            ? $candidato
            : 'heroicon-o-puzzle-piece';
    }

    protected function resolverIconoConRespaldo(?string $icono, ?string $iconoRespaldo): array
    {
        $iconoPrincipal = $this->resolverIcono((string) $icono);
        $iconoAlternativo = $this->resolverIcono((string) $iconoRespaldo);

        if ($iconoPrincipal === 'heroicon-o-puzzle-piece' && $iconoAlternativo !== 'heroicon-o-puzzle-piece') {
            $iconoPrincipal = $iconoAlternativo;
        }

        if ($this->esIconoFontAwesome($iconoPrincipal)) {
            return ['tipo' => 'fa', 'valor' => $iconoPrincipal];
        }

        return ['tipo' => 'blade', 'valor' => $iconoPrincipal];
    }

    protected function esIconoFontAwesome(string $icono): bool
    {
        return (bool) preg_match('/^fa([\s-]|$)/', $icono);
    }

    protected function existeComponenteBlade(string $componente): bool
    {
        /** @var BladeCompiler $blade */
        $blade = app('blade.compiler');

        return $blade->componentExists($componente);
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
    </style>

    <div class="flex flex-col gap-4">
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
                        'en-curso' => ['nombre' => 'En Desarrollo', 'icon' => 'heroicon-o-beaker']
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
                {{-- VISTA: CATÁLOGO EXPLORADOR --}}
                @if($tabActiva === 'mejoras')
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @forelse($this->mejoras as $mejora)
                            <div class="os-glass os-card rounded-[2rem] p-6 flex flex-col border-transparent hover:border-indigo-500/30">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="p-3.5 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600">
                                        @if(($mejora['icono_resuelto']['tipo'] ?? 'blade') === 'fa')
                                            <i class="{{ $mejora['icono_resuelto']['valor'] }} h-7 w-7"></i>
                                        @else
                                            <x-dynamic-component :component="$mejora['icono_resuelto']['valor'] ?? 'heroicon-o-puzzle-piece'" class="h-7 w-7" />
                                        @endif
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
                            @if(($this->detalleMejora['icono_resuelto']['tipo'] ?? 'blade') === 'fa')
                                <i class="{{ $this->detalleMejora['icono_resuelto']['valor'] }} h-9 w-9 text-white"></i>
                            @else
                                <x-dynamic-component :component="$this->detalleMejora['icono_resuelto']['valor'] ?? 'heroicon-o-cpu-chip'" class="h-9 w-9 text-white" />
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $this->detalleMejora['titulo'] }}</h2>
                                <span class="px-3 py-1 rounded-lg bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-[10px] font-black uppercase">Core v1.0</span>
                            </div>
                            <p class="text-sm font-bold text-indigo-600/70 uppercase mt-1 tracking-widest">{{ $this->detalleMejora['subtitulo'] }}</p>
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
</x-app.container>
@endvolt
</x-layouts.empty>
