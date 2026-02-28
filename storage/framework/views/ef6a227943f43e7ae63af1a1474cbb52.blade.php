<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // Importante para subir archivos
use App\Models\Producto;
use App\Models\Categoria;

?>


        <x-app.container>
            <div class="max-w-4xl mx-auto">
                <x-elements.back-button
                    class="mb-6"
                    text="Volver a Productos"
                    :href="route('productos')"
                />

                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Nuevo Producto</h1>
                    <p class="text-sm text-gray-500">Completa la información para registrar un nuevo artículo en el catálogo.</p>
                </div>

                <form wire:submit="save" class="space-y-8">
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d=" incumbents-13-16-5-2-8-9 5-2 8-9 4-8-11-8-11-8-11-8-11z"></path>
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Información General
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre del Producto</label>
                                <input type="text" wire:model="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('nombre') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría</label>
                                <select wire:model="categoria_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Selecciona una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('categoria_id') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">SKU (Código Interno)</label>
                                <input type="text" wire:model="sku" placeholder="Ej: PROD-001" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('sku') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea wire:model="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                @error('descripcion') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <h2 class="text-lg font-semibold mb-4 text-gray-800">Precios y Puntos</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Precio de Venta ($)</label>
                                    <input type="number" step="0.01" wire:model="precio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('precio') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Puntos por Unidad</label>
                                    <input type="number" wire:model="puntos_por_unidad" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('puntos_por_unidad') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <h2 class="text-lg font-semibold mb-4 text-gray-800">Inventario y Estado</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stock Inicial</label>
                                    <input type="number" wire:model="stock_actual" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('stock_actual') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="pt-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="activo" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-700">Producto Visible/Activo</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h2 class="text-lg font-semibold mb-4 text-gray-800">Dimensiones</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-bold">Altura</label>
                                        <input type="text" wire:model="altura" placeholder="cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-bold">Anchura</label>
                                        <input type="text" wire:model="anchura" placeholder="cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-bold">Profundidad</label>
                                        <input type="text" wire:model="profundidad" placeholder="cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-bold">Bulto/Peso</label>
                                        <input type="text" wire:model="bulto" placeholder="kg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold mb-4 text-gray-800">Imagen del Producto</h2>
                                <div class="flex items-center justify-center w-full">
                                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative">
                                        @if ($imagen)
                                            <img src="{{ $imagen->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover opacity-50">
                                        @endif
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                            <p class="text-sm text-gray-500"><span class="font-semibold">Haz clic para subir</span></p>
                                        </div>
                                        <input type="file" wire:model="imagen" class="hidden" />
                                    </label>
                                </div>
                                @error('imagen') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <x-button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-lg transition-all transform hover:scale-105">
                            Guardar Producto
                        </x-button>
                    </div>
                </form>
            </div>
        </x-app.container>
    