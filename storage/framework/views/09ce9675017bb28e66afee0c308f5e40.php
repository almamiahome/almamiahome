<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'align' => 'center',
    'text' => 'Heading Text',
    'description' => '',
    'show_subheadline' => false
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
    'align' => 'center',
    'text' => 'Heading Text',
    'description' => '',
    'show_subheadline' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $heading_alignment = config('devdojo.auth.appearance.alignment.heading');
?>

<div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'flex flex-col sm:mx-auto sm:w-full mb-5 sm:max-w-md',
        'items-start' => $heading_alignment == 'left',
        'items-center' => $heading_alignment == 'center',
        'items-end' => $heading_alignment == 'right'
        
    ]); ?>"
    id="auth-heading-container"
    style="color:<?php echo e(config('devdojo.auth.appearance.color.text')); ?>"
    >
    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'flex flex-col w-full',
        'items-start' => $heading_alignment == 'left',
        'items-center' => $heading_alignment == 'center',
        'items-end' => $heading_alignment == 'right',
    ]); ?>"
>
        <?php if (isset($component)) { $__componentOriginal750c918a0b7b8bc9e85b7a3437026879 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal750c918a0b7b8bc9e85b7a3437026879 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.logo','data' => ['height' => config('devdojo.auth.appearance.logo.height'),'isImage' => (config('devdojo.auth.appearance.logo.type') == 'image'),'imageSrc' => config('devdojo.auth.appearance.logo.image_src'),'svgString' => config('devdojo.auth.appearance.logo.svg_string')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['height' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.appearance.logo.height')),'isImage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((config('devdojo.auth.appearance.logo.type') == 'image')),'imageSrc' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.appearance.logo.image_src')),'svgString' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.appearance.logo.svg_string'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal750c918a0b7b8bc9e85b7a3437026879)): ?>
<?php $attributes = $__attributesOriginal750c918a0b7b8bc9e85b7a3437026879; ?>
<?php unset($__attributesOriginal750c918a0b7b8bc9e85b7a3437026879); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal750c918a0b7b8bc9e85b7a3437026879)): ?>
<?php $component = $__componentOriginal750c918a0b7b8bc9e85b7a3437026879; ?>
<?php unset($__componentOriginal750c918a0b7b8bc9e85b7a3437026879); ?>
<?php endif; ?>
    </div>
    <h1 id="auth-heading-title" class="mt-1 text-xl font-medium leading-9"><?php echo e($text ?? ''); ?></h1>
    <!--[if BLOCK]><![endif]--><?php if(($description ?? false) && $show_subheadline): ?>
        <p id="auth-heading-description" class="mb-1.5 space-x-0.5 text-sm leading-5 text-center opacity-[67%]"><?php echo e($description ?? ''); ?></p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/src/../resources/views/components/elements/heading.blade.php ENDPATH**/ ?>