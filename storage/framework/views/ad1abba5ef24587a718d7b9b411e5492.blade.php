<?php

use function Laravel\Folio\{middleware, name};
use App\Services\PedidoCartService;
use Livewire\Volt\Component;

?>


    <x-app.container>

        {{-- Styles moved to assets/css/app.css --}}

        <div
            class="container mx-auto p-4"
            x-data="pedidoApp()"
            x-init="init()"
        >

            <h1 class="text-xl font-bold mb-4">Crear Pedido</h1>

            <template x-if="messages.success">
                <div class="p-3 mt-4 text-green-700 bg-green-100 border border-green-300 rounded" x-text="messages.success"></div>
            </template>
            <template x-if="messages.error">
                <div class="p-3 mt-4 text-red-700 bg-red-100 border border-red-300 rounded" x-text="messages.error"></div>
            </template>

            <!-- Filtros categorías -->
            <div class="flex flex-wrap gap-3 mb-4 mt-6">
                <button
                    :class="filter_categoria === '' ? 'bg-orange-500 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    @click="filter_categoria=''"
                    class="px-4 py-2 rounded transition font-medium">
                    Todas
                </button>
                <template x-for="cat in categorias" :key="cat">
                    <button
                        :class="filter_categoria === cat
                            ? 'bg-orange-500 text-white shadow-md'
                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                        @click="filter_categoria=cat"
                        class="px-4 py-2 rounded transition font-medium"
                        x-text="cat">
                    </button>
                </template>
            </div>

            <!-- Barra de búsqueda -->
            <div class="mb-4">
  <input type="text" placeholder="Buscar producto o SKU..."
       x-model="search"
       @keyup.enter.prevent="addBySku()"
       class="w-full sm:w-1/2 border-gray-300 rounded-md shadow px-4 py-3 focus:ring-2 focus:ring-orange-500">
    <template x-if="messages.error">
        <p class="text-xs text-red-500 mt-1" x-text="messages.error"></p>
    </template>
