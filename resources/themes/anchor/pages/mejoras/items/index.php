<?php

declare(strict_types=1);

use function Laravel\Folio\name;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

\Laravel\Folio\middleware('auth');
name('mejoras.items');

new class extends Component {
    public array $archivos = [];

    public ?string $archivoSeleccionado = null;

    public int $indiceModuloSeleccionado = 0;

    public array $modulos = [];

    public array $form = [];

    public string $mensajeExito = '';

    public string $mensajeError = '';

    public string $busquedaIcono = '';

    public string $iconoPersonalizado = '';

    public bool $mostrarModalIcono = false;

    public bool $mostrarModalDatoPersonalizado = false;

    public string $nuevaClavePersonalizada = '';

    public string $nuevoValorPersonalizado = '';

    public string $ejemploDatoPersonalizado = "{{ dato_personalizado.promocion }}";

    public string $htmlPrevisualizacion = '';

    public string $errorParseo = '';

    public array $iconosDisponibles = [
        'heroicon-o-sparkles',
        'heroicon-o-bolt',
        'heroicon-o-rocket-launch',
        'heroicon-o-star',
        'heroicon-o-cpu-chip',
        'heroicon-o-command-line',
        'heroicon-o-chart-bar',
        'heroicon-o-beaker',
        'heroicon-o-puzzle-piece',
        'heroicon-o-shopping-bag',
        'heroicon-o-gift',
        'heroicon-o-fire',
        'heroicon-o-shield-check',
        'heroicon-o-light-bulb',
    ];

    public function mount(): void
    {
        $this->cargarArchivos();

        if ($this->archivoSeleccionado !== null) {
            $this->cargarArchivo($this->archivoSeleccionado);
        }
    }

    public function cargarArchivos(): void
    {
        $files = File::glob(resource_path('themes/anchor/pages/mejoras/items/*.json')) ?: [];

        $this->archivos = collect($files)
            ->map(fn (string $ruta): array => [
                'nombre' => basename($ruta),
                'ruta' => $ruta,
            ])
            ->values()
            ->all();

        if ($this->archivoSeleccionado === null && count($this->archivos) > 0) {
            $this->archivoSeleccionado = $this->archivos[0]['nombre'];
        }
    }

    public function seleccionarArchivo(string $archivo): void
    {
        $this->archivoSeleccionado = $archivo;
        $this->indiceModuloSeleccionado = 0;
        $this->mensajeExito = '';
        $this->mensajeError = '';
        $this->errorParseo = '';

        $this->cargarArchivo($archivo);
    }

    public function seleccionarModulo(int $indice): void
    {
        if (! isset($this->modulos[$indice])) {
            return;
        }

        $this->indiceModuloSeleccionado = $indice;
        $this->hidratarFormularioDesdeModulo();
    }

    public function cargarArchivo(string $archivo): void
    {
        $ruta = resource_path('themes/anchor/pages/mejoras/items/'.$archivo);

        if (! File::exists($ruta)) {
            $this->mensajeError = 'No se encontró el archivo JSON seleccionado.';
            $this->modulos = [];
            $this->form = [];

            return;
        }

        $contenido = File::get($ruta);
        $decodificado = json_decode($contenido, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decodificado)) {
            $this->errorParseo = 'No se pudo interpretar el JSON: '.json_last_error_msg();
            $this->mensajeError = 'Corrige el archivo antes de editarlo desde esta vista.';
            $this->modulos = [];
            $this->form = [];

            return;
        }

        $this->errorParseo = '';
        $this->modulos = array_is_list($decodificado) ? $decodificado : [$decodificado];
        $this->indiceModuloSeleccionado = min($this->indiceModuloSeleccionado, max(count($this->modulos) - 1, 0));
        $this->hidratarFormularioDesdeModulo();
    }

    public function hidratarFormularioDesdeModulo(): void
    {
        $modulo = $this->modulos[$this->indiceModuloSeleccionado] ?? [];

        $this->form = [
            'id' => (string) ($modulo['id'] ?? Str::slug((string) ($modulo['titulo'] ?? 'modulo'))),
            'titulo' => (string) ($modulo['titulo'] ?? ''),
            'subtitulo' => (string) ($modulo['subtitulo'] ?? ''),
            'estado' => (string) ($modulo['estado'] ?? 'pendiente'),
            'icono' => (string) ($modulo['icono'] ?? ''),
            'tipos_planes' => $this->normalizarListaTexto($modulo['tipos_planes'] ?? []),
            'etiquetas' => $this->normalizarListaTexto($modulo['etiquetas'] ?? []),
            'categorias' => $this->normalizarListaTexto($modulo['categorias'] ?? []),
            'precios' => $this->normalizarPrecios($modulo['precios'] ?? []),
            'ejemplos_html' => $this->normalizarEjemplosHtml($modulo['ejemplos_html'] ?? []),
            'tareas' => $this->normalizarTareas($modulo['tareas'] ?? []),
            'dato_personalizado' => $this->normalizarDatosPersonalizados($modulo['dato_personalizado'] ?? []),
        ];

        $this->actualizarPreview();
    }

    public function actualizarPreview(): void
    {
        $html = (string) (($this->form['ejemplos_html'][0]['html'] ?? '') ?: '');
        $this->htmlPrevisualizacion = $this->sanitizarHtml($html);
    }

    public function abrirModalIcono(): void
    {
        $this->iconoPersonalizado = (string) ($this->form['icono'] ?? '');
        $this->mostrarModalIcono = true;
    }

    public function aplicarIcono(string $icono): void
    {
        $this->form['icono'] = $icono;
        $this->iconoPersonalizado = $icono;
        $this->mostrarModalIcono = false;
    }

    public function aplicarIconoPersonalizado(): void
    {
        $this->form['icono'] = trim($this->iconoPersonalizado);
        $this->mostrarModalIcono = false;
    }

    public function abrirModalDatoPersonalizado(): void
    {
        $this->mostrarModalDatoPersonalizado = true;
    }

    public function agregarDatoPersonalizado(): void
    {
        $clave = Str::slug(trim($this->nuevaClavePersonalizada), '_');

        if ($clave === '' || trim($this->nuevoValorPersonalizado) === '') {
            $this->mensajeError = 'Completa la clave y el valor del dato personalizado.';

            return;
        }

        $this->form['dato_personalizado'][$clave] = trim($this->nuevoValorPersonalizado);
        $this->ejemploDatoPersonalizado = "{{ dato_personalizado.{$clave} }}";
        $this->nuevaClavePersonalizada = '';
        $this->nuevoValorPersonalizado = '';
        $this->mostrarModalDatoPersonalizado = false;
    }

    public function agregarItem(string $campo): void
    {
        if (! isset($this->form[$campo])) {
            return;
        }

        $this->form[$campo][] = in_array($campo, ['precios', 'ejemplos_html', 'tareas'], true)
            ? $this->plantillaEstructurada($campo)
            : '';
    }

    public function duplicarItem(string $campo, int $indice): void
    {
        if (! isset($this->form[$campo][$indice])) {
            return;
        }

        array_splice($this->form[$campo], $indice + 1, 0, [$this->form[$campo][$indice]]);
        $this->actualizarPreview();
    }

    public function eliminarItem(string $campo, int $indice): void
    {
        if (! isset($this->form[$campo][$indice])) {
            return;
        }

        unset($this->form[$campo][$indice]);
        $this->form[$campo] = array_values($this->form[$campo]);
        $this->actualizarPreview();
    }

    public function descartarCambios(): void
    {
        $this->mensajeExito = '';
        $this->mensajeError = '';
        $this->hidratarFormularioDesdeModulo();
    }

    public function guardar(): void
    {
        $this->mensajeExito = '';
        $this->mensajeError = '';

        $this->validarFormulario();

        if ($this->mensajeError !== '') {
            return;
        }

        $this->modulos[$this->indiceModuloSeleccionado] = $this->formatearModuloParaGuardar($this->form);

        $json = json_encode($this->modulos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            $this->mensajeError = 'No fue posible serializar el JSON del módulo.';

            return;
        }

        $ruta = resource_path('themes/anchor/pages/mejoras/items/'.$this->archivoSeleccionado);
        $tmp = $ruta.'.tmp';

        try {
            File::put($tmp, $json.PHP_EOL);
            File::move($tmp, $ruta);
            $this->mensajeExito = 'Cambios guardados correctamente.';
        } catch (\Throwable $e) {
            if (File::exists($tmp)) {
                File::delete($tmp);
            }

            $this->mensajeError = 'Ocurrió un error durante el guardado atómico: '.$e->getMessage();
        }
    }

    protected function validarFormulario(): void
    {
        if (trim((string) ($this->form['titulo'] ?? '')) === '') {
            $this->mensajeError = 'El título es obligatorio.';

            return;
        }

        if (! in_array($this->form['estado'] ?? '', ['instalado', 'en progreso', 'pendiente', 'no instalado'], true)) {
            $this->mensajeError = 'Selecciona un estado válido.';

            return;
        }

        foreach (($this->form['precios'] ?? []) as $precio) {
            if (trim((string) ($precio['concepto'] ?? '')) === '' || trim((string) ($precio['monto'] ?? '')) === '') {
                $this->mensajeError = 'Cada precio debe tener concepto y monto.';

                return;
            }
        }
    }

    protected function plantillaEstructurada(string $campo): array
    {
        return match ($campo) {
            'precios' => ['concepto' => '', 'monto' => '', 'periodicidad' => '', 'detalle' => ''],
            'ejemplos_html' => ['titulo' => '', 'html' => ''],
            'tareas' => ['titulo' => '', 'descripcion' => '', 'estado' => 'pendiente'],
            default => [],
        };
    }

    protected function normalizarListaTexto(mixed $valor): array
    {
        if (is_string($valor)) {
            return [$valor];
        }

        if (! is_array($valor)) {
            return [];
        }

        return collect($valor)
            ->map(fn (mixed $item): string => trim((string) $item))
            ->filter(fn (string $item): bool => $item !== '')
            ->values()
            ->all();
    }

    protected function normalizarPrecios(mixed $valor): array
    {
        if (! is_array($valor)) {
            return [];
        }

        return collect($valor)
            ->filter(fn ($item): bool => is_array($item))
            ->map(fn (array $item): array => [
                'concepto' => (string) ($item['concepto'] ?? ''),
                'monto' => (string) ($item['monto'] ?? ''),
                'periodicidad' => (string) ($item['periodicidad'] ?? ''),
                'detalle' => (string) ($item['detalle'] ?? ''),
            ])
            ->values()
            ->all();
    }

    protected function normalizarEjemplosHtml(mixed $valor): array
    {
        if (! is_array($valor)) {
            return [];
        }

        return collect($valor)
            ->map(function (mixed $item): array {
                if (is_string($item)) {
                    return ['titulo' => 'Ejemplo', 'html' => $item];
                }

                if (! is_array($item)) {
                    return ['titulo' => 'Ejemplo', 'html' => ''];
                }

                return [
                    'titulo' => (string) ($item['titulo'] ?? 'Ejemplo'),
                    'html' => (string) ($item['html'] ?? ''),
                ];
            })
            ->values()
            ->all();
    }

    protected function normalizarTareas(mixed $valor): array
    {
        if (! is_array($valor)) {
            return [];
        }

        return collect($valor)
            ->filter(fn ($item): bool => is_array($item))
            ->map(fn (array $item): array => [
                'titulo' => (string) ($item['titulo'] ?? ''),
                'descripcion' => (string) ($item['descripcion'] ?? ''),
                'estado' => (string) ($item['estado'] ?? 'pendiente'),
            ])
            ->values()
            ->all();
    }

    protected function normalizarDatosPersonalizados(mixed $valor): array
    {
        if (! is_array($valor)) {
            return [];
        }

        return collect($valor)
            ->mapWithKeys(fn (mixed $item, mixed $key): array => [Str::slug((string) $key, '_') => (string) $item])
            ->all();
    }

    protected function formatearModuloParaGuardar(array $form): array
    {
        $form['id'] = Str::slug((string) ($form['id'] ?: $form['titulo']));
        $form['tipos_planes'] = $this->normalizarListaTexto($form['tipos_planes'] ?? []);
        $form['etiquetas'] = $this->normalizarListaTexto($form['etiquetas'] ?? []);
        $form['categorias'] = $this->normalizarListaTexto($form['categorias'] ?? []);

        return $form;
    }

    protected function sanitizarHtml(string $html): string
    {
        $limpio = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html) ?? '';
        $limpio = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $limpio) ?? '';
        $limpio = preg_replace('/on[a-z]+\s*=\s*"[^"]*"/i', '', $limpio) ?? '';
        $limpio = preg_replace('/on[a-z]+\s*=\s*\'[^\']*\'/i', '', $limpio) ?? '';
        $limpio = preg_replace('/javascript:/i', '', $limpio) ?? '';

        return strip_tags($limpio, '<div><section><article><header><footer><main><p><span><strong><em><ul><ol><li><a><button><h1><h2><h3><h4><h5><h6><br><hr><code><pre><small><img>');
    }
};
?>

