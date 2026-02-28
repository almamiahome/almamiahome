<?php

use App\Models\Cobro;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

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
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <?php if (isset($component)) { $__componentOriginal3f5896b1021d72739df36ad207fd93d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f5896b1021d72739df36ad207fd93d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.heading','data' => ['title' => 'Cobros','description' => 'Sigue los cobros de bonos a líderes y coordinadoras.','border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Cobros','description' => 'Sigue los cobros de bonos a líderes y coordinadoras.','border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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

            <div class="flex flex-wrap gap-2">
                <div class="flex items-center gap-2 px-3 py-2 text-sm bg-white border rounded-lg shadow-sm">
                    <span class="text-gray-500">Total registrados:</span>
                    <span class="font-semibold text-gray-900"><?php echo e($cobros->count()); ?></span>
                </div>
                <div class="flex items-center gap-2 px-3 py-2 text-sm bg-white border rounded-lg shadow-sm">
                    <span class="text-gray-500">Pendientes:</span>
                    <span class="font-semibold text-amber-600"><?php echo e($cobros->where('estado', 'pendiente')->count()); ?></span>
                </div>
            </div>
        </div>

        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
            <div class="p-3 text-green-700 bg-green-100 border border-green-300 rounded">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="p-4 space-y-4 bg-white border rounded-2xl shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Cobros programados</p>
                        <p class="text-xs text-gray-500">Listado de bonos registrados y su estado.</p>
                    </div>
                </div>

                <!--[if BLOCK]><![endif]--><?php if($cobros->count()): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-4 py-3">Pedido</th>
                                    <th class="px-4 py-3">Líder</th>
                                    <th class="px-4 py-3">Coordinadora</th>
                                    <th class="px-4 py-3">Monto</th>
                                    <th class="px-4 py-3">Campaña</th>
                                    <th class="px-4 py-3">Pago programado</th>
                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cobros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cobro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-gray-800">
                                            <?php echo e(optional($cobro->pedido)->codigo_pedido ?? 'Pedido #'.$cobro->pedido_id); ?>

                                        </td>
                                        <td class="px-4 py-2 text-gray-800">
                                            <?php echo e(optional($cobro->lider)->name ?? 'Sin líder'); ?>

                                        </td>
                                        <td class="px-4 py-2 text-gray-800">
                                            <?php echo e(optional($cobro->coordinadora)->name ?? 'Sin coordinadora'); ?>

                                        </td>
                                        <td class="px-4 py-2 font-semibold text-right text-gray-900">
                                            $<?php echo e(number_format($cobro->monto, 2, ',', '.')); ?>

                                        </td>
                                        <td class="px-4 py-2 text-gray-700"><?php echo e($cobro->mes_campana); ?></td>
                                        <td class="px-4 py-2 text-gray-700"><?php echo e($cobro->mes_pago_programado); ?></td>
                                        <td class="px-4 py-2">
                                            <span
                                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                    'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border',
                                                    $cobro->estado === 'cobrado'
                                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                                        : 'bg-amber-50 text-amber-700 border-amber-200',
                                                ]); ?>"
                                            >
                                                <?php echo e(ucfirst($cobro->estado)); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-right space-x-2">
                                            <!--[if BLOCK]><![endif]--><?php if($cobro->estado !== 'cobrado'): ?>
                                                <button
                                                    wire:click="marcarCobrado(<?php echo e($cobro->id); ?>)"
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-md bg-emerald-600 text-xs font-medium text-white hover:bg-emerald-700"
                                                >
                                                    Marcar cobrado
                                                </button>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-gray-500">Todavía no hay cobros registrados.</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="p-4 space-y-4 bg-white border rounded-2xl shadow-sm">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">Registrar nuevo cobro</p>
                    <span class="text-xs text-gray-500">Campos obligatorios *</span>
                </div>

                <form wire:submit="saveCobro" class="space-y-3">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Pedido</label>
                        <select wire:model="form.pedido_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">Sin pedido asociado</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pedido->id); ?>"><?php echo e($pedido->codigo_pedido); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.pedido_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Líder</label>
                            <select wire:model="form.lider_id" class="w-full border-gray-300 rounded-lg">
                                <option value="">Sin líder</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($usuario->id); ?>"><?php echo e($usuario->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.lider_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Coordinadora</label>
                            <select wire:model="form.coordinadora_id" class="w-full border-gray-300 rounded-lg">
                                <option value="">Sin coordinadora</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($usuario->id); ?>"><?php echo e($usuario->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.coordinadora_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Mes de campaña *</label>
                            <input
                                type="month"
                                wire:model="form.mes_campana"
                                class="w-full border-gray-300 rounded-lg"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.mes_campana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Monto *</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="form.monto"
                                class="w-full border-gray-300 rounded-lg"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.monto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Estado *</label>
                            <select wire:model="form.estado" class="w-full border-gray-300 rounded-lg">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['pendiente', 'cobrado', 'observado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($estado); ?>"><?php echo e(ucfirst($estado)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-700">Fecha de cobro</label>
                            <input
                                type="date"
                                wire:model="form.fecha_pago"
                                class="w-full border-gray-300 rounded-lg"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.fecha_pago'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Concepto</label>
                        <input
                            type="text"
                            wire:model="form.concepto"
                            class="w-full border-gray-300 rounded-lg"
                        >
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.concepto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','class' => 'w-full justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'w-full justify-center']); ?>Guardar cobro <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                </form>
            </div>
        </div>
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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/20aabadb6855268577d5a66f0d9346d7.blade.php ENDPATH**/ ?>