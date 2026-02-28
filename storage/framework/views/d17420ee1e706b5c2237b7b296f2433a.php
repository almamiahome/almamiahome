<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Catalogo;
use App\Models\CatalogoPagina;
use App\Models\CatalogoPaginaProducto;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

?>


    <?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['class' => 'relative space-y-10 py-8']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'relative space-y-10 py-8']); ?>
        
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] left-[15%] w-[40%] h-[40%] rounded-full bg-pink-500/10 blur-[120px]"></div>
            <div class="absolute top-[20%] right-[5%] w-[30%] h-[30%] rounded-full bg-blue-500/10 blur-[100px]"></div>
        </div>

        <div class="px-2">
            <h1 class="text-4xl font-black tracking-tighter text-slate-900">
                Editor de Catalogos <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600">Catálogo</span>
            </h1>
            <p class="mt-2 text-slate-500 font-medium max-w-2xl">
                Gestiona la experiencia visual. Vincula productos a tus páginas de forma facil.
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-1 gap-8">
            
            <div class="xl:col-span-4 space-y-6">
                <section class="bg-white/60 backdrop-blur-2xl border border-white/80 rounded-[2.5rem] shadow-xl p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-pink-500 flex items-center justify-center text-white shadow-lg shadow-pink-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Catálogos</h3>
                    </div>

                    <form wire:submit.prevent="saveCatalogo" class="space-y-5">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nombre</label>
                            <input type="text" wire:model="catalogoForm.nombre" class="w-full h-12 px-4 bg-white border-slate-200 rounded-2xl font-bold focus:border-pink-500 transition-all shadow-inner" placeholder="Ej: Nueva Colección">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['catalogoForm.nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs font-bold text-red-500"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Descripción</label>
                            <textarea wire:model="catalogoForm.descripcion" rows="2" class="w-full p-4 bg-white border-slate-200 rounded-2xl font-bold focus:border-pink-500 transition-all shadow-inner"></textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Portada</label>
                            <input type="file" wire:model="catalogoForm.imagen_portada" class="w-full text-xs">
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 h-12 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:shadow-pink-200 transition-all">
                                <!--[if BLOCK]><![endif]--><?php if($this->catalogoEditingId): ?> Actualizar <?php else: ?> Crear Catálogo <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>
                            <!--[if BLOCK]><![endif]--><?php if($this->catalogoEditingId): ?>
                                <button type="button" wire:click="resetCatalogoForm" class="px-4 h-12 bg-slate-100 text-slate-500 rounded-2xl font-bold text-xs">✕</button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </form>

                    <div class="mt-8 space-y-3">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $catalogos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catalogo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="group p-4 bg-white/40 border border-white rounded-3xl hover:bg-white transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="cursor-pointer" wire:click="selectCatalogo(<?php echo e($catalogo->id); ?>)">
                                        <p class="font-black text-slate-800"><?php echo e($catalogo->nombre); ?></p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e($catalogo->paginas->count()); ?> Páginas</p>
                                    </div>
                                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="editCatalogo(<?php echo e($catalogo->id); ?>)" class="p-2 text-slate-400 hover:text-indigo-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                        <button wire:click="deleteCatalogo(<?php echo e($catalogo->id); ?>)" class="p-2 text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </section>
            </div>

            <div class="xl:col-span-8 space-y-8">
                
                <section class="bg-white/60 backdrop-blur-2xl border border-white/80 rounded-[2.5rem] shadow-xl p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-violet-500 flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Galería de Páginas</h3>
                        </div>

                        <form wire:submit.prevent="savePagina" class="flex flex-wrap items-end gap-3">
                            <div class="w-20">
                                <input type="number" wire:model="paginaForm.numero" class="w-full h-10 bg-white border-slate-200 rounded-xl font-bold text-sm" placeholder="Nº">
                            </div>
                            <div class="w-40">
                                <input type="file" wire:model="paginaForm.imagen" class="w-full text-[10px]">
                            </div>
                            <button type="submit" class="h-10 px-6 bg-violet-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest">
                                <!--[if BLOCK]><![endif]--><?php if($this->paginaEditingId): ?> Actualizar <?php else: ?> Añadir <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-h-[300px] overflow-y-auto pr-2">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $paginas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="relative aspect-[3/4] rounded-2xl overflow-hidden border-2 border-white shadow-sm cursor-pointer hover:scale-[1.02] transition-transform" 
                                 wire:click="$set('mapForm.catalogo_pagina_id', <?php echo e($pagina->id); ?>)">
                                <!--[if BLOCK]><![endif]--><?php if($pagina->imagen): ?>
                                    <img src="<?php echo e(asset('storage/'.$pagina->imagen)); ?>" class="w-full h-full object-cover">
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="absolute bottom-2 left-2 bg-black/50 backdrop-blur-md px-2 py-1 rounded-lg text-white text-[10px] font-black">
                                    Pág <?php echo e($pagina->numero); ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full py-8 text-center text-slate-400 font-bold border-2 border-dashed border-slate-100 rounded-3xl">
                                Sube la primera página para comenzar.
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </section>

                <section class="bg-white/60 backdrop-blur-2xl border border-white/80 rounded-[2.5rem] shadow-xl p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Mapeo de Productos</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-slate-100 min-h-[400px] flex items-center justify-center">
                            <?php 
                                $paginaActiva = $paginas->firstWhere('id', (int)($mapForm['catalogo_pagina_id'] ?? 0));
                            ?>

                            <!--[if BLOCK]><![endif]--><?php if($paginaActiva && $paginaActiva->imagen): ?>
                                <div class="relative w-full">
                                    <img src="<?php echo e(asset('storage/'.$paginaActiva->imagen)); ?>" class="w-full">
                                    
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $paginaActiva->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="absolute h-4 w-4 bg-indigo-600 rounded-full border-2 border-white shadow-lg"
                                             style="left: <?php echo e($p->pos_x); ?>%; top: <?php echo e($p->pos_y); ?>%; transform: translate(-50%, -50%);">
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                                    <div class="absolute h-6 w-6 bg-emerald-500 rounded-full border-4 border-white shadow-xl animate-pulse"
                                         style="left: <?php echo e($mapForm['pos_x']); ?>%; top: <?php echo e($mapForm['pos_y']); ?>%; transform: translate(-50%, -50%);">
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-slate-400 font-bold p-8 text-center uppercase text-[10px] tracking-widest">Selecciona una página de la galería</p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="space-y-6">
                            <form wire:submit.prevent="saveMap" class="space-y-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Producto</label>
                                    <select wire:model="mapForm.producto_id" class="w-full h-12 px-4 bg-white border-slate-200 rounded-2xl font-bold">
                                        <option value="">Seleccionar...</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($prod->id); ?>"><?php echo e($prod->nombre); ?> (<?php echo e($prod->sku); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black uppercase text-slate-400 text-center block">X (%)</label>
                                        <input type="number" step="0.1" wire:model="mapForm.pos_x" class="w-full h-12 text-center bg-white border-slate-200 rounded-2xl font-black text-emerald-600">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black uppercase text-slate-400 text-center block">Y (%)</label>
                                        <input type="number" step="0.1" wire:model="mapForm.pos_y" class="w-full h-12 text-center bg-white border-slate-200 rounded-2xl font-black text-emerald-600">
                                    </div>
                                </div>

                                <button type="submit" class="w-full h-14 bg-emerald-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-100">
                                    <!--[if BLOCK]><![endif]--><?php if($this->mapEditingId): ?> Actualizar Punto <?php else: ?> Vincular Producto <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </button>
                            </form>

                            <!--[if BLOCK]><![endif]--><?php if($paginaActiva): ?>
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black uppercase text-slate-400">Vínculos en esta página:</p>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $paginaActiva->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                            <span class="text-xs font-black text-slate-700"><?php echo e($p->producto?->nombre); ?></span>
                                            <div class="flex gap-1">
                                                <button wire:click="editMap(<?php echo e($p->id); ?>)" class="p-1 text-indigo-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                                <button wire:click="deleteMap(<?php echo e($p->id); ?>)" class="p-1 text-red-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </section>
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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/20ec7c149c47eaa9080fd4e6b6721d44.blade.php ENDPATH**/ ?>