<x-layouts.empty>
    @volt('mejoras.items')
    <x-app.container class="max-w-[1800px] py-8">
        {{-- Refinamiento Estético Liquid Glass --}}
        <style>
            .liquid-glass {
                background: rgba(255, 255, 255, 0.45);
                backdrop-filter: blur(25px) saturate(180%);
                -webkit-backdrop-filter: blur(25px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
            }
            .dark .liquid-glass {
                background: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4);
            }
            .glass-input {
                background: rgba(255, 255, 255, 0.5) !important;
                border: 1px solid rgba(148, 163, 184, 0.2) !important;
                transition: all 0.3s ease;
            }
            .dark .glass-input {
                background: rgba(30, 41, 59, 0.5) !important;
                border: 1px solid rgba(71, 85, 105, 0.3) !important;
            }
            .glass-input:focus {
                background: rgba(255, 255, 255, 0.8) !important;
                border-color: #6366f1 !important;
                ring: 2px ring rgba(99, 102, 241, 0.2);
            }
            /* Scrollbar personalizado */
            .custom-scroll::-webkit-scrollbar { width: 5px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.3); border-radius: 10px; }
            
            /* Añade esto a tu sección de estilos existente */
body {
    background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop');
    background-size: cover;
    background-position: center;
    background-attachment: fixed; /* Crucial para el efecto glass al hacer scroll */
    background-repeat: no-repeat;
}

