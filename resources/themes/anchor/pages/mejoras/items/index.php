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

<x-layouts.app>
    @volt('mejoras.items')
    <x-app.container class="max-w-[1700px] py-6">
        <style>
            .glass {
                background: rgba(255,255,255,0.66);
                backdrop-filter: blur(22px) saturate(170%);
                border: 1px solid rgba(148, 163, 184, 0.28);
                box-shadow: 0 20px 40px -25px rgba(15, 23, 42, 0.35);
            }
            .dark .glass {
                background: rgba(10, 12, 18, 0.6);
                border-color: rgba(71, 85, 105, 0.35);
            }
        </style>

        <div class="space-y-5">
            <div class="glass rounded-3xl p-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white">Editor de módulos JSON</h1>
                    <p class="text-sm text-slate-500">Administración de mejoras con UX estilo liquid glass.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="descartarCambios" class="px-4 py-2 rounded-xl text-sm font-bold border border-slate-300 dark:border-zinc-700">Descartar</button>
                    <button wire:click="guardar" class="px-4 py-2 rounded-xl text-sm font-bold bg-indigo-600 text-white">Guardar</button>
                </div>
            </div>

            @if ($mensajeExito !== '')
                <div class="glass rounded-2xl p-3 text-emerald-600 text-sm font-semibold">{{ $mensajeExito }}</div>
            @endif
            @if ($mensajeError !== '')
                <div class="glass rounded-2xl p-3 text-rose-600 text-sm font-semibold">{{ $mensajeError }}</div>
            @endif
            @if ($errorParseo !== '')
                <div class="glass rounded-2xl p-3 text-amber-600 text-sm font-semibold">{{ $errorParseo }}</div>
            @endif

            <div class="grid lg:grid-cols-4 gap-5">
                <aside class="glass rounded-3xl p-4 space-y-4 lg:col-span-1">
                    <h2 class="text-sm font-extrabold uppercase tracking-wide text-slate-500">Archivos JSON</h2>
                    <div class="space-y-2">
                        @foreach ($archivos as $archivo)
                            <button wire:click="seleccionarArchivo('{{ $archivo['nombre'] }}')" class="w-full text-left px-3 py-2 rounded-xl text-sm {{ $archivoSeleccionado === $archivo['nombre'] ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-zinc-900' }}">
                                {{ $archivo['nombre'] }}
                            </button>
                        @endforeach
                    </div>

                    <h2 class="text-sm font-extrabold uppercase tracking-wide text-slate-500 pt-2">Módulos</h2>
                    <div class="space-y-2 max-h-[340px] overflow-auto">
                        @foreach ($modulos as $indice => $modulo)
                            <button wire:click="seleccionarModulo({{ $indice }})" class="w-full text-left px-3 py-2 rounded-xl text-sm {{ $indiceModuloSeleccionado === $indice ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-zinc-900' }}">
                                {{ $modulo['titulo'] ?? 'Módulo '.($indice + 1) }}
                            </button>
                        @endforeach
                    </div>
                </aside>

                <section class="glass rounded-3xl p-5 space-y-6 lg:col-span-2">
                    <div class="grid md:grid-cols-2 gap-4">
                        <label class="space-y-1 text-sm font-semibold">Título
                            <input wire:model.live="form.titulo" class="w-full rounded-xl border-slate-300 dark:bg-zinc-900" />
                        </label>
                        <label class="space-y-1 text-sm font-semibold">Subtítulo
                            <input wire:model.live="form.subtitulo" class="w-full rounded-xl border-slate-300 dark:bg-zinc-900" />
                        </label>
                        <label class="space-y-1 text-sm font-semibold">Estado
                            <select wire:model.live="form.estado" class="w-full rounded-xl border-slate-300 dark:bg-zinc-900">
                                <option value="instalado">Instalado</option>
                                <option value="en progreso">En progreso</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="no instalado">No instalado</option>
                            </select>
                        </label>
                        <label class="space-y-1 text-sm font-semibold">Icono
                            <div class="flex gap-2">
                                <input wire:model.live="form.icono" class="w-full rounded-xl border-slate-300 dark:bg-zinc-900" placeholder="heroicon-o-star" />
                                <button type="button" wire:click="abrirModalIcono" class="px-3 rounded-xl bg-slate-200 dark:bg-zinc-800">Elegir</button>
                            </div>
                        </label>
                    </div>

                    @foreach (['tipos_planes' => 'Tipos de planes', 'etiquetas' => 'Etiquetas', 'categorias' => 'Categorías'] as $campo => $titulo)
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold">{{ $titulo }}</h3>
                                <button type="button" wire:click="agregarItem('{{ $campo }}')" class="text-xs px-2 py-1 rounded-lg bg-indigo-600 text-white">Agregar</button>
                            </div>
                            @foreach (($form[$campo] ?? []) as $i => $valor)
                                <div class="flex gap-2">
                                    <input wire:model.live="form.{{ $campo }}.{{ $i }}" class="w-full rounded-xl border-slate-300 dark:bg-zinc-900" />
                                    <button type="button" wire:click="duplicarItem('{{ $campo }}', {{ $i }})" class="px-2 rounded-lg bg-slate-200 dark:bg-zinc-800">Duplicar</button>
                                    <button type="button" wire:click="eliminarItem('{{ $campo }}', {{ $i }})" class="px-2 rounded-lg bg-rose-500 text-white">Eliminar</button>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold">Precios</h3>
                            <button type="button" wire:click="agregarItem('precios')" class="text-xs px-2 py-1 rounded-lg bg-indigo-600 text-white">Agregar</button>
                        </div>
                        @foreach (($form['precios'] ?? []) as $i => $precio)
                            <div class="grid md:grid-cols-4 gap-2 p-3 rounded-2xl bg-slate-100 dark:bg-zinc-900">
                                <input wire:model.live="form.precios.{{ $i }}.concepto" placeholder="Concepto" class="rounded-xl border-slate-300 dark:bg-zinc-800" />
                                <input wire:model.live="form.precios.{{ $i }}.monto" placeholder="Monto" class="rounded-xl border-slate-300 dark:bg-zinc-800" />
                                <input wire:model.live="form.precios.{{ $i }}.periodicidad" placeholder="Periodicidad" class="rounded-xl border-slate-300 dark:bg-zinc-800" />
                                <input wire:model.live="form.precios.{{ $i }}.detalle" placeholder="Detalle" class="rounded-xl border-slate-300 dark:bg-zinc-800" />
                                <div class="md:col-span-4 flex gap-2 justify-end">
                                    <button type="button" wire:click="duplicarItem('precios', {{ $i }})" class="px-2 py-1 rounded-lg bg-slate-200 dark:bg-zinc-700">Duplicar bloque</button>
                                    <button type="button" wire:click="eliminarItem('precios', {{ $i }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white">Eliminar bloque</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold">Ejemplos HTML</h3>
                            <button type="button" wire:click="agregarItem('ejemplos_html')" class="text-xs px-2 py-1 rounded-lg bg-indigo-600 text-white">Agregar</button>
                        </div>
                        @foreach (($form['ejemplos_html'] ?? []) as $i => $ejemplo)
                            <div class="space-y-2 p-3 rounded-2xl bg-slate-100 dark:bg-zinc-900">
                                <input wire:model.live="form.ejemplos_html.{{ $i }}.titulo" placeholder="Título del bloque" class="w-full rounded-xl border-slate-300 dark:bg-zinc-800" />
                                <textarea wire:model.live="form.ejemplos_html.{{ $i }}.html" wire:keyup="actualizarPreview" rows="6" class="w-full rounded-xl border-slate-300 dark:bg-zinc-800 font-mono text-xs" placeholder="<section>...</section>"></textarea>
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="duplicarItem('ejemplos_html', {{ $i }})" class="px-2 py-1 rounded-lg bg-slate-200 dark:bg-zinc-700">Duplicar bloque</button>
                                    <button type="button" wire:click="eliminarItem('ejemplos_html', {{ $i }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white">Eliminar bloque</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold">Tareas</h3>
                            <button type="button" wire:click="agregarItem('tareas')" class="text-xs px-2 py-1 rounded-lg bg-indigo-600 text-white">Agregar</button>
                        </div>
                        @foreach (($form['tareas'] ?? []) as $i => $tarea)
                            <div class="grid md:grid-cols-3 gap-2 p-3 rounded-2xl bg-slate-100 dark:bg-zinc-900">
                                <input wire:model.live="form.tareas.{{ $i }}.titulo" placeholder="Título" class="rounded-xl border-slate-300 dark:bg-zinc-800" />
                                <input wire:model.live="form.tareas.{{ $i }}.descripcion" placeholder="Descripción" class="rounded-xl border-slate-300 dark:bg-zinc-800" />
                                <select wire:model.live="form.tareas.{{ $i }}.estado" class="rounded-xl border-slate-300 dark:bg-zinc-800">
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en progreso">En progreso</option>
                                    <option value="completa">Completa</option>
                                </select>
                                <div class="md:col-span-3 flex justify-end gap-2">
                                    <button type="button" wire:click="duplicarItem('tareas', {{ $i }})" class="px-2 py-1 rounded-lg bg-slate-200 dark:bg-zinc-700">Duplicar bloque</button>
                                    <button type="button" wire:click="eliminarItem('tareas', {{ $i }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white">Eliminar bloque</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold">Datos personalizados</h3>
                            <button type="button" wire:click="abrirModalDatoPersonalizado" class="text-xs px-2 py-1 rounded-lg bg-indigo-600 text-white">Agregar en modal</button>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-100 dark:bg-zinc-900 text-xs text-slate-500">
                            Ejemplo para HTML: <code>{{ $ejemploDatoPersonalizado }}</code>
                        </div>
                        @foreach (($form['dato_personalizado'] ?? []) as $clave => $valor)
                            <div class="grid grid-cols-3 gap-2">
                                <input value="{{ $clave }}" disabled class="rounded-xl border-slate-300 bg-slate-100 dark:bg-zinc-800" />
                                <input wire:model.live="form.dato_personalizado.{{ $clave }}" class="col-span-2 rounded-xl border-slate-300 dark:bg-zinc-900" />
                            </div>
                        @endforeach
                    </div>
                </section>

                <aside class="glass rounded-3xl p-4 space-y-3 lg:col-span-1">
                    <h2 class="font-black">Previsualización segura</h2>
                    <p class="text-xs text-slate-500">El HTML se sanitiza para evitar scripts y atributos peligrosos.</p>
                    <div class="rounded-2xl p-4 bg-white/80 dark:bg-zinc-900/80 border border-slate-200 dark:border-zinc-700 min-h-[420px] overflow-auto">
                        {!! $htmlPrevisualizacion !!}
                    </div>
                </aside>
            </div>
        </div>

        @if ($mostrarModalIcono)
            <div class="fixed inset-0 z-50 bg-slate-950/50 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="glass rounded-3xl w-full max-w-2xl p-5 space-y-4">
                    <h3 class="font-black">Seleccionar icono</h3>
                    <input wire:model.live="busquedaIcono" placeholder="Buscar icono..." class="w-full rounded-xl border-slate-300 dark:bg-zinc-900" />
                    <div class="grid sm:grid-cols-2 gap-2 max-h-72 overflow-auto">
                        @foreach ($iconosDisponibles as $icono)
                            @if (str_contains($icono, $busquedaIcono))
                                <button wire:click="aplicarIcono('{{ $icono }}')" class="text-left px-3 py-2 rounded-xl bg-slate-100 dark:bg-zinc-900">
                                    <div class="font-semibold">{{ $icono }}</div>
                                    <div class="text-xs text-slate-500">Vista previa: {{ $icono }}</div>
                                </button>
                            @endif
                        @endforeach
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold">Icono personalizado en texto</label>
                        <input wire:model.live="iconoPersonalizado" class="w-full rounded-xl border-slate-300 dark:bg-zinc-900" />
                    </div>
                    <div class="flex justify-end gap-2">
                        <button wire:click="$set('mostrarModalIcono', false)" class="px-3 py-2 rounded-xl border">Cancelar</button>
                        <button wire:click="aplicarIconoPersonalizado" class="px-3 py-2 rounded-xl bg-indigo-600 text-white">Aplicar</button>
                    </div>
                </div>
            </div>
        @endif

        @if ($mostrarModalDatoPersonalizado)
            <div class="fixed inset-0 z-50 bg-slate-950/50 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="glass rounded-3xl w-full max-w-xl p-5 space-y-4">
                    <h3 class="font-black">Nuevo dato personalizado</h3>
                    <label class="space-y-1 text-sm font-semibold">Clave
                        <input wire:model.live="nuevaClavePersonalizada" class="w-full rounded-xl border-slate-300 dark:bg-zinc-900" placeholder="promocion_primavera" />
                    </label>
                    <label class="space-y-1 text-sm font-semibold">Valor
                        <input wire:model.live="nuevoValorPersonalizado" class="w-full rounded-xl border-slate-300 dark:bg-zinc-900" placeholder="-20%" />
                    </label>
                    <p class="text-xs text-slate-500">Uso recomendado en HTML: <code>{{ '{' }}{ dato_personalizado.tu_clave }}{{ '}' }}</code></p>
                    <div class="flex justify-end gap-2">
                        <button wire:click="$set('mostrarModalDatoPersonalizado', false)" class="px-3 py-2 rounded-xl border">Cancelar</button>
                        <button wire:click="agregarDatoPersonalizado" class="px-3 py-2 rounded-xl bg-indigo-600 text-white">Agregar</button>
                    </div>
                </div>
            </div>
        @endif
    </x-app.container>
    @endvolt
</x-layouts.app>
