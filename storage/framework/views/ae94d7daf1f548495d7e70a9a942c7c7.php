<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'socialProviders' => \Devdojo\Auth\Helper::activeProviders(),
    'separator' => true,
    'separator_text' => 'or'
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
    'socialProviders' => \Devdojo\Auth\Helper::activeProviders(),
    'separator' => true,
    'separator_text' => 'or'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<!--[if BLOCK]><![endif]--><?php if(count($socialProviders)): ?>
    <!--[if BLOCK]><![endif]--><?php if($separator && config('devdojo.auth.settings.social_providers_location') != 'top'): ?>
        <?php if (isset($component)) { $__componentOriginale7f1ea1a1403195260f3e2a2d00f7a0c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale7f1ea1a1403195260f3e2a2d00f7a0c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.separator','data' => ['class' => 'my-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'my-6']); ?><?php echo e($separator_text); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale7f1ea1a1403195260f3e2a2d00f7a0c)): ?>
<?php $attributes = $__attributesOriginale7f1ea1a1403195260f3e2a2d00f7a0c; ?>
<?php unset($__attributesOriginale7f1ea1a1403195260f3e2a2d00f7a0c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7f1ea1a1403195260f3e2a2d00f7a0c)): ?>
<?php $component = $__componentOriginale7f1ea1a1403195260f3e2a2d00f7a0c; ?>
<?php unset($__componentOriginale7f1ea1a1403195260f3e2a2d00f7a0c); ?>
<?php endif; ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <div class="relative space-y-2 w-full <?php if(config('devdojo.auth.settings.social_providers_location') != 'top' && !$separator): ?><?php echo e('mt-3'); ?><?php endif; ?>">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $socialProviders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal4f9808c6fe8ae46d5c3b1a7c8164925f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4f9808c6fe8ae46d5c3b1a7c8164925f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.social-button','data' => ['slug' => $slug,'provider' => $provider]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.social-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['slug' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($slug),'provider' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($provider)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4f9808c6fe8ae46d5c3b1a7c8164925f)): ?>
<?php $attributes = $__attributesOriginal4f9808c6fe8ae46d5c3b1a7c8164925f; ?>
<?php unset($__attributesOriginal4f9808c6fe8ae46d5c3b1a7c8164925f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4f9808c6fe8ae46d5c3b1a7c8164925f)): ?>
<?php $component = $__componentOriginal4f9808c6fe8ae46d5c3b1a7c8164925f; ?>
<?php unset($__componentOriginal4f9808c6fe8ae46d5c3b1a7c8164925f); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    <?php if($separator && config('devdojo.auth.settings.social_providers_location') == 'top'): ?>
        <?php if (isset($component)) { $__componentOriginale7f1ea1a1403195260f3e2a2d00f7a0c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale7f1ea1a1403195260f3e2a2d00f7a0c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.separator','data' => ['class' => 'my-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'my-6']); ?><?php echo e($separator_text); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale7f1ea1a1403195260f3e2a2d00f7a0c)): ?>
<?php $attributes = $__attributesOriginale7f1ea1a1403195260f3e2a2d00f7a0c; ?>
<?php unset($__attributesOriginale7f1ea1a1403195260f3e2a2d00f7a0c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7f1ea1a1403195260f3e2a2d00f7a0c)): ?>
<?php $component = $__componentOriginale7f1ea1a1403195260f3e2a2d00f7a0c; ?>
<?php unset($__componentOriginale7f1ea1a1403195260f3e2a2d00f7a0c); ?>
<?php endif; ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/src/../resources/views/components/elements/social-providers.blade.php ENDPATH**/ ?>