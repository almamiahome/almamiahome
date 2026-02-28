<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'name' => null,
    'id' => null,
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
    'label' => null,
    'name' => null,
    'id' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex items-center h-5">
    <input type="checkbox" <?php echo e($attributes->whereStartsWith('wire:model')); ?> id="<?php echo e($id ?? ''); ?>" name="<?php echo e($name ?? ''); ?>" class="hidden peer">
    <label for="<?php echo e($id ?? ''); ?>" class="peer-checked:[&_svg]:scale-100 text-sm font-medium text-neutral-600 dark:text-neutral-400 dark:peer-checked:text-gray-300 peer-checked:text-gray-800 [&_svg]:scale-0 peer-checked:[&_.custom-checkbox]:border-gray-800 peer-checked:[&_.custom-checkbox]:bg-gray-800 dark:peer-checked:[&_.custom-checkbox]:bg-white select-none flex items-center space-x-2">
        <span class="flex justify-center items-center w-5 h-5 rounded border border-gray-300 dark:border-gray-700 custom-checkbox text-neutral-900">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-white duration-300 ease-out dark:text-gray-800">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </span>
        <span><?php echo e($label ?? ''); ?></span>
    </label>
</div><?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/src/../resources/views/components/elements/checkbox.blade.php ENDPATH**/ ?>