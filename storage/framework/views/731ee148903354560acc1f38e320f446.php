
    <?php if (isset($component)) { $__componentOriginala766c2d312d6f7864fe218e2500d2bba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala766c2d312d6f7864fe218e2500d2bba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '5d1e9f4bd2de9159582ae964fcb99436::container','data' => ['class' => 'py-12 sm:py-20 space-y-12']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'py-12 sm:py-20 space-y-12']); ?>


        <div class="relative p-10 md:p-16 bg-white/40 dark:bg-zinc-900/30 backdrop-blur-2xl rounded-[3rem] border border-white/60 shadow-2xl overflow-hidden mb-12">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
    
    <div class="relative z-10 space-y-6">
        <div class="flex items-center gap-3">
            <span class="px-4 py-1.5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-full shadow-lg shadow-indigo-500/20">
                Documentación interna
            </span>
            <span class="w-12 h-[1px] bg-zinc-300 dark:bg-zinc-700"></span>
        </div>

        <h1 class="text-4xl md:text-6xl font-black text-zinc-800 dark:text-zinc-100 tracking-tighter leading-[0.95]">
            Guía completa del <br>
            <span class="text-indigo-600">sistema Alma Mia</span>
        </h1>

        <p class="text-lg md:text-xl text-zinc-500 dark:text-zinc-400 max-w-4xl leading-relaxed font-medium">
            Esta página resume la <span class="text-zinc-800 dark:text-zinc-200 font-bold">arquitectura del proyecto</span>, explica cada área funcional y detalla el funcionamiento del menú lateral para todos los roles: 
            <span class="text-indigo-500 font-bold">vendedora, líder, coordinadora y administradora</span>. 
            Es el punto de partida estratégico para nuevas incorporaciones al equipo de desarrollo o de operaciones.
        </p>

        <div class="flex items-center gap-4 pt-4">
            <div class="flex -space-x-2">
                <div class="w-8 h-8 rounded-full border-2 border-white bg-zinc-200 flex items-center justify-center text-[10px] font-bold text-zinc-600">V</div>
                <div class="w-8 h-8 rounded-full border-2 border-white bg-indigo-100 flex items-center justify-center text-[10px] font-bold text-indigo-600">L</div>
                <div class="w-8 h-8 rounded-full border-2 border-white bg-purple-100 flex items-center justify-center text-[10px] font-bold text-purple-600">C</div>
                <div class="w-8 h-8 rounded-full border-2 border-white bg-zinc-800 flex items-center justify-center text-[10px] font-bold text-white">A</div>
            </div>
            <p class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">Manual Multi-Rol Activo</p>
        </div>
    </div>
