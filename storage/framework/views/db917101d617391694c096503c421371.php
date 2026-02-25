<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'level' => 'h1',
    'title' => 'No Heading Title Entered',
    'description' => 'Be sure to include the description attribute',
    'align' => 'center'
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
    'level' => 'h1',
    'title' => 'No Heading Title Entered',
    'description' => 'Be sure to include the description attribute',
    'align' => 'center'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>


<div <?php echo e($attributes->class([
        'relative w-full',
        'text-left' => $align == 'left',
        'text-right' => $align == 'right',
        'text-center' => $align != 'left' && $align != 'right'
    ])); ?>>
    <<?php echo e($level); ?> class="text-3xl sm:text-4xl text-left md:text-center font-medium tracking-tighter lg:text-5xl"><?php echo $title; ?></<?php echo e($level); ?>>
    <p class="mt-4 text-sm sm:text-base font-medium text-left md:text-center md:text-balance text-zinc-500 <?php if($align == 'left'): ?><?php echo e('ml-auto'); ?><?php elseif($align == 'right'): ?><?php echo e('mr-auto'); ?><?php else: ?><?php echo e('mx-auto max-w-2xl'); ?><?php endif; ?>"><?php echo $description; ?></p>
</div><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/marketing/elements/heading.blade.php ENDPATH**/ ?>