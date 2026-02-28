<?php

use function Laravel\Folio\{middleware, name};
use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.heading','data' => ['title' => 'Zona Líder','description' => 'Gestioná tu espacio como líder.','border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Zona Líder','description' => 'Gestioná tu espacio como líder.','border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                <div class="flex flex-wrap gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500">Desde</label>
                        <input type="date" wire:model.debounce.300ms="startDate" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500">Hasta</label>
                        <input type="date" wire:model.debounce.300ms="endDate" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500">Estado</label>
                        <select wire:model="estado" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700">
                            <option value="">Todos</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $estadosDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>"><?php echo e(ucfirst($value)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(!empty($premios)): ?>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Campaña</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-white"><?php echo e($premios['campana']); ?></p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">Rango actual: <?php echo e($premios['rango']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500">Premio total</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">$<?php echo e(number_format($premios['premio_total'], 0, ',', '.')); ?></p>
                            <!--[if BLOCK]><![endif]--><?php if($premios['premio_crecimiento'] > 0): ?>
                                <p class="text-xs text-emerald-600">Incluye crecimiento: $<?php echo e(number_format($premios['premio_crecimiento'], 0, ',', '.')); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-3">
                        <div class="space-y-1">
                            <p class="text-xs text-slate-500">Revendedoras activas</p>
                            <p class="text-lg font-semibold text-slate-900 dark:text-white"><?php echo e($premios['revendedoras_activas']); ?></p>
                            <p class="text-xs text-slate-500">Unidades: <?php echo e($premios['unidades']); ?></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-slate-500">Cobranzas</p>
                            <!--[if BLOCK]><![endif]--><?php if($premios['cobranzas_ok']): ?>
                                <span class="px-3 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">Pago dentro de los 7 días</span>
                            <?php else: ?>
                                <span class="px-3 py-1 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">Pago fuera de ventana</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <p class="text-xs text-slate-500">Fecha de pago: <?php echo e($premios['fecha_pago_equipo'] ?? 'Sin registrar'); ?></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-slate-500">Reparto 1C/2C/3C</p>
                            <p class="text-sm text-slate-700 dark:text-slate-200">1C: <?php echo e($premios['repartos']['1c']); ?> • 2C: <?php echo e($premios['repartos']['2c']); ?> • 3C: <?php echo e($premios['repartos']['3c']); ?></p>
                            <p class="text-xs text-slate-500">Total reparto: $<?php echo e(number_format($premios['repartos']['monto'], 0, ',', '.')); ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Altas del mes</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white"><?php echo e($premios['altas_mes']); ?> altas</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $premios['altas_pagadas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="px-3 py-1 text-[10px] rounded-full bg-indigo-50 text-indigo-700">Cuota <?php echo e($pago['cuota']); ?>: $<?php echo e(number_format($pago['monto_pagado'], 0, ',', '.')); ?> (<?php echo e($pago['cierre_codigo']); ?>)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-600 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-200">
                    Aún no registramos métricas de campaña para tu red. Cuando cierres tu próxima campaña verás aquí tus revendedoras activas, cobranzas, altas y repartos.
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Pedidos</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white"><?php echo e($resumen['pedidos']); ?></p>
                    <p class="text-xs text-slate-500">Total en el período</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Unidades</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white"><?php echo e(number_format($resumen['unidades'], 0, ',', '.')); ?></p>
                    <p class="text-xs text-slate-500">Cantidad total vendida</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Monto</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">$<?php echo e(number_format($resumen['monto'], 2, ',', '.')); ?></p>
                    <p class="text-xs text-slate-500">Total facturado</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Vendedoras asociadas</h3>
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['size' => 'sm','wire:click' => 'exportVendedoras']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'exportVendedoras']); ?>Exportar CSV <?php echo $__env->renderComponent(); ?>
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
                    <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm dark:bg-blue-900/30 dark:border-blue-800">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Vendedora</th>
                                    <th class="px-4 py-3 text-left">Pedidos</th>
                                    <th class="px-4 py-3 text-left">Unidades</th>
                                    <th class="px-4 py-3 text-left">Monto</th>
                                    <th class="px-4 py-3 text-left">Estado</th>
                                    <th class="px-4 py-3 text-left">Último pedido</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $vendedoras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fila): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white"><?php echo e($fila['nombre']); ?></td>
                                        <td class="px-4 py-3"><?php echo e($fila['pedidos']); ?></td>
                                        <td class="px-4 py-3"><?php echo e($fila['unidades']); ?></td>
                                        <td class="px-4 py-3">$<?php echo e(number_format($fila['monto'], 2, ',', '.')); ?></td>
                                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600 dark:bg-blue-800/50 dark:text-blue-100"><?php echo e($fila['estado']); ?></span></td>
                                        <td class="px-4 py-3 text-xs text-slate-500"><?php echo e($fila['ultimo_pedido'] ? \Carbon\Carbon::parse($fila['ultimo_pedido'])->format('d/m/Y') : 'N/D'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">No hay pedidos en el período seleccionado.</td>
                                    </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Estados del período</h3>
                    <div class="bg-white border rounded-2xl shadow-sm divide-y divide-slate-100 dark:bg-blue-900/30 dark:border-blue-800">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $estados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white"><?php echo e($estado['estado']); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($estado['unidades']); ?> unidades</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold bg-slate-100 rounded-full text-slate-700 dark:bg-blue-800/60 dark:text-blue-100"><?php echo e($estado['pedidos']); ?> pedidos</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="px-4 py-6 text-center text-sm text-slate-500">Sin datos para mostrar.</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/c14aaefbaad0b5397f0d3c5294ccdd9c.blade.php ENDPATH**/ ?>