</div>

        
<div class="grid gap-8 lg:grid-cols-12">
    <div class="lg:col-span-7 space-y-6">
        <h2 class="text-3xl font-black text-zinc-800 dark:text-zinc-100 flex items-center gap-3">
            <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
            Arquitectura técnica
        </h2>
        
        <div class="p-8 bg-white/60 dark:bg-zinc-900/40 backdrop-blur-xl rounded-[2.5rem] border border-white/60 shadow-xl">
            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-8 font-medium">
                La plataforma está construida sobre <span class="text-zinc-800 dark:text-zinc-200 font-bold">Laravel 12</span> y se apoya en <span class="text-zinc-800 dark:text-zinc-200 font-bold">Wave</span> como base SaaS. 
                Implementa la pila <span class="text-indigo-600 font-bold">TALL</span> (Tailwind, Alpine, Livewire 3 y Volt) con ruteo dinámico mediante <span class="text-indigo-600 font-bold">Laravel Folio</span>.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-white shadow-sm flex items-start gap-4">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-cpu-bold'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-indigo-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Backend</p>
                        <p class="text-xs font-bold text-zinc-700 dark:text-zinc-300">Laravel 12 + Wave (Spatie Auth)</p>
                    </div>
                </div>
                <div class="p-4 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-white shadow-sm flex items-start gap-4">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-palette-bold'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-indigo-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Frontend</p>
                        <p class="text-xs font-bold text-zinc-700 dark:text-zinc-300">TailwindCSS + Anchor Theme</p>
                    </div>
                </div>
                <div class="p-4 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-white shadow-sm flex items-start gap-4">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-lightning-bold'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-indigo-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Interactividad</p>
                        <p class="text-xs font-bold text-zinc-700 dark:text-zinc-300">Livewire 3 + Volt (Reactive)</p>
                    </div>
                </div>
                <div class="p-4 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-white shadow-sm flex items-start gap-4">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-shield-check-bold'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-indigo-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Seguridad</p>
                        <p class="text-xs font-bold text-zinc-700 dark:text-zinc-300">2FA, JWT API & Stripe Pay</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-zinc-100 dark:bg-zinc-800 rounded-md text-[10px] font-bold text-zinc-500 uppercase">Filament Admin</span>
                <span class="px-3 py-1 bg-zinc-100 dark:bg-zinc-800 rounded-md text-[10px] font-bold text-zinc-500 uppercase">MySQL / MariaDB</span>
                <span class="px-3 py-1 bg-zinc-100 dark:bg-zinc-800 rounded-md text-[10px] font-bold text-zinc-500 uppercase">Laravel Folio Routing</span>
            </div>
        </div>
    </div>

    <div class="lg:col-span-5">
        <div class="h-full p-8 bg-zinc-900 dark:bg-black rounded-[2.5rem] shadow-2xl relative overflow-hidden group border border-zinc-800">
            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-folder-open-bold'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute top-0 right-0 w-48 h-48 text-white/[0.03] -mt-10 -mr-10 group-hover:text-indigo-500/10 transition-colors duration-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
            
            <h3 class="text-xl font-black text-white mb-8 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-tree-structure-bold'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-6 h-6 text-indigo-400']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                Estructura clave
            </h3>

            <ul class="space-y-5 relative z-10">
                <li class="group/item">
                    <code class="text-indigo-400 font-bold text-xs block mb-1 tracking-tight">resources/themes/anchor</code>
                    <p class="text-zinc-500 text-[11px] leading-relaxed group-hover/item:text-zinc-300 transition-colors">
                        Núcleo visual: layouts, componentes Blade y ruteo de páginas Folio.
                    </p>
                </li>
                <li class="group/item">
                    <code class="text-indigo-400 font-bold text-xs block mb-1 tracking-tight">app/Livewire & app/Volt</code>
                    <p class="text-zinc-500 text-[11px] leading-relaxed group-hover/item:text-zinc-300 transition-colors">
                        Cerebro reactivo de la UI. Componentes de lógica inmediata.
                    </p>
                </li>
                <li class="group/item">
                    <code class="text-indigo-400 font-bold text-xs block mb-1 tracking-tight">app/Models</code>
                    <p class="text-zinc-500 text-[11px] leading-relaxed group-hover/item:text-zinc-300 transition-colors">
                        Capa de datos: relaciones Eloquent, fillables y casts.
                    </p>
                </li>
                <li class="group/item">
                    <code class="text-indigo-400 font-bold text-xs block mb-1 tracking-tight">database/migrations & seeders</code>
                    <p class="text-zinc-500 text-[11px] leading-relaxed group-hover/item:text-zinc-300 transition-colors">
                        Estructura de tablas y datos maestros iniciales.
                    </p>
                </li>
                <li class="group/item">
                    <code class="text-indigo-400 font-bold text-xs block mb-1 tracking-tight">config/wave.php</code>
                    <p class="text-zinc-500 text-[11px] leading-relaxed group-hover/item:text-zinc-300 transition-colors">
                        Configuración global: colores primarios y opciones SaaS.
                    </p>
                </li>
                <li class="group/item">
                    <code class="text-indigo-400 font-bold text-xs block mb-1 tracking-tight">docs/</code>
                    <p class="text-zinc-500 text-[11px] leading-relaxed group-hover/item:text-zinc-300 transition-colors">
                        Repositorio de conocimiento y reglas de negocio.
                    </p>
                </li>
            </ul>
        </div>
    </div>
