<div
    x-data="{
        step: 1,
        maxStep: 3,
        show: <?php if ((object) ('show') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('show'->value()); ?>')<?php echo e('show'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('show'); ?>')<?php endif; ?>.live,
        init() {
            if (this.show) {
                this.enforceLightMode();
            }
        },
        enforceLightMode() {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        },
    }"
    x-init="init()"
    x-effect="show ? enforceLightMode() : null"
>
    <!--[if BLOCK]><![endif]--><?php if($show): ?>
        <?php
            // Arrays JSON desde settings (Wave helpers)
            $departamentos = json_decode(setting('almamia.departamentos.mendoza') ?? '[]', true) ?? [];
            $zonas = json_decode(setting('almamia.zona.mendoza') ?? '[]', true) ?? [];

            if (!is_array($departamentos)) {
                $departamentos = [];
            }
            if (!is_array($zonas)) {
                $zonas = [];
            }
        ?>

        <div class="fixed inset-0 z-[9999] flex items-center justify-center overflow-y-auto px-4 py-8 sm:py-12">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm"></div>

            <div class="relative z-10 w-full max-w-3xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-zinc-900 sm:p-8 md:max-h-[90vh] md:overflow-y-auto">
                
                <div class="mb-6 text-center">
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#294395] sm:text-sm">
                        Paso <span x-text="step"></span> de <span x-text="maxStep"></span>
                    </p>
                    <h2 class="mt-3 text-2xl font-bold text-slate-900 dark:text-white sm:text-3xl">
                        Bienvenida a AlmaMia
                    </h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 sm:text-base">
                        Necesitamos algunos datos básicos para personalizar tu experiencia y conectarte con tu zona.
                    </p>
                </div>

                
                <div class="mb-6 space-y-3">
    
    <div class="relative h-2 w-full rounded-full bg-slate-200/80 overflow-hidden">
        <div
            class="absolute inset-y-0 left-0 rounded-full bg-[#d54794] transition-all duration-300 ease-out"
            :style="{ width: (step / maxStep * 100) + '%' }"
        ></div>
    </div>

    
    <div class="flex items-center justify-between text-[11px] sm:text-xs font-medium text-slate-500">
        <div class="flex flex-col items-start">
            <span :class="step >= 1 ? 'text-[#294395]' : ''">
                Paso 1
            </span>
            <span class="hidden sm:inline text-[11px] text-slate-400">
                Datos personales
            </span>
        </div>

        <div class="flex flex-col items-center">
            <span :class="step >= 2 ? 'text-[#294395]' : ''">
                Paso 2
            </span>
            <span class="hidden sm:inline text-[11px] text-slate-400">
                Dirección
            </span>
        </div>

        <div class="flex flex-col items-end">
            <span :class="step >= 3 ? 'text-[#294395]' : ''">
                Paso 3
            </span>
            <span class="hidden sm:inline text-[11px] text-slate-400">
                Rol y red
            </span>
        </div>
    </div>
