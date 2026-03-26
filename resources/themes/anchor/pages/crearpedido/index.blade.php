<?php

use function Laravel\Folio\{middleware, name};
use App\Services\PedidoCartService;
use Livewire\Volt\Component;

middleware('auth');
name('crearpedido');

// Servicio compartido con /catalogo para centralizar cálculos y validaciones del carrito.
new class extends Component {

    public $productos = [];
    public $categorias = [];
    public $vendedoras = [];
    public $lideres = [];
    public $responsable = [];
    public $paginas_catalogo = [];
    public $gastos_administrativos = [];
    public $codigo_pedido;
    public $vendedoraSeleccionadaId = null;
    public $liderSeleccionadoId = null;
    public $esVendedoraAutenticada = false;
    public $esLiderAutenticado = false;

    public function mount(PedidoCartService $pedidoCartService): void
    {
        $usuario = auth()->user();

        $this->codigo_pedido = $pedidoCartService->generarCodigoPedido();
        $this->categorias = $pedidoCartService->obtenerCategorias();
        $this->productos = $pedidoCartService->obtenerProductosConReglas();
        $this->gastos_administrativos = $pedidoCartService->obtenerGastosAdministrativos();

        $contexto = $pedidoCartService->obtenerContextoUsuarios($usuario);

        $this->vendedoras = $contexto['vendedoras'];
        $this->lideres = $contexto['lideres'];
        $this->vendedoraSeleccionadaId = $contexto['vendedoraSeleccionadaId'];
        $this->liderSeleccionadoId = $contexto['liderSeleccionadoId'];
        $this->responsable = $contexto['responsable'];
        $this->esLiderAutenticado = $contexto['esLiderAutenticado'];
        $this->esVendedoraAutenticada = $contexto['esVendedoraAutenticada'];
    }

    public function storePedido($cart, $vendedora_id, $lider_id, $gastosSeleccionados, $observaciones, PedidoCartService $pedidoCartService)
    {
        $resultado = $pedidoCartService->storePedido([
            'cart'          => $cart,
            'gastos'        => $gastosSeleccionados,
            'observaciones' => $observaciones,
            'vendedora_id'  => $vendedora_id,
            'lider_id'      => $lider_id,
        ], $this->codigo_pedido, auth()->user());

        if (isset($resultado['success'])) {
            $this->codigo_pedido = $resultado['codigo_pedido'];
        }

        return $resultado;
    }

};

?>




<x-layouts.app>
    @volt('crearpedido')
    <x-app.container data-tour-scope="crear-pedido">

        {{-- Styles moved to assets/css/app.css --}}

        <div
            class="container mx-auto p-4"
            x-data="pedidoApp()"
            x-init="init()"
        >

            <div
                class="flex flex-wrap items-center justify-between gap-3 mb-4"
                data-tour-step="1"
                data-tour-title="Creación de pedido"
                data-tour-text="Este flujo te guía para armar un pedido completo de Alma Mía, desde selección de productos hasta confirmación."
            >
            <h1 class="text-xl font-bold">Crear Pedido</h1>
            <a href="{{ url('/catalogo') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Ir a Catálogo
            </a>
            </div>

            <template x-if="messages.success">
                <div class="p-3 mt-4 text-green-700 bg-green-100 border border-green-300 rounded" x-text="messages.success"></div>
            </template>
            <template x-if="messages.error">
                <div class="p-3 mt-4 text-red-700 bg-red-100 border border-red-300 rounded" x-text="messages.error"></div>
            </template>

            <section
                id="seccion-productos"
                data-tour-step="2"
                data-tour-title="Selección de productos"
                data-tour-text="Filtrá por categoría o buscá por nombre y SKU para cargar artículos al carrito con rapidez."
            >
            <!-- Filtros categorías y busqueda -->
            <div class="mt-8 mb-6">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Filtrar por categoría</label>
        <span class="text-[10px] bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 px-2 py-0.5 rounded-full font-bold md:hidden italic">Desliza para ver más</span>
    </div>
    
    <div class="flex overflow-x-auto md:flex-wrap gap-2 pb-2 md:pb-0 no-scrollbar" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">
        <button
            :class="filter_categoria === '' 
                ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-none ring-2 ring-indigo-600 ring-offset-2 dark:ring-offset-slate-950' 
                : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-indigo-300 hover:text-indigo-600'"
            @click="filter_categoria=''"
            class="whitespace-nowrap px-5 py-2.5 rounded-xl transition-all duration-200 font-semibold text-sm flex-shrink-0">
            Todas
        </button>
        
        <template x-for="cat in categorias" :key="cat">
            <button
                :class="filter_categoria === cat
                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-none ring-2 ring-indigo-600 ring-offset-2 dark:ring-offset-slate-950'
                    : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-indigo-300 hover:text-indigo-600'"
                @click="filter_categoria=cat"
                class="whitespace-nowrap px-5 py-2.5 rounded-xl transition-all duration-200 font-semibold text-sm flex-shrink-0"
                x-text="cat">
            </button>
        </template>
    </div>
