<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Livewire\Volt\Component;
use Barryvdh\DomPDF\Facade\Pdf;

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

        <h1 class="text-2xl font-bold mb-6">Rótulos de pedidos</h1>

        
        <div class="flex flex-wrap items-end gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                <select
                    wire:model="mes"
                    class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <!--[if BLOCK]><![endif]--><?php for($m = 1; $m <= 12; $m++): ?>
                        <?php
                            $value = str_pad($m, 2, '0', STR_PAD_LEFT);
                            $nombreMes = \Carbon\Carbon::createFromDate(2000, $m, 1)->locale('es')->monthName;
                        ?>
                        <option value="<?php echo e($value); ?>"><?php echo e(ucfirst($nombreMes)); ?></option>
                    <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                <select
                    wire:model="anio"
                    class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <!--[if BLOCK]><![endif]--><?php for($y = now()->year - 3; $y <= now()->year + 1; $y++): ?>
                        <option value="<?php echo e($y); ?>"><?php echo e($y); ?></option>
                    <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <div class="ml-auto flex items-center gap-3">
                <p class="text-sm text-gray-600">
                    Límite de bulto por rótulo:
                    <span class="font-semibold"><?php echo e($limiteBulto); ?></span>
                </p>

                <button onclick="window.print()" type="button" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold shadow hover:bg-indigo-700 transition">
                    Imprimir
                </button>

            </div>
        </div>

        
        <div class="print-area border rounded-xl bg-white p-4 shadow-sm">

            <?php
                $pages = array_chunk($rotulos, 30); // 4 columnas x 13 filas
            ?>

            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pageIndex => $pageRotulos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="<?php echo e($pageIndex > 0 ? 'page-break' : ''); ?> mb-6 last:mb-0">

                    <div class="grid grid-cols-3 gap-0 border border-gray-300">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $pageRotulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $rotulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-gray-300 h-24 px-2 py-1 text-[11px] leading-tight flex flex-col justify-between">
                                <div>
                                    <p><span class="font-semibold">Revendedora:</span> <?php echo e($rotulo['vendedora']); ?></p>
                                    <p><span class="font-semibold">Líder:</span> <?php echo e($rotulo['lider']); ?></p>
                                </div>
                                <p><span class="font-semibold">Bulto N°:</span> <?php echo e($rotulo['numero']); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                        
                        <!--[if BLOCK]><![endif]--><?php for($empty = count($pageRotulos); $empty < 52; $empty++): ?>
                            <div class="border border-gray-300 h-24 px-2 py-1"></div>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-500">
                    No hay pedidos con rótulos para el período seleccionado.
                </p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>

           <style>
            /* Ocultar toda la UI al imprimir */
                @media print {
        
                /* Ocultar todo excepto la grilla */
                body * {
                    visibility: hidden !important;
                }
        
                /* Mostrar solo el contenedor de la grilla */
                .print-area, .print-area * {
                    visibility: visible !important;
                }
        
                /* Ubicar la grilla en la parte superior */
                .print-area {
                    position: absolute !important;
                    top: 0;
                    left: 0;
                    width: 100%;
                }
            }
        </style>


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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/6552bd65b4b1853985aadd24db067560.blade.php ENDPATH**/ ?>