<?php

use function Laravel\Folio\{middleware, name};
use App\Services\PedidoCartService;
use Livewire\Volt\Component;

?>


    <x-app.container>
        <style>
            @keyframes catalogoNeonPulse {
              0%,
              100% {
                box-shadow:
                  0 0 14px rgba(99, 102, 241, 0.9),
                  0 0 28px rgba(167, 139, 250, 0.65),
                  0 0 38px rgba(99, 102, 241, 0.25);
                filter: drop-shadow(0 0 6px rgba(167, 139, 250, 0.55));
              }

              50% {
                box-shadow:
                  0 0 8px rgba(99, 102, 241, 0.6),
                  0 0 16px rgba(167, 139, 250, 0.4),
                  0 0 28px rgba(99, 102, 241, 0.18);
                filter: drop-shadow(0 0 4px rgba(167, 139, 250, 0.4));
              }
            }

            @keyframes catalogoCheckFlash {
              0% {
                transform: scale(1);
              }

              35% {
                transform: scale(1.12);
              }

              70% {
                transform: scale(0.96);
              }

              100% {
                transform: scale(1);
              }
            }

            @layer components {
              .btn-catalogo-neon {
                animation: catalogoNeonPulse 2.8s ease-in-out infinite;
              }

              .btn-catalogo-neon:hover {
                box-shadow:
                  0 0 18px rgba(99, 102, 241, 1),
                  0 0 36px rgba(167, 139, 250, 0.7),
                  0 0 48px rgba(99, 102, 241, 0.35);
              }

              .btn-catalogo-flotante {
                width: clamp(2.4rem, 8vw, 2.8rem);
                height: clamp(2.4rem, 8vw, 2.8rem);
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 9999px;
                background-color: #4f46e5;
                color: #fff;
                box-shadow: 0 10px 25px rgba(79, 70, 229, 0.25);
                @apply shadow-lg ring-2 ring-indigo-200/80 backdrop-blur transition hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-300/70;
              }

              @media (min-width: 768px) {
                .btn-catalogo-flotante {
                  width: 3rem;
                  height: 3rem;
                }
              }

              .btn-catalogo-icon {
                @apply transition duration-200 ease-out;
              }

              .btn-catalogo-icon--check {
                @apply text-white;
              }

              .btn-catalogo-exito {
                animation: catalogoCheckFlash 0.9s ease;
                @apply bg-emerald-600 ring-emerald-200;
              }

              .btn-catalogo-deshabilitado {
                @apply cursor-not-allowed bg-gray-300 text-gray-500 ring-gray-200 hover:scale-100;
                box-shadow: none;
                animation: none;
              }

              .btn-catalogo-deshabilitado .btn-catalogo-icon {
                opacity: 0.6;
              }
            }

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

        <div
            class="container mx-auto"
            x-data="pedidoAppWithSwipe()"
            x-init="init()"
            x-on:keydown.arrow-left.window="paginaAnterior()"
            x-on:keydown.arrow-right.window="paginaSiguiente()"
        >

            <!-- Mensajes -->
            <template x-if="messages.success">
                <div class="p-3 mt-4 text-green-700 bg-green-100 border border-green-300 rounded" x-text="messages.success"></div>
            </template>

            <!-- === Wrapper general del catálogo (2 columnas en desktop) === -->
            <div class="mt-6 mb-6 lg:grid lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)] lg:gap-6 lg:items-start">

                <!-- Columna izquierda: visor + miniaturas mobile -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-sm uppercase tracking-wide text-slate-500 font-semibold">Catálogo</p>
                            <h2 class="text-2xl font-bold text-slate-800">Agregá productos al carrito</h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="px-3 py-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700"
                                @click="paginaAnterior()"
                            >
                                ⟨
                            </button>
                            <button
                                type="button"
                                class="px-3 py-2 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700"
                                @click="paginaSiguiente()"
                            >
                                ⟩
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <template x-if="paginas.length === 0">
                            <div class="p-6 rounded-2xl border border-dashed text-center text-gray-500 bg-white">
                                No hay páginas de catálogo configuradas.
                            </div>
                        </template>

                        <template x-if="paginas.length > 0">
                            <div>
                                <!-- Slider principal con efecto "revista" (slide suave) -->
                                <div
                                    class="relative overflow-hidden rounded-2xl shadow bg-white"
                                    x-on:touchstart="handleTouchStart($event)"
                                    x-on:touchend="handleTouchEnd($event)"
                                    x-on:mousedown="handleMouseDown($event)"
                                    x-on:mouseup="handleMouseUp($event)"
                                    x-on:mouseleave="handleMouseUp($event)"
                                >
                                    <div class="relative w-full bg-slate-100 flex justify-center">
                                        <div
                                            class="relative inline-block w-full bg-slate-100 overflow-hidden"
                                            style="min-height: 320px;"
                                        >
                                            <div
                                                class="flex transition-transform duration-600 ease-out"
                                                :style="`transform: translateX(-${paginaActiva * 100}%);`"
                                            >
                                                <template x-for="(pagina, index) in paginas" :key="pagina.id">
                                                    <div class="relative min-w-full flex justify-center">
                                                        <div class="relative inline-block">
                                                            <img
                                                                :src="pagina.imagen_path"
                                                                class="block max-h-[100vh] max-w-full h-auto"
                                                                :alt="`Página ${pagina.numero}`"
                                                            >

                                                            <template x-for="producto in pagina.productos" :key="producto.id">
                                                                <button
                                                                    type="button"
                                                                    class="btn-catalogo-flotante btn-catalogo-neon absolute z-20 flex items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg ring-2 ring-indigo-200/80 backdrop-blur -translate-x-1/2 -translate-y-1/2 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-300/70"
                                                                    :style="posicionFlotante(producto)"
                                                                    :disabled="esProductoVisibilidadBloqueado(producto.producto ?? producto)"
                                                                    :aria-disabled="esProductoVisibilidadBloqueado(producto.producto ?? producto)"
                                                                    @click="addProductoDesdePagina(producto)"
                                                                    :class="{
                                                                        'btn-catalogo-exito': estadoBotonCatalogo[producto.producto_id ?? producto.id] === 'agregado',
                                                                        'btn-catalogo-deshabilitado': esProductoVisibilidadBloqueado(producto.producto ?? producto),
                                                                    }"
                                                                >
                                                                    <span class="relative flex items-center justify-center">
                                                                        <svg
                                                                            x-show="estadoBotonCatalogo[producto.producto_id ?? producto.id] !== 'agregado'"
                                                                            x-cloak
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            class="btn-catalogo-icon h-5 w-5"
                                                                            viewBox="0 0 256 256"
                                                                            fill="currentColor"
                                                                            aria-hidden="true"
                                                                        >
                                                                            <path d="M230.14,58.87A8,8,0,0,0,224,56H62.68L56.6,22.57A8,8,0,0,0,48.73,16H24a8,8,0,0,0,0,16h18L67.56,172.29a24,24,0,0,0,5.33,11.27,28,28,0,1,0,44.4,8.44h45.42A27.75,27.75,0,0,0,160,204a28,28,0,1,0,28-28H91.17a8,8,0,0,1-7.87-6.57L80.13,152h116a24,24,0,0,0,23.61-19.71l12.16-66.86A8,8,0,0,0,230.14,58.87ZM104,204a12,12,0,1,1-12-12A12,12,0,0,1,104,204Zm96,0a12,12,0,1,1-12-12A12,12,0,0,1,200,204Zm4-74.57A8,8,0,0,1,196.1,136H77.22L65.59,72H214.41Z" />
                                                                        </svg>

                                                                        <svg
                                                                            x-show="estadoBotonCatalogo[producto.producto_id ?? producto.id] === 'agregado'"
                                                                            x-cloak
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            class="btn-catalogo-icon btn-catalogo-icon--check h-5 w-5"
                                                                            viewBox="0 0 256 256"
                                                                            fill="currentColor"
                                                                            aria-hidden="true"
                                                                        >
                                                                            <path d="M232.49,80.49a12,12,0,0,0-17,0l-99,99-36.95-37A12,12,0,0,0,62.49,160l45.44,45.45a12,12,0,0,0,17,0l107.52-107.52A12,12,0,0,0,232.49,80.49Z" />
                                                                        </svg>
                                                                    </span>
                                                                    <span class="sr-only">Agregar al carrito</span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Miniaturas MOBILE (fila horizontal) -->
                                <div class="mt-4 lg:hidden">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">
                                            Miniaturas
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            <span x-text="paginaActiva + 1"></span>
                                            /
                                            <span x-text="paginas.length"></span>
                                        </p>
                                    </div>

                                    <div class="flex gap-3 overflow-x-auto pb-1">
                                        <template x-for="(pagina, index) in paginas" :key="`thumb-mobile-${pagina.id}`">
                                            <button
                                                type="button"
                                                class="flex-shrink-0 border rounded-xl overflow-hidden relative group focus:outline-none"
                                                :class="paginaActiva === index ? 'ring-2 ring-indigo-500 border-indigo-500' : 'border-slate-200'"
                                                @click.prevent="irAPagina(index)"
                                            >
                                                <div class="w-24 h-32 bg-slate-100 flex items-center justify-center overflow-hidden">
                                                    <img
                                                        :src="pagina.imagen_path"
                                                        :alt="`Miniatura página ${pagina.numero}`"
                                                        class="w-full h-full object-cover"
                                                    >
                                                </div>
                                                <div class="absolute top-1 left-1 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-black/70 text-white">
                                                    <span x-text="pagina.numero"></span>
                                                </div>
                                            </button>
                                        </template>
                                    </div>

                                    <!-- Indicadores MOBILE -->
                                    <div class="flex justify-center gap-2 mt-4">
                                        <template x-for="(pagina, index) in paginas" :key="`indicator-${pagina.id}`">
                                            <button
                                                type="button"
                                                class="h-2.5 rounded-full transition-all"
                                                :class="paginaActiva === index ? 'w-6 bg-indigo-600' : 'w-2.5 bg-gray-300'"
                                                @click="irAPagina(index)"
                                            ></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Columna derecha (DESKTOP): lista de páginas scrolleable 100vh -->
                <div class="hidden lg:flex lg:flex-col lg:h-screen lg:overflow-y-auto">
                    <div class="flex items-center justify-between mb-3 mt-1">
                        <h3 class="text-sm font-semibold tracking-wide text-slate-600 uppercase">
                            Páginas del catálogo
                        </h3>
                        <span class="text-xs text-slate-500">
                            <span x-text="paginas.length"></span> páginas
                        </span>
                    </div>

                    <div class="space-y-3 pb-6">
                        <template x-for="(pagina, index) in paginas" :key="`list-${pagina.id}`">
                            <button
                                type="button"
                                class="w-full text-left border rounded-xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow relative group focus:outline-none"
                                :class="paginaActiva === index ? 'ring-2 ring-indigo-500 border-indigo-500' : 'border-slate-200'"
                                @click.prevent="irAPagina(index)"
                            >
                                <div class="flex">
                                    <div class="w-24 h-32 flex-shrink-0 bg-slate-100 overflow-hidden">
                                        <img
                                            :src="pagina.imagen_path"
                                            :alt="`Página ${pagina.numero}`"
                                            class="w-full h-full object-cover"
                                        >
                                    </div>
                                    <div class="flex-1 p-3 flex flex-col justify-between">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <p class="text-xs font-semibold tracking-[0.18em] uppercase text-slate-500">
                                                Página <span x-text="pagina.numero"></span>
                                            </p>
                                            <span
                                                class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                                :class="paginaActiva === index ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500'"
                                            >
                                                Ver
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 line-clamp-2">
                                            Productos: <span x-text="pagina.productos?.length || 0"></span>
                                        </p>
                                    </div>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

            </div>
            <!-- FIN GRID CATÁLOGO -->

            <!-- Carrito -->
            <div class="bg-white dark:bg-slate-950 shadow rounded-2xl p-4 mb-4 border border-slate-200 dark:border-slate-800 overflow-hidden">
                <h2 class="text-lg font-semibold mb-3 text-gray-800">Carrito de compras</h2>
                <div class="mb-4">
                    <label class="font-medium text-gray-700">Código pedido</label>
                    <input type="text" x-model="codigo_pedido" class="w-full border rounded px-3 py-3 bg-gray-50" readonly>
                </div>

                <template x-if="cart.length === 0">
                    <p class="text-gray-500">El carrito está vacío.</p>
                </template>

                <!-- Cards responsive (mobile) -->
                <div class="md:hidden" x-show="cart.length > 0">
                    <div class="grid grid-cols-1 gap-4">
                        <template x-for="(item,index) in cart" :key="item.producto_id">
                            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm p-4 space-y-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-[11px] uppercase tracking-wide text-slate-500 font-semibold">Producto</p>
                                        <p class="text-base font-semibold text-slate-900 dark:text-white leading-snug" x-text="item.nombre"></p>
                                    </div>
                                    <button
                                        @click="removeItem(index)"
                                        class="flex-shrink-0 p-2 rounded-full bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 transition"
                                        aria-label="Eliminar producto"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h3.999A2 2 0 0116 5v2" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500 font-semibold">Cantidad</p>
                                    <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/80 rounded-xl px-2 py-2 w-full sm:max-w-xs">
                                        <button @click="decreaseQty(index)" class="px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">-</button>
                                        <input
                                            type="number"
                                            min="1"
                                            x-model.number="item.cantidad"
                                            @input="updateItem(index)"
                                            class="w-20 text-center border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-2 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100"
                                        >
                                        <button @click="increaseQty(index)" class="px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">+</button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 divide-y-0 divide-x-0 divide-slate-200 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-200">
                                    <div class="space-y-1">
                                        <p class="text-[11px] uppercase tracking-wide text-slate-500 font-semibold">Precio catálogo</p>
                                        <p class="font-semibold text-slate-900 dark:text-white">$ <span x-text="formatMoney(item.precio_unitario_catalogo)"></span></p>
                                    </div>
  
                                   <!--  <div class="space-y-1">
                                     <p class="text-[11px] uppercase tracking-wide text-slate-500 font-semibold">Descuento</p>
                                        <p class="font-semibold text-slate-900 dark:text-white" x-text="(item.porcentaje_descuento ?? 0) + '%' "></p>
                                    </div> -->



                                    <div class="space-y-1">
                                        <p class="text-[11px] uppercase tracking-wide text-slate-500 font-semibold">Precio con descuento</p>
                                        <p class="font-semibold text-slate-900 dark:text-white">$ <span x-text="formatMoney(item.precio_con_descuento)"></span></p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[11px] uppercase tracking-wide text-slate-500 font-semibold">Subtotal</p>
                                        <p class="font-semibold text-slate-900 dark:text-white">$ <span x-text="formatMoney(item.subtotal)"></span></p>
                                    </div>
                                    <div class="space-y-1 col-span-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                                        <p class="text-[11px] uppercase tracking-wide text-slate-500 font-semibold">Puntos</p>
                                        <p class="font-semibold text-slate-900 dark:text-white" x-text="(item.puntos * item.cantidad)"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Tabla (desktop y tablets) -->
                <div class="hidden md:block" x-show="cart.length > 0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-max">
                            <thead class="bg-gray-100 text-gray-700 dark:bg-slate-900 dark:text-slate-200">
                                <tr>
                                    <th class="px-3 py-2 text-left">Producto</th>
                                    <th class="px-3 py-2 text-left">Cantidad</th>
                                    <th class="px-3 py-2 text-left">Precio catálogo</th>
                                  <!--  <th class="px-3 py-2 text-left">Descuento</th> -->
                                    <th class="px-3 py-2 text-left">Precio con descuento</th>
                                    <th class="px-3 py-2 text-left">Subtotal</th>
                                    <th class="px-3 py-2 text-left">Puntos</th>
                                    <th class="px-3 py-2 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item,index) in cart" :key="item.producto_id">
                                    <tr class="border-t hover:bg-gray-50 dark:hover:bg-slate-800/60">
                                        <td class="px-3 py-2 text-gray-800 dark:text-slate-100" x-text="item.nombre"></td>
                                        <td class="px-3 py-2">
                                            <div class="flex items-center gap-1">
                                                <button @click="decreaseQty(index)" class="px-2 py-1 bg-gray-200 dark:bg-slate-800 rounded hover:bg-gray-300 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-100">-</button>
                                                <input type="number" min="1" x-model.number="item.cantidad" @input="updateItem(index)" class="w-16 border border-slate-200 dark:border-slate-700 rounded px-2 py-1 text-center bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                                <button @click="increaseQty(index)" class="px-2 py-1 bg-gray-200 dark:bg-slate-800 rounded hover:bg-gray-300 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-100">+</button>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-gray-700 dark:text-slate-200" x-text="formatMoney(item.precio_unitario_catalogo)"></td>
                                     <!--   <td class="px-3 py-2 text-gray-700 dark:text-slate-200" x-text="(item.porcentaje_descuento ?? 0) + '%'"></td> -->
                                        <td class="px-3 py-2 text-gray-700 dark:text-slate-200" x-text="formatMoney(item.precio_con_descuento)"></td>
                                        <td class="px-3 py-2 text-gray-700 dark:text-slate-200" x-text="formatMoney(item.subtotal)"></td>
                                        <td class="px-3 py-2 text-gray-700 dark:text-slate-200" x-text="(item.puntos * item.cantidad)"></td>
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
                    </div>
                </div>

                <div class="mt-4 text-right" x-show="cart.length > 0">
                    <button @click="clearCart()" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 text-gray-700 font-medium">
                        Vaciar carrito
                    </button>
                </div>

                <!-- Cards de resumen -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" x-show="cart.length > 0">
                    <div class="resumen-card">
                        <p class="resumen-title">Subtotal (con descuento)</p>
                        <p class="resumen-value">$ <span x-text="formatMoney(subtotal)"></span></p>
                    </div>

                    <div class="resumen-card">
                        <p class="resumen-title">Ganancias</p>
                        <p class="resumen-value">$ <span x-text="formatMoney(totalGanancias)"></span></p>
                    </div>

                    <div class="resumen-card">
                        <p class="resumen-title">Total unidades</p>
                        <p class="resumen-value" x-text="totalUnidades"></p>
                        <p class="resumen-title mt-4">Total puntos</p>
                        <p class="resumen-value" style="font-size:1.2rem" x-text="totalPuntos"></p>
                    </div>

                    <div class="resumen-card">
                        <p class="resumen-title">Gastos administrativos</p>
                        <p class="resumen-value">$ <span x-text="formatMoney(totalGastos)"></span></p>

                        <div class="mt-3 space-y-2 max-h-32 overflow-y-auto pr-1">
                            <template x-for="gasto in gastosDisponibles" :key="gasto.id">
                                <div class="gasto-item">
                                    <div>
                                        <p class="gasto-title" x-text="gasto.concepto"></p>
                                        <p class="gasto-subtitle" x-text="gasto.tipo ?? 'Sin tipo'"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="gasto-monto">$ <span x-text="formatMoney(gasto.monto)"></span></p>
                                    </div>
                                </div>
                            </template>
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

            <!-- Selects (invertidos: primero Líder, luego Vendedora) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div x-show="!esVendedoraAutenticada && !esLiderAutenticado" x-cloak>
                    <label class="font-medium text-gray-700">Líder</label>
                    <select
                        x-model="lider_id"
                        @change="updateDatosPedido()"
                        class="w-full border rounded px-3 py-3"
                    >
                        <option value="">Seleccionar líder</option>
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
                               text-white bg-green-600 hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed"
                    >
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

            <!-- MODAL ERROR / NO CUMPLE CONDICIÓN -->
            <div
                x-show="messages.error"
                x-cloak
                class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50"
            >
                <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md">
                    <div class="flex items-center justify-center mb-4">
                        <div class="h-14 w-14 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v4m0 4h.01M4.93 4.93l14.14 14.14M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 text-center mb-2">
                        Atención
                    </h2>
                    <p class="text-sm text-gray-600 text-center mb-4" x-text="messages.error"></p>
                    <div class="flex justify-center">
                        <button
                            type="button"
                            @click="messages.error = null"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition"
                        >
                            Entendido
                        </button>
                    </div>
                </div>
            </div>
            <!-- FIN MODAL ERROR -->

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
            <!-- FIN MODAL ÉXITO -->

        </div>

        @include('theme::pages.partials.pedido-app-script', [
            'categorias' => $this->categorias,
            'productos' => $this->productos,
            'paginas' => $this->paginas_catalogo,
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

        <script>
            /**
             * Extiende window.pedidoApp() para agregar:
             * - Swipe en mobile (touch).
             * - Swipe en desktop (mouse drag).
             * - irAPagina sin hacer scroll hacia arriba.
             * - Funciona con el slider por transform/translateX (efecto revista).
             */
            window.pedidoAppWithSwipe = function () {
                const baseFactory = (typeof window.pedidoApp === 'function')
                    ? window.pedidoApp
                    : null;

                const base = baseFactory ? baseFactory() : {};

                return {
                    // Mezclamos todo lo original
                    ...base,

                    // Estado para swipe táctil
                    touchStartX: null,
                    touchStartY: null,

                    // Estado para swipe con mouse
                    mouseDown: false,
                    mouseStartX: null,
                    mouseStartY: null,

                    /**
                     * Sobreescribimos irAPagina para NO mover el scroll vertical.
                     */
                    irAPagina(index) {
                        const currentScrollTop = window.scrollY || document.documentElement.scrollTop;
                        const currentScrollLeft = window.scrollX || document.documentElement.scrollLeft;

                        if (base && typeof base.irAPagina === 'function') {
                            base.irAPagina.call(this, index);
                        } else {
                            this.paginaActiva = index;
                        }

                        this.$nextTick(() => {
                            window.scrollTo({
                                top: currentScrollTop,
                                left: currentScrollLeft,
                                behavior: 'auto',
                            });
                        });
                    },

                    // TOUCH
                    handleTouchStart(event) {
                        if (!event.touches || !event.touches.length) return;
                        this.touchStartX = event.touches[0].clientX;
                        this.touchStartY = event.touches[0].clientY;
                    },

                    handleTouchEnd(event) {
                        if (this.touchStartX === null) return;

                        const touch = (event.changedTouches && event.changedTouches[0]) || null;
                        if (!touch) {
                            this.touchStartX = null;
                            this.touchStartY = null;
                            return;
                        }

                        const deltaX = touch.clientX - this.touchStartX;
                        const deltaY = touch.clientY - this.touchStartY;

                        if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
                            if (deltaX < 0) {
                                // Swipe izquierda -> siguiente página
                                if (typeof this.paginaSiguiente === 'function') {
                                    this.paginaSiguiente();
                                }
                            } else {
                                // Swipe derecha -> página anterior
                                if (typeof this.paginaAnterior === 'function') {
                                    this.paginaAnterior();
                                }
                            }
                        }

                        this.touchStartX = null;
                        this.touchStartY = null;
                    },

                    // MOUSE
                    handleMouseDown(event) {
                        this.mouseDown = true;
                        this.mouseStartX = event.clientX;
                        this.mouseStartY = event.clientY;
                    },

                    handleMouseUp(event) {
                        if (!this.mouseDown) return;

                        this.mouseDown = false;

                        const deltaX = event.clientX - this.mouseStartX;
                        const deltaY = event.clientY - this.mouseStartY;

                        if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
                            if (deltaX < 0) {
                                // Arrastró hacia la izquierda
                                if (typeof this.paginaSiguiente === 'function') {
                                    this.paginaSiguiente();
                                }
                            } else {
                                // Arrastró hacia la derecha
                                if (typeof this.paginaAnterior === 'function') {
                                    this.paginaAnterior();
                                }
                            }
                        }

                        this.mouseStartX = null;
                        this.mouseStartY = null;
                    },
                };
            };
        </script>
        
        <script>
    // 1. Declaramos la función inmediatamente en la ventana global (window)
    // Esto asegura que esté disponible ANTES de que Alpine despierte.
    window.posicionFlotante = function(producto) {
        if (!producto) return 'display: none !important;';
        
        // El servicio usa pos_x y pos_y. Verificamos ambos nombres por seguridad.
        const x = producto.pos_x ?? producto.coordenada_x;
        const y = producto.pos_y ?? producto.coordenada_y;
        
        if (x === undefined || x === null) return 'display: none !important;';
        
        return `left: ${x}%; top: ${y}%; display: block; position: absolute;`;
    };

    // 2. Registro de inicialización para depuración
    document.addEventListener('alpine:init', () => {
        console.log('Catálogo: Alpine iniciado correctamente');
    });
</script>

    </x-app.container>
    