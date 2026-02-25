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

<x-layouts.app>
    @volt('editor.index')
        <x-app.container class="space-y-6">
            {{-- HEADER --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-50">
                        Editor de Tema <span class="text-sky-500">ALMAMIA</span>
                    </h1>
                    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400">
                        Editando en:
                        <span class="font-mono">
                            resources/themes/anchor/{{ $currentDir ?: '' }}
                        </span>
                    </p>
                </div>
                <div class="text-xs text-slate-500 dark:text-slate-400">
                    Solo <span class="font-semibold text-sky-600 dark:text-sky-400">admin</span> (Spatie Role) ·
                    Usá con cuidado, esto edita archivos reales.
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
                {{-- SIDEBAR: EXPLORADOR --}}
                <aside class="lg:col-span-1">
                    <div class="flex h-[70vh] flex-col rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
                        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                            <div class="space-y-0.5">
                                <h2 class="text-sm font-semibold tracking-wide text-slate-900 dark:text-slate-100">
                                    Explorador
                                </h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $currentDir === '' ? '/' : '/' . $currentDir }}
                                </p>
                            </div>
                            <button
                                wire:click="goUp"
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700"
                            >
                                <span class="text-xs">⬆️</span>
                                Arriba
                            </button>
                        </div>

                        <div class="flex-1 space-y-4 overflow-y-auto px-3 py-3 text-xs">
                            {{-- Carpetas --}}
                            <div>
                                <div class="mb-1 flex items-center justify-between">
                                    <span class="text-[10px] uppercase tracking-[0.15em] text-slate-500 dark:text-slate-400">
                                        Carpetas
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    @forelse($directories as $dir)
                                        <button
                                            type="button"
                                            wire:click="changeDir('{{ $currentDir === '' ? $dir : $currentDir . '/' . $dir }}')"
                                            class="group flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-left hover:bg-slate-50 dark:hover:bg-slate-800"
                                        >
                                            <span class="text-sm text-amber-400">📁</span>
                                            <span class="truncate text-[13px] text-slate-800 group-hover:text-sky-600 dark:text-slate-100 dark:group-hover:text-sky-300">
                                                {{ $dir }}
                                            </span>
                                        </button>
                                    @empty
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                            Sin subcarpetas.
                                        </p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Archivos --}}
                            <div>
                                <div class="mb-1 flex items-center justify-between">
                                    <span class="text-[10px] uppercase tracking-[0.15em] text-slate-500 dark:text-slate-400">
                                        Archivos
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    @forelse($files as $file)
                                        <button
                                            type="button"
                                            wire:click="openFile('{{ $file }}')"
                                            class="group flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-left hover:bg-slate-50 dark:hover:bg-slate-800
                                                {{ $selectedFile === $file ? 'bg-slate-100 border border-sky-500/40 dark:bg-slate-800/80 dark:border-sky-500/60' : '' }}"
                                        >
                                            <span class="text-sm text-sky-500 dark:text-sky-300">📄</span>
                                            <span class="truncate text-[13px] text-slate-800 group-hover:text-sky-600 dark:text-slate-100 dark:group-hover:text-sky-200">
                                                {{ $file }}
                                            </span>
                                        </button>
                                    @empty
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                            Sin archivos en esta carpeta.
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Crear carpeta / archivo --}}
                        <div class="space-y-2 border-t border-slate-100 px-3 py-3 text-[11px] dark:border-slate-800">
                            <form wire:submit.prevent="createFolder" class="flex items-center gap-1.5">
                                <input
                                    type="text"
                                    wire:model.defer="newFolderName"
                                    placeholder="Nueva carpeta"
                                    class="flex-1 rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] text-slate-800 focus:border-sky-500 focus:ring-0 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                                >
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-[11px] text-slate-800 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700"
                                >
                                    + Carp
                                </button>
                            </form>

                            <form wire:submit.prevent="createFile" class="flex items-center gap-1.5">
                                <input
                                    type="text"
                                    wire:model.defer="newFileName"
                                    placeholder="Nuevo archivo (ej: lider/index.blade.php)"
                                    class="flex-1 rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] text-slate-800 focus:border-sky-500 focus:ring-0 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                                >
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-md bg-sky-600 px-2 py-1 text-[11px] text-white hover:bg-sky-500"
                                >
                                    + File
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                {{-- PANEL PRINCIPAL: EDITOR --}}
                <section class="lg:col-span-3">
                    <div class="flex h-[70vh] flex-col rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
                        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,.8)]"></span>
                                    <span class="text-xs font-medium text-slate-700 dark:text-slate-100">
                                        {{ $selectedFile ? $this->displayPath() : 'Ningún archivo seleccionado' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    {{ $statusMessage ?: 'Seleccioná un archivo del panel izquierdo para comenzar a editar.' }}
                                </p>
                            </div>

                            @if($selectedFile)
                                <div class="flex items-center gap-2 text-[11px]">
                                    <button
                                        type="button"
                                        wire:click="save"
                                        class="inline-flex items-center gap-1 rounded-md bg-emerald-600 px-3 py-1.5 font-medium text-white hover:bg-emerald-500"
                                    >
                                        💾 Guardar
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 overflow-hidden">
                            @if($selectedFile)
                                <div class="h-full">
                                    {{-- Textarea base que Livewire controla. CodeMirror se monta encima. --}}
                                    <textarea
                                        id="editor-textarea"
                                        wire:model.defer="content"
                                        class="h-full w-full border-0 bg-slate-950 p-4 font-mono text-xs text-slate-100 outline-none"
                                    ></textarea>
                                </div>
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-slate-500 dark:text-slate-400">
                                    Elegí un archivo del explorador para editarlo acá.
                                </div>
                            @endif
                        </div>

                        @if($selectedFile)
                            <div class="flex items-center justify-between border-t border-slate-100 px-4 py-2 text-[11px] text-slate-500 dark:border-slate-800 dark:text-slate-400">
                                <span>CodeMirror · Dark mode · PHP / Blade</span>
                                <span>{{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>

@push('styles')
    {{-- CodeMirror Dark Theme (Dracula) --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css"
          integrity="sha512-yq0pUWLWyd4InENcy+XUG6uHgnIY7M9xeY4V0z3Y0JVpaz6RtWLpmjHtkobaN6D+PfYZ7R6pujISiFDUFxIr2w=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/dracula.min.css"
          integrity="sha512-6Lj3VAb9YSEuxsjjXNMTvEihQ/HYMv7svuFj0CQ5G+T9x0ox2I8GHOZfWTLV4niK1hx3F5VnfW4HI7c1xX1v7w=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"
            integrity="sha512-M7b5ippoN9P5f90zDm2An3tRDAYwtKjYNZNGDhihJ1ytY1/6Yr2vNhbbX6YsJhBKt3vnDnN/SUXOc6Bx/6CkVQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/xml/xml.min.js"
            integrity="sha512-+DcYqKqRR3YKHyCuXapnwXCfJOLLmObAun1vDLteA94ppIqhzyapMI2vlA38nSxrdbidKdvUSsfx8bVsgcuyoA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js"
            integrity="sha512-0n0KBPXn1HULICwhf66A1VpzwuNFuIBqmoeZaZX6mE6oPD58Ll35H5TADaBrZEcD3xKhsR4HIX66vepQP9en5A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js"
            integrity="sha512-jV8xDSHLVQQP4Jrped1IovnHgwlHGawEq+y3OC/YLXTr4Wr9PXgC7cmkQCLOwBEm90LkAMPx/+qvOBvyfOkH1w=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/htmlmixed/htmlmixed.min.js"
            integrity="sha512-BuLFMlZ3IjVZrDMAYA9Yg3gCBXlI3VzBkYOPxfN3E3xaQa58aeGeq/QAdzTZziEtGlUZEM6I4vGU+mLjxJvLCg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/php/php.min.js"
            integrity="sha512-+nAqd6/ePDh6gP0iDLuhvZux41DmvNmO1rEDX3cOBAazbr33U9leLsmgZ0jgu1LEIq0CqjfSOT2pRwO8VxZr4g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            let cmEditor = null;

            function initEditor() {
                const textarea = document.getElementById('editor-textarea');
                if (!textarea) return;

                if (textarea._cmInitialized) return;

                cmEditor = CodeMirror.fromTextArea(textarea, {
                    mode: 'application/x-httpd-php',
                    theme: 'dracula',
                    lineNumbers: true,
                    lineWrapping: true,
                    tabSize: 4,
                    indentUnit: 4,
                    indentWithTabs: false,
                    matchBrackets: true,
                });

                textarea._cmInitialized = true;

                cmEditor.on('change', function (cm) {
                    cm.save();
                });

                window.cmEditor = cmEditor;
            }

            initEditor();

            if (window.Livewire && Livewire.hook) {
                Livewire.hook('message.processed', () => {
                    initEditor();
                });
            }
        });
    </script>
@endpush
