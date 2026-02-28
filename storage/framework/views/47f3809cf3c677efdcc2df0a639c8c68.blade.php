<?php

use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\File;

?>


        <x-app.container class="space-y-6 pb-20" x-data="{ tab: 'explorer' }">
            {{-- HEADER --}}
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between bg-white/40 dark:bg-zinc-900/30 p-6 rounded-[2rem] border border-slate-200/60 dark:border-zinc-800/60 shadow-sm backdrop-blur-xl">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full">Desarrollo</span>
                    </div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-50">
                        Editor de Tema <span class="text-indigo-600 dark:text-indigo-400">ALMAMIA</span>
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                        Ruta actual:
                        <code class="font-mono text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded ml-1">
                            resources/themes/anchor/{{ $currentDir ?: '' }}
                        </code>
                    </p>
                </div>
                <div class="p-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 rounded-2xl text-xs text-red-800 dark:text-red-300 max-w-sm">
                    <p class="font-bold flex items-center gap-2 mb-1">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        Acceso restringido (Admin)
                    </p>
                    <p class="leading-relaxed">Usá esta herramienta con extremo cuidado. Los cambios editados aquí modifican los archivos reales del sistema en el servidor.</p>
                </div>
            </div>

            {{-- SISTEMA DE PESTAÑAS (TABS) --}}
            <div class="flex items-center gap-2 border-b border-slate-200 dark:border-zinc-800 pb-px">
                <button 
                    @click="tab = 'explorer'" 
                    :class="tab === 'explorer' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                    class="px-6 py-3 text-sm font-bold uppercase tracking-wider border-b-2 transition-colors duration-200 focus:outline-none flex items-center gap-2"
                >
                    <span>📁</span> Explorador de Archivos
                </button>
                <button 
                    @click="tab = 'editor'; setTimeout(() => { if(window.cmEditor) window.cmEditor.refresh(); }, 50);" 
                    :class="tab === 'editor' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                    class="px-6 py-3 text-sm font-bold uppercase tracking-wider border-b-2 transition-colors duration-200 focus:outline-none flex items-center gap-2"
                >
                    <span>💻</span> Editor de Código
                    @if($selectedFile)
                        <span class="inline-flex items-center justify-center w-2 h-2 bg-emerald-500 rounded-full ml-1"></span>
                    @endif
                </button>
            </div>

            <div class="bg-white dark:bg-zinc-900/80 border border-slate-200 dark:border-zinc-800 rounded-b-[2rem] rounded-tr-[2rem] shadow-xl overflow-hidden min-h-[70vh] flex flex-col">
                
                {{-- TAB 1: EXPLORADOR --}}
                <div x-show="tab === 'explorer'" x-transition.opacity class="flex-1 flex flex-col">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800/50 px-6 py-4 bg-slate-50/50 dark:bg-zinc-900/50">
                        <div class="space-y-1">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                Directorio actual
                            </h2>
                            <p class="text-xs font-mono text-slate-500 dark:text-slate-400 bg-white dark:bg-zinc-800 px-2 py-1 rounded border border-slate-200 dark:border-zinc-700">
                                {{ $currentDir === '' ? '/' : '/' . $currentDir }}
                            </p>
                        </div>
                        <button
                            wire:click="goUp"
                            type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-white dark:bg-zinc-800 px-4 py-2 text-xs font-bold uppercase tracking-wide text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-zinc-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all shadow-sm"
                        >
                            <span>⬆️</span> Subir de nivel
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-6 py-4 text-sm bg-white dark:bg-zinc-900/30">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Carpetas --}}
                            <div class="space-y-3">
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-zinc-800 pb-2">
                                    Carpetas
                                </h3>
                                <div class="space-y-1">
                                    @forelse($directories as $dir)
                                        <button
                                            type="button"
                                            wire:click="changeDir('{{ $currentDir === '' ? $dir : $currentDir . '/' . $dir }}')"
                                            class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors"
                                        >
                                            <span class="text-lg text-amber-400 drop-shadow-sm group-hover:scale-110 transition-transform">📁</span>
                                            <span class="font-medium text-slate-700 group-hover:text-indigo-600 dark:text-slate-300 dark:group-hover:text-indigo-400">
                                                {{ $dir }}
                                            </span>
                                        </button>
                                    @empty
                                        <div class="flex items-center gap-3 px-3 py-4 text-slate-400 dark:text-zinc-600 italic text-sm bg-slate-50 dark:bg-zinc-800/30 rounded-xl border border-dashed border-slate-200 dark:border-zinc-700">
                                            <span>📭</span> No hay subcarpetas.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Archivos --}}
                            <div class="space-y-3">
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-zinc-800 pb-2">
                                    Archivos
                                </h3>
                                <div class="space-y-1">
                                    @forelse($files as $file)
                                        <button
                                            type="button"
                                            wire:click="openFile('{{ $file }}')"
                                            @click="tab = 'editor'; setTimeout(() => { if(window.cmEditor) window.cmEditor.refresh(); }, 150);"
                                            class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left transition-all
                                                {{ $selectedFile === $file ? 'bg-indigo-50 border border-indigo-200 dark:bg-indigo-900/20 dark:border-indigo-500/30 shadow-sm' : 'hover:bg-slate-100 dark:hover:bg-zinc-800' }}"
                                        >
                                            <span class="text-lg text-sky-500 dark:text-sky-400 drop-shadow-sm group-hover:scale-110 transition-transform">
                                                {{ Str::endsWith($file, ['.php', '.blade.php']) ? '🐘' : (Str::endsWith($file, ['.js', '.json']) ? '🟨' : '📄') }}
                                            </span>
                                            <span class="font-medium truncate {{ $selectedFile === $file ? 'text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-700 group-hover:text-indigo-600 dark:text-slate-300 dark:group-hover:text-indigo-400' }}">
                                                {{ $file }}
                                            </span>
                                            @if($selectedFile === $file)
                                                <span class="ml-auto flex h-2 w-2 rounded-full bg-indigo-500"></span>
                                            @endif
                                        </button>
                                    @empty
                                        <div class="flex items-center gap-3 px-3 py-4 text-slate-400 dark:text-zinc-600 italic text-sm bg-slate-50 dark:bg-zinc-800/30 rounded-xl border border-dashed border-slate-200 dark:border-zinc-700">
                                            <span>📭</span> No hay archivos aquí.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Crear carpeta / archivo --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/80 px-6 py-5 mt-auto">
                        <form wire:submit.prevent="createFolder" class="flex items-center gap-2">
                            <input
                                type="text"
                                wire:model.defer="newFolderName"
                                placeholder="Nombre de nueva carpeta"
                                class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-slate-100 placeholder-slate-400"
                            >
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-slate-800 px-4 py-2.5 font-bold text-white hover:bg-slate-700 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white transition-colors"
                                title="Crear Carpeta"
                            >
                                + Carpeta
                            </button>
                        </form>

                        <form wire:submit.prevent="createFile" class="flex items-center gap-2">
                            <input
                                type="text"
                                wire:model.defer="newFileName"
                                placeholder="Nuevo archivo (ej: vista.blade.php)"
                                class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-slate-100 placeholder-slate-400"
                            >
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 font-bold text-white hover:bg-indigo-500 transition-colors shadow-md shadow-indigo-500/20"
                                title="Crear Archivo"
                            >
                                + Archivo
                            </button>
                        </form>
                    </div>
                </div>

                {{-- TAB 2: EDITOR PRINCIPAL --}}
                <div x-show="tab === 'editor'" style="display: none;" x-transition.opacity class="flex-1 flex flex-col h-full bg-[#f8f9fa] dark:bg-[#282a36]">
                    <div class="flex flex-wrap items-center justify-between border-b border-slate-200 dark:border-[#1e1f29] px-6 py-3 bg-white dark:bg-[#21222c]">
                        <div class="space-y-1">
                            <div class="flex items-center gap-3">
                                @if($selectedFile)
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,.6)]"></span>
                                @else
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                @endif
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-100 font-mono">
                                    {{ $selectedFile ? $this->displayPath() : 'Ningún archivo seleccionado' }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                {{ $statusMessage ?: 'Seleccioná un archivo desde la pestaña Explorador para comenzar a editar.' }}
                            </p>
                        </div>

                        @if($selectedFile)
                            <div class="flex items-center gap-3 mt-3 sm:mt-0">
                                <span id="cm-mode-indicator" class="hidden sm:inline-block px-2 py-1 bg-slate-100 dark:bg-zinc-800 rounded text-[10px] font-mono text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-zinc-700">PHP/Blade</span>
                                <button
                                    type="button"
                                    wire:click="save"
                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-2 text-sm font-bold text-white hover:bg-emerald-500 transition-colors shadow-lg shadow-emerald-500/30"
                                >
                                    <span>💾</span> Guardar Cambios
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 relative" id="editor-container" wire:ignore data-filename="{{ $selectedFile }}">
                        @if($selectedFile)
                            {{-- Contenedor de Textarea: wire:ignore previene que Livewire lo destruya --}}
                            <textarea
                                id="editor-textarea"
                                wire:model.defer="content"
                                class="absolute inset-0 h-full w-full opacity-0 pointer-events-none"
                            ></textarea>
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 space-y-4">
                                <span class="text-6xl opacity-20 dark:opacity-40">💻</span>
                                <p class="text-lg font-medium">El editor está esperando un archivo.</p>
                                <button @click="tab = 'explorer'" class="px-4 py-2 bg-white dark:bg-zinc-800 rounded-lg shadow border border-slate-200 dark:border-zinc-700 text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:scale-105 transition-transform">
                                    Ir al Explorador
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($selectedFile)
                        <div class="flex items-center justify-between border-t border-slate-200 dark:border-[#1e1f29] px-6 py-2 text-[10px] uppercase font-bold tracking-widest text-slate-500 bg-white dark:bg-[#21222c]">
                            <span class="flex items-center gap-2">
                                CodeMirror 5 <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span> Modo Adaptativo
                            </span>
                            <span>{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </x-app.container>
    