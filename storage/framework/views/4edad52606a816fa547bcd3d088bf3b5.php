<?php

use function Laravel\Folio\{middleware, name};
use App\Http\Controllers\Crecimiento\CierreCampanaController;
use App\Models\CierreCampana;
use Livewire\Volt\Component;

?>


    <?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['class' => 'space-y-8']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-8']); ?>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <?php if (isset($component)) { $__componentOriginal3f5896b1021d72739df36ad207fd93d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f5896b1021d72739df36ad207fd93d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.heading','data' => ['title' => 'Cierre General de Crecimiento','description' => 'Consolida actividad, altas, unidades y cobranzas por líder.','border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Cierre General de Crecimiento','description' => 'Consolida actividad, altas, unidades y cobranzas por líder.','border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
            <div class="flex gap-2">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['wire:click' => 'cerrarCierre','class' => 'bg-red-100 text-red-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'cerrarCierre','class' => 'bg-red-100 text-red-700']); ?>Cerrar campaña <?php echo $__env->renderComponent(); ?>
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

        <!--[if BLOCK]><![endif]--><?php if($estadoMensaje): ?>
            <div class="p-3 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                <?php echo e($estadoMensaje); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="space-y-4">
                <div class="p-4 bg-white border rounded-xl shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-700">Campañas</h3>
                    <div class="space-y-2">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $cierres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cierre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <button
                                wire:click="selectedCierreId = <?php echo e($cierre->id); ?>; refrescarResumen();"
                                class="w-full text-left p-3 border rounded-lg <?php echo e($selectedCierreId === $cierre->id ? 'bg-indigo-50 border-indigo-200' : 'bg-slate-50 hover:bg-slate-100'); ?>"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-slate-800"><?php echo e($cierre->nombre); ?></p>
                                        <p class="text-xs text-slate-500">Código <?php echo e($cierre->codigo); ?> • <?php echo e($cierre->estado); ?></p>
                                    </div>
                                    <span class="text-xs text-slate-500"><?php echo e(optional($cierre->fecha_cierre)->format('d/m')); ?></span>
                                </div>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-sm text-slate-500">Aún no hay cierres registrados.</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <div class="p-4 bg-white border rounded-xl shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-slate-700">Registrar campaña</h3>
                    <div class="space-y-3">
                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '5d1e9f4bd2de9159582ae964fcb99436::input','data' => ['label' => 'Nombre','wire:model.live' => 'nuevo.nombre']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nombre','wire:model.live' => 'nuevo.nombre']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '5d1e9f4bd2de9159582ae964fcb99436::input','data' => ['label' => 'Código','wire:model.live' => 'nuevo.codigo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Código','wire:model.live' => 'nuevo.codigo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '5d1e9f4bd2de9159582ae964fcb99436::input','data' => ['label' => 'Fecha de inicio','type' => 'date','wire:model.live' => 'nuevo.fecha_inicio']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Fecha de inicio','type' => 'date','wire:model.live' => 'nuevo.fecha_inicio']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '5d1e9f4bd2de9159582ae964fcb99436::input','data' => ['label' => 'Fecha de cierre','type' => 'date','wire:model.live' => 'nuevo.fecha_cierre']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Fecha de cierre','type' => 'date','wire:model.live' => 'nuevo.fecha_cierre']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                        <div class="flex justify-end">
                            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['wire:click' => 'registrarCierre','class' => 'bg-indigo-600 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'registrarCierre','class' => 'bg-indigo-600 text-white']); ?>Registrar <?php echo $__env->renderComponent(); ?>
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
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 bg-white border rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-slate-800">Resumen del plan</h3>
                        <!--[if BLOCK]><![endif]--><?php if(data_get($resumen, 'estado')): ?>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">Estado: <?php echo e($resumen['estado']); ?></span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!--[if BLOCK]><![endif]--><?php if(data_get($resumen, 'nota')): ?>
                        <div class="p-3 mb-4 text-sm text-indigo-900 bg-indigo-50 border border-indigo-100 rounded-lg">
                            <?php echo e($resumen['nota']); ?>

                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div class="p-4 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Líderes</p>
                            <p class="text-xl font-bold text-slate-800"><?php echo e(data_get($resumen, 'lideres', 0)); ?></p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Actividad promedio</p>
                            <p class="text-xl font-bold text-slate-800"><?php echo e(number_format(data_get($resumen, 'actividad_promedio', 0), 0)); ?>%</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Premio total</p>
                            <p class="text-xl font-bold text-slate-800">$<?php echo e(number_format(data_get($resumen, 'premio_total', 0), 0, ',', '.')); ?></p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Actualizado</p>
                            <p class="text-xl font-bold text-slate-800"><?php echo e(data_get($resumen, 'actualizado_en', '—')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-slate-800">Totales por líder</h3>
                        <p class="text-sm text-slate-500">Rango, actividad, cobranzas, altas del mes y reparto 1C/2C/3C.</p>
                    </div>

                    <!--[if BLOCK]><![endif]--><?php if(empty($totales)): ?>
                        <p class="text-sm text-slate-500">Selecciona una campaña para ver sus métricas.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50">
                                    <tr class="text-left text-slate-600">
                                        <th class="p-2">Líder</th>
                                        <th class="p-2">Rango</th>
                                        <th class="p-2">Rev. activas</th>
                                        <th class="p-2">Unidades</th>
                                        <th class="p-2">Cobranzas</th>
                                        <th class="p-2">Altas y pagos</th>
                                        <th class="p-2">Repartos 1C/2C/3C</th>
                                        <th class="p-2">Crecimiento</th>
                                        <th class="p-2">Premio</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $totales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fila): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="p-2 font-semibold text-slate-800"><?php echo e($fila['lider']); ?></td>
                                            <td class="p-2 text-slate-600"><?php echo e($fila['rango']); ?></td>
                                            <td class="p-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-semibold text-slate-800"><?php echo e($fila['revendedoras_activas']); ?></span>
                                                    <!--[if BLOCK]><![endif]--><?php if($fila['actividad_ok']): ?>
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">Meta</span>
                                                    <?php else: ?>
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">Pendiente</span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-semibold text-slate-800"><?php echo e($fila['unidades']); ?></span>
                                                    <!--[if BLOCK]><![endif]--><?php if($fila['unidades_ok']): ?>
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">OK</span>
                                                    <?php else: ?>
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">Falta</span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <div class="flex items-center gap-2">
                                                    <!--[if BLOCK]><![endif]--><?php if($fila['cobranzas_ok']): ?>
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">Al día</span>
                                                    <?php else: ?>
                                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-rose-100 text-rose-700">Fuera de plazo</span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <span class="text-xs text-slate-500">Pago: <?php echo e($fila['fecha_pago_equipo'] ?? '—'); ?></span>
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <p class="font-semibold text-slate-800"><?php echo e($fila['altas_mes']); ?> altas</p>
                                                <div class="flex flex-wrap gap-2 mt-1">
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $fila['altas_pagadas_en_cierre']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="px-2 py-1 text-[10px] rounded-full bg-indigo-50 text-indigo-700">
                                                            C<?php echo e($pago['cuota']); ?>: $<?php echo e(number_format($pago['monto_pagado'], 0, ',', '.')); ?>

                                                        </span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                            <td class="p-2">
                                                <p class="text-sm text-slate-700">1C: <?php echo e($fila['cantidad_1c']); ?> • 2C: <?php echo e($fila['cantidad_2c']); ?> • 3C: <?php echo e($fila['cantidad_3c']); ?></p>
                                                <p class="text-xs text-slate-500">Reparto total: $<?php echo e(number_format($fila['monto_reparto_total'], 0, ',', '.')); ?></p>
                                            </td>
                                            <td class="p-2">
                                                <!--[if BLOCK]><![endif]--><?php if($fila['premio_crecimiento'] > 0): ?>
                                                    <span class="px-2 py-1 text-[10px] rounded-full bg-emerald-50 text-emerald-700">$<?php echo e(number_format($fila['premio_crecimiento'], 0, ',', '.')); ?></span>
                                                <?php else: ?>
                                                    <span class="text-xs text-slate-500">—</span>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                            <td class="p-2 font-semibold text-slate-800">$<?php echo e(number_format($fila['premio_total'], 0, ',', '.')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
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
<?php /**PATH /home/unquxtyh/public_html/storage/framework/views/41867347f260f6732fbc8c4bc7817d04.blade.php ENDPATH**/ ?>