<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <?php echo $__env->make('theme::partials.head', ['seo' => ($seo ?? null) ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body x-data class="flex flex-col min-h-screen overflow-x-hidden <?php if($bodyClass ?? false): ?><?php echo e($bodyClass); ?><?php endif; ?>" x-cloak>

    <?php if (isset($component)) { $__componentOriginal37f86377bdff423908fd085f6e736be2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37f86377bdff423908fd085f6e736be2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::marketing.elements.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('marketing.elements.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37f86377bdff423908fd085f6e736be2)): ?>
<?php $attributes = $__attributesOriginal37f86377bdff423908fd085f6e736be2; ?>
<?php unset($__attributesOriginal37f86377bdff423908fd085f6e736be2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37f86377bdff423908fd085f6e736be2)): ?>
<?php $component = $__componentOriginal37f86377bdff423908fd085f6e736be2; ?>
<?php unset($__componentOriginal37f86377bdff423908fd085f6e736be2); ?>
<?php endif; ?>

    <main class="flex-grow overflow-x-hidden">
        <?php echo e($slot); ?>

    </main>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('notifications');

$__html = app('livewire')->mount($__name, $__params, 'lw-4015807225-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php echo $__env->make('theme::partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('theme::partials.footer-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo e($javascript ?? ''); ?>


</body>
</html>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/layouts/marketing.blade.php ENDPATH**/ ?>