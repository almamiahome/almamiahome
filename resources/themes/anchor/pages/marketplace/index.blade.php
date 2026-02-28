<?php
use function Laravel\Folio\name;
// Si quieres que sea público, quita el middleware 'auth'
\Laravel\Folio\middleware('auth'); 
name('marketplace');

use Livewire\Volt\Component;
// Aquí puedes importar tu modelo de productos si ya existe
// use App\Models\Product; 

new class extends Component {
    public $search = '';

    public function getProductsProperty()
    {
        // Ejemplo de lógica de búsqueda
        // return Product::where('name', 'like', '%' . $this->search . '%')->get();
        return collect([]); // Mock vacío por ahora
    }
};
?>

<x-layouts.app>
    @volt('marketplace')
        <x-app.container>
            <div class="flex flex-col mb-8 gap-4">
                <x-app.heading
                    title="Marketplace"
                    description="Explora los servicios y productos disponibles en la plataforma."
                    :border="false"
                />
                
                <div class="relative w-full max-w-lg">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        placeholder="Buscar productos..." 
                        class="w-full py-2 pl-10 pr-4 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($this->products as $product)
                    <!-- Card de Producto -->
                    <div class="overflow-hidden bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div class="aspect-video bg-gray-200"></div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2">Descripción del producto...</p>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-lg font-bold text-indigo-600">$0.00</span>
                                <x-button class="text-xs">Ver detalle</x-button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay productos</h3>
                        <p class="mt-1 text-sm text-gray-500">Intenta ajustar tu búsqueda o vuelve más tarde.</p>
                    </div>
                @endforelse
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>