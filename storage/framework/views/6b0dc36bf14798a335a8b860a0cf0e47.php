<?php

use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\File;

\Laravel\Folio\middleware([
    'auth',
    function ($request, $next) {
        if (! $request->user() || ! $request->user()->hasRole('admin')) {
            abort(403, 'No tenés permisos para acceder a esta sección.');
        }

        return $next($request);
    },
]);

name('editor.index');

new class extends Component {
    public string $root;
    public string $currentDir = '';
    public array $directories = [];
    public array $files = [];
    public ?string $selectedFile = null;
    public string $content = '';
    public string $statusMessage = '';

    public string $newFolderName = '';
    public string $newFileName = '';

    public function mount(): void
    {
        $this->root = base_path('resources/themes/anchor');
        $this->changeDir('');
    }

    protected function absolutePath(string $relative = ''): string
    {
        $relative = trim($relative, '/');

        if (str_contains($relative, '..')) {
            abort(403, 'Ruta no permitida.');
        }

        return $relative === ''
            ? $this->root
            : $this->root . DIRECTORY_SEPARATOR . $relative;
    }

    public function changeDir(string $dir): void
    {
        $dir = trim($dir, '/');
        $absolute = $this->absolutePath($dir);

        if (! File::isDirectory($absolute)) {
            return;
        }

        $this->currentDir   = $dir;
        $this->selectedFile = null;
        $this->content      = '';

        $this->directories = collect(File::directories($absolute))
            ->map(fn ($path) => basename($path))
            ->sort()
            ->values()
            ->all();

        $this->files = collect(File::files($absolute))
            ->map(fn ($file) => $file->getFilename())
            ->sort()
            ->values()
            ->all();
    }

    public function goUp(): void
    {
        if ($this->currentDir === '') {
            return;
        }

        $parent = dirname($this->currentDir);

        if ($parent === '.' || $parent === DIRECTORY_SEPARATOR) {
            $parent = '';
        }

        $this->changeDir($parent);
    }

    public function openFile(string $filename): void
    {
        $filename = trim($filename, '/');

        if ($filename === '') {
            return;
        }

        $relative = $this->currentDir === ''
            ? $filename
            : $this->currentDir . '/' . $filename;

        $absolute = $this->absolutePath($relative);

        if (! File::exists($absolute) || ! File::isFile($absolute)) {
            return;
        }

        $this->selectedFile  = $filename;
        $this->content       = File::get($absolute);
        $this->statusMessage = 'Editando: ' . $this->displayPath();
    }

    public function save(): void
    {
        if (! $this->selectedFile) {
            return;
        }

        $relative = $this->currentDir === ''
            ? $this->selectedFile
            : $this->currentDir . '/' . $this->selectedFile;

        $absolute = $this->absolutePath($relative);

        File::put($absolute, $this->content);

        $this->statusMessage = 'Guardado ✔ ' . now()->format('H:i:s');
    }

    public function createFolder(): void
    {
        $name = trim($this->newFolderName);

        if (
            $name === '' ||
            str_contains($name, '..') ||
            str_contains($name, '/') ||
            str_contains($name, '\\')
        ) {
            return;
        }

        $relative = $this->currentDir === ''
            ? $name
            : $this->currentDir . '/' . $name;

        $absolute = $this->absolutePath($relative);

        if (! File::exists($absolute)) {
            File::makeDirectory($absolute, 0755, true);

            $this->newFolderName = '';
            $this->changeDir($this->currentDir);
            $this->statusMessage = 'Carpeta creada.';
        }
    }

    public function createFile(): void
    {
        $name = trim($this->newFileName);

        if (
            $name === '' ||
            str_contains($name, '..') ||
            str_contains($name, '/') ||
            str_contains($name, '\\')
        ) {
            return;
        }

        // Si no tiene extensión, asumimos .blade.php
        if (! str_contains($name, '.')) {
            $name .= '.blade.php';
        }

        $relative = $this->currentDir === ''
            ? $name
            : $this->currentDir . '/' . $name;

        $absolute = $this->absolutePath($relative);

        if (! File::exists($absolute)) {
            // Archivo completamente vacío, sin estructura de ejemplo
            File::put($absolute, '');
        }

        $this->newFileName = '';
        $this->changeDir($this->currentDir);
        $this->openFile(basename($absolute));
        $this->statusMessage = 'Archivo creado (vacío).';
    }

    public function displayPath(): string
    {
        $dir  = $this->currentDir;
        $file = $this->selectedFile;

        return trim(($dir ? $dir . '/' : '') . ($file ?? ''), '/') ?: '/';
    }
};

?>

<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiZWRpdG9yLmluZGV4IiwicGF0aCI6InJlc291cmNlc1wvdGhlbWVzXC9hbmNob3JcL3BhZ2VzXC9lZGl0b3JcL2luZGV4LmJsYWRlLnBocCJ9", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-4179404865-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>

