<?php

use function Laravel\Folio\{middleware, name};
use App\Models\User;
use Livewire\Volt\Component;

?>


        <?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['class' => 'space-y-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-6']); ?>
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <?php if (isset($component)) { $__componentOriginal3f5896b1021d72739df36ad207fd93d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f5896b1021d72739df36ad207fd93d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.heading','data' => ['title' => 'Lideres','description' => 'Gestiona los usuarios con rol lider y asigna vendedoras como lideres.','border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Lideres','description' => 'Gestiona los usuarios con rol lider y asigna vendedoras como lideres.','border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3f5896b1021d72739df36ad207fd93d6)): ?>
<?php $attributes = $__attributesOriginal3f5896b1021d72739df36ad207fd93d6; ?>
<?php unset($__attributesOriginal3f5896b1021d72739df36ad207fd93d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3f5896b1021d72739df36ad207fd93d6)): ?>
<?php $component = $__componentOriginal3f5896b1021d72739df36ad207fd93d6; ?>
<?php unset($__componentOriginal3f5896b1021d72739df36ad207fd93d6); ?>
<?php endif; ?>
                <div class="flex justify-end">
                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','class' => 'w-full md:w-auto','wire:click' => 'openAddModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','class' => 'w-full md:w-auto','wire:click' => 'openAddModal']); ?>
                        Agregar lider
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($lideres->isEmpty()): ?>
                <div class="p-10 text-center bg-white border border-dashed rounded-xl text-slate-500">
                    Todavia no hay usuarios con rol lider. Usa el boton "Agregar lider" para asignar una vendedora.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm">
                    <table class="min-w-full text-left text-sm">
    <thead class="text-xs uppercase tracking-wide bg-slate-50 text-slate-500">
        <tr>
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">Nombre</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Coordinadora</th>
            <th class="px-4 py-3">Otros roles</th>
            <th class="px-4 py-3">Creado</th>
            <th class="px-4 py-3 text-right">Acciones</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $lideres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="align-middle">
                <td class="px-4 py-3 text-slate-600 text-xs">
                    <?php echo e($lider->id); ?>

                </td>

                <td class="px-4 py-3 font-medium text-slate-900">
                    <?php echo e($lider->name ?? 'Sin nombre'); ?>

                </td>

                <td class="px-4 py-3 text-slate-700">
                    <?php echo e($lider->email); ?>

                </td>

                <td class="px-4 py-3 text-slate-700 text-xs">
                    <!--[if BLOCK]><![endif]--><?php if($lider->coordinadora_id): ?>
                        <?php $coordinadora = \App\Models\User::find($lider->coordinadora_id) ?>
                        <!--[if BLOCK]><![endif]--><?php if($coordinadora): ?>
                            <span class="inline-flex flex-col">
                                <span class="font-medium text-slate-800"><?php echo e($coordinadora->name); ?></span>
                                <span class="text-slate-400">#<?php echo e($lider->coordinadora_id); ?></span>
                            </span>
                        <?php else: ?>
                            <span class="text-slate-400">ID: <?php echo e($lider->coordinadora_id); ?></span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                        <span class="text-slate-400">Sin coordinadora</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </td>

                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-1">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $lider->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php if($role->name !== 'lider'): ?>
                                <span class="inline-flex px-2 py-0.5 text-[11px] rounded-full bg-slate-100 text-slate-700">
                                    <?php echo e($role->name); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php if($lider->roles->where('name', '!=', 'lider')->isEmpty()): ?>
                            <span class="text-[11px] text-slate-400">
                                Sin otros roles
                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </td>

                <td class="px-4 py-3 text-slate-600 text-xs">
                    <?php echo e($lider->created_at?->format('d/m/Y H:i')); ?>

                </td>

                <td class="px-4 py-3 text-right">
                    <button
                        type="button"
                        x-data
                        @click="if (confirm('Seguro que queres quitar el rol de lider a este usuario?')) { $wire.removeLider(<?php echo e($lider->id); ?>) }"
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 transition-colors"
                    >
                        Quitar rol lider
                    </button>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </tbody>
</table>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($showAddModal): ?>
                <div
                    x-data="{ open: true }"
                    x-show="open"
                    x-transition.opacity
                    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8 bg-black/40"
                    @keydown.window.escape="open = false; $wire.closeModal()"
                    @click.self="open = false; $wire.closeModal()"
                >
                    <div
                        class="w-full max-w-lg overflow-hidden bg-white border border-slate-100 rounded-2xl shadow-2xl"
                        @click.stop
                    >
                        <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">
                                    Agregar lider
                                </h2>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    Solo se muestran vendedoras que todavia no tienen rol lider.
                                </p>
                            </div>
                            <button
                                class="inline-flex items-center justify-center w-8 h-8 text-slate-400 rounded-full hover:bg-slate-200 hover:text-slate-700 transition-colors"
                                type="button"
                                @click="open = false; $wire.closeModal()"
                            >
                                ✕
                            </button>
                        </div>

                        <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                            <!--[if BLOCK]><![endif]--><?php if($usuariosDisponibles->isEmpty()): ?>
                                <p class="text-sm text-slate-600">
                                    No hay vendedoras disponibles sin rol lider. Primero crea o asigna vendedoras desde la seccion correspondiente.
                                </p>
                            <?php else: ?>
                                <form wire:submit.prevent="addLider" class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">
                                            Vendedora
                                        </label>
                                        <select
                                            wire:model="form.user_id"
                                            class="w-full mt-1 border-gray-300 rounded-lg shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                        >
                                            <option value="">Selecciona una vendedora</option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $usuariosDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>">
                                                    <?php echo e($user->name ?? 'Sin nombre'); ?> — <?php echo e($user->email); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-xs text-red-500"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                        <p class="mt-1 text-xs text-slate-500">
                                            Esta vendedora pasara a formar parte del equipo de lideres.
                                        </p>
                                    </div>
                                </form>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="flex flex-col gap-2 px-6 py-4 border-t bg-slate-50 sm:flex-row sm:justify-end">
                            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','class' => 'w-full sm:w-auto bg-slate-200 text-slate-700 hover:bg-slate-300','@click' => 'open = false; $wire.closeModal()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','class' => 'w-full sm:w-auto bg-slate-200 text-slate-700 hover:bg-slate-300','@click' => 'open = false; $wire.closeModal()']); ?>
                                Cancelar
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>

                            <!--[if BLOCK]><![endif]--><?php if(!$usuariosDisponibles->isEmpty()): ?>
                                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','class' => 'w-full sm:w-auto','wire:click' => 'addLider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','class' => 'w-full sm:w-auto','wire:click' => 'addLider']); ?>
                                    Guardar
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal08db35abc15b88d7e891883ef0dd6bed)): ?>
<?php $attributes = $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed; ?>
<?php unset($__attributesOriginal08db35abc15b88d7e891883ef0dd6bed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal08db35abc15b88d7e891883ef0dd6bed)): ?>
<?php $component = $__componentOriginal08db35abc15b88d7e891883ef0dd6bed; ?>
<?php unset($__componentOriginal08db35abc15b88d7e891883ef0dd6bed); ?>
<?php endif; ?>
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/ae8de7fe8680d00d5582c8364e74ca5b.blade.php ENDPATH**/ ?>