/* Ajuste opcional: añade una capa de tinte para mejorar el contraste */
body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.1); /* Tinte claro para modo luz */
    z-index: -1;
}

.dark body::before {
    background: rgba(15, 23, 42, 0.4); /* Tinte oscuro para modo noche */
}
        </style>

        <div class="space-y-6">
            {{-- HEADER DINÁMICO --}}
            <header class="liquid-glass rounded-[2.5rem] p-6 flex flex-wrap items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <x-heroicon-s-cpu-chip class="h-8 w-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Core Modules Builder</h1>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-500 dark:text-indigo-400">JSON Infrastructure v2.0</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <button wire:click="descartarCambios" class="px-6 py-2.5 rounded-2xl text-sm font-bold text-slate-600 hover:bg-white/50 dark:text-slate-300 transition-all">
                        Descartar
                    </button>
                    <button wire:click="guardar" class="px-8 py-2.5 rounded-2xl text-sm font-black bg-slate-900 text-white hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-500 shadow-xl shadow-indigo-500/10 transition-all active:scale-95">
                        Sincronizar Cambios
                    </button>
                </div>
            </header>

            {{-- ALERTAS --}}
            @if ($mensajeExito || $mensajeError || $errorParseo)
                <div class="grid gap-3">
                    @if ($mensajeExito) <div class="liquid-glass border-emerald-500/50 rounded-2xl p-4 text-emerald-600 dark:text-emerald-400 text-sm font-bold flex items-center gap-2"><x-heroicon-s-check-circle class="h-5 w-5"/> {{ $mensajeExito }}</div> @endif
                    @if ($mensajeError) <div class="liquid-glass border-rose-500/50 rounded-2xl p-4 text-rose-600 text-sm font-bold flex items-center gap-2"><x-heroicon-s-x-circle class="h-5 w-5"/> {{ $mensajeError }}</div> @endif
                </div>
            @endif

            <div class="grid lg:grid-cols-12 gap-6">
                
                {{-- COLUMNA IZQUIERDA: ESTRUCTURA --}}
                <aside class="lg:col-span-3 space-y-6">
                    {{-- Archivos --}}
                    <div class="liquid-glass rounded-[2rem] p-5">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Archivos Fuente</h2>
                            <button wire:click="nuevoArchivo" class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-all">
                                <x-heroicon-s-plus class="h-4 w-4"/>
                            </button>
                        </div>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto custom-scroll pr-2">
                            @foreach ($archivos as $archivo)
                                <button wire:click="seleccionarArchivo('{{ $archivo['nombre'] }}')" 
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ $archivoSeleccionado === $archivo['nombre'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-white/40 dark:text-slate-400 dark:hover:bg-slate-800/40' }}">
                                    <x-heroicon-o-document-text class="h-4 w-4 opacity-70"/>
                                    <span class="truncate">{{ $archivo['nombre'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Módulos del Archivo --}}
                    <div class="liquid-glass rounded-[2rem] p-5">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Módulos Activos</h2>
                            @if($archivoSeleccionado)
                                <button wire:click="nuevoModulo" class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all">
                                    <x-heroicon-s-plus class="h-4 w-4"/>
                                </button>
                            @endif
                        </div>
                        <div class="space-y-1.5 max-h-[400px] overflow-y-auto custom-scroll pr-2">
                            @forelse ($modulos as $indice => $modulo)
                                <button wire:click="seleccionarModulo({{ $indice }})" 
                                    class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ $indiceModuloSeleccionado === $indice ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white/30 dark:bg-slate-800/30 text-slate-600 dark:text-slate-400 hover:border-emerald-500/50 border border-transparent' }}">
                                    <span class="truncate">{{ $modulo['titulo'] ?? 'Módulo sin nombre' }}</span>
                                    <x-heroicon-s-chevron-right class="h-4 w-4 opacity-50"/>
                                </button>
                            @empty
                                <p class="text-center py-4 text-xs font-medium text-slate-400 italic">Seleccione un archivo para ver módulos</p>
                            @endforelse
                        </div>
                    </div>
                </aside>

                {{-- COLUMNA CENTRAL: EDITOR --}}
                <main class="lg:col-span-6 space-y-6">
                    <div class="liquid-glass rounded-[2.5rem] p-8">
                        @if($indiceModuloSeleccionado !== null)
                            <div class="space-y-8">
                                {{-- Sección General --}}
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Título del Módulo</label>
                                        <input wire:model.live="form.titulo" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-bold" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Subtítulo / Lead</label>
                                        <input wire:model.live="form.subtitulo" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-bold" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Estado de Implementación</label>
                                        <select wire:model.live="form.estado" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-bold">
                                            <option value="instalado">✅ Instalado</option>
                                            <option value="en progreso">🚧 En progreso</option>
                                            <option value="pendiente">⏳ Pendiente</option>
                                            <option value="no instalado">❌ No instalado</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Identificador de Icono</label>
                                        <div class="flex gap-2">
                                            <input wire:model.live="form.icono" class="glass-input flex-1 rounded-2xl px-4 py-3 text-sm font-mono" />
                                            <button wire:click="abrirModalIcono" class="px-4 rounded-2xl bg-indigo-500/10 text-indigo-600 text-xs font-black uppercase hover:bg-indigo-500 hover:text-white transition-all">Explorar</button>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-slate-200 dark:border-slate-700/50">

                                {{-- Listas Dinámicas --}}
                                @foreach (['tipos_planes' => 'Arquitectura de Planes', 'etiquetas' => 'Keywords & SEO', 'categorias' => 'Taxonomía'] as $campo => $titulo)
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center px-2">
                                            <h3 class="text-xs font-black uppercase tracking-widest text-indigo-500">{{ $titulo }}</h3>
                                            <button wire:click="agregarItem('{{ $campo }}')" class="flex items-center gap-1 text-[10px] font-black bg-indigo-500 text-white px-3 py-1 rounded-full uppercase">Nuevo</button>
                                        </div>
                                        <div class="grid gap-3">
                                            @foreach (($form[$campo] ?? []) as $i => $valor)
                                                <div class="flex gap-2 group">
                                                    <input wire:model.live="form.{{ $campo }}.{{ $i }}" class="glass-input flex-1 rounded-xl px-4 py-2 text-sm" />
                                                    <button wire:click="duplicarItem('{{ $campo }}', {{ $i }})" class="p-2 opacity-0 group-hover:opacity-100 bg-slate-100 dark:bg-slate-800 rounded-xl transition-all"><x-heroicon-o-document-duplicate class="h-4 w-4"/></button>
                                                    <button wire:click="eliminarItem('{{ $campo }}', {{ $i }})" class="p-2 bg-rose-500/10 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all"><x-heroicon-s-trash class="h-4 w-4"/></button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Ejemplos HTML --}}
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center px-2">
                                        <h3 class="text-xs font-black uppercase tracking-widest text-indigo-500">Bloques de Código (HTML)</h3>
                                        <button wire:click="agregarItem('ejemplos_html')" class="text-[10px] font-black bg-indigo-500 text-white px-3 py-1 rounded-full uppercase">Añadir Sandbox</button>
                                    </div>
                                    @foreach (($form['ejemplos_html'] ?? []) as $i => $ejemplo)
                                        <div class="liquid-glass border-slate-200/50 rounded-2xl p-4 space-y-3">
                                            <input wire:model.live="form.ejemplos_html.{{ $i }}.titulo" placeholder="Nombre del bloque..." class="glass-input w-full rounded-xl px-4 py-2 text-sm font-bold" />
                                            <textarea wire:model.live="form.ejemplos_html.{{ $i }}.html" wire:keyup="actualizarPreview" rows="5" class="glass-input w-full rounded-xl px-4 py-3 text-xs font-mono" placeholder="<div class='...'></div>"></textarea>
                                            <div class="flex justify-end gap-2">
                                                <button wire:click="duplicarItem('ejemplos_html', {{ $i }})" class="text-[10px] font-bold px-3 py-1 bg-slate-200 dark:bg-slate-700 rounded-lg">Duplicar</button>
                                                <button wire:click="eliminarItem('ejemplos_html', {{ $i }})" class="text-[10px] font-bold px-3 py-1 bg-rose-500 text-white rounded-lg">Eliminar</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="h-96 flex flex-col items-center justify-center text-center space-y-4">
                                <div class="h-20 w-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300">
                                    <x-heroicon-o-cursor-arrow-ripple class="h-10 w-10"/>
                                </div>
                                <p class="text-slate-500 font-medium">Seleccione un módulo para comenzar la edición</p>
                            </div>
                        @endif
                    </div>
                </main>

                {{-- COLUMNA DERECHA: PREVIEW --}}
                <aside class="lg:col-span-3 space-y-6">
                    <div class="liquid-glass rounded-[2rem] p-6 sticky top-8">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Live Preview (Sanitized)</h2>
                        </div>
                        
                        <div class="rounded-2xl bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 p-4 min-h-[500px] overflow-auto custom-scroll shadow-inner">
                            @if($htmlPrevisualizacion)
                                {!! $htmlPrevisualizacion !!}
                            @else
                                <div class="h-full flex items-center justify-center italic text-slate-400 text-xs">Sin contenido HTML</div>
                            @endif
                        </div>

                        {{-- Datos Personalizados Quick Access --}}
                        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700/50">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-[10px] font-black uppercase text-slate-400">Tokens personalizados</h3>
                                <button wire:click="abrirModalDatoPersonalizado" class="p-1 rounded bg-indigo-500 text-white"><x-heroicon-s-plus class="h-3 w-3"/></button>
                            </div>
                            <div class="space-y-2">
                                @foreach (($form['dato_personalizado'] ?? []) as $clave => $valor)
                                    <div class="flex flex-col p-2 bg-indigo-500/5 rounded-lg border border-indigo-500/10">
                                        <span class="text-[9px] font-black text-indigo-500 truncate">@{{ {{ $clave }} }}</span>
                                        <input wire:model.live="form.dato_personalizado.{{ $clave }}" class="bg-transparent border-none p-0 text-xs font-bold focus:ring-0 text-slate-700 dark:text-slate-300" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        {{-- MODAL ICONOS (Liquid Style) --}}
        @if ($mostrarModalIcono)
            <div class="fixed inset-0 z-50 backdrop-blur-md flex items-center justify-center p-6 bg-slate-950/20">
                <div class="liquid-glass rounded-[3rem] w-full max-w-2xl p-8 shadow-2xl border-white/50">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-xl font-black">Librería de Assets</h3>
                            <p class="text-xs text-slate-500">Seleccione un icono de Heroicons para su módulo</p>
                        </div>
                        <button wire:click="$set('mostrarModalIcono', false)" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                            <x-heroicon-s-x-mark class="h-6 w-6"/>
                        </button>
                    </div>
                    
                    <input wire:model.live="busquedaIcono" placeholder="Filtrar por nombre (ej: star, user, home...)" class="glass-input w-full rounded-2xl px-6 py-4 mb-6 text-sm font-bold" />
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-80 overflow-y-auto custom-scroll pr-2 mb-6">
                        @foreach ($iconosDisponibles as $icono)
                            @if (str_contains($icono, $busquedaIcono))
                                <button wire:click="aplicarIcono('{{ $icono }}')" class="flex items-center gap-3 p-3 rounded-2xl bg-white/40 dark:bg-slate-800/40 border border-transparent hover:border-indigo-500 transition-all text-left">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500 font-bold text-xs">
                                        Icon
                                    </div>
                                    <span class="text-xs font-bold truncate">{{ $icono }}</span>
                                </button>
                            @endif
                        @endforeach
                    </div>

                    <div class="flex gap-4">
                        <input wire:model.live="iconoPersonalizado" placeholder="O pegue identificador manual..." class="glass-input flex-1 rounded-2xl px-4 py-3 text-sm font-mono" />
                        <button wire:click="aplicarIconoPersonalizado" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:shadow-lg hover:shadow-indigo-500/30 transition-all">Vincular</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- MODAL NUEVO DATO --}}
        @if ($mostrarModalDatoPersonalizado)
            <div class="fixed inset-0 z-50 backdrop-blur-md flex items-center justify-center p-6 bg-slate-950/20">
                <div class="liquid-glass rounded-[3rem] w-full max-w-md p-8 shadow-2xl">
                    <h3 class="text-xl font-black mb-6">Nuevo Token Dinámico</h3>
                    <div class="space-y-4 mb-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Clave (Sin espacios)</label>
                            <input wire:model.live="nuevaClavePersonalizada" placeholder="ej: color_destacado" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-mono" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Valor Inicial</label>
                            <input wire:model.live="nuevoValorPersonalizado" placeholder="ej: #FF5500" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-bold" />
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="$set('mostrarModalDatoPersonalizado', false)" class="flex-1 py-3 font-bold text-slate-500">Cancelar</button>
                        <button wire:click="agregarDatoPersonalizado" class="flex-2 px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-indigo-500/20">Registrar Token</button>
                    </div>
                </div>
            </div>
        @endif

    </x-app.container>
    @endvolt
</x-layouts.empty>