</div>

<div class="relative group mb-8">
    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>
    <input 
        type="text" 
        placeholder="Buscar producto o SKU..."
        x-model="search"
        @keyup.enter.prevent="addBySku()"
        class="w-full md:w-2/3 lg:w-1/2 pl-11 pr-4 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
    >
    
    <template x-if="messages.error">
        <div class="absolute mt-2 left-0 flex items-center gap-1.5 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-lg animate-in fade-in slide-in-from-top-1">
            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <p class="text-xs font-bold text-red-600 dark:text-red-400" x-text="messages.error"></p>
        </div>
    </template>
</div>

           <div class="space-y-6 mb-6">
    <template x-for="cat in categorias" :key="cat">
        <div x-show="(filter_categoria === '' || filter_categoria === cat) && productos.filter(p => p.categorias.includes(cat) && matchesSearch(p)).length > 0" 
             class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm transition-all">
            
            <button
                type="button"
                class="w-full flex items-center justify-between px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                @click="openCategorias[cat] = !openCategorias[cat]">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                    <span class="font-bold text-slate-800 dark:text-white uppercase tracking-tight" x-text="cat"></span>
                    <span class="ml-2 px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-[10px] font-bold text-slate-500 dark:text-slate-400" 
                          x-text="productos.filter(p => p.categorias.includes(cat) && matchesSearch(p)).length"></span>
                </div>
                <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400">
                    <span class="text-xs font-bold uppercase tracking-tighter" x-text="openCategorias[cat] ? 'Cerrar' : 'Expandir'"></span>
                    <svg class="w-5 h-5 transition-transform duration-300" :class="openCategorias[cat] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </button>

            <div class="p-4 md:p-6" 
                 x-show="openCategorias[cat] || (search.trim().length > 0 && productos.filter(p => p.categorias.includes(cat) && matchesSearch(p)).length > 0)" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 md:gap-6">
                    <template
                        x-for="producto in productos.filter(p => p.categorias.includes(cat) && matchesSearch(p))"
                        :key="producto.id">
                        
                        <div class="group relative flex flex-col bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-3 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-200 dark:hover:border-indigo-900 transition-all duration-300"
                             :class="esProductoVisibilidadBloqueado(producto) ? 'opacity-60 grayscale border-dashed border-red-200 bg-red-50/30' : ''">
                            
                            <div class="relative aspect-square w-full mb-4 rounded-xl bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <template x-if="producto.imagen">
                                    <img :src="producto.imagen" loading="lazy" class="w-full h-full object-contain bg-white p-2 transition-transform duration-500 group-hover:scale-105">
                                </template>
                                <template x-if="!producto.imagen">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="text-[10px] uppercase font-bold tracking-tight">Sin imagen</span>
                                    </div>
                                </template>

                               <!-- <div class="absolute top-2 right-2">
                                    <span class="bg-emerald-500 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-lg" 
                                          x-text="'+' + (producto.puntos) + ' pts'"></span> 
                                </div> -->
                            </div>

                            <div class="flex-1 flex flex-col">
                                <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider mb-1" x-text="cat"></p>
                                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100 line-clamp-2 leading-tight mb-2 h-10" x-text="producto.nombre"></h3>
                                
                                <div class="flex items-baseline gap-1 mb-1">
                                    <span class="text-xs font-bold text-slate-400">$</span>
                                    <span class="text-lg font-black text-slate-900 dark:text-white" x-text="formatMoney(producto.precio)"></span>
                                </div>

                                <div class="flex items-center justify-between mt-2 pt-3 border-t border-slate-50 dark:border-slate-800">
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">SKU: <span class="text-slate-600 dark:text-slate-300" x-text="producto.sku"></span></span>
                                </div>

                                <button
                                    @click="!esProductoVisibilidadBloqueado(producto) && addToCart(producto)"
                                    :disabled="esProductoVisibilidadBloqueado(producto)"
                                    class="mt-4 w-full flex items-center justify-center gap-2 py-2.5 rounded-xl font-bold text-xs transition-all duration-200"
                                    :class="esProductoVisibilidadBloqueado(producto)
                                        ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                                        : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-200 dark:shadow-none active:scale-95'"
                                >
                                    <svg x-show="!esProductoVisibilidadBloqueado(producto)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                    <span x-text="esProductoVisibilidadBloqueado(producto) ? 'No disponible' : 'Agregar'"></span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>
            </section>

            <!-- Carrito -->
            <div
                id="seccion-carrito"
                class="bg-white shadow rounded-2xl p-4 mb-4"
                data-tour-step="3"
                data-tour-title="Control del carrito"
                data-tour-text="Revisá cantidades, subtotales y puntos antes de guardar el pedido para evitar diferencias de cierre."
            >
                <div class="bg-white dark:bg-slate-950 shadow-sm rounded-3xl p-6 mb-6 border border-slate-200 dark:border-slate-800 transition-all">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Carrito de compras</h2>
            <p class="text-xs text-slate-500">Revisa y ajusta los productos de tu pedido</p>
        </div>
        
        <div class="relative max-w-xs">
            <label class="absolute -top-2 left-3 px-1 bg-white dark:bg-slate-950 text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Código pedido</label>
            <input type="text" x-model="codigo_pedido" 
                class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-mono font-bold text-slate-700 dark:text-slate-300 focus:outline-none" 
                readonly>
        </div>
    </div>

    <template x-if="cart.length === 0">
        <div class="text-center py-12 border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-2xl">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            <p class="mt-4 text-slate-500 font-medium">El carrito está vacío.</p>
        </div>
    </template>

    <div class="md:hidden space-y-4" x-show="cart.length > 0">
        <template x-for="(item,index) in cart" :key="item.producto_id">
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="p-4">
                    <div class="flex justify-between items-start gap-3 mb-3">
                        <div class="min-w-0">
                            <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1" x-text="item.sku ?? 'Sin SKU'"></span>
                            <p class="text-base font-bold text-slate-900 dark:text-white truncate" x-text="item.nombre"></p>
                        </div>
                        <button @click="removeItem(index)" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h3.999A2 2 0 0116 5v2" /></svg>
                        </button>
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 dark:bg-slate-800/50 rounded-xl p-2 mb-4">
                        <span class="text-xs font-bold text-slate-500 pl-2 uppercase tracking-tighter">Cantidad</span>
                        <div class="flex items-center gap-3">
                            <button @click="decreaseQty(index)" class="w-8 h-8 flex items-center justify-center bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-200 font-bold">-</button>
                            <input type="number" x-model.number="item.cantidad" @input="updateItem(index)" class="w-10 text-center bg-transparent border-0 focus:ring-0 text-sm font-bold text-slate-800 dark:text-white">
                            <button @click="increaseQty(index)" class="w-8 h-8 flex items-center justify-center bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-200 font-bold">+</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Precio Desc.</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="'$ ' + formatMoney(item.precio_con_descuento)"></p>
                        </div>
                        <div class="p-3 rounded-xl bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30">
                            <p class="text-[10px] font-bold text-indigo-400 uppercase mb-1">Subtotal</p>
                            <p class="text-sm font-black text-indigo-700 dark:text-indigo-300" x-text="'$ ' + formatMoney(item.subtotal)"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/10 px-4 py-2 flex justify-between items-center border-t border-emerald-100 dark:border-emerald-900/20">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Puntos acumulados</span>
                    <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400" x-text="(item.puntos * item.cantidad) + ' pts'"></span>
                </div>
            </div>
        </template>
    </div>

    <div class="hidden md:block overflow-x-auto" x-show="cart.length > 0">
        <table class="w-full text-left border-separate border-spacing-y-2">
            <thead>
                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    <th class="px-4 py-3">Producto</th>
                    <th class="px-4 py-3 text-center">Cantidad</th>
                    <th class="px-4 py-3 text-right">Precio Cat.</th>
                    <th class="px-4 py-3 text-right">Precio Desc.</th>
                    <th class="px-4 py-3 text-right text-indigo-600">Subtotal</th>
                    <th class="px-4 py-3 text-center">Puntos</th>
                    <th class="px-4 py-3 text-center w-20"></th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <template x-for="(item,index) in cart" :key="item.producto_id">
                    <tr class="group bg-white dark:bg-slate-900 hover:shadow-md hover:translate-y-[-2px] transition-all duration-200">
                        <td class="px-4 py-4 rounded-l-2xl border-y border-l border-slate-100 dark:border-slate-800">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-400 mb-0.5" x-text="item.sku ?? '—'"></span>
                                <span class="font-bold text-slate-800 dark:text-slate-100" x-text="item.nombre"></span>
                            </div>
                        </td>
                        <td class="px-4 py-4 border-y border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="decreaseQty(index)" class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition-colors text-slate-600 dark:text-white font-bold">-</button>
                                <input type="number" x-model.number="item.cantidad" @input="updateItem(index)" class="w-12 text-center border-0 bg-transparent font-bold text-slate-800 dark:text-white focus:ring-0">
                                <button @click="increaseQty(index)" class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition-colors text-slate-600 dark:text-white font-bold">+</button>
                            </div>
                        </td>
                        <td class="px-4 py-4 border-y border-slate-100 dark:border-slate-800 text-right text-slate-400" x-text="'$ ' + formatMoney(item.precio_unitario_catalogo)"></td>
                        <td class="px-4 py-4 border-y border-slate-100 dark:border-slate-800 text-right font-semibold text-slate-700 dark:text-slate-200" x-text="'$ ' + formatMoney(item.precio_con_descuento)"></td>
                        <td class="px-4 py-4 border-y border-slate-100 dark:border-slate-800 text-right font-black text-indigo-600 dark:text-indigo-400" x-text="'$ ' + formatMoney(item.subtotal)"></td>
                        <td class="px-4 py-4 border-y border-slate-100 dark:border-slate-800 text-center">
                            <span class="px-2 py-1 rounded-md bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold" x-text="item.puntos * item.cantidad"></span>
                        </td>
                        <td class="px-4 py-4 rounded-r-2xl border-y border-r border-slate-100 dark:border-slate-800 text-center">
                            <button @click="removeItem(index)" class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex justify-between items-center" x-show="cart.length > 0">
        <button @click="clearCart()" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h3.999A2 2 0 0116 5v2" /></svg>
            Vaciar carrito
        </button>
    </div>
