<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Str;

?>


        <x-app.container>
            <x-elements.back-button text="Volver al listado" :href="route('productos')" class="mb-4" />

            <x-app.heading
                title="Creación masiva de productos"
                description="Pegá una lista de productos. Formato: nombre | precio | puntos | categoría (opcional)"
                :border="false"
            />

            @if (session()->has('message'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="saveMasivo" class="space-y-4 max-w-3xl">
                <div>
                    <label for="input_text" class="block mb-2 text-sm font-medium text-gray-700">
                        Lista de productos
                    </label>
                    <textarea
                        id="input_text"
                        rows="10"
                        wire:model.defer="input_text"
                        placeholder="Ejemplo:
Camisa Azul | 25000 | 50 | Ropa
Pantalón Jeans | 35000 | 60 | Ropa
Perfume Floral | 12000 | 30 | Perfumería"
                        class="w-full border-gray-300 rounded-md shadow-xs focus:ring focus:ring-indigo-200"
                    ></textarea>
                </div>

                <x-button type="submit">
                    Crear productos
                </x-button>
            </form>
        </x-app.container>
    