</div>


                <form wire:submit.prevent="save" class="space-y-5">
                    
                    <div x-show="step === 1" x-cloak class="space-y-5">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-name">
                                Nombre completo
                            </label>
                            <input
                                id="onboarding-name"
                                type="text"
                                wire:model.defer="name"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Ej: Ana Pérez"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-dni">
                                DNI
                            </label>
                            <input
                                id="onboarding-dni"
                                type="text"
                                wire:model.defer="dni"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Sin puntos ni espacios"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['dni'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-whatsapp">
                                WhatsApp
                            </label>
                            <input
                                id="onboarding-whatsapp"
                                type="text"
                                wire:model.defer="whatsapp"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Número con código de área"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div x-show="step === 2" x-cloak class="space-y-5">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-direccion">
                                Dirección
                            </label>
                            <input
                                id="onboarding-direccion"
                                type="text"
                                wire:model.defer="direccion"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Calle y número"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-departamento">
                                Departamento
                            </label>
                            <select
                                id="onboarding-departamento"
                                wire:model.defer="departamento"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                            >
                                <option value="">Elegí un departamento</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $departamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item); ?>"><?php echo e($item); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['departamento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-zona">
                                Zona
                            </label>
                            <select
                                id="onboarding-zona"
                                wire:model.defer="zona"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                            >
                                <option value="">Elegí una zona</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $zonas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item); ?>"><?php echo e($item); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['zona'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div x-show="step === 3" x-cloak class="space-y-8">
                        
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                ¿Qué rol querés ocupar?
                            </label>
                            <p class="mb-3 text-sm text-slate-500 dark:text-slate-400">
                                Elegí si te sumarás como vendedora o como líder. Esto nos permite mostrarte los módulos correctos.
                            </p>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-[#294395]',
                                    'border-[#294395] bg-[#294395]/5 text-[#294395]' => $role === 'vendedora',
                                ]); ?>">
                                    <input
                                        type="radio"
                                        class="mt-1"
                                        name="onboarding-role"
                                        value="vendedora"
                                        wire:model.live="role"
                                    >
                                    <span>
                                        Vendedora
                                        <span class="block text-xs font-normal text-slate-500">Accedés al catálogo y cargás tus pedidos.</span>
                                    </span>
                                </label>
                                <label class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-[#294395]',
                                    'border-[#294395] bg-[#294395]/5 text-[#294395]' => $role === 'lider',
                                ]); ?>">
                                    <input
                                        type="radio"
                                        class="mt-1"
                                        name="onboarding-role"
                                        value="lider"
                                        wire:model.live="role"
                                    >
                                    <span>
                                        Líder
                                        <span class="block text-xs font-normal text-slate-500">Gestionás a tu red y acompañás sus ventas.</span>
                                    </span>
                                </label>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Podés cambiar tu rol más adelante junto con tu coordinadora si es necesario.
                            </p>
                        </div>

                        
                        <div class="space-y-5">
                            <!--[if BLOCK]><![endif]--><?php if($role === 'vendedora'): ?>
                                <div class="space-y-3">
                                    <div id="lider-input-group">
                                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-lider">
                                            ID de tu líder
                                        </label>
                                        <input
                                            id="onboarding-lider"
                                            type="number"
                                            wire:model.live="lider_id"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                            placeholder="Ingresá el ID exacto de quien te acompaña"
                                        >
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            El ID debe existir en AlmaMia; solicitá a tu líder que te lo comparta.
                                        </p>

                                        <!--[if BLOCK]><![endif]--><?php if($liderSeleccionado): ?>
                                            <p class="mt-2 text-xs text-emerald-600 dark:text-emerald-400">
                                                Te estás uniendo con:
                                                <span class="font-semibold"><?php echo e($liderSeleccionado['name']); ?></span>
                                                (ID <?php echo e($liderSeleccionado['id']); ?>)
                                            </p>
                                        <?php elseif($lider_id): ?>
                                            <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                                                No encontramos una líder con ese ID. Revisalo con tu líder antes de continuar.
                                            </p>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['lider_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <div id="lider-select-group" class="hidden">
                                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-lider-select">
                                            Seleccioná a tu líder disponible
                                        </label>
                                        <select
                                            id="onboarding-lider-select"
                                            wire:model.defer="lider_id"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                        >
                                            <option value="">Elegí una líder</option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $lideresDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($lider['id']); ?>"><?php echo e($lider['name']); ?> (ID <?php echo e($lider['id']); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            Usá este listado solo si verificaste el nombre de tu líder.
                                        </p>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['lider_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if($role === 'lider'): ?>
                                <div class="space-y-3">
                                    <div id="coordinadora-input-group">
                                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-coordinadora">
                                            ID de tu coordinadora
                                        </label>
                                        <input
                                            id="onboarding-coordinadora"
                                            type="number"
                                            wire:model.live="coordinadora_id"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                            placeholder="Ingresá el ID exacto de tu coordinadora"
                                        >
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            Verificamos que exista para mantener la red actualizada.
                                        </p>

                                        <!--[if BLOCK]><![endif]--><?php if($coordinadoraSeleccionada): ?>
                                            <p class="mt-2 text-xs text-emerald-600 dark:text-emerald-400">
                                                Tu coordinadora es:
                                                <span class="font-semibold"><?php echo e($coordinadoraSeleccionada['name']); ?></span>
                                                (ID <?php echo e($coordinadoraSeleccionada['id']); ?>)
                                            </p>
                                        <?php elseif($coordinadora_id): ?>
                                            <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                                                No encontramos una coordinadora con ese ID. Revisalo con tu coordinadora antes de continuar.
                                            </p>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['coordinadora_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <div id="coordinadora-select-group" class="hidden">
                                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-coordinadora-select">
                                            Seleccioná a tu coordinadora disponible
                                        </label>
                                        <select
                                            id="onboarding-coordinadora-select"
                                            wire:model.defer="coordinadora_id"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                        >
                                            <option value="">Elegí una coordinadora</option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $coordinadorasDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coordinadora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($coordinadora['id']); ?>"><?php echo e($coordinadora['name']); ?> (ID <?php echo e($coordinadora['id']); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            Usá este listado solo si verificaste el nombre de tu coordinadora.
                                        </p>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['coordinadora_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if(!$role): ?>
                                <p class="text-sm text-amber-600 dark:text-amber-400">
                                    Primero seleccioná tu rol para definir si necesitás líder o coordinadora.
                                </p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    

<div class="flex flex-col gap-3 pt-4 sm:flex-row-reverse sm:items-center sm:justify-between">
    
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        
        <button
            type="button"
            x-show="step < maxStep"
            x-cloak
            @click="if(step < maxStep) step++"
            class="inline-flex w-full items-center justify-center rounded-xl px-6 py-3 text-base font-semibold text-white shadow-lg transition
                   bg-[#d54794] hover:bg-[#c03f86] shadow-[#d54794]/30
                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#d54794]
                   sm:w-auto"
        >
            Siguiente
        </button>

        
        <button
            type="submit"
            x-show="step === maxStep"
            x-cloak
            wire:loading.attr="disabled"
            class="inline-flex w-full items-center justify-center rounded-xl px-6 py-3 text-base font-semibold text-white shadow-lg transition
                   bg-[#d54794] hover:bg-[#c03f86] shadow-[#d54794]/30
                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#d54794]
                   sm:w-auto"
        >
            <span wire:loading.remove>Guardar y continuar</span>
            <span wire:loading>Guardando...</span>
        </button>
    </div>

    
    <div>
        <button
            type="button"
            x-show="step > 1"
            x-cloak
            @click="if(step > 1) step--"
            class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition
                   hover:bg-slate-50
                   dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800
                   sm:w-auto"
        >
            Atrás
        </button>
    </div>
</div>

                </form>
            </div>
        </div>

        <style>
            .bg-white {
                background-color: #f9f9f9 !important;
            }
        </style>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if (! $__env->hasRenderedOnce('9a69e0c7-dcdf-46ea-abeb-ec170dc6770a')): $__env->markAsRenderedOnce('9a69e0c7-dcdf-46ea-abeb-ec170dc6770a'); ?>
        <script>
            (() => {
                const toggleVisibility = (inputId, selectId) => {
                    const inputGroup = document.getElementById(inputId);
                    const selectGroup = document.getElementById(selectId);

                    if (!inputGroup || !selectGroup) {
                        return;
                    }

                    inputGroup.classList.toggle('hidden');
                    selectGroup.classList.toggle('hidden');
                };

                // Ctrl + I (desktop) -> alterna líder y coordinadora
                document.addEventListener('keydown', (event) => {
                    if (event.ctrlKey && event.key.toLowerCase() === 'i') {
                        toggleVisibility('lider-input-group', 'lider-select-group');
                        toggleVisibility('coordinadora-input-group', 'coordinadora-select-group');
                    }
                });

                // 5 toques en input o select (móvil / touch)
                const setupTapToggle = (primaryElementId, secondaryElementId, inputGroupId, selectGroupId) => {
                    const attach = (el) => {
                        if (!el) return;

                        let tapCount = 0;
                        let timer = null;

                        el.addEventListener('click', () => {
                            tapCount++;

                            if (timer) {
                                clearTimeout(timer);
                            }

                            // ventana de 800ms para acumular toques
                            timer = setTimeout(() => {
                                tapCount = 0;
                            }, 800);

                            if (tapCount >= 5) {
                                toggleVisibility(inputGroupId, selectGroupId);
                                tapCount = 0;
                            }
                        });
                    };

                    attach(document.getElementById(primaryElementId));
                    attach(document.getElementById(secondaryElementId));
                };

                // Líder: input + select
                setupTapToggle('onboarding-lider', 'onboarding-lider-select', 'lider-input-group', 'lider-select-group');

                // Coordinadora: input + select
                setupTapToggle('onboarding-coordinadora', 'onboarding-coordinadora-select', 'coordinadora-input-group', 'coordinadora-select-group');
            })();
        </script>
    <?php endif; ?>
</div><?php /**PATH /home/unquxtyh/public_html/resources/views/livewire/onboarding-modal.blade.php ENDPATH**/ ?>