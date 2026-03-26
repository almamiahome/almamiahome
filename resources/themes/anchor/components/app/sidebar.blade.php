<div x-data="{ sidebarOpen: false }" @open-sidebar.window="sidebarOpen = true"
    x-init="$watch('sidebarOpen', value => document.body.classList.toggle('overflow-hidden', value))"
    class="relative" x-cloak>
    
    {{-- Backdrop para móvil (Efecto de oscurecimiento suave) --}}
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @click="sidebarOpen=false" 
         class="fixed inset-0 bg-black/20 backdrop-blur-sm lg:hidden"></div>
    
    {{-- Sidebar LIQUID GLASS --}} 
    <div :class="{
            'translate-y-full lg:translate-y-0 lg:left-4': !sidebarOpen,
            'translate-y-0 lg:left-4': sidebarOpen,
        }"
        class="fixed z-[5000] inset-x-4 bottom-4 top-20 md:top-4 md:left-4 flex flex-col overflow-hidden  transition-all duration-500 ease-in-out 
               /* Configuración del Cristal */
               bg-white/70 dark:bg-black/40 backdrop-blur-2xl 
               rounded-[2.5rem] border border-white/40 dark:border-white/10 
               shadow-[0_8px_32px_0_rgba(31,38,135,0.15)]
               /* Dimensiones */
               w-auto md:w-72 lg:w-64 
               @if(config('wave.dev_bar')) pb-10 @endif">
        
        <div class="flex flex-col justify-between h-full pt-8 pb-6">
            <div class="relative flex flex-col h-full overflow-y-auto scrollbar-hidden">
                
                {{-- Logo con Brillo --}}
                <div class="flex items-center justify-between px-7 mb-6">
                    <a href="/" class="flex items-center transition-transform hover:scale-105 active:scale-95">
                        <x-logo class="w-auto h-8 drop-shadow-md" />
                    </a>
                    <button x-on:click="sidebarOpen=false" class="lg:hidden p-2 rounded-full bg-white/30 dark:bg-zinc-800/50 text-zinc-800 dark:text-white transition-colors hover:bg-white/50">
                        <x-phosphor-x-bold class="w-4 h-4" />
                    </button>
                </div>

                {{-- Navegación --}}
                <nav class="flex flex-col px-4 space-y-1.5">
                    <style>
                        /* Estilo para el link activo con efecto glass */
                        .sidebar-link-active { 
                            background: rgba(255, 255, 255, 0.4) !important; 
                            backdrop-filter: blur(12px); 
                            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                            border: 1px solid rgba(255,255,255,0.5);
                        }
                        .dark .sidebar-link-active {
                            background: rgba(255, 255, 255, 0.1) !important;
                            border: 1px solid rgba(255,255,255,0.1);
                        }
                        /* Ocultar scrollbar pero mantener funcionalidad */
                        .scrollbar-hidden::-webkit-scrollbar { display: none; }
                        .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
                    </style>
                    
                    {{-- Links Principales --}}
                    <x-app.sidebar-link href="/dashboard" icon="phosphor-house" :active="Request::is('dashboard')">Escritorio</x-app.sidebar-link>
                    <x-app.sidebar-link href="/crearpedido" icon="phosphor-plus-circle" :active="Request::is('crearpedido*')">Crear Pedido</x-app.sidebar-link>
                    <x-app.sidebar-link href="/catalogo" icon="phosphor-book" :active="Request::is('catalogo')">Catálogo</x-app.sidebar-link>
                    <x-app.sidebar-link href="/mis-pedidos" icon="phosphor-list-checks" :active="Request::is('mis-pedidos*')">Mis Pedidos</x-app.sidebar-link>

                    @hasanyrole('lider|admin')
                        <x-app.sidebar-link href="/vendedoras" icon="phosphor-handshake" :active="Request::is('vendedoras*')">Vendedoras</x-app.sidebar-link>
                    @endhasanyrole

                    @role('admin')
                        <x-app.sidebar-link href="/pedidos" icon="phosphor-shopping-cart" :active="Request::is('pedidos*')">Pedidos</x-app.sidebar-link>
                    @endrole

                    @hasanyrole('coordinadora|admin')
                        <x-app.sidebar-link href="/lideres" icon="phosphor-user-circle" :active="Request::is('lideres*')">Líderes</x-app.sidebar-link>
                    @endhasanyrole

                    @role('admin')
                        <x-app.sidebar-link href="/coordinadoras" icon="phosphor-user-switch" :active="Request::is('coordinadoras*')">Coordinadoras</x-app.sidebar-link>
                    @endrole

                    @role('lider')
                        <x-app.sidebar-link href="/zona-lider" icon="phosphor-crown" :active="Request::is('zona-lider*')">Zona Líder</x-app.sidebar-link>
                    @endrole

                    @role('coordinadora')
                        <x-app.sidebar-link href="/zona-coordinadora" icon="phosphor-users-three" :active="Request::is('zona-coordinadora*')">Zona Coordinadora</x-app.sidebar-link>
                    @endrole

                    <x-app.sidebar-link href="/perfil" icon="phosphor-user" :active="Request::is('perfil')">Perfil</x-app.sidebar-link>

                    @hasanyrole('lider|coordinadora|admin')
                        <x-app.sidebar-link href="/incorporar" icon="phosphor-user-plus" :active="Request::is('incorporar*')">Incorporar</x-app.sidebar-link>
                    @endhasanyrole

                    @role('admin')
                        {{-- Dropdown Catálogo --}}
                        <x-app.sidebar-dropdown text="Catálogo" icon="phosphor-package" id="catalogo_dropdown"
                            :active="Request::is('catalogo/admin*') || Request::is('categorias*') || Request::is('productos*') || Request::is('stock*') || Request::is('rotulos*')"
                            :open="Request::is('catalogo/admin*') || Request::is('categorias*') || Request::is('productos*') || Request::is('stock*') || Request::is('rotulos*') ? '1' : '0'">
                            
                            <x-app.sidebar-link href="/catalogo/admin" icon="phosphor-wrench" :active="Request::is('catalogo/admin*')">Editar Catálogo</x-app.sidebar-link>
                            <x-app.sidebar-link href="/productos" icon="phosphor-tag" :active="Request::is('productos*')">Productos</x-app.sidebar-link>
                            <x-app.sidebar-link href="/categorias" icon="phosphor-folders" :active="Request::is('categorias*')">Categorías</x-app.sidebar-link>
                            <x-app.sidebar-link href="/stock" icon="phosphor-package" :active="Request::is('stock*')">Stock</x-app.sidebar-link>
                            <x-app.sidebar-link href="/rotulos" icon="phosphor-ticket" :active="Request::is('rotulos*')">Rótulos</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>

                        {{-- Dropdown Ventas --}}
                        <x-app.sidebar-dropdown text="Ventas" icon="phosphor-storefront" id="ventas_dropdown"
                            :active="Request::is('campanas*') || Request::is('pedidos/facturas-masivas*')"
                            :open="Request::is('campanas*') || Request::is('pedidos/facturas-masivas*') ? '1' : '0'">
                            <x-app.sidebar-link href="/campanas" icon="phosphor-flag-banner" :active="Request::is('campanas*')">Campañas</x-app.sidebar-link>
                            <x-app.sidebar-link href="/pedidos/facturas-masivas" icon="phosphor-file-text" :active="Request::is('pedidos/facturas-masivas*')">Facturas Masivas</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>

                        {{-- Dropdown Finanzas --}}
                        <x-app.sidebar-dropdown text="Finanzas" icon="phosphor-currency-circle-dollar" id="finanzas_dropdown"
                            :active="Request::is('facturas*') || Request::is('gastos*') || Request::is('pagos*') || Request::is('cobros*')"
                            :open="Request::is('facturas*') || Request::is('gastos*') || Request::is('pagos*') || Request::is('cobros*') ? '1' : '0'">
                            <x-app.sidebar-link href="/gastos" icon="phosphor-coins" :active="Request::is('gastos*')">Gastos Administrativos</x-app.sidebar-link>
                            <x-app.sidebar-link href="/pagos" icon="phosphor-credit-card" :active="Request::is('pagos*')">Pagos</x-app.sidebar-link>
                            <x-app.sidebar-link href="/cobros" icon="phosphor-bank" :active="Request::is('cobros*')">Cobros</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>

                        {{-- Dropdown Crecimiento --}}
                        <x-app.sidebar-dropdown text="Crecimiento" icon="phosphor-trend-up" id="crecimiento_dropdown"
                            :active="Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('campanas*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*')"
                            :open="Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('campanas*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') ? '1' : '0'">
                            <x-app.sidebar-link href="/puntaje-reglas" icon="phosphor-trophy" :active="Request::is('puntaje-reglas*')">Puntaje Reglas</x-app.sidebar-link>
                            <x-app.sidebar-link href="/rangos" icon="phosphor-medal" :active="Request::is('rangos*')">Rangos</x-app.sidebar-link>
                            <x-app.sidebar-link href="/campanas" icon="phosphor-flag-banner" :active="Request::is('campanas*')">Campañas</x-app.sidebar-link>
                            <x-app.sidebar-link href="/bono-lideres" icon="phosphor-currency-circle-dollar" :active="Request::is('bono-lideres*')">Bono Líderes</x-app.sidebar-link>
                            <x-app.sidebar-link href="/bono-coordinadoras" icon="phosphor-coins" :active="Request::is('bono-coordinadoras*')">Bono Coordinadoras</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>

                        {{-- Dropdown Cierre --}}
                        <x-app.sidebar-dropdown text="Cierre General" icon="phosphor-chart-pie" id="cierre_dropdown"
                            :active="Request::is('resumen-lideres*') || Request::is('resumen-coordinadoras*') || Request::is('resumen-revendedoras*')"
                            :open="Request::is('resumen-lideres*') || Request::is('resumen-coordinadoras*') || Request::is('resumen-revendedoras*') ? '1' : '0'">
                            <x-app.sidebar-link href="/crecimiento-cierre-general" icon="phosphor-chart-bar" :active="Request::is('crecimiento-cierre-general*')">Cierre General</x-app.sidebar-link>
                            <x-app.sidebar-link href="/resumen-lideres" icon="phosphor-crown" :active="Request::is('resumen-lideres*')">Resumen Líderes</x-app.sidebar-link>
                            <x-app.sidebar-link href="/resumen-coordinadoras" icon="phosphor-users-three" :active="Request::is('resumen-coordinadoras*')">Resumen Coordinadoras</x-app.sidebar-link>
                            <x-app.sidebar-link href="/resumen-revendedoras" icon="phosphor-user-list" :active="Request::is('resumen-revendedoras*')">Resumen Revendedoras</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>
                    @endrole

                    <x-app.sidebar-link :hideUntilGroupHover="false" href="{{ route('notificaciones') }}" icon="phosphor-bell-duotone" active="false">Notificaciones</x-app.sidebar-link>

                    @role('admin')
                        <x-app.sidebar-dropdown text="UI V2" icon="phosphor-layout" id="ui_v2_dropdown"
                            :active="Request::is('admin-ui-v2*')"
                            :open="Request::is('admin-ui-v2*') ? '1' : '0'">
                            <x-app.sidebar-link href="/admin-ui-v2/seguimiento-lideres" icon="phosphor-chart-line-up" :active="Request::is('admin-ui-v2/seguimiento-lideres')">Seguimiento Líderes V2</x-app.sidebar-link>
                            <x-app.sidebar-link href="/admin-ui-v2/premios-vigentes" icon="phosphor-gift" :active="Request::is('admin-ui-v2/premios-vigentes')">Premios Vigentes V2</x-app.sidebar-link>
                            <x-app.sidebar-link href="/admin-ui-v2/paneles-lideres-coordinadoras" icon="phosphor-table" :active="Request::is('admin-ui-v2/paneles-lideres-coordinadoras')">Paneles Liderazgo V2</x-app.sidebar-link>
                            <x-app.sidebar-link href="/admin-ui-v2/panel-alternativa" icon="phosphor-squares-four" :active="Request::is('admin-ui-v2/panel-alternativa')">Panel Alternativa V2</x-app.sidebar-link>
                            <x-app.sidebar-link href="/admin-ui-v2/perfil-seguimiento-lideres" icon="phosphor-user-focus" :active="Request::is('admin-ui-v2/perfil-seguimiento-lideres')">Perfil Líder V2</x-app.sidebar-link>
                        </x-app.sidebar-dropdown>
                        <div class="my-2 border-t border-white/20"></div>
                        <x-app.sidebar-link href="/usuarios" icon="phosphor-users-three" :active="Request::is('usuarios*')">Usuarios</x-app.sidebar-link>
                        <x-app.sidebar-link href="/mejoras" icon="phosphor-sparkle" :active="Request::is('mejoras*')">Mejoras</x-app.sidebar-link>
                        <x-app.sidebar-link href="/tareas" icon="phosphor-kanban" :active="Request::is('tareas*')">Tareas</x-app.sidebar-link>
                        <x-app.sidebar-link href="/notas" icon="phosphor-note" :active="Request::is('notas*')">Notas</x-app.sidebar-link>
                        <x-app.sidebar-link href="/agente" icon="phosphor-robot-duotone" active="false">Agente</x-app.sidebar-link>
                        <x-app.sidebar-link href="{{ route('settings.profile') }}" icon="phosphor-gear-duotone" active="false">Ajustes</x-app.sidebar-link>
                        <x-app.sidebar-link :href="route('changelogs')" icon="phosphor-book-open-text-duotone" :active="Request::is('changelog') || Request::is('changelog/*')">Novedades</x-app.sidebar-link>
                        <x-app.sidebar-link href="/editor" target="_blank" icon="phosphor-code-duotone" active="false">Editor</x-app.sidebar-link>
                    @endrole

                </nav>
            </div>

            {{-- Footer del Sidebar (Menú de Usuario) --}}
            <div class="px-4 mt-auto">
                <div class="p-2 rounded-3xl bg-white/40 dark:bg-black/30 border border-white/30 dark:border-white/10 shadow-sm backdrop-blur-md">
                    <x-app.user-menu />
                </div>
            </div>
        </div>
    </div>
</div>