<?php $__env->startPush('styles'); ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css" xintegrity="sha512-yq0pUWLWyd4InENcy+XUG6uHgnIY7M9xeY4V0z3Y0JVpaz6RtWLpmjHtkobaN6D+PfYZ7R6pujISiFDUFxIr2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/dracula.min.css" xintegrity="sha512-6Lj3VAb9YSEuxsjjXNMTvEihQ/HYMv7svuFj0CQ5G+T9x0ox2I8GHOZfWTLV4niK1hx3F5VnfW4HI7c1xX1v7w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/eclipse.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        /* Ajustes finos para que CodeMirror ocupe todo el espacio y tenga tipografía moderna */
        .CodeMirror {
            height: 100% !important;
            font-family: 'Fira Code', 'JetBrains Mono', 'Menlo', 'Monaco', 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
            padding-top: 10px;
            padding-bottom: 20px;
        }
        /* Bordes sutiles en los números de línea para el modo light */
        .cm-s-eclipse .CodeMirror-gutters {
            border-right: 1px solid #f1f5f9;
            background-color: #f8f9fa;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js" xintegrity="sha512-M7b5ippoN9P5f90zDm2An3tRDAYwtKjYNZNGDhihJ1ytY1/6Yr2vNhbbX6YsJhBKt3vnDnN/SUXOc6Bx/6CkVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/xml/xml.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/htmlmixed/htmlmixed.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/php/php.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            initEditorLogic();
        });

        // Fallback por si estás usando Livewire 3 (Volt)
        document.addEventListener('livewire:initialized', function () {
            initEditorLogic();
        });

        function initEditorLogic() {
            let cmEditor = null;

            function determineMode(filename) {
                if (!filename) return 'application/x-httpd-php';
                filename = filename.toLowerCase();
                if (filename.endsWith('.js')) return 'javascript';
                if (filename.endsWith('.json')) return 'application/json';
                if (filename.endsWith('.css')) return 'css';
                if (filename.endsWith('.html')) return 'htmlmixed';
                return 'application/x-httpd-php'; // Defecto para php y blade
            }

            function getTheme() {
                // Detectar si HTML tiene la clase dark (estándar de Tailwind)
                return document.documentElement.classList.contains('dark') ? 'dracula' : 'eclipse';
            }

            function initEditor() {
                const textarea = document.getElementById('editor-textarea');
                const container = document.getElementById('editor-container');
                
                if (!textarea || !container) return;
                
                // Si ya existe, solo actualizamos el valor y el modo para no recrearlo desde cero
                if (textarea._cmInitialized && window.cmEditor) {
                    const newMode = determineMode(container.dataset.filename);
                    window.cmEditor.setOption("mode", newMode);
                    window.cmEditor.setOption("theme", getTheme());
                    
                    let indicator = document.getElementById('cm-mode-indicator');
                    if(indicator) indicator.innerText = newMode;
                    
                    // Si el textarea cambió su valor nativamente (al abrir otro archivo)
                    if(window.cmEditor.getValue() !== textarea.value) {
                         window.cmEditor.setValue(textarea.value);
                    }
                    setTimeout(() => window.cmEditor.refresh(), 100);
                    return;
                }

                const mode = determineMode(container.dataset.filename);
                
                cmEditor = CodeMirror.fromTextArea(textarea, {
                    mode: mode,
                    theme: getTheme(),
                    lineNumbers: true,
                    lineWrapping: true,
                    tabSize: 4,
                    indentUnit: 4,
                    indentWithTabs: false,
                    matchBrackets: true,
                    viewportMargin: Infinity
                });

                textarea._cmInitialized = true;

                // Sincronizar hacia Livewire al tipear (esto permite que wire:model.defer funcione)
                cmEditor.on('change', function (cm) {
                    textarea.value = cm.getValue();
                    textarea.dispatchEvent(new Event('input')); // Dispara evento para Livewire
                });

                window.cmEditor = cmEditor;
                
                let indicator = document.getElementById('cm-mode-indicator');
                if(indicator) indicator.innerText = mode;
                
                // Forzar repintado por si el tab estaba oculto
                setTimeout(() => cmEditor.refresh(), 200);
            }

            // Inicializar al cargar
            initEditor();

            // Interceptar cuando Livewire procesa actualizaciones en el DOM
            if (window.Livewire && Livewire.hook) {
                // Para Livewire 2
                if(Livewire.hook.message && Livewire.hook.message.processed) {
                    Livewire.hook('message.processed', () => { initEditor(); });
                }
                // Para Livewire 3
                if(Livewire.hook.morph && Livewire.hook.morph.updated) {
                    Livewire.hook('morph.updated', () => { initEditor(); });
                }
            }

            // Detectar cambios de tema del sistema operativo o botón de Tailwind
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class' && window.cmEditor) {
                        window.cmEditor.setOption("theme", getTheme());
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        }
    </script>
<?php $__env->stopPush(); ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/editor/index.blade.php ENDPATH**/ ?>