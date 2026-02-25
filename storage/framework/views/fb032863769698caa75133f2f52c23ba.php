<a href="/" style="height:<?php echo e($height ?? '30'); ?>px; width:auto; display:block" aria-label="<?php echo e(config('app.name')); ?> Logo">
    <!--[if BLOCK]><![endif]--><?php if($isImage): ?>
        <img src="<?php echo e(url($imageSrc)); ?>" style="height:100%; width:auto" alt="" />
    <?php else: ?>
        <?php echo str_replace('<svg', '<svg style="height:100%; width:auto"', $svgString); ?>

    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</a><?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/src/../resources/views/components/elements/logo.blade.php ENDPATH**/ ?>