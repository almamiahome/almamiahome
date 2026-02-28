<div x-data="{ sidebarOpen: false }" @open-sidebar.window="sidebarOpen = true"
    x-init="$watch('sidebarOpen', value => document.body.classList.toggle('overflow-hidden', value))"
    class="relative" x-cloak>
    
    
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @click="sidebarOpen=false" 
         class="fixed inset-0 bg-black/20 backdrop-blur-sm lg:hidden"></div>
    
     
    <div :class="{
            'translate-y-full lg:translate-y-0 lg:left-4': !sidebarOpen,
            'translate-y-0 lg:left-4': sidebarOpen,
        }"
        class="fixed inset-x-4 bottom-4 top-20 md:top-4 md:left-4 flex flex-col overflow-hidden  transition-all duration-500 ease-in-out 
               /* Configuración del Cristal */
               bg-white/70 dark:bg-black/40 backdrop-blur-2xl 
               rounded-[2.5rem] border border-white/40 dark:border-white/10 
               shadow-[0_8px_32px_0_rgba(31,38,135,0.15)]
               /* Dimensiones */
               w-auto md:w-72 lg:w-64 
               <?php if(config('wave.dev_bar')): ?> pb-10 <?php endif; ?>">
        
        <div class="flex flex-col justify-between h-full pt-8 pb-6">
            <div class="relative flex flex-col h-full overflow-y-auto scrollbar-hidden">
                
                
                <div class="flex items-center justify-between px-7 mb-6">
                    <a href="/" class="flex items-center transition-transform hover:scale-105 active:scale-95">
                        <?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo','data' => ['class' => 'w-auto h-8 drop-shadow-md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-auto h-8 drop-shadow-md']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $attributes = $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $component = $__componentOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
                    </a>
                    <button x-on:click="sidebarOpen=false" class="lg:hidden p-2 rounded-full bg-white/30 dark:bg-zinc-800/50 text-zinc-800 dark:text-white transition-colors hover:bg-white/50">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-x-bold'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
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
                    </button>
                </div>

                
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
                    
                    
                    <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/dashboard','icon' => 'phosphor-house','active' => Request::is('dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/dashboard','icon' => 'phosphor-house','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('dashboard'))]); ?>Escritorio <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/crearpedido','icon' => 'phosphor-plus-circle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/crearpedido','icon' => 'phosphor-plus-circle']); ?>Crear Pedido <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/catalogo','icon' => 'phosphor-book']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/catalogo','icon' => 'phosphor-book']); ?>Catálogo <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/mis-pedidos','icon' => 'phosphor-list-checks']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/mis-pedidos','icon' => 'phosphor-list-checks']); ?>Mis Pedidos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'lider|admin')): ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/vendedoras','icon' => 'phosphor-handshake']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/vendedoras','icon' => 'phosphor-handshake']); ?>Vendedoras <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/pedidos','icon' => 'phosphor-shopping-cart']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/pedidos','icon' => 'phosphor-shopping-cart']); ?>Pedidos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'coordinadora|admin')): ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/lideres','icon' => 'phosphor-user-circle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/lideres','icon' => 'phosphor-user-circle']); ?>Líderes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/coordinadoras','icon' => 'phosphor-user-switch']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/coordinadoras','icon' => 'phosphor-user-switch']); ?>Coordinadoras <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'lider')): ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/zona-lider','icon' => 'phosphor-crown']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/zona-lider','icon' => 'phosphor-crown']); ?>Zona Líder <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'coordinadora')): ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/zona-coordinadora','icon' => 'phosphor-users-three']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/zona-coordinadora','icon' => 'phosphor-users-three']); ?>Zona Coordinadora <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/perfil','icon' => 'phosphor-user','active' => Request::is('perfil')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/perfil','icon' => 'phosphor-user','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('perfil'))]); ?>Perfil <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'lider|coordinadora|admin')): ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/incorporar','icon' => 'phosphor-user-plus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/incorporar','icon' => 'phosphor-user-plus']); ?>Incorporar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                        
                        <?php if (isset($component)) { $__componentOriginal456a49cac8cca255edec02e5cd077ea8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal456a49cac8cca255edec02e5cd077ea8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-dropdown','data' => ['text' => 'Catálogo','icon' => 'phosphor-package','id' => 'catalogo_dropdown','active' => Request::is('categorias*') || Request::is('productos*') || Request::is('stock*') || Request::is('envios*') || Request::is('rotulos*'),'open' => Request::is('categorias*') || Request::is('productos*') || Request::is('stock*') || Request::is('envios*') || Request::is('rotulos*') ? '1' : '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => 'Catálogo','icon' => 'phosphor-package','id' => 'catalogo_dropdown','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('categorias*') || Request::is('productos*') || Request::is('stock*') || Request::is('envios*') || Request::is('rotulos*')),'open' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('categorias*') || Request::is('productos*') || Request::is('stock*') || Request::is('envios*') || Request::is('rotulos*') ? '1' : '0')]); ?>
                            
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/catalogo/admin','icon' => 'phosphor-wrench','active' => Request::is('catalogo/admin*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/catalogo/admin','icon' => 'phosphor-wrench','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('catalogo/admin*'))]); ?>Editar Catálogo <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/productos','icon' => 'phosphor-tag']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/productos','icon' => 'phosphor-tag']); ?>Productos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/categorias','icon' => 'phosphor-folders']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/categorias','icon' => 'phosphor-folders']); ?>Categorías <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/rotulos','icon' => 'phosphor-ticket']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/rotulos','icon' => 'phosphor-ticket']); ?>Rótulos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal456a49cac8cca255edec02e5cd077ea8)): ?>
<?php $attributes = $__attributesOriginal456a49cac8cca255edec02e5cd077ea8; ?>
<?php unset($__attributesOriginal456a49cac8cca255edec02e5cd077ea8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal456a49cac8cca255edec02e5cd077ea8)): ?>
<?php $component = $__componentOriginal456a49cac8cca255edec02e5cd077ea8; ?>
<?php unset($__componentOriginal456a49cac8cca255edec02e5cd077ea8); ?>
<?php endif; ?>

                        
                        <?php if (isset($component)) { $__componentOriginal456a49cac8cca255edec02e5cd077ea8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal456a49cac8cca255edec02e5cd077ea8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-dropdown','data' => ['text' => 'Finanzas','icon' => 'phosphor-currency-circle-dollar','id' => 'finanzas_dropdown','active' => Request::is('facturas*') || Request::is('gastos*') || Request::is('pagos*') || Request::is('cobros*'),'open' => Request::is('facturas*') || Request::is('gastos*') || Request::is('pagos*') || Request::is('cobros*') ? '1' : '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => 'Finanzas','icon' => 'phosphor-currency-circle-dollar','id' => 'finanzas_dropdown','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('facturas*') || Request::is('gastos*') || Request::is('pagos*') || Request::is('cobros*')),'open' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('facturas*') || Request::is('gastos*') || Request::is('pagos*') || Request::is('cobros*') ? '1' : '0')]); ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/gastos','icon' => 'phosphor-coins']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/gastos','icon' => 'phosphor-coins']); ?>Gastos Administrativos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/pagos','icon' => 'phosphor-credit-card']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/pagos','icon' => 'phosphor-credit-card']); ?>Pagos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/cobros','icon' => 'phosphor-bank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/cobros','icon' => 'phosphor-bank']); ?>Cobros <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal456a49cac8cca255edec02e5cd077ea8)): ?>
