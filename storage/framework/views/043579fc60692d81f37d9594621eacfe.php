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

<?php if (isset($component)) { $__componentOriginalb341a6979a8988bb968367f224ff0c62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb341a6979a8988bb968367f224ff0c62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::layouts.empty','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoibWVqb3Jhcy5pdGVtcyIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvbWVqb3Jhc1wvaXRlbXNcL2luZGV4LmJsYWRlLnBocCJ9", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-2719747878-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb341a6979a8988bb968367f224ff0c62)): ?>
<?php $attributes = $__attributesOriginalb341a6979a8988bb968367f224ff0c62; ?>
<?php unset($__attributesOriginalb341a6979a8988bb968367f224ff0c62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb341a6979a8988bb968367f224ff0c62)): ?>
<?php $component = $__componentOriginalb341a6979a8988bb968367f224ff0c62; ?>
<?php unset($__componentOriginalb341a6979a8988bb968367f224ff0c62); ?>
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/mejoras/items/index.blade.php ENDPATH**/ ?>