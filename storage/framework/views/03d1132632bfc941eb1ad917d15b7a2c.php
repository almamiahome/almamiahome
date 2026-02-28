<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title><?php echo e($title ?? 'Auth'); ?></title>
<?php if(config('devdojo.auth.settings.dev_mode')): ?>
    <?php echo app('Illuminate\Foundation\Vite')(['packages/devdojo/auth/resources/css/auth.css', 'packages/devdojo/auth/resources/css/auth.js']); ?>
<?php else: ?>
    <script src="<?php echo e(asset('/auth/build/assets/scripts.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('/auth/build/assets/styles.css')); ?>" />
<?php endif; ?>

<?php
    $buttonRGBColor = \Devdojo\Auth\Helper::convertHexToRGBString(config('devdojo.auth.appearance.color.button'));
    $inputBorderRGBColor = \Devdojo\Auth\Helper::convertHexToRGBString(config('devdojo.auth.appearance.color.input_border'));
?>
<style>
    .auth-component-button:focus{
        --tw-ring-opacity: 1; --tw-ring-color: rgb(<?php echo e($buttonRGBColor); ?> / var(--tw-ring-opacity));
    }
    .auth-component-input{
        color: <?php echo e(config('devdojo.auth.appearance.color.input_text')); ?>

    }
    .auth-component-input:focus, .auth-component-code-input:focus{
        --tw-ring-color: rgb(<?php echo e($inputBorderRGBColor); ?> / var(--tw-ring-opacity));
        border-color: rgb(<?php echo e($inputBorderRGBColor); ?> / var(--tw-border-opacity));
    }
    .auth-component-input-label-focused{
        color: <?php echo e(config('devdojo.auth.appearance.color.input_border')); ?>

    }
</style>

<?php if(file_exists(public_path('auth/app.css'))): ?>
    <link rel="stylesheet" href="/auth/app.css" />
<?php endif; ?>

<link href="<?php echo e(url(config('devdojo.auth.appearance.favicon.light'))); ?>" rel="icon" media="(prefers-color-scheme: light)" />
<link href="<?php echo e(url(config('devdojo.auth.appearance.favicon.dark'))); ?>" rel="icon" media="(prefers-color-scheme: dark)" />

<?php echo $__env->yieldPushContent('devdojo-auth-head-scripts'); ?>
<?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/src/../resources/views/includes/head.blade.php ENDPATH**/ ?>