<?php $attributes = $__attributesOriginal456a49cac8cca255edec02e5cd077ea8; ?>
<?php unset($__attributesOriginal456a49cac8cca255edec02e5cd077ea8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal456a49cac8cca255edec02e5cd077ea8)): ?>
<?php $component = $__componentOriginal456a49cac8cca255edec02e5cd077ea8; ?>
<?php unset($__componentOriginal456a49cac8cca255edec02e5cd077ea8); ?>
<?php endif; ?>

                        
                        <?php if (isset($component)) { $__componentOriginal456a49cac8cca255edec02e5cd077ea8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal456a49cac8cca255edec02e5cd077ea8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-dropdown','data' => ['text' => 'Crecimiento','icon' => 'phosphor-trend-up','id' => 'crecimiento_dropdown','active' => Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*'),'open' => Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') ? '1' : '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => 'Crecimiento','icon' => 'phosphor-trend-up','id' => 'crecimiento_dropdown','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*')),'open' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') ? '1' : '0')]); ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/puntaje-reglas','icon' => 'phosphor-trophy']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/puntaje-reglas','icon' => 'phosphor-trophy']); ?>Puntaje Reglas <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/rangos','icon' => 'phosphor-medal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/rangos','icon' => 'phosphor-medal']); ?>Rangos <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal456a49cac8cca255edec02e5cd077ea8)): ?>
