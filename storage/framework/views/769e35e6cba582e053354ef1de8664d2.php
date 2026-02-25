<?php if (isset($component)) { $__componentOriginal4c1c5c7a9cb759b3852c337ba5e47e86 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4c1c5c7a9cb759b3852c337ba5e47e86 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.link','data' => ['attributes' => $attributes->except('wire:navigate'),'class' => 'underline cursor-pointer opacity-[67%] hover:opacity-[80%]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes->except('wire:navigate')),'class' => 'underline cursor-pointer opacity-[67%] hover:opacity-[80%]']); ?>
    <?php echo e($slot); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4c1c5c7a9cb759b3852c337ba5e47e86)): ?>
<?php $attributes = $__attributesOriginal4c1c5c7a9cb759b3852c337ba5e47e86; ?>
<?php unset($__attributesOriginal4c1c5c7a9cb759b3852c337ba5e47e86); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4c1c5c7a9cb759b3852c337ba5e47e86)): ?>
<?php $component = $__componentOriginal4c1c5c7a9cb759b3852c337ba5e47e86; ?>
<?php unset($__componentOriginal4c1c5c7a9cb759b3852c337ba5e47e86); ?>
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/src/../resources/views/components/elements/text-link.blade.php ENDPATH**/ ?>