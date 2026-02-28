<?php

use Devdojo\Auth\Models\SocialProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Devdojo\Auth\Helper;
use Devdojo\Auth\Traits\HasConfigs;
use function Laravel\Folio\{middleware, name};

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.heading','data' => ['text' => ($language->register->headline ?? 'No Heading'),'description' => ($language->register->subheadline ?? 'No Description'),'showSubheadline' => ($language->register->show_subheadline ?? false)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->register->headline ?? 'No Heading')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->register->subheadline ?? 'No Description')),'show_subheadline' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->register->show_subheadline ?? false))]); ?>
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
        <?php if (isset($component)) { $__componentOriginal5e3bc9e260ffc907e06641be57725bc5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5e3bc9e260ffc907e06641be57725bc5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.session-message','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.session-message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5e3bc9e260ffc907e06641be57725bc5)): ?>
<?php $attributes = $__attributesOriginal5e3bc9e260ffc907e06641be57725bc5; ?>
<?php unset($__attributesOriginal5e3bc9e260ffc907e06641be57725bc5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5e3bc9e260ffc907e06641be57725bc5)): ?>
<?php $component = $__componentOriginal5e3bc9e260ffc907e06641be57725bc5; ?>
<?php unset($__componentOriginal5e3bc9e260ffc907e06641be57725bc5); ?>
<?php endif; ?>

        <!--[if BLOCK]><![endif]--><?php if(config('devdojo.auth.settings.social_providers_location') == 'top'): ?>
            <?php if (isset($component)) { $__componentOriginal153730c5f2e0c23f563de5c45f788222 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal153730c5f2e0c23f563de5c45f788222 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.social-providers','data' => ['separator' => $showEmailRegistration]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.social-providers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['separator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showEmailRegistration)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal153730c5f2e0c23f563de5c45f788222)): ?>
<?php $attributes = $__attributesOriginal153730c5f2e0c23f563de5c45f788222; ?>
<?php unset($__attributesOriginal153730c5f2e0c23f563de5c45f788222); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal153730c5f2e0c23f563de5c45f788222)): ?>
<?php $component = $__componentOriginal153730c5f2e0c23f563de5c45f788222; ?>
<?php unset($__componentOriginal153730c5f2e0c23f563de5c45f788222); ?>
<?php endif; ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if($showEmailRegistration): ?>
        <form wire:submit="register" class="space-y-5">

            <!--[if BLOCK]><![endif]--><?php if($showNameField): ?>
            <?php if (isset($component)) { $__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.input','data' => ['label' => config('devdojo.auth.language.register.name'),'type' => 'text','wire:model' => 'name','autofocus' => 'true','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.language.register.name')),'type' => 'text','wire:model' => 'name','autofocus' => 'true','required' => true]); ?>
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
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($showEmailField): ?>
            <?php
            $autofocusEmail = ($showNameField) ? false : true;
            ?>
            <?php if (isset($component)) { $__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.input','data' => ['label' => config('devdojo.auth.language.register.email_address'),'id' => 'email','name' => 'email','type' => 'email','wire:model' => 'email','dataAuth' => 'email-input','autofocus' => $autofocusEmail,'autocomplete' => 'email','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.language.register.email_address')),'id' => 'email','name' => 'email','type' => 'email','wire:model' => 'email','data-auth' => 'email-input','autofocus' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($autofocusEmail),'autocomplete' => 'email','required' => true]); ?>
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
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($showPasswordField): ?>
            <?php if (isset($component)) { $__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.input','data' => ['label' => config('devdojo.auth.language.register.password'),'type' => 'password','wire:model' => 'password','id' => 'password','name' => 'password','dataAuth' => 'password-input','autocomplete' => 'new-password','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.language.register.password')),'type' => 'password','wire:model' => 'password','id' => 'password','name' => 'password','data-auth' => 'password-input','autocomplete' => 'new-password','required' => true]); ?>
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
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($showPasswordConfirmationField): ?>
            <?php if (isset($component)) { $__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.input','data' => ['label' => config('devdojo.auth.language.register.password_confirmation'),'type' => 'password','wire:model' => 'password_confirmation','id' => 'password_confirmation','name' => 'password_confirmation','dataAuth' => 'password-confirmation-input','autocomplete' => 'new-password','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.language.register.password_confirmation')),'type' => 'password','wire:model' => 'password_confirmation','id' => 'password_confirmation','name' => 'password_confirmation','data-auth' => 'password-confirmation-input','autocomplete' => 'new-password','required' => true]); ?>
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
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <?php if (isset($component)) { $__componentOriginale045ae3e208dea0492f04ea3df6736d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale045ae3e208dea0492f04ea3df6736d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.button','data' => ['dataAuth' => 'submit-button','rounded' => 'md','submit' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-auth' => 'submit-button','rounded' => 'md','submit' => 'true']); ?><?php echo e(config('devdojo.auth.language.register.button')); ?> <?php echo $__env->renderComponent(); ?>
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

        <div class="<?php if(config('devdojo.auth.settings.social_providers_location') != 'top' && $showEmailRegistration): ?><?php echo e('mt-3'); ?><?php endif; ?> space-x-0.5 text-sm leading-5 <?php if(config('devdojo.auth.settings.center_align_text')): ?><?php echo e('text-center'); ?><?php else: ?><?php echo e('text-left'); ?><?php endif; ?>" style="color:<?php echo e(config('devdojo.auth.appearance.color.text')); ?>">
            <span class="opacity-[47%]"><?php echo e(config('devdojo.auth.language.register.already_have_an_account')); ?></span>
            <?php if (isset($component)) { $__componentOriginal0a4d0d2280e431af2f3b66256442a2b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4d0d2280e431af2f3b66256442a2b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.text-link','data' => ['dataAuth' => 'login-link','href' => ''.e(route('auth.login')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.text-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-auth' => 'login-link','href' => ''.e(route('auth.login')).'']); ?><?php echo e(config('devdojo.auth.language.register.sign_in')); ?> <?php echo $__env->renderComponent(); ?>
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

        <?php if(config('devdojo.auth.settings.social_providers_location') != 'top'): ?>
            <?php if (isset($component)) { $__componentOriginal153730c5f2e0c23f563de5c45f788222 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal153730c5f2e0c23f563de5c45f788222 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.social-providers','data' => ['separator' => $showEmailRegistration]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.social-providers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['separator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showEmailRegistration)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal153730c5f2e0c23f563de5c45f788222)): ?>
<?php $attributes = $__attributesOriginal153730c5f2e0c23f563de5c45f788222; ?>
<?php unset($__attributesOriginal153730c5f2e0c23f563de5c45f788222); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal153730c5f2e0c23f563de5c45f788222)): ?>
<?php $component = $__componentOriginal153730c5f2e0c23f563de5c45f788222; ?>
<?php unset($__componentOriginal153730c5f2e0c23f563de5c45f788222); ?>
<?php endif; ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/65f6b72467108f89fcaf9ab8c962cab8.blade.php ENDPATH**/ ?>