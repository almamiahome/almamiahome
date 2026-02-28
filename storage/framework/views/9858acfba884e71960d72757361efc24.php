<?php

use Devdojo\Auth\Traits\HasConfigs;
use Illuminate\Support\Facades\Password;
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

?>


        <?php if (isset($component)) { $__componentOriginal3f973831dd71576ffdd02e6771b8ded9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f973831dd71576ffdd02e6771b8ded9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.container','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

        <?php if (isset($component)) { $__componentOriginal24dc16c842e59ae7d21862f2f4f5fd63 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal24dc16c842e59ae7d21862f2f4f5fd63 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.heading','data' => ['text' => ($language->passwordResetRequest->headline ?? 'No Heading'),'description' => ($language->passwordResetRequest->subheadline ?? 'No Description'),'showSubheadline' => ($language->passwordResetRequest->show_subheadline ?? false)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->passwordResetRequest->headline ?? 'No Heading')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->passwordResetRequest->subheadline ?? 'No Description')),'show_subheadline' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->passwordResetRequest->show_subheadline ?? false))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal24dc16c842e59ae7d21862f2f4f5fd63)): ?>
<?php $attributes = $__attributesOriginal24dc16c842e59ae7d21862f2f4f5fd63; ?>
<?php unset($__attributesOriginal24dc16c842e59ae7d21862f2f4f5fd63); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal24dc16c842e59ae7d21862f2f4f5fd63)): ?>
<?php $component = $__componentOriginal24dc16c842e59ae7d21862f2f4f5fd63; ?>
<?php unset($__componentOriginal24dc16c842e59ae7d21862f2f4f5fd63); ?>
<?php endif; ?>

        <!--[if BLOCK]><![endif]--><?php if($emailSentMessage): ?>
            <div class="p-4 mb-2 bg-green-50 rounded-md dark:bg-green-600">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400 dark:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <div class="ml-3">
                        <p class="text-sm font-medium leading-5 text-green-800 dark:text-green-200">
                            <?php echo e($emailSentMessage); ?>

                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <form wire:submit="sendResetPasswordLink" class="space-y-5">
                <?php if (isset($component)) { $__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.input','data' => ['label' => config('devdojo.auth.language.passwordResetRequest.email'),'type' => 'email','id' => 'email','name' => 'email','dataAuth' => 'email-input','wire:model' => 'email','autofocus' => 'true','autocomplete' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.language.passwordResetRequest.email')),'type' => 'email','id' => 'email','name' => 'email','data-auth' => 'email-input','wire:model' => 'email','autofocus' => 'true','autocomplete' => 'email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff)): ?>
<?php $attributes = $__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff; ?>
<?php unset($__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff)): ?>
<?php $component = $__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff; ?>
<?php unset($__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginale045ae3e208dea0492f04ea3df6736d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale045ae3e208dea0492f04ea3df6736d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.button','data' => ['type' => 'primary','dataAuth' => 'submit-button','rounded' => 'md','submit' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'primary','data-auth' => 'submit-button','rounded' => 'md','submit' => 'true']); ?><?php echo e(config('devdojo.auth.language.passwordResetRequest.button')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale045ae3e208dea0492f04ea3df6736d1)): ?>
<?php $attributes = $__attributesOriginale045ae3e208dea0492f04ea3df6736d1; ?>
<?php unset($__attributesOriginale045ae3e208dea0492f04ea3df6736d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale045ae3e208dea0492f04ea3df6736d1)): ?>
<?php $component = $__componentOriginale045ae3e208dea0492f04ea3df6736d1; ?>
<?php unset($__componentOriginale045ae3e208dea0492f04ea3df6736d1); ?>
<?php endif; ?>
            </form>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <div class="mt-3 space-x-0.5 text-sm leading-5 text-center" style="color:<?php echo e(config('devdojo.auth.appearance.color.text')); ?>">
            <span class="opacity-[47%]"><?php echo e(config('devdojo.auth.language.passwordResetRequest.or')); ?></span>
            <?php if (isset($component)) { $__componentOriginal0a4d0d2280e431af2f3b66256442a2b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4d0d2280e431af2f3b66256442a2b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.text-link','data' => ['dataAuth' => 'login-link','href' => ''.e(route('auth.login')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.text-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-auth' => 'login-link','href' => ''.e(route('auth.login')).'']); ?><?php echo e(config('devdojo.auth.language.passwordResetRequest.return_to_login')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a4d0d2280e431af2f3b66256442a2b2)): ?>
<?php $attributes = $__attributesOriginal0a4d0d2280e431af2f3b66256442a2b2; ?>
<?php unset($__attributesOriginal0a4d0d2280e431af2f3b66256442a2b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a4d0d2280e431af2f3b66256442a2b2)): ?>
<?php $component = $__componentOriginal0a4d0d2280e431af2f3b66256442a2b2; ?>
<?php unset($__componentOriginal0a4d0d2280e431af2f3b66256442a2b2); ?>
<?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3f973831dd71576ffdd02e6771b8ded9)): ?>
<?php $attributes = $__attributesOriginal3f973831dd71576ffdd02e6771b8ded9; ?>
<?php unset($__attributesOriginal3f973831dd71576ffdd02e6771b8ded9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3f973831dd71576ffdd02e6771b8ded9)): ?>
<?php $component = $__componentOriginal3f973831dd71576ffdd02e6771b8ded9; ?>
<?php unset($__componentOriginal3f973831dd71576ffdd02e6771b8ded9); ?>
<?php endif; ?>
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/38a2320ba5fa8ab6eeb00028230e5e73.blade.php ENDPATH**/ ?>