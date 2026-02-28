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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoibWVqb3JhcyIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvbWVqb3Jhc1wvaW5kZXguYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-861150856-0', $__slots ?? [], get_defined_vars());

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
<?php endif; ?>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/mejoras/index.blade.php ENDPATH**/ ?>