<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'circular' => true,
	'alt' => null,
    'size' => 'md',
	'indicator' => false,
	'badge' => false,
	'badgeText' => null,
	'groupSrcs' => null,
    'src' => '',
	'srcset' => '',
	'user' => auth()->user()
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
    'circular' => true,
	'alt' => null,
    'size' => 'md',
	'indicator' => false,
	'badge' => false,
	'badgeText' => null,
	'groupSrcs' => null,
    'src' => '',
	'srcset' => '',
	'user' => auth()->user()
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
	$avatarSize = match($size)
	{
		'2xs' => 'size-6',
		'xs' => 'size-8',
		'sm' => 'size-12',
		'md' => 'size-16',
		'lg' => 'size-24',
		'xl' => 'size-32',
		default => 'size-16',
	};

	$groupMargin = match($size){
		'xs' => '-ml-3',
		'sm' => '-ml-5',
		'md' => '-ml-7',
		default => '-ml-7',
	};
?>

<div
	class="<?php echo \Illuminate\Support\Arr::toCssClasses([
		$avatarSize,
		'relative' => empty($groupSrcs),
		'flex' => !empty($groupSrcs)
	]); ?>"
	>
	<!--[if BLOCK]><![endif]--><?php if(filter_var($badge, FILTER_VALIDATE_BOOLEAN) && ($size == 'sm' || $size == 'xs') && !empty($badgeText)): ?>
		<?php if (isset($component)) { $__componentOriginal986dce9114ddce94a270ab00ce6c273d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal986dce9114ddce94a270ab00ce6c273d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.badge','data' => ['class' => \Illuminate\Support\Arr::toCssClasses([
				'absolute size-6 !rounded-full bg-purple-600 border-white border-2 right-0 !p-0 text-white',
				'translate-x-1/2 -translate-y-1/2' => $size == 'xs',
				'translate-x-1/3 -translate-y-1/3' => $size == 'sm',
			])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
				'absolute size-6 !rounded-full bg-purple-600 border-white border-2 right-0 !p-0 text-white',
				'translate-x-1/2 -translate-y-1/2' => $size == 'xs',
				'translate-x-1/3 -translate-y-1/3' => $size == 'sm',
			]))]); ?>
			<?php echo e($badgeText); ?>

		 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal986dce9114ddce94a270ab00ce6c273d)): ?>
<?php $attributes = $__attributesOriginal986dce9114ddce94a270ab00ce6c273d; ?>
<?php unset($__attributesOriginal986dce9114ddce94a270ab00ce6c273d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal986dce9114ddce94a270ab00ce6c273d)): ?>
<?php $component = $__componentOriginal986dce9114ddce94a270ab00ce6c273d; ?>
<?php unset($__componentOriginal986dce9114ddce94a270ab00ce6c273d); ?>
<?php endif; ?>
	<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

	<!--[if BLOCK]><![endif]--><?php if(!empty($groupSrcs)): ?>
		<?php for($i = 0; $i < count($groupSrcs); $i++): ?>
			<!--[if BLOCK]><![endif]--><?php if($i >= 4): ?>
				<span
					class="<?php echo \Illuminate\Support\Arr::toCssClasses([
						'flex rounded-full size-full justify-center items-center text-white shrink-0 font-semibold bg-berry-blue',
						$avatarSize,
						$groupMargin,
						'text-xs' => $size == '2xs',
						'text-sm' => $size == 'xs',
						'text-xl' => $size == 'sm',
						'text-2xl' => $size == 'md',
						'text-4xl' => $size == 'lg',
						'text-5xl' => $size == 'xl',
					]); ?>"
				>
					<?php echo e('+' . (count($groupSrcs) - $i)); ?>

				</span>
				<?php break; ?>
			<?php else: ?>
				<?php if (isset($component)) { $__componentOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.avatar','data' => ['src' => $groupSrcs[$i]['src'],'srcset' => $groupSrcs[$i]['srcset'],'alt' => ($groupSrcs[$i]['alt'] ?? $alt),'size' => $avatarSize,'circular' => $circular,'class' => (($i != 0) ? $groupMargin : '')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($groupSrcs[$i]['src']),'srcset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($groupSrcs[$i]['srcset']),'alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($groupSrcs[$i]['alt'] ?? $alt)),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarSize),'circular' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($circular),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((($i != 0) ? $groupMargin : ''))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb)): ?>
<?php $attributes = $__attributesOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb; ?>
<?php unset($__attributesOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb)): ?>
<?php $component = $__componentOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb; ?>
<?php unset($__componentOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb); ?>
<?php endif; ?>
			<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

		<?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
	<?php else: ?>
		<?php if (isset($component)) { $__componentOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.avatar','data' => ['xData' => '{ src: \'\', refreshAvatarSrc(){ this.src=\''.e($src).'\' + \'?\' + new Date().getTime() } }','xInit' => 'refreshAvatarSrc(); $nextTick(function(){ $el.style.display=\'block\'; })','@refreshAvatar.window' => 'refreshAvatarSrc()','xBind:src' => 'src','alt' => $alt,'size' => $avatarSize,'circular' => $circular,'style' => 'display:none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-data' => '{ src: \'\', refreshAvatarSrc(){ this.src=\''.e($src).'\' + \'?\' + new Date().getTime() } }','x-init' => 'refreshAvatarSrc(); $nextTick(function(){ $el.style.display=\'block\'; })','@refresh-avatar.window' => 'refreshAvatarSrc()','x-bind:src' => 'src','alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($alt),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($avatarSize),'circular' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($circular),'style' => 'display:none']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb)): ?>
<?php $attributes = $__attributesOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb; ?>
<?php unset($__attributesOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb)): ?>
<?php $component = $__componentOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb; ?>
<?php unset($__componentOriginal7aa0b6b1aa4a6b63824d7be5e541d1cb); ?>
<?php endif; ?>
	<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(filter_var($indicator, FILTER_VALIDATE_BOOLEAN) && $size != '2xs'): ?>
		<span
			class="<?php echo \Illuminate\Support\Arr::toCssClasses([
				'absolute bottom-0 right-0 rounded-full border-white bg-green-500',
				'size-2.5 border' => $size == 'xs',
				'size-3.5 border' => $size == 'sm',
				'size-5 border-2' => $size == 'md',
				'size-7 border-2' => $size == 'lg',
				'size-9 border-2' => $size == 'xl',
			]); ?>"
		></span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/unquxtyh/public_html/resources/views/components/avatar.blade.php ENDPATH**/ ?>