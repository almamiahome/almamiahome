<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;

middleware('auth');
name('pedidos.factura');

?>

<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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

             <?php
        /** @var \App\Models\Pedido $pedido */
        $pedido = \App\Models\Pedido::with(['articulos', 'vendedora', 'lider', 'responsable'])
            ->findOrFail($pedido);

        $datos = $pedido->datos_pedido ?? [];
        if (is_string($datos)) {
            $datos = json_decode($datos, true) ?? [];
        }

        // Función para obtener info combinada (JSON o Relación)
        $buildInfo = function (string $tipo) use ($pedido, $datos) {
            $relacion = $pedido->{$tipo} ?? null;
            $dataJson = $datos[$tipo] ?? [];
            return [
                'nombre'    => $dataJson['nombre'] ?? ($relacion->name ?? '—'),
                'direccion' => $dataJson['direccion'] ?? ($relacion ? $relacion->profile('direccion') : '—'),
                'zona'      => $dataJson['zona'] ?? ($relacion ? $relacion->profile('zona') : '—'),
            ];
        };

        $infoVendedora = $buildInfo('vendedora');
        $infoLider     = $buildInfo('lider');

        $formatCurrency = fn ($value) => '$ ' . number_format((float) $value, 2, ',', '.');
        $formatNumber   = fn ($value) => number_format((float) $value, 0, ',', '.');
        
        $fechaDoc = $pedido->fecha instanceof \Carbon\Carbon ? $pedido->fecha->format('d/m/Y') : $pedido->fecha;
        $facturaNumero = str_pad((string) $pedido->id, 8, '0', STR_PAD_LEFT);
    ?>

    <style>
        .brand-blue { color: #004a99; }
        .bg-brand-blue { background-color: #004a99; }
        .brand-pink { color: #e91e63; }
        .bg-brand-pink { background-color: #e91e63; }
        .border-brand-pink { border-color: #e91e63; }

        @media print {
            body * {
                visibility: hidden !important;
            }

            #factura-print-area,
            #factura-print-area * {
                visibility: visible !important;
            }

            #factura-print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            @page { size: A4; margin: 10mm; }
        }

        .coupon-section {
            border-top: 2px dashed #cbd5e1;
            position: relative;
        }
        .coupon-section::before, .coupon-section::after {
            content: "✂";
            position: absolute;
            top: -14px;
            font-size: 1.2rem;
            color: #94a3b8;
        }
        .coupon-section::before { left: -5px; }
        .coupon-section::after { right: -5px; }

        .item-card-mini {
            font-size: 0.75rem;
            padding: 0.4rem 0.75rem;
        }
    </style>

    
    <div class="flex justify-between items-center mb-6 no-print">
        <?php if (isset($component)) { $__componentOriginal3f5896b1021d72739df36ad207fd93d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f5896b1021d72739df36ad207fd93d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.heading','data' => ['title' => 'Comprobante de Pedido','description' => 'Vista para impresión y control de vendedora','border' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Comprobante de Pedido','description' => 'Vista para impresión y control de vendedora','border' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','variant' => 'outline','onclick' => 'window.print()','class' => 'flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','onclick' => 'window.print()','class' => 'flex items-center gap-2']); ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir 
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'button','onclick' => 'window.print()','class' => 'bg-rose-600 hover:bg-rose-700 flex items-center gap-2 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','onclick' => 'window.print()','class' => 'bg-rose-600 hover:bg-rose-700 flex items-center gap-2 text-white']); ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Descargar PDF
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['tag' => 'a','href' => ''.e(url('/pedidos')).'','class' => 'bg-slate-500 hover:bg-slate-600 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','href' => ''.e(url('/pedidos')).'','class' => 'bg-slate-500 hover:bg-slate-600 text-white']); ?>
                Volver
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



     <div class="flex justify-center overflow-auto pb-10">
        <div id="factura-print-area" class="invoice-print-area invoice-a4 relative bg-white px-8 py-10 text-sm text-slate-800 shadow-2xl rounded-2xl border border-slate-200 w-full max-w-[210mm] min-h-[297mm]">

            <div class="relative z-10">
            
            <!-- Encabezado -->
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <div class="flex flex-col">
                    <img src="https://almamiafragancias.com.ar/storage/logos/logo-claro.png" alt="Alma Mía" class="h-14 w-auto mb-2 object-contain">
                    <h1 class="text-sm font-black brand-blue tracking-tighter uppercase">Factura de Venta Interna</h1>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Número</p>
                    <p class="text-lg font-mono font-bold brand-pink">#<?php echo e($facturaNumero); ?></p>
                    <p class="text-[10px] text-gray-500 font-medium">Fecha: <?php echo e($fechaDoc); ?></p>
                    <p class="text-[10px] text-gray-500 font-medium">Fecha: <?php echo e($pedido->codigo_pedido); ?></p>
               
                </div>
            </div>

            <!-- Datos Principales -->
            <div class="grid grid-cols-2 bg-slate-50/50 border-b border-gray-100">
                <div class="p-4 border-r border-gray-100">
                    <p class="text-[9px] uppercase font-black text-slate-400 mb-1">Vendedora / Responsable</p>
                    <p class="font-bold text-slate-800 text-sm"><?php echo e($infoVendedora['nombre']); ?></p>
                    <p class="text-[11px] text-slate-500 leading-tight"><?php echo e($infoVendedora['direccion']); ?></p>
                    <p class="text-[10px] font-semibold brand-blue mt-1"><?php echo e($infoVendedora['zona']); ?></p>
                </div>
                <div class="p-4">
                    <p class="text-[9px] uppercase font-black text-slate-400 mb-1">Líder de Equipo</p>
                    <p class="font-bold text-slate-700 text-sm"><?php echo e($infoLider['nombre']); ?></p>
                    <p class="text-[11px] text-slate-500"><?php echo e($pedido->mes); ?></p>
                    <div class="mt-2">
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-100 text-rose-600 font-bold uppercase">
                            <?php echo e($pedido->estado); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Listado de Productos (Cards Mini)
            <div class="p-4 space-y-1.5 min-h-[300px]">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Detalle de Artículos</p>
                
                <?php $__empty_1 = true; $__currentLoopData = $pedido->articulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="item-card-mini border border-slate-100 rounded-lg flex justify-between items-center bg-white hover:border-brand-pink transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 bg-rose-50 rounded flex items-center justify-center text-brand-pink font-bold text-[10px]">
                                <?php echo e($item->cantidad); ?>

                            </div>
                            <div>
                                <p class="font-bold text-slate-800 leading-none"><?php echo e($item->producto); ?></p>
                                <p class="text-[9px] text-slate-400 mt-1"><?php echo e(Str::limit($item->descripcion, 50)); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-slate-700"><?php echo e($formatCurrency($item->subtotal)); ?></p>
                            <p class="text-[9px] font-bold text-brand-pink uppercase tracking-tighter"><?php echo e($formatNumber($item->puntos)); ?> PTS</p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-10 text-center text-slate-400 italic text-xs">No hay productos en este pedido.</div>
                <?php endif; ?>
            </div>  -->
            
            
                
                <section class="mb-8 min-h-[400px]">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white">
                                <th class="px-3 py-2 text-left text-[10px] font-bold uppercase tracking-wider rounded-l-lg">SKU</th>
                                <th class="px-3 py-2 text-left text-[10px] font-bold uppercase tracking-wider">Producto</th>
                                <th class="px-3 py-2 text-left text-[10px] font-bold uppercase tracking-wider">Categoría</th>
                                <th class="px-3 py-2 text-right text-[10px] font-bold uppercase tracking-wider">Unidades</th>
                                <th class="px-3 py-2 text-right text-[10px] font-bold uppercase tracking-wider">$ Catalogo</th>
                                <th class="px-3 py-2 text-right text-[10px] font-bold uppercase tracking-wider">$ Unitario</th>
                                <th class="px-3 py-2 text-right text-[10px] font-bold uppercase tracking-wider">Subtotal</th>
                                <th class="px-3 py-2 text-right text-[10px] font-bold uppercase tracking-wider rounded-r-lg">Pts.</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $pedido->articulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-3 py-3 font-bold text-slate-900 text-[12px]"><?php echo e($item->sku ?? "—"); ?></td>
                                    <td class="px-3 py-3 font-bold text-slate-900 text-[12px]"><?php echo e($item->producto); ?></td>
                                    <td class="px-3 py-3 text-slate-600 text-[11px] leading-tight"><?php echo e($item->descripcion); ?></td>
                                    <td class="px-3 py-3 text-right font-medium text-[12px]"><?php echo e($formatNumber($item->cantidad)); ?></td>
                                    <td class="px-3 py-3 text-right text-slate-500 text-[11px]"><?php echo e($formatCurrency($item->precio_catalogo)); ?></td>
                                    <td class="px-3 py-3 text-right text-slate-500 text-[11px]"><?php echo e($formatCurrency($item->precio_unitario)); ?></td>
                                    <td class="px-3 py-3 text-right font-bold text-slate-900 text-[12px]"><?php echo e($formatCurrency($item->subtotal)); ?></td>
                                    <td class="px-3 py-3 text-right text-rose-500 font-bold text-[11px]"><?php echo e($formatNumber($item->puntos)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="px-2 py-12 text-center text-slate-400 italic">
                                        No hay artículos registrados en este pedido.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>

            <!-- Totales e Info -->
            <div class="px-6 py-4 bg-white border-t border-gray-100 flex justify-between items-center">
                <div class="text-[10px] text-slate-400 max-w-[200px]">
                    <p>Este documento es un comprobante para el control de entrega y puntos acumulados.</p>
                </div>
                <div class="w-48 space-y-1 text-right">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Subtotal:</span>
                        <span class="font-semibold text-slate-700"><?php echo e($formatCurrency($pedido->total_precio_catalogo)); ?></span>
                    </div>
                    <div class="flex justify-between text-sm pt-1 border-t border-slate-50">
                        <span class="font-bold brand-blue">TOTAL A PAGAR:</span>
                        <span class="font-black brand-blue"><?php echo e($formatCurrency($pedido->total_a_pagar)); ?></span>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN RECORTABLE (CUPÓN) -->
            <div class="coupon-section p-6 bg-slate-50 mt-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-[10px] font-black brand-blue uppercase mb-1">Cupón de Recepción</p>
                        <h2 class="text-xs font-bold text-slate-700">PEDIDO: <?php echo e($pedido->codigo_pedido); ?></h2>
                    </div>
                    <img src="https://almamiafragancias.com.ar/storage/logos/logo-claro.png" alt="" class="h-6 opacity-50 grayscale">
                </div>

                <div class="grid grid-cols-3 gap-4 text-center mb-6">
                    <div class="bg-white p-2 rounded border border-dashed border-slate-200">
                        <p class="text-[8px] uppercase font-bold text-slate-400">Factura N°</p>
                        <p class="text-xs font-bold text-slate-800">#<?php echo e($facturaNumero); ?></p>
                    </div>
                    <div class="bg-white p-2 rounded border border-dashed border-slate-200">
                        <p class="text-[8px] uppercase font-bold text-slate-400">Puntos Totales</p>
                        <p class="text-xs font-bold brand-pink"><?php echo e($formatNumber($pedido->total_puntos)); ?> PTS</p>
                    </div>
                    <div class="bg-white p-2 rounded border border-dashed border-slate-200">
                        <p class="text-[8px] uppercase font-bold text-slate-400">Total a pagar</p>
                        <p class="text-xs font-bold brand-blue"><?php echo e($formatCurrency($pedido->total_a_pagar)); ?></p>
                    </div>
                    <div class="bg-white p-2 rounded border border-dashed border-slate-200">
                        <p class="text-[8px] uppercase font-bold text-slate-400">Responsable</p>
                        <p class="text-xs font-bold text-slate-800"><?php echo e($infoVendedora['nombre']); ?></p>
                    </div>
                    <div class="bg-white p-2 rounded border border-dashed border-slate-200">
                        <p class="text-[8px] uppercase font-bold text-slate-400">Direccion</p>
                        <p class="text-xs font-bold brand-pink"><?php echo e($infoVendedora['direccion']); ?></p>
                    </div>
                    <div class="bg-white p-2 rounded border border-dashed border-slate-200">
                        <p class="text-[8px] uppercase font-bold text-slate-400">Zona</p>
                        <p class="text-xs font-bold brand-blue"><?php echo e($infoVendedora['zona']); ?></p>
                    </div>
                </div>
            





<p class="font-bold text-slate-800 text-sm"></p>
                    <p class="text-[11px] text-slate-500 leading-tight"></p>
                    <p class="text-[10px] font-semibold brand-blue mt-1"></p>






             </div>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/pedidos/[pedido]/factura.blade.php ENDPATH**/ ?>