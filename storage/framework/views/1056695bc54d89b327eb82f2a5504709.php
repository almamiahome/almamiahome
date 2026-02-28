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

    <?php if (isset($component)) { $__componentOriginala5f5a12ac664d74a73009d0c66568c8d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5f5a12ac664d74a73009d0c66568c8d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::elements.back-button','data' => ['class' => 'max-w-3xl mx-auto mt-4 md:mt-8','text' => 'Return Back Home','href' => route('home')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elements.back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-w-3xl mx-auto mt-4 md:mt-8','text' => 'Return Back Home','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('home'))]); ?>
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
    
    <article id="post-<?php echo e($page['id']); ?>" class="max-w-3xl px-5 mx-auto mb-32 prose prose-lg lg:prose-xl lg:px-0">

        <meta property="name" content="<?php echo e($page['title']); ?>">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="<?php echo e(Carbon\Carbon::parse($page['updated_at'])->toIso8601String()); ?>">
        <meta property="datePublished" content="<?php echo e(Carbon\Carbon::parse($page['created_at'])->toIso8601String()); ?>">

        <div class="max-w-4xl mx-auto mt-6">

            <h1 class="flex flex-col leading-none">
                <span><?php echo e($page['title']); ?></span>
                
            </h1>

        </div>

        <?php if($page['image']): ?>
            <div class="relative">
                <img class="w-full h-auto rounded-lg" src="<?php echo e(url($page['image'])); ?>" alt="<?php echo e(url($page['image'])); ?>" srcset="<?php echo e(url($page['image'])); ?>">
            </div>
        <?php endif; ?>

        <div class="max-w-4xl mx-auto">
            <?php echo $page['body']; ?>

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/page.blade.php ENDPATH**/ ?>