</div>

                <!-- Cards de resumen -->
               <div class="mt-8 space-y-6" x-show="cart.length > 0">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col">
            <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.15em]">Resumen de Negocio</h3>
            </div>
            
            <div class="p-6 flex-1 grid grid-cols-1 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Subtotal de Venta</p>
                    <div class="flex items-baseline mt-1">
                        <span class="text-xl font-medium text-gray-400 mr-1">$</span>
                        <span class="text-4xl font-black text-gray-900 tracking-tight" x-text="formatMoney(subtotal)"></span>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
                    <div class="absolute -right-4 -top-4 text-emerald-100">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.477.859h4z"></path></svg>
                    </div>

                    <div class="relative">
                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Tu Ganancia Estimada</p>
                        <div class="flex items-baseline mt-1 text-emerald-700">
                            <span class="text-xl font-bold mr-1">$</span>
                            <span class="text-4xl font-black" x-text="formatMoney(totalGanancias)"></span>
                        </div>
                        <p class="mt-2 text-xs text-emerald-600/80 font-medium">Este monto representa tu beneficio neto por la venta.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col">
            <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.15em]">Detalle Operativo</h3>
                <div class="flex items-center space-x-3">
                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold uppercase" x-text="totalUnidades + ' Unidades'"></span>
                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold uppercase" x-text="totalPuntos + ' Puntos'"></span>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-end mb-4">
                    <p class="text-sm font-semibold text-gray-700">Gastos Administrativos</p>
                    <p class="text-sm font-bold text-gray-500">$ <span x-text="formatMoney(totalGastos)"></span></p>
                </div>
                
                <div class="space-y-3 max-h-[180px] overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="gasto in gastosDisponibles" :key="gasto.id">
                        <div class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:border-indigo-200 hover:shadow-sm transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-400 group-hover:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-800 leading-none" x-text="gasto.concepto"></p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-medium" x-text="gasto.tipo ?? 'Cargo'"></p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-700">$<span x-text="formatMoney(gasto.monto)"></span></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</div>
                <!-- Total general -->
                <div class="mt-6" x-show="cart.length > 0">
                    <div class="total-card">
                        <div>
                            <p class="total-title">Total a pagar</p>
                            <p class="total-description">Incluye productos con descuento + gastos administrativos</p>
                        </div>
                        <p class="total-value">$ <span x-text="formatMoney(subtotal + totalGastos)"></span></p>
                    </div>
                </div>

            </div>
            
            <style>
                /* === CARDS DEL RESUMEN === */
