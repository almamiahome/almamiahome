<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'position' => 'bottom'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'position' => 'bottom'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div x-data="{ dropdownOpen: false }" :class="{ 'block z-50 w-auto lg:w-full dark:bg-zinc-900 dark:border-zinc-800' : open, 'hidden': ! open }" class="relative flex-shrink-0 sm:p-0 dark:text-zinc-200 sm:flex sm:w-auto sm:bg-transparent sm:items-center" x-cloak>
    <button @click="dropdownOpen=!dropdownOpen" class="flex p-2.5 lg:p-2 w-full space-x-1 text-[13px] hover:bg-zinc-200/70 rounded-lg justify-between items-center w-full hover:text-black dark:hover:text-zinc-100 dark:hover:bg-zinc-700/60 space-x-1.5 overflow-hidden group-hover:autoflow-auto items">
        <span class="relative flex items-center space-x-2">
            <?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['src' => ''.e(auth()->user()->avatar()).'','alt' => ''.e(auth()->user()->name).' photo','size' => '2xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['src' => ''.e(auth()->user()->avatar()).'','alt' => ''.e(auth()->user()->name).' photo','size' => '2xs']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $attributes = $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $component = $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                'flex-shrink-0 ease-out duration-50',
                'hidden' => ($position != 'bottom')
            ]); ?>"><?php echo e(Auth::user()->name); ?></span>
        </span>
        <svg :class="{ 'rotate-180' : '<?php echo e($position); ?>' == 'bottom' }" class="relative right-0 w-4 h-4 ease-out mr-4 -translate-x-0.5 fill-current group-hover:delay-150 duration-0 group-hover:duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
    </button>
    <div wire:ignore x-show="dropdownOpen" @mouse.leave="dropdownOpen=false" @click.away="dropdownOpen=false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 sm:scale-95" x-transition:enter-end="transform opacity-100 sm:scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 sm:scale-100" x-transition:leave-end="transform opacity-0 sm:scale-95" 
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'z-50',
            'left-0  absolute w-full bottom-0 sm:origin-bottom mb-12' => ($position == 'bottom'),
            'top-0 sm:origin-top right-0 mr-5 mt-14 w-full max-w-xs fixed' => ($position != 'bottom')
        ]); ?>" 
        x-cloak>
        <div class="pt-0 mt-1 bg-white border dark:border-zinc-700 text-zinc-600 dark:text-white/70 dark:bg-zinc-900 dark:shadow-xl sm:space-y-0.5 sm:border shadow-md rounded-xl border-zinc-200/70 dark:border-white/10">
            <div class="px-[18px] py-3.5 text-[13px] font-bold text-ellipsis overflow-hidden whitespace-nowrap"><?php echo e(auth()->user()->email); ?></div>
            <div class="w-full h-px my-2 bg-slate-100 dark:bg-zinc-700"></div>
            <div class="relative px-2 py-1">
                <?php if (isset($component)) { $__componentOriginal5c9baf053f899599918c5e90b635f357 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c9baf053f899599918c5e90b635f357 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.light-dark-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.light-dark-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c9baf053f899599918c5e90b635f357)): ?>
<?php $attributes = $__attributesOriginal5c9baf053f899599918c5e90b635f357; ?>
<?php unset($__attributesOriginal5c9baf053f899599918c5e90b635f357); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c9baf053f899599918c5e90b635f357)): ?>
<?php $component = $__componentOriginal5c9baf053f899599918c5e90b635f357; ?>
<?php unset($__componentOriginal5c9baf053f899599918c5e90b635f357); ?>
<?php endif; ?>
            </div>
            <div class="w-full h-px my-2 bg-slate-100 dark:bg-zinc-700"></div>
            <div class="relative flex flex-col p-2 space-y-1">
               
               <!--
                <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['hideUntilGroupHover' => false,'href' => ''.e('/profile/' . auth()->user()->username).'','icon' => 'phosphor-planet-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hideUntilGroupHover' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'href' => ''.e('/profile/' . auth()->user()->username).'','icon' => 'phosphor-planet-duotone','active' => 'false']); ?>Public Profile <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes(['hideUntilGroupHover' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'href' => ''.e(route('settings.profile')).'','icon' => 'phosphor-gear-duotone','active' => 'false']); ?>Settings <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $attributes = $__attributesOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__attributesOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd8bf167f40cf8687e797695f7591708)): ?>
<?php $component = $__componentOriginalbd8bf167f40cf8687e797695f7591708; ?>
<?php unset($__componentOriginalbd8bf167f40cf8687e797695f7591708); ?>
<?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('notsubscriber')): ?>
                <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => '/settings/subscription','icon' => 'phosphor-sparkle-duotone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/settings/subscription','icon' => 'phosphor-sparkle-duotone']); ?>Upgrade <?php echo $__env->renderComponent(); ?>
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
                -->
                <?php if(auth()->user()->isAdmin()): ?>
                <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['hideUntilGroupHover' => false,'ajax' => false,'href' => '/admin','icon' => 'phosphor-crown-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hideUntilGroupHover' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'ajax' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'href' => '/admin','icon' => 'phosphor-crown-duotone','active' => 'false']); ?>Ver Admin <?php echo $__env->renderComponent(); ?>
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
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                    <?php echo csrf_field(); ?>
                    <button onclick="event.preventDefault(); this.closest('form').submit();" class="relative w-full flex cursor-pointer hover:text-zinc-700 dark:hover:text-zinc-100 select-none hover:bg-zinc-200 dark:hover:bg-zinc-700/60 items-center rounded-lg p-2 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-sign-out-duotone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'flex-shrink-0 w-auto h-5 ml-1 mr-2']); ?>
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
                        <span>Cerrar Sesion</span>
                    </button>
                </form>
                <?php if (is_impersonating()) : ?>
                <?php if (isset($component)) { $__componentOriginalbd8bf167f40cf8687e797695f7591708 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd8bf167f40cf8687e797695f7591708 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.sidebar-link','data' => ['href' => ''.e(route('impersonate.leave')).'','icon' => 'phosphor-user-circle-duotone','active' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('impersonate.leave')).'','icon' => 'phosphor-user-circle-duotone','active' => 'false']); ?>Leave impersonation <?php echo $__env->renderComponent(); ?>
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
    </div>
</div><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/app/user-menu.blade.php ENDPATH**/ ?>