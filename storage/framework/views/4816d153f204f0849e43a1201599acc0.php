<?php
    use function Laravel\Folio\{name};
    name('blog.post');
?>

<?php if (isset($component)) { $__componentOriginalf103f87f9e6975b672a2453f5462c100 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf103f87f9e6975b672a2453f5462c100 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::layouts.marketing','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.marketing'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <article id="post-<?php echo e($post->id); ?>" class="max-w-4xl px-5 pb-20 mx-auto prose prose-md dark:prose-invert lg:prose-lg lg:px-0">

        <?php if (isset($component)) { $__componentOriginala5f5a12ac664d74a73009d0c66568c8d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5f5a12ac664d74a73009d0c66568c8d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::elements.back-button','data' => ['class' => 'max-w-4xl mx-auto mt-4 md:mt-8','text' => 'back to the blog','href' => route('blog')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elements.back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-w-4xl mx-auto mt-4 md:mt-8','text' => 'back to the blog','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('blog'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5f5a12ac664d74a73009d0c66568c8d)): ?>
<?php $attributes = $__attributesOriginala5f5a12ac664d74a73009d0c66568c8d; ?>
<?php unset($__attributesOriginala5f5a12ac664d74a73009d0c66568c8d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5f5a12ac664d74a73009d0c66568c8d)): ?>
<?php $component = $__componentOriginala5f5a12ac664d74a73009d0c66568c8d; ?>
<?php unset($__componentOriginala5f5a12ac664d74a73009d0c66568c8d); ?>
<?php endif; ?>

        <meta property="name" content="<?php echo e($post->title); ?>">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="<?php echo e(Carbon\Carbon::parse($post->updated_at)->toIso8601String()); ?>">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="<?php echo e(Carbon\Carbon::parse($post->created_at)->toIso8601String()); ?>">

        <div class="max-w-4xl mx-auto mt-6">

            <h1 class="flex flex-col leading-none">
                <span><?php echo e($post->title); ?></span>
                
            </h1>


        </div>

        <div class="relative">
            <img class="w-full h-auto rounded-lg" src="<?php echo e($post->image()); ?>" alt="<?php echo e($post->title); ?>" srcset="<?php echo e($post->image()); ?>">
        </div>

        <div class="max-w-4xl mx-auto">
            <?php echo $post->body; ?>

        </div>

    </article>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf103f87f9e6975b672a2453f5462c100)): ?>
<?php $attributes = $__attributesOriginalf103f87f9e6975b672a2453f5462c100; ?>
<?php unset($__attributesOriginalf103f87f9e6975b672a2453f5462c100); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf103f87f9e6975b672a2453f5462c100)): ?>
<?php $component = $__componentOriginalf103f87f9e6975b672a2453f5462c100; ?>
<?php unset($__componentOriginalf103f87f9e6975b672a2453f5462c100); ?>
<?php endif; ?>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/blog/[.Wave.Category-slug]/[.Wave.Post-slug].blade.php ENDPATH**/ ?>