<?php $attributes = $__attributesOriginal456a49cac8cca255edec02e5cd077ea8; ?>
<?php unset($__attributesOriginal456a49cac8cca255edec02e5cd077ea8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal456a49cac8cca255edec02e5cd077ea8)): ?>
<?php $component = $__componentOriginal456a49cac8cca255edec02e5cd077ea8; ?>
<?php unset($__componentOriginal456a49cac8cca255edec02e5cd077ea8); ?>
<?php endif; ?>

                        
                        <?php if (isset($component)) { $__componentOriginal456a49cac8cca255edec02e5cd077ea8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal456a49cac8cca255edec02e5cd077ea8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-dropdown','data' => ['text' => 'Cierre General','icon' => 'phosphor-chart-pie','id' => 'cierre_dropdown','active' => Request::is('resumen-lideres*') || Request::is('resumen-coordinadoras*') || Request::is('resumen-revendedoras*'),'open' => Request::is('resumen-lideres*') || Request::is('resumen-coordinadoras*') || Request::is('resumen-revendedoras*') ? '1' : '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => 'Cierre General','icon' => 'phosphor-chart-pie','id' => 'cierre_dropdown','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('resumen-lideres*') || Request::is('resumen-coordinadoras*') || Request::is('resumen-revendedoras*')),'open' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('resumen-lideres*') || Request::is('resumen-coordinadoras*') || Request::is('resumen-revendedoras*') ? '1' : '0')]); ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/crecimiento-cierre-general','icon' => 'phosphor-chart-bar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/crecimiento-cierre-general','icon' => 'phosphor-chart-bar']); ?>Cierre General <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/resumen-lideres','icon' => 'phosphor-crown']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/resumen-lideres','icon' => 'phosphor-crown']); ?>Resumen Líderes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/resumen-coordinadoras','icon' => 'phosphor-users-three']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/resumen-coordinadoras','icon' => 'phosphor-users-three']); ?>Resumen Coordinadoras <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/resumen-revendedoras','icon' => 'phosphor-user-list']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/resumen-revendedoras','icon' => 'phosphor-user-list']); ?>Resumen Revendedoras <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal456a49cac8cca255edec02e5cd077ea8)): ?>
<?php $attributes = $__attributesOriginal456a49cac8cca255edec02e5cd077ea8; ?>
<?php unset($__attributesOriginal456a49cac8cca255edec02e5cd077ea8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal456a49cac8cca255edec02e5cd077ea8)): ?>
<?php $component = $__componentOriginal456a49cac8cca255edec02e5cd077ea8; ?>
<?php unset($__componentOriginal456a49cac8cca255edec02e5cd077ea8); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['hideUntilGroupHover' => false,'href' => ''.e(route('notificaciones')).'','icon' => 'phosphor-bell-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hideUntilGroupHover' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'href' => ''.e(route('notificaciones')).'','icon' => 'phosphor-bell-duotone','active' => 'false']); ?>Notificaciones <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                        <div class="my-2 border-t border-white/20"></div>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/usuarios','icon' => 'phosphor-users-three','active' => Request::is('usuarios*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/usuarios','icon' => 'phosphor-users-three','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('usuarios*'))]); ?>Usuarios <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/agente','icon' => 'phosphor-robot-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/agente','icon' => 'phosphor-robot-duotone','active' => 'false']); ?>Agente <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => ''.e(route('settings.profile')).'','icon' => 'phosphor-gear-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('settings.profile')).'','icon' => 'phosphor-gear-duotone','active' => 'false']); ?>Ajustes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => route('changelogs'),'icon' => 'phosphor-book-open-text-duotone','active' => Request::is('changelog') || Request::is('changelog/*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('changelogs')),'icon' => 'phosphor-book-open-text-duotone','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('changelog') || Request::is('changelog/*'))]); ?>Novedades <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/editor','target' => '_blank','icon' => 'phosphor-code-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/editor','target' => '_blank','icon' => 'phosphor-code-duotone','active' => 'false']); ?>Editor <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                    <?php endif; ?>

                </nav>
            </div>

            
            <div class="px-4 mt-auto">
                <div class="p-2 rounded-3xl bg-white/40 dark:bg-black/30 border border-white/30 dark:border-white/10 shadow-sm backdrop-blur-md">
                    <?php if (isset($component)) { $__componentOriginal262b77ea6d221dabc34c6f9febac9fba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal262b77ea6d221dabc34c6f9febac9fba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.user-menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.user-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal262b77ea6d221dabc34c6f9febac9fba)): ?>
<?php $attributes = $__attributesOriginal262b77ea6d221dabc34c6f9febac9fba; ?>
<?php unset($__attributesOriginal262b77ea6d221dabc34c6f9febac9fba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal262b77ea6d221dabc34c6f9febac9fba)): ?>
<?php $component = $__componentOriginal262b77ea6d221dabc34c6f9febac9fba; ?>
<?php unset($__componentOriginal262b77ea6d221dabc34c6f9febac9fba); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/app/sidebar.blade.php ENDPATH**/ ?>