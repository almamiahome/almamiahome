<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

?>


    |---LINE:279---|<x-app.container
        x-data="{
            tab: 'resumen',
            tabs: ['resumen', 'equipo', 'catalogo'],
            go(direction) {
                const index = this.tabs.indexOf(this.tab);
                const target = (index + direction + this.tabs.length) % this.tabs.length;
                this.tab = this.tabs[target];
            }
        }"
        x-cloak
        class="space-y-6 pb-12"
    >
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            
            <div class="relative overflow-hidden rounded-3xl bg-white p-8 ring-1 ring-slate-200/60 shadow-sm lg:col-span-8 flex flex-col justify-center">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-[#294395]/5 to-[#e91e63]/5 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-lg bg-[#294395]/5 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-[#294395] ring-1 ring-[#294395]/10">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#e91e63] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#e91e63]"></span>
                            </span>
                            Rol Activo: |---LINE:304---|{{ ucfirst($rolActual) }}
                        </div>
                        <div>
                            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
                                Bienvenido, |---LINE:308---|{{ $usuario?->name }} 👋
                            </h1>
                            <p class="mt-2 text-sm text-slate-500 font-medium max-w-xl">
                                |---LINE:311---|{{ $config['titulo'] }}. |---LINE:311---|{{ $config['subtitulo'] }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 text-left md:text-right">
                        <div class="inline-flex flex-col rounded-2xl bg-slate-50 px-5 py-3 ring-1 ring-slate-200/60">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Tus objetivos actuales</span>
                            <span class="mt-1 text-sm font-bold text-slate-700">
                                <span class="text-[#294395]">|---LINE:320---|{{ $metaPedidos }}</span> Pedidos · 
                                <span class="text-[#e91e63]">|---LINE:321---|{{ $metaPuntos }}</span> Pts
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="min-h-screen bg-[#fbfbfd] dark:bg-zinc-950 p-6 md:p-12 font-sans selection:bg-pink-200">
    
    <div class="max-w-7xl mx-auto mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-pink-500 mb-1">Panel de Control</p>
            <h1 class="text-4xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Mis Aplicaciones</h1>
        </div>
        <div class="flex items-center gap-4 text-zinc-500 dark:text-zinc-400">
            <div class="text-right hidden sm:block">
                <p class="text-lg font-semibold leading-none text-zinc-800 dark:text-zinc-200" id="os-clock">|---LINE:337---|{{ now()->format('H:i') }}</p>
                <p class="text-xs uppercase tracking-tighter">|---LINE:338---|{{ now()->translatedFormat('l, d \d\e F') }}</p>
            </div>
            <div class="h-10 w-px bg-zinc-200 dark:bg-zinc-800 hidden sm:block"></div>
            |---LINE:341---|<x-app.user-menu />
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-9 gap-y-10 gap-x-4">
        
        |---LINE:347---|@php
            $user = auth()->user();
            
            $allApps = [
                // SECCIÓN GENERAL
                ['href' => '/dashboard', 'icon' => 'phosphor-house-duotone', 'label' => 'Inicio', 'color' => 'bg-gradient-to-br from-blue-400 to-blue-600', 'role' => true],
                ['href' => '/crearpedido', 'icon' => 'phosphor-plus-circle-duotone', 'label' => 'Nuevo Pedido', 'color' => 'bg-gradient-to-br from-emerald-400 to-emerald-600', 'role' => true],
                ['href' => '/catalogo', 'icon' => 'phosphor-book-duotone', 'label' => 'Catálogo', 'color' => 'bg-gradient-to-br from-pink-400 to-pink-600', 'role' => true],
                ['href' => '/perfil', 'icon' => 'phosphor-user-duotone', 'label' => 'Mi Perfil', 'color' => 'bg-zinc-700', 'role' => true],
                ['href' => route('notificaciones'), 'icon' => 'phosphor-bell-duotone', 'label' => 'Notificaciones', 'color' => 'bg-gradient-to-br from-amber-400 to-amber-600', 'role' => true],

                // ROLES ESPECÍFICOS
                ['href' => '/mis-pedidos', 'icon' => 'phosphor-list-checks-duotone', 'label' => 'Mis Pedidos', 'color' => 'bg-violet-500', 'role' => $user->hasAnyRole(['lider', 'vendedora'])],
                ['href' => '/vendedoras', 'icon' => 'phosphor-handshake-duotone', 'label' => 'Vendedoras', 'color' => 'bg-orange-500', 'role' => $user->hasAnyRole(['lider', 'admin'])],
                ['href' => '/pedidos', 'icon' => 'phosphor-shopping-cart-duotone', 'label' => 'Pedidos', 'color' => 'bg-rose-500', 'role' => $user->hasRole('admin')],
                ['href' => '/lideres', 'icon' => 'phosphor-user-circle-duotone', 'label' => 'Líderes', 'color' => 'bg-indigo-500', 'role' => $user->hasAnyRole(['coordinadora', 'admin'])],
                ['href' => '/coordinadoras', 'icon' => 'phosphor-user-switch-duotone', 'label' => 'Coordinadoras', 'color' => 'bg-blue-600', 'role' => $user->hasRole('admin')],
                ['href' => '/zona-lider', 'icon' => 'phosphor-crown-duotone', 'label' => 'Zona Líder', 'color' => 'bg-yellow-500', 'role' => $user->hasRole('lider')],
                ['href' => '/zona-coordinadora', 'icon' => 'phosphor-users-three-duotone', 'label' => 'Zona Coord.', 'color' => 'bg-teal-500', 'role' => $user->hasRole('coordinadora')],
                ['href' => '/incorporar', 'icon' => 'phosphor-user-plus-duotone', 'label' => 'Incorporar', 'color' => 'bg-lime-600', 'role' => $user->hasAnyRole(['lider', 'coordinadora', 'admin'])],

                // ADMIN - CATÁLOGO
                ['href' => '/catalogo/admin', 'icon' => 'phosphor-wrench-duotone', 'label' => 'Editar Cat.', 'color' => 'bg-slate-600', 'role' => $user->hasRole('admin')],
                ['href' => '/productos', 'icon' => 'phosphor-tag-duotone', 'label' => 'Productos', 'color' => 'bg-orange-400', 'role' => $user->hasRole('admin')],
                ['href' => '/categorias', 'icon' => 'phosphor-folders-duotone', 'label' => 'Categorías', 'color' => 'bg-sky-500', 'role' => $user->hasRole('admin')],
                ['href' => '/rotulos', 'icon' => 'phosphor-ticket-duotone', 'label' => 'Rótulos', 'color' => 'bg-fuchsia-500', 'role' => $user->hasRole('admin')],

                // ADMIN - FINANZAS
                ['href' => '/gastos', 'icon' => 'phosphor-coins-duotone', 'label' => 'Gastos', 'color' => 'bg-red-500', 'role' => $user->hasRole('admin')],
                ['href' => '/pagos', 'icon' => 'phosphor-credit-card-duotone', 'label' => 'Pagos', 'color' => 'bg-emerald-600', 'role' => $user->hasRole('admin')],
                ['href' => '/cobros', 'icon' => 'phosphor-bank-duotone', 'label' => 'Cobros', 'color' => 'bg-amber-700', 'role' => $user->hasRole('admin')],

                // ADMIN - CRECIMIENTO Y CIERRE
                ['href' => '/puntaje-reglas', 'icon' => 'phosphor-trophy-duotone', 'label' => 'Reglas Puntos', 'color' => 'bg-yellow-600', 'role' => $user->hasRole('admin')],
                ['href' => '/rangos', 'icon' => 'phosphor-medal-duotone', 'label' => 'Rangos', 'color' => 'bg-purple-600', 'role' => $user->hasRole('admin')],
                ['href' => '/crecimiento-cierre-general', 'icon' => 'phosphor-chart-bar-duotone', 'label' => 'Cierre Gral.', 'color' => 'bg-zinc-800', 'role' => $user->hasRole('admin')],
                ['href' => '/resumen-lideres', 'icon' => 'phosphor-crown-duotone', 'label' => 'Res. Líderes', 'color' => 'bg-indigo-700', 'role' => $user->hasRole('admin')],
                ['href' => '/resumen-coordinadoras', 'icon' => 'phosphor-users-three-duotone', 'label' => 'Res. Coord.', 'color' => 'bg-teal-700', 'role' => $user->hasRole('admin')],
                ['href' => '/resumen-revendedoras', 'icon' => 'phosphor-user-list-duotone', 'label' => 'Res. Vended.', 'color' => 'bg-rose-700', 'role' => $user->hasRole('admin')],

                // ADMIN - SISTEMA
                ['href' => '/usuarios', 'icon' => 'phosphor-users-three-duotone', 'label' => 'Usuarios', 'color' => 'bg-cyan-600', 'role' => $user->hasRole('admin')],
                ['href' => '/agente', 'icon' => 'phosphor-robot-duotone', 'label' => 'Agente AI', 'color' => 'bg-violet-700', 'role' => $user->hasRole('admin')],
                ['href' => route('settings.profile'), 'icon' => 'phosphor-gear-duotone', 'label' => 'Ajustes', 'color' => 'bg-slate-500', 'role' => $user->hasRole('admin')],
                ['href' => route('changelogs'), 'icon' => 'phosphor-book-open-text-duotone', 'label' => 'Novedades', 'color' => 'bg-zinc-400', 'role' => $user->hasRole('admin')],
                ['href' => '/editor', 'icon' => 'phosphor-code-duotone', 'label' => 'Editor', 'color' => 'bg-black', 'role' => $user->hasRole('admin')],
            ];
        |---LINE:394---|@endphp

        |---LINE:396---|@foreach($allApps as $app)
            |---LINE:397---|@if($app['role'])
                <div class="flex flex-col items-center group">
                    <a href="|---LINE:399---|{{ $app['href'] }}" 
                       wire:navigate 
                       class="relative flex flex-col items-center gap-3 no-underline outline-none focus:ring-0">
                        
                        <div class="relative">
                            <div class="absolute inset-0 |---LINE:404---|{{ $app['color'] }} opacity-20 blur-xl group-hover:opacity-40 transition-opacity duration-500 rounded-2xl"></div>
                            
                            <div class="|---LINE:406---|{{ $app['color'] }} w-16 h-16 sm:w-[72px] sm:h-[72px] rounded-[1.4rem] shadow-xl flex items-center justify-center text-white relative overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:scale-110 group-hover:-translate-y-2 group-active:scale-95 group-active:translate-y-0 border-t border-white/20">
                                
                                |---LINE:408---|<x-dynamic-component :component="$app['icon']" class="w-8 h-8 sm:w-9 sm:h-9 text-white drop-shadow-lg relative z-10" />

                                <div class="absolute top-0 inset-x-0 h-1/2 bg-gradient-to-b from-white/20 to-transparent z-0"></div>
                            </div>
                        </div>

                        <span class="text-[11px] sm:text-[12px] font-semibold text-zinc-600 dark:text-zinc-400 text-center leading-tight tracking-tight w-24 line-clamp-2 transition-colors group-hover:text-zinc-900 dark:group-hover:text-white">
                            |---LINE:415---|{{ $app['label'] }}
                        </span>
                    </a>
                </div>
            |---LINE:419---|@endif
        |---LINE:420---|@endforeach

    </div>
</div>

<style>
    /* Suavizado para el scroll y animaciones */
    html { scroll-behavior: smooth; }

    /* Animación de entrada suave tipo "pop" */
    |---LINE:430---|@keyframes appPop {
        0% { opacity: 0; transform: scale(0.5) translateY(30px); filter: blur(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
    }

    .group {
        animation: appPop 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards;
    }

    /* Staggered animation (retraso progresivo) */
    |---LINE:440---|@for $i from 1 through 35 {
        .group:nth-child(#{$i}) { animation-delay: #{$i * 0.02}s; }
    }

    /* Estilo para los nombres de las apps que se cortan con puntos suspensivos */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    // Reloj dinámico en tiempo real
    function updateClock() {
        const now = new Date();
        const clock = document.getElementById('os-clock');
        if(clock) {
            clock.textContent = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        }
    }
    setInterval(updateClock, 1000);
</script>

            <div class="flex flex-col gap-6 lg:col-span-4">
                <div class="flex-1 rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm flex flex-col justify-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-white/10 group-hover:translate-x-full duration-1000 transition-transform"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Progreso Pedidos</span>
                        <span class="text-xl font-black text-[#294395]">|---LINE:470---|{{ $avancePedidos }}%</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-[#294395] transition-all duration-1000 ease-out" style="width: |---LINE:473---|{{ $avancePedidos }}%"></div>
                    </div>
                    <p class="mt-3 text-xs font-medium text-slate-400 text-right">|---LINE:475---|{{ $pedidosTotales }} / |---LINE:475---|{{ $metaPedidos }} meta</p>
                </div>

                <div class="flex-1 rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm flex flex-col justify-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-white/10 group-hover:translate-x-full duration-1000 transition-transform"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Puntos Acumulados</span>
                        <span class="text-xl font-black text-[#e91e63]">|---LINE:482---|{{ $puntosTotal }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-[#e91e63] transition-all duration-1000 ease-out" style="width: |---LINE:485---|{{ $avancePuntos }}%"></div>
                    </div>
                    <p class="mt-3 text-xs font-medium text-slate-400 text-right">Sugerido: |---LINE:487---|{{ $metaPuntos }} pts</p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            |---LINE:493---|@foreach($config['acciones'] as $accion)
                <a href="|---LINE:494---|{{ url($accion['ruta']) }}" class="group flex flex-col justify-between rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md hover:ring-[#294395]/30">
                    <div>
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-[#294395] ring-1 ring-slate-100 group-hover:bg-[#294395] group-hover:text-white transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5.25v13.5m6.75-6.75H5.25" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">|---LINE:501---|{{ $accion['titulo'] }}</h3>
                        <p class="mt-2 text-xs font-medium text-slate-500 leading-relaxed">|---LINE:502---|{{ $accion['descripcion'] }}</p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#e91e63] group-hover:gap-2 transition-all">
                        Abrir
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </div>
                </a>
            |---LINE:509---|@endforeach
        </section>

        |---LINE:512---|@if($rolActual !== 'admin')
            <section class="grid gap-6 lg:grid-cols-12">
                <div class="rounded-3xl bg-white p-8 ring-1 ring-slate-200/60 shadow-sm lg:col-span-7 flex flex-col">
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Rendimiento e Insignias</h2>
                            <p class="mt-1 text-sm text-slate-500 font-medium">Sigue sumando para desbloquear nuevos niveles.</p>
                        </div>
                        <span class="rounded-lg bg-indigo-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-indigo-600 ring-1 ring-indigo-100">Gamificación</span>
                    </div>

                    <div class="grid gap-6 flex-1 content-start">
                        <div class="space-y-6">
                            <div class="group relative">
                                <div class="flex items-end justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Insignia de constancia</p>
                                        <p class="text-xs text-slate-500">|---LINE:529---|{{ $metaPedidos }} pedidos para lograrlo</p>
                                    </div>
                                    <span class="text-sm font-black text-[#294395]">|---LINE:531---|{{ $avancePedidos }}%</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-[#294395] to-[#4b68c9]" style="width: |---LINE:534---|{{ $avancePedidos }}%"></div>
                                </div>
                            </div>
                            
                            <div class="group relative">
                                <div class="flex items-end justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Racha de puntos</p>
                                        <p class="text-xs text-slate-500">|---LINE:542---|{{ $metaPuntos }} puntos para nuevo rango</p>
                                    </div>
                                    <span class="text-sm font-black text-[#e91e63]">|---LINE:544---|{{ $avancePuntos }}%</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-[#e91e63] to-[#ff6b9d]" style="width: |---LINE:547---|{{ $avancePuntos }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto pt-6 grid grid-cols-3 gap-4 border-t border-slate-100">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Pedidos</p>
                                <p class="mt-1 text-2xl font-black text-slate-900">|---LINE:555---|{{ $pedidosTotales }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Puntos Netos</p>
                                <p class="mt-1 text-2xl font-black text-[#e91e63]">|---LINE:559---|{{ $puntosTotal }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Unidades</p>
                                <p class="mt-1 text-2xl font-black text-[#294395]">|---LINE:563---|{{ $unidadesTotales }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-0 ring-1 ring-slate-200/60 shadow-sm lg:col-span-5 flex flex-col overflow-hidden">
                    <div class="flex items-center justify-between p-6 pb-4 border-b border-slate-100">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Actividad Reciente</h2>
                        <a href="|---LINE:572---|{{ url('/mis-pedidos') }}" class="text-xs font-bold uppercase tracking-wider text-[#294395] hover:text-[#e91e63] transition-colors">Ver historial</a>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto divide-y divide-slate-100 p-2">
                        |---LINE:576---|@forelse($pedidosRecientes as $pedido)
                            <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-full bg-[#294395]/10 text-[#294395]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Pedido #|---LINE:583---|{{ $pedido->codigo_pedido ?? $pedido->id }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">|---LINE:584---|{{ $pedido->fecha ?? $pedido->created_at?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-[#e91e63]">|---LINE:588---|{{ $pedido->total_puntos ?? 0 }} <span class="text-[10px] uppercase text-slate-400">pts</span></p>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">|---LINE:589---|{{ ucfirst($pedido->estado ?? 'Revisión') }}</p>
                                </div>
                            </div>
                        |---LINE:592---|@empty
                            <div class="p-10 flex flex-col items-center justify-center text-center h-full">
                                <div class="h-16 w-16 rounded-full bg-slate-50 flex items-center justify-center mb-4 ring-1 ring-slate-100">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Sin movimientos</p>
                                <p class="text-xs text-slate-500 mt-1 max-w-[200px]">Carga tu primer pedido para empezar a sumar puntos.</p>
                            </div>
                        |---LINE:600---|@endforelse
                    </div>
                </div>
            </section>
        |---LINE:604---|@endif

        |---LINE:606---|@if($rolActual === 'admin')
            <section class="space-y-6">
                <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                                <svg class="w-6 h-6 text-[#294395]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Rendimiento Global
                            </h2>
                            <p class="mt-1 text-sm text-slate-500 font-medium">Indicadores comerciales del sistema.</p>
                        </div>

                        <form method="GET" action="|---LINE:618---|{{ route('dashboard') }}" class="flex flex-wrap items-end gap-3 rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-100">
                            <label class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-1">Filtro</span>
                                <select name="filtro" class="h-10 rounded-xl border-0 bg-white px-4 text-sm font-bold text-slate-700 ring-1 ring-slate-200 focus:ring-2 focus:ring-[#294395] cursor-pointer">
                                    <option value="mes" |---LINE:622---|@selected($filtroAdmin === 'mes')>Mensual</option>
                                    <option value="rango" |---LINE:623---|@selected($filtroAdmin === 'rango')>Rango personalizado</option>
                                </select>
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-1">Mes</span>
                                <input name="mes" type="month" value="|---LINE:629---|{{ $mesAdmin }}" class="h-10 rounded-xl border-0 bg-white px-4 text-sm font-bold text-slate-700 ring-1 ring-slate-200 focus:ring-2 focus:ring-[#294395]" />
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-1">Desde</span>
                                <input name="fecha_inicio" type="date" value="|---LINE:634---|{{ $fechaInicioAdmin }}" class="h-10 rounded-xl border-0 bg-white px-4 text-sm font-bold text-slate-700 ring-1 ring-slate-200 focus:ring-2 focus:ring-[#294395]" />
                            </label>

                            <label class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-1">Hasta</span>
                                <input name="fecha_fin" type="date" value="|---LINE:639---|{{ $fechaFinAdmin }}" class="h-10 rounded-xl border-0 bg-white px-4 text-sm font-bold text-slate-700 ring-1 ring-slate-200 focus:ring-2 focus:ring-[#294395]" />
                            </label>

                            <button type="submit" class="h-10 rounded-xl bg-slate-900 px-6 text-xs font-bold uppercase tracking-wider text-white transition-colors hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                                Aplicar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm border-l-4 border-l-[#294395]">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Unidades Vendidas</p>
                        <p class="text-3xl font-black text-slate-900">|---LINE:652---|{{ number_format($resumenAdmin['unidades_vendidas'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm border-l-4 border-l-indigo-400">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Total Catálogo Vendido</p>
                        <p class="text-3xl font-black text-slate-900"><span class="text-slate-400 text-2xl mr-1">$</span>|---LINE:656---|{{ number_format($resumenAdmin['total_catalogo_vendido'], 2, ',', '.') }}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm border-l-4 border-l-[#e91e63]">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Total Facturado</p>
                        <p class="text-3xl font-black text-[#e91e63]"><span class="text-[#e91e63]/50 text-2xl mr-1">$</span>|---LINE:660---|{{ number_format($resumenAdmin['total_facturado'], 2, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    
                    <div class="rounded-3xl bg-white ring-1 ring-slate-200/60 shadow-sm overflow-hidden flex flex-col">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">Últimos Pedidos</h3>
                        </div>
                        <div class="overflow-x-auto p-2">
                            <table class="w-full text-left text-sm whitespace-nowrap">
                                <thead class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    <tr>
                                        <th class="px-4 py-3">Cód.</th>
                                        <th class="px-4 py-3">Fecha</th>
                                        <th class="px-4 py-3">Vendedora</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    |---LINE:681---|@forelse($ultimosPedidosAdmin as $pedido)
                                        <tr class="hover:bg-slate-50 rounded-xl transition-colors">
                                            <td class="px-4 py-3 font-bold text-[#294395]">#|---LINE:683---|{{ $pedido->codigo_pedido }}</td>
                                            <td class="px-4 py-3 text-slate-500">|---LINE:684---|{{ $pedido->fecha ? Carbon::parse($pedido->fecha)->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-4 py-3 font-medium text-slate-700">|---LINE:685---|{{ $pedido->vendedora?->name ?? 'Sin vendedora' }}</td>
                                            <td class="px-4 py-3 text-right font-black text-slate-900">$|---LINE:686---|{{ number_format((float) $pedido->total_a_pagar, 2, ',', '.') }}</td>
                                        </tr>
                                    |---LINE:688---|@empty
                                        <tr><td colspan="4" class="px-4 py-8 text-center text-xs text-slate-400">No hay pedidos registrados en este período.</td></tr>
                                    |---LINE:690---|@endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white ring-1 ring-slate-200/60 shadow-sm overflow-hidden flex flex-col">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">Nuevos Registros</h3>
                        </div>
                        <div class="overflow-x-auto p-2">
                            <table class="w-full text-left text-sm whitespace-nowrap">
                                <thead class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    <tr>
                                        <th class="px-4 py-3">Usuario</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3 text-right">Alta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    |---LINE:710---|@forelse($ultimosRegistrosAdmin as $registro)
                                        <tr class="hover:bg-slate-50 rounded-xl transition-colors">
                                            <td class="px-4 py-3 font-bold text-slate-900">|---LINE:712---|{{ $registro->name }}</td>
                                            <td class="px-4 py-3 text-slate-500 text-xs">|---LINE:713---|{{ $registro->email }}</td>
                                            <td class="px-4 py-3 text-right text-slate-500 text-xs">|---LINE:714---|{{ $registro->created_at?->format('d/m/y H:i') }}</td>
                                        </tr>
                                    |---LINE:716---|@empty
                                        <tr><td colspan="3" class="px-4 py-8 text-center text-xs text-slate-400">No hay registros recientes.</td></tr>
                                    |---LINE:718---|@endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm">
                        <h3 class="mb-5 text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-100 text-amber-600">🏆</span>
                            Top 5 Vendedoras
                        </h3>
                        <div class="space-y-3">
                            |---LINE:730---|@forelse($topVendedorasAdmin as $index => $vendedora)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100/50">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-[10px] font-black text-slate-400 ring-1 ring-slate-200 shadow-sm">|---LINE:733---|{{ $index + 1 }}</span>
                                        <span class="font-bold text-slate-700 text-sm">|---LINE:734---|{{ $vendedora->name }}</span>
                                    </div>
                                    <span class="font-black text-[#e91e63] text-sm">$|---LINE:736---|{{ number_format((float) $vendedora->total_ventas, 2, ',', '.') }}</span>
                                </div>
                            |---LINE:738---|@empty
                                <div class="p-4 text-center text-xs text-slate-400">Sin datos para mostrar.</div>
                            |---LINE:740---|@endforelse
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/60 shadow-sm">
                        <h3 class="mb-5 text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">⭐</span>
                            Top 5 Líderes
                        </h3>
                        <div class="space-y-3">
                            |---LINE:750---|@forelse($topLideresAdmin as $index => $lider)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100/50">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-[10px] font-black text-slate-400 ring-1 ring-slate-200 shadow-sm">|---LINE:753---|{{ $index + 1 }}</span>
                                        <span class="font-bold text-slate-700 text-sm">|---LINE:754---|{{ $lider->name }}</span>
                                    </div>
                                    <span class="font-black text-[#294395] text-sm">$|---LINE:756---|{{ number_format((float) $lider->total_ventas, 2, ',', '.') }}</span>
                                </div>
                            |---LINE:758---|@empty
                                <div class="p-4 text-center text-xs text-slate-400">Sin datos para mostrar.</div>
                            |---LINE:760---|@endforelse
                        </div>
                    </div>
                </div>

                <div x-data="dashboardAccesosRapidos()" x-init="init()" class="rounded-3xl bg-slate-900 p-6 shadow-sm">
                    <div class="mb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-white">Herramientas Rápidas</h3>
                            <p class="mt-1 text-xs text-slate-400">Arrastra las tarjetas para ordenar tu panel de control.</p>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-white/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <template x-for="(item, index) in items" :key="item.href + index">
                            <a
                                :href="item.href"
                                draggable="true"
                                |---LINE:781---|@dragstart="dragStart(index)"
                                |---LINE:782---|@dragover.prevent
                                |---LINE:783---|@drop="drop(index)"
                                class="group flex cursor-move items-center justify-between rounded-xl bg-white/5 px-4 py-3 ring-1 ring-white/10 transition-all hover:bg-white hover:ring-white"
                            >
                                <span class="text-xs font-bold text-slate-300 group-hover:text-slate-900" x-text="item.label"></span>
                                <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                            </a>
                        </template>
                    </div>
                </div>
            </section>
        |---LINE:793---|@endif

        <script>
            function dashboardAccesosRapidos() {
                return {
                    items: [],
                    dragIndex: null,
                    storageKey: 'dashboard-accesos-rapidos-anchor',
                    init() {
                        const links = Array.from(document.querySelectorAll('aside a[href], nav a[href]'))
                            .map((link) => ({
                                href: link.getAttribute('href'),
                                label: (link.textContent || '').trim(),
                            }))
                            .filter((item) => item.href && item.label && !item.href.startsWith('#'))
                            .filter((item, index, arr) => arr.findIndex((el) => el.href === item.href) === index)
                            .slice(0, 12);

                        const guardado = localStorage.getItem(this.storageKey);

                        if (!guardado) {
                            this.items = links;
                            return;
                        }

                        try {
                            const guardados = JSON.parse(guardado);
                            const porHref = new Map(links.map((item) => [item.href, item]));
                            const restaurados = guardados.map((item) => porHref.get(item.href)).filter(Boolean);
                            const nuevos = links.filter((item) => !restaurados.some((r) => r.href === item.href));
                            this.items = [...restaurados, ...nuevos];
                        } catch (error) {
                            this.items = links;
                        }
                    },
                    dragStart(index) {
                        this.dragIndex = index;
                    },
                    drop(index) {
                        if (this.dragIndex === null || this.dragIndex === index) {
                            return;
                        }
                        const movido = this.items.splice(this.dragIndex, 1)[0];
                        this.items.splice(index, 0, movido);
                        this.dragIndex = null;
                        localStorage.setItem(this.storageKey, JSON.stringify(this.items));
                    },
                };
            }
        </script>
    </x-marketing.container>
    |---LINE:844---|