<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use function Laravel\Folio\{middleware, name};
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Devdojo\Auth\Traits\HasConfigs;

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.heading','data' => ['text' => ($language->login->headline ?? 'No Heading'),'description' => ($language->login->subheadline ?? 'No Description'),'showSubheadline' => ($language->login->show_subheadline ?? false)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->login->headline ?? 'No Heading')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->login->subheadline ?? 'No Description')),'show_subheadline' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($language->login->show_subheadline ?? false))]); ?>
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

            <!--[if BLOCK]><![endif]--><?php if(config('devdojo.auth.settings.login_show_social_providers') && config('devdojo.auth.settings.social_providers_location') == 'top'): ?>
                <?php if (isset($component)) { $__componentOriginal153730c5f2e0c23f563de5c45f788222 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal153730c5f2e0c23f563de5c45f788222 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.social-providers','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.social-providers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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

            <form wire:submit="authenticate" class="space-y-5">

                <!--[if BLOCK]><![endif]--><?php if($showPasswordField): ?>
                    <?php if (isset($component)) { $__componentOriginala03d2edaa1f61071e85a03cf8fc6ace8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala03d2edaa1f61071e85a03cf8fc6ace8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.input-placeholder','data' => ['value' => ''.e($email).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.input-placeholder'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => ''.e($email).'']); ?>
                        <button type="button" data-auth="edit-email-button" wire:click="editIdentity" class="font-medium text-blue-500"><?php echo e(config('devdojo.auth.language.login.edit')); ?></button>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala03d2edaa1f61071e85a03cf8fc6ace8)): ?>
