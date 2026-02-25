<div x-data="{ sidebarOpen: false }"  @open-sidebar.window="sidebarOpen = true"
    x-init="
        $watch('sidebarOpen', function(value){
            if(value){ document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); }
        });
    "
    class="relative z-50 w-screen md:w-auto" x-cloak>
    {{-- Backdrop for mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed top-0 right-0 z-50 w-screen h-screen duration-300 ease-out bg-black/20 dark:bg-white/10"></div>
    
    {{-- Sidebar --}} 
    <div :class="{
            'translate-y-full md:-translate-x-full': !sidebarOpen,
            'translate-y-0 md:translate-x-0': sidebarOpen,
        }"
        class="fixed inset-x-0 bottom-0 md:top-0 md:left-0 flex items-stretch translate-y-full md:-translate-x-full overflow-hidden lg:translate-x-0 lg:translate-y-0 z-50 h-dvh md:h-screen transition-[width,transform] duration-150 ease-out bg-zinc-50 dark:bg-zinc-900 w-full md:w-64 group @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">
        <div class="flex flex-col justify-between w-full overflow-auto md:h-full h-svh pt-4 pb-2.5">
            <div class="relative flex flex-col">
                <button x-on:click="sidebarOpen=false" class="flex items-center justify-center flex-shrink-0 w-10 h-10 ml-4 rounded-md lg:hidden text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 dark:hover:bg-zinc-700/70 hover:bg-gray-200/70">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>

                <div class="flex items-center px-5 space-x-2">
                    <a href="/" class="flex justify-center items-center py-4 pl-0.5 space-x-1 font-bold text-zinc-900">
                        <x-logo class="w-auto h-7" />
                    </a>
                </div>
                <div class="flex items-center px-4 pt-1 pb-3">
                    <div class="relative flex items-center w-full h-full rounded-lg">
                        <x-phosphor-magnifying-glass class="absolute left-0 w-5 h-5 ml-2 text-gray-400 -translate-y-px" />
                        <input type="text" class="w-full py-2 pl-8 text-sm border rounded-lg bg-zinc-200/70 focus:bg-white duration-50 dark:bg-zinc-950 ease border-zinc-200 dark:border-zinc-700/70 dark:ring-zinc-700/70 focus:ring dark:text-zinc-200 dark:focus:ring-zinc-700/70 dark:focus:border-zinc-700 focus:ring-zinc-200 focus:border-zinc-300 dark:placeholder-zinc-400" placeholder="Buscar">
                    </div>
                </div>

                <div class="flex flex-col justify-start items-center px-4 space-y-1.5 w-full h-full text-slate-600 dark:text-zinc-400">
                    <x-app.sidebar-link href="/dashboard" icon="phosphor-house" :active="Request::is('dashboard')">Escritorio</x-app.sidebar-link>
                        <x-app.sidebar-link href="/crearpedido" icon="phosphor-plus-circle">Crear Pedido</x-app.sidebar-link>
                        <x-app.sidebar-link href="/catalogo" icon="phosphor-book">Catálogo</x-app.sidebar-link>

                    @hasanyrole('lider|vendedora')
                        <x-app.sidebar-link href="/mis-pedidos" icon="phosphor-list-checks">Mis Pedidos</x-app.sidebar-link>
                    @endhasanyrole

                    @hasanyrole('lider|admin')
                        <x-app.sidebar-link href="/vendedoras" icon="phosphor-handshake">Vendedoras</x-app.sidebar-link>
                    @endhasanyrole

                    @hasanyrole('coordinadora|admin')
                        <x-app.sidebar-link href="/lideres" icon="phosphor-user-circle">Líderes</x-app.sidebar-link>
                    @endhasanyrole

                    @role('admin')
                        <x-app.sidebar-link href="/coordinadoras" icon="phosphor-user-switch">Coordinadoras</x-app.sidebar-link>
                    @endrole

                    @role('lider')
                        <x-app.sidebar-link href="/zona-lider" icon="phosphor-crown">Zona Líder</x-app.sidebar-link>
                    @endrole

                    @role('coordinadora')
                        <x-app.sidebar-link href="/zona-coordinadora" icon="phosphor-users-three">Zona Coordinadora</x-app.sidebar-link>
                    @endrole

                    <x-app.sidebar-link href="/perfil" icon="phosphor-user" :active="Request::is('perfil')">
                        Perfil
                    </x-app.sidebar-link>

                    @hasanyrole('lider|coordinadora|admin')
                        <x-app.sidebar-link href="/incorporar" icon="phosphor-user-plus">Incorporar</x-app.sidebar-link>
                    @endhasanyrole

                    @role('admin')
                        <!-- Catálogo -->
                        <x-app.sidebar-dropdown text="Catálogo" icon="phosphor-package" id="catalogo_dropdown"
                            :active="Request::is('categorias*') || Request::is('productos*') || Request::is('stock*') || Request::is('envios*') || Request::is('rotulos*')"
                            :open="Request::is('categorias*') || Request::is('productos*') || Request::is('stock*') || Request::is('envios*') || Request::is('rotulos*') ? '1' : '0'">
                            <x-app.sidebar-link href="/catalogo/admin" icon="phosphor-wrench" :active="Request::is('catalogo/admin*')">
                                Editar Catálogo
                            </x-app.sidebar-link>
                            <x-app.sidebar-link href="/pedidos" icon="phosphor-shopping-cart">Pedidos</x-app.sidebar-link>
                            <x-app.sidebar-link href="/productos" icon="phosphor-tag">Productos</x-app.sidebar-link>
                            <x-app.sidebar-link href="/categorias" icon="phosphor-folders">Categorías</x-app.sidebar-link>
                          <!--  <x-app.sidebar-link href="/stock" icon="phosphor-warehouse">Stock</x-app.sidebar-link> -->
                            <x-app.sidebar-link href="/rotulos" icon="phosphor-ticket">Rótulos</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>

                        <!-- Ventas 
                        <x-app.sidebar-dropdown text="Ventas" icon="phosphor-handshake" id="ventas_dropdown"
                            :active="Request::is('campanas*') || Request::is('pedidos*') || Request::is('clientes*')"
                            :open="Request::is('campanas*') || Request::is('pedidos*') || Request::is('clientes*') ? '1' : '0'">
                           <!-- <x-app.sidebar-link href="/campanas" icon="phosphor-megaphone">Campañas</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>
                        -->
                        

                        <!-- Finanzas -->
                        <x-app.sidebar-dropdown text="Finanzas" icon="phosphor-currency-circle-dollar" id="finanzas_dropdown"
                            :active="Request::is('facturas*') || Request::is('gastos*') || Request::is('pagos*') || Request::is('cobros*')"
                            :open="Request::is('facturas*') || Request::is('gastos*') || Request::is('pagos*') || Request::is('cobros*') ? '1' : '0'">
                            <x-app.sidebar-link href="/gastos" icon="phosphor-coins">Gastos Administrativos</x-app.sidebar-link>
                            <x-app.sidebar-link href="/pagos" icon="phosphor-credit-card">Pagos</x-app.sidebar-link>
                            <x-app.sidebar-link href="/cobros" icon="phosphor-bank">Cobros</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>

                        <!-- Crecimiento -->
                        <x-app.sidebar-dropdown text="Crecimiento" icon="phosphor-trend-up" id="crecimiento_dropdown"
                            :active="Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') || Request::is('crecimiento-cierre-general*')"
                            :open="Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') || Request::is('crecimiento-cierre-general*') ? '1' : '0'">
                            <x-app.sidebar-link href="/puntaje-reglas" icon="phosphor-trophy">Puntaje Reglas</x-app.sidebar-link>
                            <x-app.sidebar-link href="/rangos" icon="phosphor-medal">Rangos</x-app.sidebar-link>
                         <!--   <x-app.sidebar-link href="/bono-lideres" icon="phosphor-star">Bono Líderes</x-app.sidebar-link>
                            <x-app.sidebar-link href="/bono-coordinadoras" icon="phosphor-star-half">Bono Coordinadoras</x-app.sidebar-link> -->
                        </x-app.sidebar-dropdown>

                        <!-- Cierre General -->
                        <x-app.sidebar-dropdown text="Cierre General" icon="phosphor-chart-pie" id="cierre_dropdown"
                            :active="Request::is('resumen-lideres*') || Request::is('resumen-coordinadoras*') || Request::is('resumen-revendedoras*')"
                            :open="Request::is('resumen-lideres*') || Request::is('resumen-coordinadoras*') || Request::is('resumen-revendedoras*') ? '1' : '0'">
                            <x-app.sidebar-link href="/crecimiento-cierre-general" icon="phosphor-chart-bar">Cierre General</x-app.sidebar-link>
                            <x-app.sidebar-link href="/resumen-lideres" icon="phosphor-crown">Resumen Líderes</x-app.sidebar-link>
                            <x-app.sidebar-link href="/resumen-coordinadoras" icon="phosphor-users-three">Resumen Coordinadoras</x-app.sidebar-link>
                            <x-app.sidebar-link href="/resumen-revendedoras" icon="phosphor-user-list">Resumen Revendedoras</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>
                    @endrole

                    <x-app.sidebar-link :hideUntilGroupHover="false" href="{{ route('notificaciones') }}" icon="phosphor-bell-duotone" active="false">Notificaciones</x-app.sidebar-link>

                    @role('admin')
                         <x-app.sidebar-link :hideUntilGroupHover="false" href="/usuarios" icon="phosphor-users-three" :active="Request::is('usuarios*')">Usuarios</x-app.sidebar-link>
                        <x-app.sidebar-link :hideUntilGroupHover="false" href="/agente" icon="phosphor-robot-duotone" active="false">Agente</x-app.sidebar-link>
                        <x-app.sidebar-link :hideUntilGroupHover="false" href="{{ route('settings.profile') }}" icon="phosphor-gear-duotone" active="false">Ajustes</x-app.sidebar-link>
                        <x-app.sidebar-link :href="route('changelogs')" icon="phosphor-book-open-text-duotone" :active="Request::is('changelog') || Request::is('changelog/*')">Novedades</x-app.sidebar-link>
                        <x-app.sidebar-link href="/editor" target="_blank" icon="phosphor-code-duotone" active="false">Editor</x-app.sidebar-link>
                    @endrole

                </div>
            </div>

            <div class="relative px-2.5 space-y-1.5 text-zinc-700 dark:text-zinc-400">
                
           <!--     
                <x-app.sidebar-link href="https://devdojo.com/questions" target="_blank" icon="phosphor-chat-duotone" active="false">Questions</x-app.sidebar-link> -->

                <div x-show="sidebarTip" x-data="{ sidebarTip: $persist(true) }" class="px-1 py-3" x-collapse x-cloak>
                    <div class="relative w-full px-4 py-3 space-y-1 border rounded-lg bg-zinc-50 text-zinc-700 dark:text-zinc-100 dark:bg-zinc-800 border-zinc-200/60 dark:border-zinc-700">
                        <button @click="sidebarTip=false" class="absolute top-0 right-0 z-50 p-1.5 mt-2.5 mr-2.5 rounded-full opacity-80 cursor-pointer hover:opacity-100 hover:bg-zinc-100 hover:dark:bg-zinc-700 hover:dark:text-zinc-300 text-zinc-500 dark:text-zinc-400">
                            <x-phosphor-x-bold class="w-3 h-3" />
                        </button>
                       <h5 class="pb-1 text-sm font-bold -translate-y-0.5">Bienvenido a tu dashboard</h5>
                        <p class="block pb-1 text-xs opacity-80 text-balance">Version 1.0.</p> 
                    </div>
                </div>

                <div class="w-full h-px my-2 bg-slate-100 dark:bg-zinc-700"></div>
                <x-app.user-menu />
            </div>
        </div>
    </div>
</div>