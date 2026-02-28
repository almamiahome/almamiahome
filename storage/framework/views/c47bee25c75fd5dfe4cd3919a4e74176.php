<a href="<?php echo e($href); ?>" class="<?php if($href == RalphJSmit\Livewire\Urls\Facades\Url::current()): ?><?php echo e('bg-zinc-100 dark:bg-zinc-700/70 text-zinc-900 dark:text-zinc-200 font-semibold'); ?><?php else: ?><?php echo e('hover:bg-zinc-100 hover:dark:bg-zinc-700/70 hover:dark:text-zinc-200 text-zinc-600 dark:text-zinc-400 font-medium'); ?><?php endif; ?> flex justify-start items-center px-4 py-3 md:py-1.5 text-sm whitespace-nowrap group rounded-md transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 relative">
    <span class="absolute left-0 block w-full lg:w-1 lg:-translate-x-1.5 transition-all duration-300 ease-out rounded-full bottom-0 translate-y-2 lg:top-1/2 lg:-translate-y-1/2 <?php if($href == RalphJSmit\Livewire\Urls\Facades\Url::current()): ?><?php echo e('w-full h-1 lg:h-5/6'); ?><?php else: ?><?php echo e('h-0'); ?><?php endif; ?> " style="background:<?php echo e(config('wave.primary_color')); ?>"></span>
    <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $icon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'flex-shrink-0 md:mr-1 md:-ml-1.5 w-5 h-5 md:w-4 md:h-4']); ?>
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
    <span class="hidden truncate md:inline-block"><?php echo e($slot); ?></span>
</a><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/elements/settings-sidebar-link.blade.php ENDPATH**/ ?>