<?php $attributes = $__attributesOriginala03d2edaa1f61071e85a03cf8fc6ace8; ?>
<?php unset($__attributesOriginala03d2edaa1f61071e85a03cf8fc6ace8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala03d2edaa1f61071e85a03cf8fc6ace8)): ?>
<?php $component = $__componentOriginala03d2edaa1f61071e85a03cf8fc6ace8; ?>
<?php unset($__componentOriginala03d2edaa1f61071e85a03cf8fc6ace8); ?>
<?php endif; ?>
                <?php else: ?>
                    <!--[if BLOCK]><![endif]--><?php if($showIdentifierInput): ?>
                        <?php if (isset($component)) { $__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.input','data' => ['label' => config('devdojo.auth.language.login.email_address'),'type' => 'email','wire:model' => 'email','autofocus' => 'true','dataAuth' => 'email-input','id' => 'email','name' => 'email','autocomplete' => 'email','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.language.login.email_address')),'type' => 'email','wire:model' => 'email','autofocus' => 'true','data-auth' => 'email-input','id' => 'email','name' => 'email','autocomplete' => 'email','required' => true]); ?>
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
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($showSocialProviderInfo): ?>
                    <div class="p-4 text-sm border rounded-md bg-zinc-50 border-zinc-200">
                        <span><?php echo e(str_replace('__social_providers_list__', implode(', ', $userSocialProviders), config('devdojo.auth.language.login.social_auth_authenticated_message'))); ?></span>
                        <button wire:click="editIdentity" type="button" class="underline translate-x-0.5"><?php echo e(config('devdojo.auth.language.login.change_email')); ?></button>
                    </div>

                    <!--[if BLOCK]><![endif]--><?php if(!config('devdojo.auth.settings.login_show_social_providers')): ?>
                        <?php if (isset($component)) { $__componentOriginal153730c5f2e0c23f563de5c45f788222 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal153730c5f2e0c23f563de5c45f788222 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.social-providers','data' => ['socialProviders' => \Devdojo\Auth\Helper::getProvidersFromArray($userSocialProviders),'separator' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.social-providers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['socialProviders' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Devdojo\Auth\Helper::getProvidersFromArray($userSocialProviders)),'separator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <?php
                    $passwordFieldClasses = $showPasswordField ? 'flex flex-col gap-6' : 'hidden';
                ?>

                <div class="<?php echo e($passwordFieldClasses); ?>">
                    <?php if (isset($component)) { $__componentOriginal5d0c71f724c1ee21b213fbf90ab5beff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0c71f724c1ee21b213fbf90ab5beff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.input','data' => ['label' => config('devdojo.auth.language.login.password'),'type' => 'password','wire:model' => 'password','dataAuth' => 'password-input','id' => 'password','name' => 'password','autocomplete' => 'current-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.language.login.password')),'type' => 'password','wire:model' => 'password','data-auth' => 'password-input','id' => 'password','name' => 'password','autocomplete' => 'current-password']); ?>
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
                    <?php if (isset($component)) { $__componentOriginal72fb75785e8a9c476aed65c3131e2e9e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72fb75785e8a9c476aed65c3131e2e9e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.checkbox','data' => ['label' => config('devdojo.auth.language.login.remember_me'),'wire:model' => 'rememberMe','id' => 'remember-me','dataAuth' => 'remember-me-input']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('devdojo.auth.language.login.remember_me')),'wire:model' => 'rememberMe','id' => 'remember-me','data-auth' => 'remember-me-input']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72fb75785e8a9c476aed65c3131e2e9e)): ?>
<?php $attributes = $__attributesOriginal72fb75785e8a9c476aed65c3131e2e9e; ?>
<?php unset($__attributesOriginal72fb75785e8a9c476aed65c3131e2e9e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72fb75785e8a9c476aed65c3131e2e9e)): ?>
<?php $component = $__componentOriginal72fb75785e8a9c476aed65c3131e2e9e; ?>
<?php unset($__componentOriginal72fb75785e8a9c476aed65c3131e2e9e); ?>
<?php endif; ?>
                    <div class="flex items-center justify-between text-sm leading-5">
                        <?php if (isset($component)) { $__componentOriginal0a4d0d2280e431af2f3b66256442a2b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4d0d2280e431af2f3b66256442a2b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.text-link','data' => ['href' => ''.e(route('auth.password.request')).'','dataAuth' => 'forgot-password-link']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.text-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('auth.password.request')).'','data-auth' => 'forgot-password-link']); ?><?php echo e(config('devdojo.auth.language.login.forget_password')); ?> <?php echo $__env->renderComponent(); ?>
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
                </div>

                <?php if (isset($component)) { $__componentOriginale045ae3e208dea0492f04ea3df6736d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale045ae3e208dea0492f04ea3df6736d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.button','data' => ['type' => 'primary','dataAuth' => 'submit-button','rounded' => 'md','size' => 'md','submit' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'primary','data-auth' => 'submit-button','rounded' => 'md','size' => 'md','submit' => 'true']); ?>
                    <?php echo e(config('devdojo.auth.language.login.button')); ?>

                 <?php echo $__env->renderComponent(); ?>
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


            <!--[if BLOCK]><![endif]--><?php if(config('devdojo.auth.settings.registration_enabled', true)): ?>
                <div class="mt-3 space-x-0.5 text-sm leading-5 <?php if(config('devdojo.auth.settings.center_align_text')): ?><?php echo e('text-center'); ?><?php else: ?><?php echo e('text-left'); ?><?php endif; ?>" style="color:<?php echo e(config('devdojo.auth.appearance.color.text')); ?>">
                    <span class="opacity-[47%]"> <?php echo e(config('devdojo.auth.language.login.dont_have_an_account')); ?> </span>
                    <?php if (isset($component)) { $__componentOriginal0a4d0d2280e431af2f3b66256442a2b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a4d0d2280e431af2f3b66256442a2b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.text-link','data' => ['dataAuth' => 'register-link','href' => ''.e(route('auth.register')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.text-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-auth' => 'register-link','href' => ''.e(route('auth.register')).'']); ?><?php echo e(config('devdojo.auth.language.login.sign_up')); ?> <?php echo $__env->renderComponent(); ?>
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
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <?php if(config('devdojo.auth.settings.login_show_social_providers') && config('devdojo.auth.settings.social_providers_location') != 'top'): ?>
                <?php if (isset($component)) { $__componentOriginal153730c5f2e0c23f563de5c45f788222 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal153730c5f2e0c23f563de5c45f788222 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'auth::components.elements.social-providers','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth::elements.social-providers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/d46aefedb659b41e66b1dc635ae50d93.blade.php ENDPATH**/ ?>