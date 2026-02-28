<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => '',
    'description' => '',
    'border' => true
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
    'title' => '',
    'description' => '',
    'border' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="<?php if($border): ?><?php echo e('pb-5 border-b border-gray-200 dark:border-gray-800'); ?><?php endif; ?> space-y-0.5">
    <h3 class="text-lg sm:text-xl font-semibold tracking-tight dark:text-zinc-100"><?php echo e($title ?? ''); ?></h3>
    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400"><?php echo e($description ?? ''); ?></p>
</div><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/app/heading.blade.php ENDPATH**/ ?>