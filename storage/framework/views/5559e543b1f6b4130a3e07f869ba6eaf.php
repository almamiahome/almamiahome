<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Livewire\Volt\Component;

?>


<?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="flex items-center justify-between mb-5">
        <?php if (isset($component)) { $__componentOriginal3f5896b1021d72739df36ad207fd93d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f5896b1021d72739df36ad207fd93d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.heading','data' => ['title' => 'Pedidos','description' => 'Listado de pedidos','border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pedidos','description' => 'Listado de pedidos','border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['tag' => 'a','href' => '/crearpedido']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','href' => '/crearpedido']); ?>Nuevo Pedido <?php echo $__env->renderComponent(); ?>
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

    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-gray-800">
                    Pedidos registrados
                </p>
                <p class="text-xs text-gray-500">
                    <!--[if BLOCK]><![endif]--><?php if($pedidos->count()): ?>
                        Mostrando <?php echo e($pedidos->count()); ?> pedido(s).
                    <?php else: ?>
                        No hay pedidos cargados todavía.
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </p>
            </div>
        </div>

        <!--[if BLOCK]><![endif]--><?php if($pedidos->count()): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-4 py-3 text-left">Código</th>
                            <th class="px-4 py-3 text-left">Vendedora</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-right">Total a pagar</th>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                
                                <td class="px-4 py-2 font-mono text-xs text-gray-800">
                                    <?php echo e($pedido->codigo_pedido); ?>

                                </td>

                                
                                <td class="px-4 py-2 text-gray-800">
                                    <?php echo e(optional($pedido->vendedora)->name ?? '-'); ?>

                                </td>

                                
                                <td class="px-4 py-2">
                                    <span
                                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border',
                                            $pedido->estado === 'Nuevo'
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                                : 'bg-gray-100 text-gray-700 border-gray-300',
                                        ]); ?>"
                                    >
                                        <?php echo e($pedido->estado); ?>

                                    </span>
                                </td>

                                
                                <td class="px-4 py-2 text-right text-gray-900 font-semibold">
                                    $<?php echo e(number_format($pedido->total_a_pagar, 2, ',', '.')); ?>

                                </td>

                                
                                <td class="px-4 py-2 text-gray-700">
                                    <!--[if BLOCK]><![endif]--><?php if($pedido->fecha instanceof \Carbon\Carbon): ?>
                                        <?php echo e($pedido->fecha->format('d/m/Y')); ?>

                                    <?php else: ?>
                                        <?php echo e($pedido->fecha); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>

                                
                                <td class="px-4 py-2 text-right space-x-2">
                                    
                                    <button
                                        wire:click="editPedido(<?php echo e($pedido->id); ?>)"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md border border-gray-300 text-xs font-medium text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        Editar
                                    </button>

                                    
                                    <a
                                        href="<?php echo e(url('/pedidos/'.$pedido->id.'/factura')); ?>"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md bg-indigo-600 text-xs font-medium text-white hover:bg-indigo-700"
                                    >
                                        Ver
                                    </a>

                                    
                                    <button
                                        wire:click="deletePedido(<?php echo e($pedido->id); ?>)"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md border border-red-200 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="px-4 py-10 text-center text-gray-500 text-sm">
                No hay pedidos cargados todavía.
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($editing): ?>
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg">
                <h2 class="mb-4 text-xl font-semibold">Editar Pedido</h2>
                <form wire:submit="savePedido" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Código</label>
                        <input
                            type="text"
                            wire:model="codigo_pedido"
                            readonly
                            class="w-full border-gray-300 rounded-md bg-gray-100"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select wire:model="estado" class="w-full border-gray-300 rounded-md">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['Nuevo','En espera','Procesando','En viaje','Entregado','Completado','Cancelado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estadoOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($estadoOption); ?>"><?php echo e($estadoOption); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea
                            wire:model="observaciones"
                            rows="3"
                            class="w-full border-gray-300 rounded-md"
                        ></textarea>
                    </div>
                    <div class="flex justify-end mt-4 space-x-3">
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','wire:click' => 'closeModal','class' => 'bg-gray-500 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'closeModal','class' => 'bg-gray-500 text-white']); ?>
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
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?>
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
                    </div>
                </form>
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
<?php /**PATH /home/unquxtyh/public_html/storage/framework/views/3d8d95fe21684427020e4d04c0a4eb00.blade.php ENDPATH**/ ?>