</div>






        
        <div class="space-y-4">
            <h2 class="text-3xl font-semibold text-primary-700">Roles disponibles</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-4xl">
                El modelo de negocio se organiza en la jerarquía Vendedora → Líder → Coordinadora,
                junto con un rol Administrador para la operación central. Cada perfil habilita
                distintas secciones del sidebar y permisos específicos.
            </p>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="p-5 border rounded-xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-primary-700">Vendedora</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Accede a su catálogo, crea pedidos
                        propios y consulta su historial.</p>
                </div>
                <div class="p-5 border rounded-xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-primary-700">Líder</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Gestiona su red de vendedoras,
                        genera pedidos para el grupo y consulta reportes de zona.</p>
                </div>
                <div class="p-5 border rounded-xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-primary-700">Coordinadora</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Supervisa líderes, valida cierres
                        y visualiza resúmenes globales.</p>
                </div>
                <div class="p-5 border rounded-xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-primary-700">Administrador</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Administra catálogo, campañas,
                        finanzas, puntaje y configuraciones generales.</p>
                </div>
            </div>
        </div>





        
        <div class="space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-semibold text-primary-700">Navegación lateral (sidebar)</h2>
                    <p class="text-gray-600 dark:text-gray-400 max-w-3xl">
                        El sidebar se encuentra en <code>resources/themes/anchor/components/app/sidebar.blade.php</code> y
                        controla la visibilidad de enlaces según el rol autenticado. A continuación se detallan sus
                        entradas principales y el objetivo de cada una.
                    </p>
                </div>
                <div class="p-4 border rounded-xl bg-primary-50 text-primary-800 border-primary-200 shadow-sm max-w-lg">
                    <p class="text-sm font-medium">Tip de edición</p>
                    
                </div>
            </div>
            
            
            
            
            <div class="grid grid-cols-1 md:grid-cols-3 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="px-4 py-3 font-semibold text-sm text-zinc-700 dark:text-zinc-200">Elemento</div>
                    <div class="px-4 py-3 font-semibold text-sm text-zinc-700 dark:text-zinc-200">Descripción</div>
                    <div class="px-4 py-3 font-semibold text-sm text-zinc-700 dark:text-zinc-200">Roles con acceso</div>
                </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-700 text-sm text-gray-700 dark:text-gray-300">
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Escritorio</div>
                        <div class="px-4 py-3">Tablero inicial con métricas personales y accesos rápidos.</div>
                        <div class="px-4 py-3">Todas las usuarias autenticadas.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Mis Pedidos / Crear Pedido / Catálogo</div>
                        <div class="px-4 py-3">Gestión directa del catálogo, creación de pedidos y consulta del historial propio.</div>
                        <div class="px-4 py-3">Vendedora y Líder.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Vendedoras</div>
                        <div class="px-4 py-3">Listado y seguimiento de la red de vendedoras.</div>
                        <div class="px-4 py-3">Líder y Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Líderes</div>
                        <div class="px-4 py-3">Administración de líderes, asignación de zonas y métricas.</div>
                        <div class="px-4 py-3">Coordinadora y Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Coordinadoras</div>
                        <div class="px-4 py-3">Gestión de coordinadoras y estructura superior.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Zona Líder / Zona Coordinadora</div>
                        <div class="px-4 py-3">Espacios operativos para gestionar indicadores y cierres de su zona.</div>
                        <div class="px-4 py-3">Líder o Coordinadora según corresponda.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Perfil</div>
                        <div class="px-4 py-3">Edición de datos personales, seguridad y preferencias.</div>
                        <div class="px-4 py-3">Todas las usuarias autenticadas.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Incorporar</div>
                        <div class="px-4 py-3">Alta de nuevas vendedoras y líderes según jerarquía.</div>
                        <div class="px-4 py-3">Líder, Coordinadora y Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Catálogo (Administración)</div>
                        <div class="px-4 py-3">Dropdown con edición de catálogo, categorías, productos, stock y rótulos.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Ventas</div>
                        <div class="px-4 py-3">Dropdown para campañas y seguimiento de pedidos corporativos.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Finanzas</div>
                        <div class="px-4 py-3">Dropdown con gastos administrativos, pagos y cobros.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Crecimiento</div>
                        <div class="px-4 py-3">Dropdown para puntaje, rangos y bonos por rol.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Cierre General</div>
                        <div class="px-4 py-3">Reportes de cierre global y resúmenes por rol.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Notificaciones</div>
                        <div class="px-4 py-3">Centro de alertas y mensajes internos.</div>
                        <div class="px-4 py-3">Todas las usuarias autenticadas.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Agente / Ajustes / Novedades / Editor</div>
                        <div class="px-4 py-3">Herramientas administrativas para IA interna, configuración de perfil Wave,
                            changelog y editor de plantillas.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                </div>
        </div>

        
        
        
        
        
        
        
        

        
        <div class="space-y-4">
            <h2 class="text-3xl font-semibold text-primary-700">Módulos principales</h2>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm space-y-3">
                    <h3 class="text-xl font-semibold text-primary-700">Catálogo y productos</h3>
                    <p class="text-gray-600 dark:text-gray-400">Administración completa del catálogo oficial: categorías,
                        productos, stock y rótulos. Los administradores pueden editar desde el dropdown
                        "Catálogo"; las vendedoras consumen la versión pública en "Catálogo".</p>
                </div>
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm space-y-3">
                    <h3 class="text-xl font-semibold text-primary-700">Pedidos y campañas</h3>
                    <p class="text-gray-600 dark:text-gray-400">Permite crear pedidos, asociarlos a campañas activas y
                        seguir su ciclo (creación, envío y facturación). Las líderes pueden consolidar pedidos
                        de su red y las coordinadoras supervisan los cierres.</p>
                </div>
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm space-y-3">
                    <h3 class="text-xl font-semibold text-primary-700">Crecimiento y bonos</h3>
                    <p class="text-gray-600 dark:text-gray-400">El módulo de crecimiento controla las reglas de puntaje,
                        rangos y bonos diferenciados por rol (líderes y coordinadoras). Los reportes de cierre
                        general consolidan estos cálculos.</p>
                </div>
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm space-y-3">
                    <h3 class="text-xl font-semibold text-primary-700">Finanzas y operaciones</h3>
                    <p class="text-gray-600 dark:text-gray-400">La sección de finanzas centraliza gastos administrativos,
                        pagos y cobros. Complementa la facturación Stripe provista por Wave y sirve de respaldo
                        operativo para el equipo interno.</p>
                </div>
            </div>
        </div>

        
        <div class="space-y-4">
            <h2 class="text-3xl font-semibold text-primary-700">Personalización visual</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-4xl">
                El color primario del tema se define en <code>config/wave.php</code> con la clave
                <code>primary_color</code>. Para adaptar la identidad de marca se recomienda:
            </p>
            <ul class="list-disc pl-6 space-y-2 text-gray-600 dark:text-gray-400">
                <li>Actualizar el valor de <code>primary_color</code> para propagarlo a botones y acentos.</li>
                <li>Reemplazar los SVG en <code>resources/views/components/logo.blade.php</code> y
                    <code>logo-icon.blade.php</code> por la versión corporativa.</li>
                <li>Sustituir los favicon en <code>public/wave/favicon.png</code> y
                    <code>public/wave/favicon-dark.png</code>.</li>
                <li>Usar clases de Tailwind en los componentes de <code>resources/themes/anchor/components</code>
                    para mantener consistencia sin tocar el core de Wave.</li>
            </ul>
        </div>

        
        <div class="space-y-4">
            <h2 class="text-3xl font-semibold text-primary-700">Buenas prácticas de desarrollo</h2>
            <ol class="list-decimal pl-6 space-y-2 text-gray-600 dark:text-gray-400">
                <li>Registrar cambios estructurales con migraciones y mantener seeders oficiales sincronizados.</li>
                <li>Actualizar la documentación funcional en <code>docs/</code> y el README cuando haya cambios de negocio.</li>
                <li>Evitar modificaciones en <code>/wave/</code>; priorizar extensiones en <code>app/</code> y
                    <code>resources/themes/</code>.</li>
                <li>Antes de subir cambios ejecutar <code>php artisan test</code> y validar funcionalidades críticas.</li>
            </ol>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala766c2d312d6f7864fe218e2500d2bba)): ?>
<?php $attributes = $__attributesOriginala766c2d312d6f7864fe218e2500d2bba; ?>
<?php unset($__attributesOriginala766c2d312d6f7864fe218e2500d2bba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala766c2d312d6f7864fe218e2500d2bba)): ?>
<?php $component = $__componentOriginala766c2d312d6f7864fe218e2500d2bba; ?>
<?php unset($__componentOriginala766c2d312d6f7864fe218e2500d2bba); ?>
<?php endif; ?>
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/edebcf6ae8dc75e61d0a37caf5ec7ae1.blade.php ENDPATH**/ ?>