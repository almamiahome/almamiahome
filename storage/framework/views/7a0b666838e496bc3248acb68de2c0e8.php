<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.heading','data' => ['title' => 'Resumen de Líderes','description' => 'Consulta los indicadores clave de tu red de líderes.','border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Resumen de Líderes','description' => 'Consulta los indicadores clave de tu red de líderes.','border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
                    <div>
                        <label class="block text-xs font-semibold text-slate-500">Zona</label>
                        <input type="text" wire:model.debounce.400ms="zona" placeholder="Ej: Norte" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700" />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Pedidos</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white"><?php echo e($resumen['pedidos']); ?></p>
                    <p class="text-xs text-slate-500">Total en el período</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                        <div class="h-2 rounded-full bg-indigo-500" style="width: <?php echo e(min(100, ($resumen['pedidos'] / max(1, $metaPedidos)) * 100)); ?>%"></div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Meta: <?php echo e($metaPedidos); ?> pedidos</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Monto</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">$<?php echo e(number_format($resumen['monto'], 2, ',', '.')); ?></p>
                    <p class="text-xs text-slate-500">Total facturado</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                        <div class="h-2 rounded-full bg-emerald-500" style="width: <?php echo e(min(100, ($resumen['monto'] / max(1, $metaMonto)) * 100)); ?>%"></div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Meta: $<?php echo e(number_format($metaMonto, 0, ',', '.')); ?></p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Puntos</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white"><?php echo e(number_format($resumen['puntos'], 0, ',', '.')); ?></p>
                    <p class="text-xs text-slate-500">Acumulado</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                        <div class="h-2 rounded-full bg-amber-500" style="width: <?php echo e(min(100, ($resumen['puntos'] / max(1, $metaPuntos)) * 100)); ?>%"></div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Meta: <?php echo e(number_format($metaPuntos, 0, ',', '.')); ?> pts</p>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm dark:bg-blue-900/40 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Unidades</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white"><?php echo e(number_format($resumen['unidades'], 0, ',', '.')); ?></p>
                    <p class="text-xs text-slate-500">Cantidad total</p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                        <div class="h-2 rounded-full bg-sky-500" style="width: <?php echo e(min(100, ($resumen['unidades'] / max(1, $metaPedidos * 5)) * 100)); ?>%"></div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Objetivo estimado</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Líderes</h3>
                        <div class="flex gap-2">
                            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['size' => 'sm','wire:click' => 'exportLideres']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','wire:click' => 'exportLideres']); ?>Exportar CSV <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['size' => 'sm','variant' => 'secondary','wire:click' => 'exportPedidos']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','variant' => 'secondary','wire:click' => 'exportPedidos']); ?>Pedidos CSV <?php echo $__env->renderComponent(); ?>
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
                    <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm dark:bg-blue-900/30 dark:border-blue-800">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Líder</th>
                                    <th class="px-4 py-3 text-left">Zona</th>
                                    <th class="px-4 py-3 text-left">Pedidos</th>
                                    <th class="px-4 py-3 text-left">Monto</th>
                                    <th class="px-4 py-3 text-left">Puntos</th>
                                    <th class="px-4 py-3 text-left">Estado</th>
                                    <th class="px-4 py-3 text-left">Progreso</th>
                                    <th class="px-4 py-3 text-left">Último</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $lideres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fila): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white"><?php echo e($fila['nombre']); ?></td>
                                        <td class="px-4 py-3"><?php echo e($fila['zona']); ?></td>
                                        <td class="px-4 py-3"><?php echo e($fila['pedidos']); ?></td>
                                        <td class="px-4 py-3">$<?php echo e(number_format($fila['monto'], 2, ',', '.')); ?></td>
                                        <td class="px-4 py-3"><?php echo e(number_format($fila['puntos'], 0, ',', '.')); ?></td>
                                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600 dark:bg-blue-800/50 dark:text-blue-100"><?php echo e($fila['estado']); ?></span></td>
                                        <td class="px-4 py-3">
                                            <div class="h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                                                <div class="h-2 rounded-full bg-emerald-500" style="width: <?php echo e(min(100, ($fila['monto'] / max(1, $metaMonto)) * 100)); ?>%"></div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-500"><?php echo e($fila['ultimo_pedido'] ? Carbon::parse($fila['ultimo_pedido'])->format('d/m/Y') : 'N/D'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">No hay pedidos con los filtros seleccionados.</td>
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
                            <div class="px-4 py-3 space-y-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white"><?php echo e($estado['estado']); ?></p>
                                    <span class="px-3 py-1 text-xs font-semibold bg-slate-100 rounded-full text-slate-700 dark:bg-blue-800/60 dark:text-blue-100"><?php echo e($estado['pedidos']); ?> pedidos</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 dark:bg-blue-800/40">
                                    <div class="h-2 rounded-full bg-indigo-500" style="width: <?php echo e($resumen['pedidos'] > 0 ? ($estado['pedidos'] / $resumen['pedidos']) * 100 : 0); ?>%"></div>
                                </div>
                                <p class="text-[11px] text-slate-500">$<?php echo e(number_format($estado['monto'], 2, ',', '.')); ?> facturado</p>
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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/15ef30bbbb6eb2bfc994571c271bbc0a.blade.php ENDPATH**/ ?>