</div>

           <div class="space-y-5 mb-6">
    <template x-for="cat in categorias" :key="cat">
        <div x-show="(filter_categoria === '' || filter_categoria === cat) && productos.filter(p => p.categorias.includes(cat) && matchesSearch(p)).length > 0" 
             class="border rounded-2xl bg-white shadow-sm">
            
            <button
                type="button"
                class="w-full flex items-center justify-between px-4 py-3 border-b"
                @click="openCategorias[cat] = !openCategorias[cat]">
                <span class="font-semibold text-gray-800" x-text="cat"></span>
                <span class="text-sm text-gray-500" x-text="openCategorias[cat] ? 'Ocultar' : 'Mostrar'"></span>
            </button>

            <div class="px-4 py-4" 
                 x-show="openCategorias[cat] || (search.trim().length > 0 && productos.filter(p => p.categorias.includes(cat) && matchesSearch(p)).length > 0)" 
                 x-transition>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <template
                        x-for="producto in productos.filter(p =>
                            p.categorias.includes(cat) && matchesSearch(p)
                        )"
                        :key="producto.id">
                        <div
                            class="border rounded-2xl shadow p-3 flex flex-col bg-white hover:shadow-lg transition"
                            :class="esProductoVisibilidadBloqueado(producto)
                                ? 'opacity-60 border-dashed border-red-400 bg-red-50/60'
                                : ''"
                        >
                            <div class="h-32 w-full mb-2 rounded bg-gray-100 flex items-center justify-center overflow-hidden">
                                <template x-if="producto.imagen">
                                    <img :src="producto.imagen" alt="" class="h-32 w-full object-cover rounded">
                                </template>
                                <template x-if="!producto.imagen">
                                    <span class="text-xs text-gray-400">Sin imagen</span>
                                </template>
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <h3 class="font-medium text-sm mb-1 text-gray-800" x-text="producto.nombre"></h3>
                                <div class="text-sm text-gray-700 mb-1">
                                    $<span x-text="formatMoney(producto.precio)"></span>
                                </div>
                                <div class="text-xs text-gray-500 mb-3">
                                    SKU: <span class="font-bold" x-text="producto.sku"></span>
                                </div>

                                <button
                                    @click="!esProductoVisibilidadBloqueado(producto) && addToCart(producto)"
                                    :disabled="esProductoVisibilidadBloqueado(producto)"
                                    :class="esProductoVisibilidadBloqueado(producto)
                                        ? 'mt-auto px-3 py-1 bg-gray-300 text-gray-500 rounded-lg text-sm cursor-not-allowed'
                                        : 'mt-auto px-3 py-1 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 text-sm transition'"
                                >
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>

            <!-- Carrito -->
            <div class="bg-white shadow rounded-2xl p-4 mb-4">
                <h2 class="text-lg font-semibold mb-3 text-gray-800">Carrito de compras</h2>
                <div class="mb-4">
                    <label class="font-medium text-gray-700">Código pedido</label>
                    <input type="text" x-model="codigo_pedido" class="w-full border rounded px-3 py-3 bg-gray-50" readonly>
                </div>

                <template x-if="cart.length === 0">
                    <p class="text-gray-500">El carrito está vacío.</p>
                </template>

                <table class="w-full text-sm" x-show="cart.length > 0">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-3 py-2 text-left">Producto</th>
                            <th class="px-3 py-2 text-left">Cantidad</th>
                            <th class="px-3 py-2 text-left">Precio catálogo</th>
                            <th class="px-3 py-2 text-left">Precio con descuento</th>
                            <th class="px-3 py-2 text-left">Subtotal</th>
                            <th class="px-3 py-2 text-left">Puntos</th>
                            <th class="px-3 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item,index) in cart" :key="item.producto_id">
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-3 py-2 text-gray-800" x-text="item.nombre"></td>
                                <td class="px-3 py-2 flex items-center gap-1">
                                    <button @click="decreaseQty(index)" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 text-gray-700">-</button>
                                    <input type="number" min="1" x-model.number="item.cantidad" @input="updateItem(index)" class="w-16 border rounded px-2 py-1 text-center">
                                    <button @click="increaseQty(index)" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 text-gray-700">+</button>
                                </td>
                                <td class="px-3 py-2 text-gray-700" x-text="formatMoney(item.precio_unitario_catalogo)"></td>
                                <td class="px-3 py-2 text-gray-700" x-text="formatMoney(item.precio_con_descuento)"></td>
                                <td class="px-3 py-2 text-gray-700" x-text="formatMoney(item.subtotal)"></td>
                                <td class="px-3 py-2 text-gray-700" x-text="(item.puntos * item.cantidad)"></td>
                                <td class="px-3 py-2">
                                    <button
                                        @click="removeItem(index)"
                                        class="p-2 rounded-full bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h3.999A2 2 0 0116 5v2" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="mt-4 text-right" x-show="cart.length > 0">
                    <button @click="clearCart()" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 text-gray-700 font-medium">Vaciar carrito</button>
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

            <!-- Selects (invertidos: primero Líder, luego Vendedora) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div x-show="!esVendedoraAutenticada && !esLiderAutenticado" x-cloak>
                    <label class="font-medium text-gray-700">Lider</label>
                    <select
                        x-model="lider_id"
                        @change="updateDatosPedido()"
                        class="w-full border rounded px-3 py-3"
                    >
                        <option value="">Seleccionar lider</option>
                        @foreach($this->lideres as $l)
                            <option value="{{ $l['id'] }}">{{ $l['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="!esVendedoraAutenticada" x-cloak>
                    <label class="font-medium text-gray-700">Vendedora</label>
                    <select
                        x-model="vendedora_id"
                        @change="updateDatosPedido()"
                        class="w-full border rounded px-3 py-3"
                    >
                        <option value="">Seleccionar vendedora</option>
                        @foreach($this->vendedoras as $v)
                            <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Datos de pedido + observaciones + botón -->
            <div class="bg-white shadow rounded-2xl p-4 mt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">

                    <!-- Líder -->
                    <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-4">
                        <p class="text-xs font-semibold tracking-[0.18em] text-gray-500 mb-3 uppercase">
                            Datos de la líder
                        </p>

                        <div class="flex items-center justify-between border-b border-gray-200 py-1.5">
                            <span class="text-gray-600">Líder</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.lider.nombre">
                                    <span class="font-semibold text-gray-900" x-text="datosPedido.lider.nombre"></span>
                                </template>
                                <template x-if="!datosPedido.lider.nombre">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-b border-gray-200 py-1.5">
                            <span class="text-gray-600">Dirección</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.lider.direccion">
                                    <span class="text-gray-800 text-right" x-text="datosPedido.lider.direccion"></span>
                                </template>
                                <template x-if="!datosPedido.lider.direccion">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-gray-600">Ciudad</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.lider.zona">
                                    <span class="text-gray-800 text-right" x-text="datosPedido.lider.zona"></span>
                                </template>
                                <template x-if="!datosPedido.lider.zona">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Vendedora -->
                    <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-4">
                        <p class="text-xs font-semibold tracking-[0.18em] text-gray-500 mb-3 uppercase">
                            Datos de la vendedora
                        </p>

                        <div class="flex items-center justify-between border-b border-gray-200 py-1.5">
                            <span class="text-gray-600">Vendedora</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.vendedora.nombre">
                                    <span class="font-semibold text-gray-900" x-text="datosPedido.vendedora.nombre"></span>
                                </template>
                                <template x-if="!datosPedido.vendedora.nombre">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-b border-gray-200 py-1.5">
                            <span class="text-gray-600">Dirección</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.vendedora.direccion">
                                    <span class="text-gray-800 text-right" x-text="datosPedido.vendedora.direccion"></span>
                                </template>
                                <template x-if="!datosPedido.vendedora.direccion">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-gray-600">Ciudad</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.vendedora.zona">
                                    <span class="text-gray-800 text-right" x-text="datosPedido.vendedora.zona"></span>
                                </template>
                                <template x-if="!datosPedido.vendedora.zona">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Responsable -->
                    <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-4">
                        <p class="text-xs font-semibold tracking-[0.18em] text-gray-500 mb-3 uppercase">
                            Responsable
                        </p>

                        <div class="flex items-center justify-between border-b border-gray-200 py-1.5">
                            <span class="text-gray-600">Nombre</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.responsable.nombre">
                                    <span class="font-semibold text-gray-900" x-text="datosPedido.responsable.nombre"></span>
                                </template>
                                <template x-if="!datosPedido.responsable.nombre">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-b border-gray-200 py-1.5">
                            <span class="text-gray-600">Dirección</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.responsable.direccion">
                                    <span class="text-gray-800 text-right" x-text="datosPedido.responsable.direccion"></span>
                                </template>
                                <template x-if="!datosPedido.responsable.direccion">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-gray-600">Ciudad</span>
                            <div class="ml-4">
                                <template x-if="datosPedido.responsable.zona">
                                    <span class="text-gray-800 text-right" x-text="datosPedido.responsable.zona"></span>
                                </template>
                                <template x-if="!datosPedido.responsable.zona">
                                    <span class="inline-block w-28 border-b border-gray-400"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-6 md:col-span-3">
                    <label class="font-medium text-gray-700">Observaciones</label>
                    <textarea x-model="observaciones" rows="2" class="w-full border rounded px-3 py-3 mt-1"></textarea>
                </div>

                <div class="mt-4 w-full text-right">
                                    <button
                    @click="handleSubmit()"
                    :disabled="isSubmitting"
                    class="px-4 py-2 rounded-lg font-semibold transition flex items-center justify-center gap-2
                           text-white bg-green-600 hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed">
                    <template x-if="!isSubmitting">
                        <span>Crear pedido</span>
                    </template>
                
                    <!-- SPINNER -->
                    <template x-if="isSubmitting">
                        <div class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Procesando…</span>
                        </div>
                    </template>
                </button>

                </div>
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
    