.resumen-card {
    background-color: #ffffff !important;
    border-radius: 18px !important;
    padding: 18px 20px !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06) !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    min-height: 150px !important;
}

.resumen-title {
    font-size: 11px !important;
    line-height: 1.2 !important;
    letter-spacing: 0.18em !important;
    text-transform: uppercase !important;
    font-weight: 600 !important;
    color: #9ca3af !important;
    margin-bottom: 6px !important;
}

.resumen-value {
    font-size: 24px !important;
    line-height: 1.1 !important;
    font-weight: 700 !important;
    color: #0f172a !important;
}

/* === LISTA DE GASTOS DENTRO DE LA CARD === */
.gasto-item {
    display: flex !important;
    align-items: flex-start !important;
    justify-content: space-between !important;
    padding: 6px 10px !important;
    border-radius: 12px !important;
    background-color: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
}

.gasto-title {
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #4b5563 !important;
}

.gasto-subtitle {
    font-size: 11px !important;
    color: #9ca3af !important;
}

.gasto-monto {
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #111827 !important;
}

/* === TOTAL A PAGAR (BARRA VERDE) === */
.total-card {
    width: 100% !important;
    border-radius: 16px !important;
    padding: 16px 24px !important;
    background: linear-gradient(90deg, #059669, #10b981) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    box-shadow: 0 16px 40px rgba(16, 185, 129, 0.45) !important;
    color: #ffffff !important;
}

.total-title {
    font-size: 13px !important;
    font-weight: 700 !important;
    letter-spacing: 0.16em !important;
    text-transform: uppercase !important;
    margin-bottom: 2px !important;
    opacity: 0.9 !important;
}

.total-description {
    font-size: 11px !important;
    opacity: 0.9 !important;
}

.total-value {
    font-size: 26px !important;
    font-weight: 800 !important;
    white-space: nowrap !important;
}

/* === DARK MODE === */
.dark .resumen-card {
    background-color: #020617 !important;
    border-color: #1e293b !important;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.65) !important;
}

.dark .resumen-title {
    color: #64748b !important;
}

.dark .resumen-value {
    color: #e5e7eb !important;
}

.dark .gasto-item {
    background-color: #020617 !important;
    border-color: #1f2937 !important;
}

.dark .gasto-title,
.dark .gasto-monto {
    color: #e5e7eb !important;
}

.dark .gasto-subtitle {
    color: #9ca3af !important;
}

            </style>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div x-show="!esVendedoraAutenticada && !esLiderAutenticado" x-cloak class="group">
        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 ml-1">Líder asignada</label>
        <div class="relative">
            <select
                x-model="lider_id"
                @change="updateDatosPedido()"
                class="w-full bg-white border-0 ring-1 ring-slate-200 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition-all focus:ring-2 focus:ring-[#294395] appearance-none"
            >
                <option value="">Seleccionar líder</option>
                @foreach($this->lideres as $l)
                    <option value="{{ $l['id'] }}">{{ $l['name'] }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    <div x-show="!esVendedoraAutenticada" x-cloak class="group">
        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 ml-1">Vendedora</label>
        <div class="relative">
            <select
                x-model="vendedora_id"
                @change="updateDatosPedido()"
                class="w-full bg-white border-0 ring-1 ring-slate-200 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition-all focus:ring-2 focus:ring-[#e91e63] appearance-none"
            >
                <option value="">Seleccionar vendedora</option>
                <template x-for="v in vendedorasDisponibles" :key="v.id">
                    <option :value="v.id" x-text="v.name"></option>
                </template>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>
</div>




<div class="bg-white rounded-3xl ring-1 ring-slate-200/60 shadow-sm overflow-hidden mt-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
        
        <div class="p-8 space-y-6">
            <div class="flex items-center gap-2 mb-2">
                <div class="h-1.5 w-1.5 rounded-full bg-[#294395]"></div>
                <h3 class="text-sm font-black uppercase tracking-tighter text-slate-800">Verificación de Envío</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Líder</p>
                    <div class="min-h-[60px] flex flex-col justify-center border-l-2 border-slate-100 pl-4">
                        <template x-if="datosPedido.lider.nombre">
                            <div>
                                <p class="text-sm font-bold text-slate-900" x-text="datosPedido.lider.nombre"></p>
                                <p class="text-xs text-slate-500 mt-1" x-text="datosPedido.lider.direccion"></p>
                                <p class="text-[10px] font-medium text-[#294395] mt-1" x-text="datosPedido.lider.zona"></p>
                            </div>
                        </template>
                        <template x-if="!datosPedido.lider.nombre">
                            <span class="text-xs italic text-slate-300">Esperando selección...</span>
                        </template>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Vendedora/Responsable</p>
                    <div class="min-h-[60px] flex flex-col justify-center border-l-2 border-slate-100 pl-4">
                        <template x-if="datosPedido.vendedora.nombre">
                            <div>
                                <p class="text-sm font-bold text-slate-900" x-text="datosPedido.vendedora.nombre"></p>
                                <p class="text-xs text-slate-500 mt-1" x-text="datosPedido.vendedora.direccion"></p>
                                <p class="text-[10px] font-medium text-[#e91e63] mt-1" x-text="datosPedido.vendedora.zona"></p>
                            </div>
                        </template>
                        <template x-if="!datosPedido.vendedora.nombre">
                            <span class="text-xs italic text-slate-300">Esperando selección...</span>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 bg-slate-50/50 space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Observaciones del pedido</label>
                <textarea 
                    x-model="observaciones" 
                    rows="3" 
                    placeholder="¿Alguna indicación especial?"
                    class="w-full border-0 ring-1 ring-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-700 bg-white shadow-inner focus:ring-2 focus:ring-slate-900 transition-all resize-none"
                ></textarea>
            </div>

            <div class="pt-2">
                <button
                    @click="handleSubmit()"
                    :disabled="isSubmitting"
                    class="w-full relative group overflow-hidden rounded-2xl bg-slate-900 px-8 py-4 text-sm font-bold text-white transition-all hover:bg-black disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <div class="relative z-10 flex items-center justify-center gap-3">
                        <template x-if="!isSubmitting">
                            <div class="flex items-center gap-2">
                                <span>Confirmar y Crear Pedido</span>
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </div>
                        </template>

                        <template x-if="isSubmitting">
                            <div class="flex items-center gap-3">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span>Procesando envío...</span>
                            </div>
                        </template>
                    </div>
                </button>
                <p class="mt-3 text-[10px] text-center text-slate-400 font-medium">Al hacer clic, el pedido será enviado para revisión inmediata.</p>
            </div>
        </div>
    </div>
</div>


            <div class="fixed right-4 z-[6] flex flex-col gap-3 bottom-24 md:bottom-6">
                <button
                    x-show="!estaEnCarrito"
                    x-cloak
                    @click="irAlCarrito()"
                    class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-3 text-xs font-bold text-white shadow-lg shadow-indigo-300 hover:bg-indigo-700 transition"
                >
                    <x-phosphor-shopping-cart class="w-4 h-4" />
                    <span>Ir al carrito</span>
                </button>

                <button
                    x-show="estaEnCarrito"
                    x-cloak
                    @click="volverAProductos()"
                    class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-3 text-xs font-bold text-white shadow-lg hover:bg-black transition"
                >
                    <x-phosphor-arrow-u-up-left class="w-4 h-4" />
                    <span>Volver a productos</span>
                </button>
            </div>

            <!-- MODAL ÉXITO PEDIDO NUEVO -->
            <div
                x-show="successModalOpen"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
            >
                <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 mb-4">
                        <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Pedido creado con éxito</h2>
                    <p class="text-sm text-gray-600 mb-4" x-text="successModalMessage || 'Tu pedido fue registrado correctamente. Ahora vas a ser redirigido a tus pedidos.'"></p>

                    <button
                        @click="goToMisPedidos()"
                        class="mt-2 inline-flex items-center justify-center px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition"
                    >
                        Ir a mis pedidos
                    </button>
                </div>
            </div>
            <!-- FIN MODAL -->

        </div>



            @include('theme::pages.partials.pedido-app-script', [
                'categorias' => $this->categorias,
                'productos' => $this->productos,
                'paginas' => [],
                'vendedoras' => $this->vendedoras,
                'lideres' => $this->lideres,
                'responsable' => $this->responsable,
                'esVendedoraAutenticada' => $this->esVendedoraAutenticada,
                'esLiderAutenticado' => $this->esLiderAutenticado,
                'gastos' => $this->gastos_administrativos,
                'vendedoraSeleccionadaId' => $this->vendedoraSeleccionadaId,
                'liderSeleccionadoId' => $this->liderSeleccionadoId,
                'codigoPedido' => $this->codigo_pedido,
            ])



    </x-app.container>
    @endvolt
</x-layouts.app>
