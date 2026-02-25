<?php echo \Filament\Support\Facades\FilamentAsset::renderScripts() ?>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

<?php if(config('wave.dev_bar')): ?>
    <?php echo $__env->make('theme::partials.dev_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>



<?php if(setting('site.google_analytics_tracking_id', '')): ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(setting('site.google_analytics_tracking_id')); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?php echo e(setting("site.google_analytics_tracking_id")); ?>');
    </script>
<?php endif; ?>

<?php
    $customJs = trim(setting('custom.js'));
?>
<?php if($customJs !== ''): ?>
    
    <script><?php echo $customJs; ?></script>
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/partials/footer-scripts.blade.php ENDPATH**/ ?>