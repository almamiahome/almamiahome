<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'autofocus' => false
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
    'id' => null,
    'name' => null,
    'type' => 'text',
    'autofocus' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php $wireModel = $attributes->get('wire:model'); ?>

<div x-data="{ 
        focusedOrFilled: false,
        focused(){
            this.focusedOrFilled=true;
        },
        blurred() {
            if(this.$refs.input.value == ''){
                this.focusedOrFilled=false;
            }
        }
    }" x-init="<?php if($autofocus ?? false): ?> setTimeout(function(){ $refs.input.focus(); }, 1); <?php endif; ?> " class="w-full h-auto">
    <div class="flex relative flex-col justify-center h-11">
        <div class="flex relative">
            <!--[if BLOCK]><![endif]--><?php if($label): ?>
                <label 
                    for="<?php echo e($id ?? ''); ?>"
                    @click="$refs.input.focus()"
                    :class="{ 'top-0 -translate-y-1 ml-2 text-xs auth-component-input-label-focused' : focusedOrFilled, 'top-[16px] ml-2.5 text-[15px] text-gray-500' : !focusedOrFilled }"
                    class="block absolute top-0 px-1.5 py-0 font-normal leading-normal bg-white duration-300 ease-out cursor-text auth-component-input dark:text-gray-300" x-cloak>
                    <?php echo e($label); ?>

                </label>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <div data-model="<?php echo e($wireModel); ?>" class="mt-1.5 w-full rounded-md shadow-sm auth-component-input-container">
                <input <?php echo e($attributes); ?> <?php echo e($attributes->whereStartsWith('wire:model')); ?> @focus-<?php echo e($id); ?>.window="$el.focus()" id="<?php echo e($id ?? ''); ?>" name="<?php echo e($name ?? ''); ?>" type="<?php echo e($type ?? ''); ?>" x-ref="input" @focus="focused()" @blur="blurred()" class="auth-component-input appearance-none flex w-full h-11 px-3.5 text-sm bg-white border rounded-md border-gray-300 ring-offset-background placeholder:text-gray-500 focus:outline-none focus:ring-1 focus:ring-zinc-800 disabled:cursor-not-allowed disabled:opacity-50 <?php $__errorArgs = [$wireModel];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
            </div>
        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$wireModel];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="my-2 text-sm text-red-600"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/src/../resources/views/components/elements/input.blade.php ENDPATH**/ ?>