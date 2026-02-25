<div x-data="{ sidebarOpen: false }"  @open-sidebar.window="sidebarOpen = true"
    x-init="
        $watch('sidebarOpen', function(value){
            if(value){ document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); }
        });
    "
    class="relative z-50 w-screen md:w-auto" x-cloak>
    
    <div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed top-0 right-0 z-50 w-screen h-screen duration-300 ease-out bg-black/20 dark:bg-white/10"></div>
    
     
    <div :class="{
            'translate-y-full md:-translate-x-full': !sidebarOpen,
            'translate-y-0 md:translate-x-0': sidebarOpen,
        }"
        class="fixed inset-x-0 bottom-0 md:top-0 md:left-0 flex items-stretch translate-y-full md:-translate-x-full overflow-hidden lg:translate-x-0 lg:translate-y-0 z-50 h-dvh md:h-screen transition-[width,transform] duration-150 ease-out bg-zinc-50 dark:bg-zinc-900 w-full md:w-64 group <?php if(config('wave.dev_bar')): ?><?php echo e('pb-10'); ?><?php endif; ?>">
        <div class="flex flex-col justify-between w-full overflow-auto md:h-full h-svh pt-4 pb-2.5">
            <div class="relative flex flex-col">
                <button x-on:click="sidebarOpen=false" class="flex items-center justify-center flex-shrink-0 w-10 h-10 ml-4 rounded-md lg:hidden text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 dark:hover:bg-zinc-700/70 hover:bg-gray-200/70">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>

                <div class="flex items-center px-5 space-x-2">
                    <a href="/" class="flex justify-center items-center py-4 pl-0.5 space-x-1 font-bold text-zinc-900">
                        <?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo','data' => ['class' => 'w-auto h-7']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-auto h-7']); ?>
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
                </div>
                <div class="flex items-center px-4 pt-1 pb-3">
                    <div class="relative flex items-center w-full h-full rounded-lg">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-magnifying-glass'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute left-0 w-5 h-5 ml-2 text-gray-400 -translate-y-px']); ?>
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
                        <input type="text" class="w-full py-2 pl-8 text-sm border rounded-lg bg-zinc-200/70 focus:bg-white duration-50 dark:bg-zinc-950 ease border-zinc-200 dark:border-zinc-700/70 dark:ring-zinc-700/70 focus:ring dark:text-zinc-200 dark:focus:ring-zinc-700/70 dark:focus:border-zinc-700 focus:ring-zinc-200 focus:border-zinc-300 dark:placeholder-zinc-400" placeholder="Buscar">
                    </div>
                </div>

                <div class="flex flex-col justify-start items-center px-4 space-y-1.5 w-full h-full text-slate-600 dark:text-zinc-400">
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

                    <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'lider|vendedora')): ?>
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
<?php $component->withAttributes(['href' => '/perfil','icon' => 'phosphor-user','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('perfil'))]); ?>
                        Perfil
                     <?php echo $__env->renderComponent(); ?>
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
                        <!-- Catálogo -->
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
<?php $component->withAttributes(['href' => '/catalogo/admin','icon' => 'phosphor-wrench','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('catalogo/admin*'))]); ?>
                                Editar Catálogo
                             <?php echo $__env->renderComponent(); ?>
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
                          <!--  <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/stock','icon' => 'phosphor-warehouse']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/stock','icon' => 'phosphor-warehouse']); ?>Stock <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?> -->
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

                        <!-- Ventas 
                        <?php if (isset($component)) { $__componentOriginal456a49cac8cca255edec02e5cd077ea8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal456a49cac8cca255edec02e5cd077ea8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-dropdown','data' => ['text' => 'Ventas','icon' => 'phosphor-handshake','id' => 'ventas_dropdown','active' => Request::is('campanas*') || Request::is('pedidos*') || Request::is('clientes*'),'open' => Request::is('campanas*') || Request::is('pedidos*') || Request::is('clientes*') ? '1' : '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => 'Ventas','icon' => 'phosphor-handshake','id' => 'ventas_dropdown','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('campanas*') || Request::is('pedidos*') || Request::is('clientes*')),'open' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('campanas*') || Request::is('pedidos*') || Request::is('clientes*') ? '1' : '0')]); ?>
                           <!-- <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/campanas','icon' => 'phosphor-megaphone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/campanas','icon' => 'phosphor-megaphone']); ?>Campañas <?php echo $__env->renderComponent(); ?>
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
                        -->
                        

                        <!-- Finanzas -->
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

                        <!-- Crecimiento -->
                        <?php if (isset($component)) { $__componentOriginal456a49cac8cca255edec02e5cd077ea8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal456a49cac8cca255edec02e5cd077ea8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-dropdown','data' => ['text' => 'Crecimiento','icon' => 'phosphor-trend-up','id' => 'crecimiento_dropdown','active' => Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') || Request::is('crecimiento-cierre-general*'),'open' => Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') || Request::is('crecimiento-cierre-general*') ? '1' : '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => 'Crecimiento','icon' => 'phosphor-trend-up','id' => 'crecimiento_dropdown','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') || Request::is('crecimiento-cierre-general*')),'open' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('puntaje-reglas*') || Request::is('rangos*') || Request::is('vendedoras*') || Request::is('lideres*') || Request::is('coordinadoras*') || Request::is('bono-lideres*') || Request::is('bono-coordinadoras*') || Request::is('crecimiento-cierre-general*') ? '1' : '0')]); ?>
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
                         <!--   <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/bono-lideres','icon' => 'phosphor-star']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/bono-lideres','icon' => 'phosphor-star']); ?>Bono Líderes <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/bono-coordinadoras','icon' => 'phosphor-star-half']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/bono-coordinadoras','icon' => 'phosphor-star-half']); ?>Bono Coordinadoras <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?> -->
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

                        <!-- Cierre General -->
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
                         <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['hideUntilGroupHover' => false,'href' => '/usuarios','icon' => 'phosphor-users-three','active' => Request::is('usuarios*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hideUntilGroupHover' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'href' => '/usuarios','icon' => 'phosphor-users-three','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Request::is('usuarios*'))]); ?>Usuarios <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['hideUntilGroupHover' => false,'href' => '/agente','icon' => 'phosphor-robot-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hideUntilGroupHover' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'href' => '/agente','icon' => 'phosphor-robot-duotone','active' => 'false']); ?>Agente <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['hideUntilGroupHover' => false,'href' => ''.e(route('settings.profile')).'','icon' => 'phosphor-gear-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hideUntilGroupHover' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'href' => ''.e(route('settings.profile')).'','icon' => 'phosphor-gear-duotone','active' => 'false']); ?>Ajustes <?php echo $__env->renderComponent(); ?>
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

                </div>
            </div>

            <div class="relative px-2.5 space-y-1.5 text-zinc-700 dark:text-zinc-400">
                
           <!--     
                <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => 'https://devdojo.com/questions','target' => '_blank','icon' => 'phosphor-chat-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => 'https://devdojo.com/questions','target' => '_blank','icon' => 'phosphor-chat-duotone','active' => 'false']); ?>Questions <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?> -->

                <div x-show="sidebarTip" x-data="{ sidebarTip: $persist(true) }" class="px-1 py-3" x-collapse x-cloak>
                    <div class="relative w-full px-4 py-3 space-y-1 border rounded-lg bg-zinc-50 text-zinc-700 dark:text-zinc-100 dark:bg-zinc-800 border-zinc-200/60 dark:border-zinc-700">
                        <button @click="sidebarTip=false" class="absolute top-0 right-0 z-50 p-1.5 mt-2.5 mr-2.5 rounded-full opacity-80 cursor-pointer hover:opacity-100 hover:bg-zinc-100 hover:dark:bg-zinc-700 hover:dark:text-zinc-300 text-zinc-500 dark:text-zinc-400">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-x-bold'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-3 h-3']); ?>
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
                       <h5 class="pb-1 text-sm font-bold -translate-y-0.5">Bienvenido a tu dashboard</h5>
                        <p class="block pb-1 text-xs opacity-80 text-balance">Version 1.0.</p> 
                    </div>
                </div>

                <div class="w-full h-px my-2 bg-slate-100 dark:bg-zinc-700"></div>
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
</div><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/app/sidebar.blade.php ENDPATH**/ ?>