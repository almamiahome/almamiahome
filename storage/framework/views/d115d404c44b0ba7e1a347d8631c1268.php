<div x-data="{ <?php echo e($id); ?>: <?php echo e($open ?? false); ?> }"
    :class="{ 'bg-zinc-200/70 dark:bg-zinc-800 dark:ring-zinc-800 ease-out duration-300 ring-2 ring-zinc-200/50 rounded-lg' : <?php echo e($id); ?> == true }"
    class="relative w-full select-none">
    <div
        @click="<?php echo e($id); ?>=!<?php echo e($id); ?>"
        class="<?php if($active): ?><?php echo e('text-zinc-900 bg-white border-zinc-200 shadow-sm font-medium dark:bg-zinc-700/60 dark:text-zinc-100'); ?><?php endif; ?> ease-linear duration-50 transition-colors flex rounded-lg w-full h-auto px-2.5 py-2 cursor-pointer text-sm border justify-start items-center overflow-hidden group-hover:autoflow-auto items"
        :class="{ 'text-zinc-900 bg-white border-zinc-200 dark:border-zinc-700 shadow-sm font-medium dark:bg-zinc-700/60 dark:text-zinc-100' : <?php echo e($id); ?> == true, 'hover:bg-zinc-100 dark:hover:bg-zinc-700/60 hover:text-zinc-900 border-transparent dark:hover:text-zinc-100' : (<?php echo e($id); ?> == false && <?php echo e(!$active); ?>) }"
    >
        <div class="flex relative items-center w-full h-auto">
            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $icon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'flex-shrink-0 mr-2 w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
            <span class="mr-0"><?php echo e($text); ?></span>
            <span :class="{ 'rotate-180' : <?php echo e($id); ?> == true }" class="mr-0.5 ml-auto w-4 h-4 duration-300 ease-out">
                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-caret-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full h-full']); ?>
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
            </span>
        </div>

        <template x-teleport="#<?php echo e($id); ?>">
            <div class="relative p-1 space-y-1" x-show="<?php echo e($id); ?>" x-collapse x-cloak>
                <?php echo e($slot); ?>

            </div>
        </template>
    </div>

    <div id="<?php echo e($id); ?>"></div>

</div>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/app/sidebar-dropdown.blade.php ENDPATH**/ ?>