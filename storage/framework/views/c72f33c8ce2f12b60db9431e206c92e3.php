<?php

use function Laravel\Folio\{middleware, name};
use App\Models\PuntajeRegla;
use App\Models\Categoria;
use Livewire\Volt\Component;

?>


        <?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['class' => 'space-y-6','xData' => '{}']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-6','x-data' => '{}']); ?>
          
           <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between mb-8">
    <div class="space-y-1">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            Puntaje y Reglas
        </h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium max-w-2xl">
            Configura las reglas de puntaje, bonificaciones y beneficios para tus categorías.
        </p>
    </div>

    <div class="flex items-center">
        <button 
            type="button" 
            wire:click="openCreateModal"
            class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-extrabold rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none transition-all transform active:scale-95"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Regla
        </button>
    </div>
</div>

            <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($reglas->isEmpty()): ?>
                <div class="p-10 text-center bg-white border border-dashed rounded-xl text-slate-500">
                    No hay reglas configuradas todavia. Crea la primera para comenzar.
                </div>
<?php else: ?>
    <div class="flex flex-col gap-3">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reglas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regla): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 hover:shadow-md transition-all">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    
                    <div class="flex lg:flex-col items-center lg:items-start gap-2 lg:w-24 shrink-0">
                        <span class="text-[10px] font-mono text-slate-400">#<?php echo e($regla->id); ?></span>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold">
                            <?php echo e($regla->puntaje_minimo ?? 0); ?> PTS
                        </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap gap-1 max-h-20 overflow-y-auto pr-2 custom-scrollbar">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $regla->categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <span class="px-2 py-0.5 text-[9px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded border border-slate-200 dark:border-slate-700 whitespace-nowrap">
                                    <?php echo e($categoria->nombre); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-[10px] text-slate-400 italic">Sin categorías</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <h4 class="mt-2 text-xs font-bold text-slate-800 dark:text-white uppercase truncate">
                            <?php echo e($regla->descripcion ?: 'Regla General'); ?>

                        </h4>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 lg:gap-10 shrink-0 px-4 py-2 lg:py-0 bg-slate-50 dark:bg-slate-800/50 rounded-lg lg:bg-transparent">
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Unidades</p>
                            <p class="text-xs font-black text-slate-700 dark:text-slate-200"><?php echo e($regla->min_unidades); ?>-<?php echo e($regla->max_unidades ?? '∞'); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-indigo-400 uppercase tracking-tighter">Bonificación</p>
                            <p class="text-xs font-black text-indigo-600">
                                <?php echo e($regla->porcentaje ? number_format($regla->porcentaje, 1).'%' : '$'.number_format($regla->bonificacion, 0)); ?>

                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Mensual</p>
                            <p class="text-xs font-black text-slate-700 dark:text-slate-200"><?php echo e($regla->puntos_mensuales ?? 0); ?></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 lg:ml-4 border-t lg:border-l lg:border-t-0 border-slate-100 dark:border-slate-800 pt-3 lg:pt-0 lg:pl-4">
                        
                        
                        
                        <button
                            type="button"
                            wire:click="openEditModal(<?php echo e($regla->id); ?>)"
                            class="px-4 py-1.5 rounded-lg text-[11px] font-bold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors"
                        >
                            Editar
                        </button>
                        <button
                            type="button"
                            x-data
                            @click="if(confirm('¿Eliminar?')) $wire.deleteRegla(<?php echo e($regla->id); ?>)"
                            class="p-1.5 text-slate-400 hover:text-red-600 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
           <!--[if BLOCK]><![endif]--><?php if($showCreateModal): ?>
    <div
        x-data="{ open: true }"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 bg-slate-900/70 backdrop-blur-md"
        @keydown.window.escape="open = false; $wire.closeModals()"
        @click.self="open = false; $wire.closeModals()"
    >
        <div
            class="w-full max-w-4xl bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-slate-200 dark:border-slate-800"
            @click.stop
        >
            <div class="relative px-10 py-8 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-none">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                                Nueva Regla de Puntaje
                            </h2>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">
                                Configure los beneficios y criterios de aplicación.
                            </p>
                        </div>
                    </div>
                    <button
                        class="p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all"
                        type="button"
                        @click="open = false; $wire.closeModals()"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="px-10 py-8 overflow-y-auto flex-1 bg-slate-50/50 dark:bg-slate-900/50">
                <form id="createReglaForm" wire:submit.prevent="saveRegla" class="space-y-10">
                    
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">01. Alcance</h3>
                            <p class="text-sm text-slate-500">Seleccione las categorías que se verán afectadas por esta regla.</p>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Categorías Relacionadas</label>
                            <select
                                multiple
                                wire:model="form.categoria_ids"
                                class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-2xl shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-base py-3 min-h-[160px]"
                            >
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categoria->id); ?>" class="py-2 px-4 rounded-lg my-1"><?php echo e($categoria->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <p class="mt-2 text-xs text-slate-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                Tip: Usa Ctrl + Click para selección múltiple.
                            </p>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.categoria_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-500 font-semibold mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </section>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">02. Definición</h3>
                            <p class="text-sm text-slate-500">Establezca los límites de unidades y el beneficio económico.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Descripción General</label>
                                <textarea
                                    wire:model="form.descripcion"
                                    rows="3"
                                    placeholder="Ej: Bonificación especial para distribuidores mayoristas..."
                                    class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-2xl shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 text-base py-3"
                                ></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-500 font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                    <span class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 text-center">Rango de Unidades</span>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <input type="number" placeholder="Min" wire:model="form.min_unidades" class="w-full border-slate-100 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-center py-3 text-lg font-bold" />
                                        </div>
                                        <span class="text-slate-300">—</span>
                                        <div class="flex-1">
                                            <input type="number" placeholder="Max" wire:model="form.max_unidades" class="w-full border-slate-100 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-center py-3 text-lg font-bold" />
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 bg-indigo-50/50 dark:bg-indigo-950/20 rounded-3xl border border-indigo-100 dark:border-indigo-900/50 shadow-sm">
                                    <span class="block text-[11px] font-black text-indigo-400 uppercase tracking-widest mb-4 text-center">Bonificación Aplicada</span>
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-1">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-indigo-400 font-bold">$</span>
                                            <input type="number" step="0.01" placeholder="0.00" wire:model="form.bonificacion" class="w-full pl-7 border-indigo-100 dark:border-indigo-800 dark:bg-slate-900 rounded-xl py-3 text-lg font-bold text-indigo-600" />
                                        </div>
                                        <div class="relative flex-1">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-indigo-400 font-bold">%</span>
                                            <input type="number" step="0.01" placeholder="0" wire:model="form.porcentaje" class="w-full pr-7 border-indigo-100 dark:border-indigo-800 dark:bg-slate-900 rounded-xl py-3 text-lg font-bold text-indigo-600" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">03. Fidelización</h3>
                            <p class="text-sm text-slate-500">Configure los puntos y beneficios extra que otorga esta regla.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Puntaje Mín.</label>
                                    <input type="number" wire:model="form.puntaje_minimo" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3 px-4 font-bold" />
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pts. Mensuales</label>
                                    <input type="number" wire:model="form.puntos_mensuales" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3 px-4 font-bold" />
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pts. Campaña</label>
                                    <input type="number" wire:model="form.puntos_por_campania" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3 px-4 font-bold" />
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Descr. del Puntaje</label>
                                    <textarea wire:model="form.puntaje_minimo_descripcion" rows="2" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Detalle de Beneficios</label>
                                    <textarea wire:model="form.beneficios" rows="2" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3"></textarea>
                                </div>
                            </div>
                        </div>
                    </section>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-4">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">04. Atributos</h3>
                            <p class="text-sm text-slate-500">Añada información técnica o etiquetas personalizadas.</p>
                            <button
                                type="button"
                                wire:click="addDatoRow"
                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-900 hover:opacity-90 transition-all shadow-lg shadow-slate-200 dark:shadow-none"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Nuevo Atributo
                            </button>
                        </div>
                        <div class="lg:col-span-2">
                            <div class="space-y-3">
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $form['datos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-3 rounded-2xl border border-slate-200 dark:border-slate-700 group transition-all hover:border-indigo-300">
                                        <div class="flex-1 grid grid-cols-2 gap-4">
                                            <input type="text" wire:model="form.datos.<?php echo e($index); ?>.key" placeholder="Ej: Color" class="border-none focus:ring-0 text-sm font-bold text-slate-900 dark:text-white bg-transparent" />
                                            <input type="text" wire:model="form.datos.<?php echo e($index); ?>.value" placeholder="Valor" class="border-none focus:ring-0 text-sm text-slate-600 dark:text-slate-400 bg-transparent border-l border-slate-100 dark:border-slate-700 pl-4" />
                                        </div>
                                        <button
                                            type="button"
                                            wire:click="removeDatoRow(<?php echo e($index); ?>)"
                                            class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-8 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl">
                                        <p class="text-sm text-slate-400 font-medium italic">No se han definido atributos adicionales.</p>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            <div class="px-10 py-6 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col sm:flex-row gap-4 sm:justify-end items-center">
                <button
                    type="button"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-2xl text-sm font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
                    @click="open = false; $wire.closeModals()"
                >
                    Descartar Cambios
                </button>
                <button
                    type="submit"
                    form="createReglaForm"
                    class="w-full sm:w-auto px-10 py-3.5 rounded-2xl text-sm font-extrabold bg-indigo-600 text-white hover:bg-indigo-700 shadow-xl shadow-indigo-200 dark:shadow-none transition-all flex items-center justify-center gap-2 transform active:scale-95"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Confirmar y Guardar
                </button>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($showEditModal): ?>
    <div
        x-data="{ open: true }"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 bg-slate-900/70 backdrop-blur-md"
        @keydown.window.escape="open = false; $wire.closeModals()"
        @click.self="open = false; $wire.closeModals()"
    >
        <div
            class="w-full max-w-4xl bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-slate-200 dark:border-slate-800"
            @click.stop
        >
            <div class="relative px-10 py-8 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-amber-500 rounded-2xl shadow-lg shadow-amber-200 dark:shadow-none">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                                Editar Regla de Puntaje
                            </h2>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">
                                Modifique los parámetros de la regla seleccionada.
                            </p>
                        </div>
                    </div>
                    <button
                        class="p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all"
                        type="button"
                        @click="open = false; $wire.closeModals()"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="px-10 py-8 overflow-y-auto flex-1 bg-slate-50/50 dark:bg-slate-900/50">
                <form id="editReglaForm" wire:submit.prevent="updateRegla" class="space-y-10">
                    
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">01. Alcance</h3>
                            <p class="text-sm text-slate-500">Categorías que aplican a esta regla.</p>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Categorías Relacionadas</label>
                            <select
                                multiple
                                wire:model="form.categoria_ids"
                                class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-2xl shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-base py-3 min-h-[160px]"
                            >
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.categoria_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-500 font-semibold mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </section>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">02. Definición</h3>
                            <p class="text-sm text-slate-500">Límites y beneficio económico.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Descripción General</label>
                                <textarea
                                    wire:model="form.descripcion"
                                    rows="3"
                                    class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-2xl shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 text-base py-3"
                                ></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-500 font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                    <span class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 text-center">Rango de Unidades</span>
                                    <div class="flex items-center gap-3">
                                        <input type="number" placeholder="Min" wire:model="form.min_unidades" class="flex-1 border-slate-100 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-center py-3 text-lg font-bold" />
                                        <span class="text-slate-300">—</span>
                                        <input type="number" placeholder="Max" wire:model="form.max_unidades" class="flex-1 border-slate-100 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-center py-3 text-lg font-bold" />
                                    </div>
                                </div>

                                <div class="p-6 bg-indigo-50/50 dark:bg-indigo-950/20 rounded-3xl border border-indigo-100 dark:border-indigo-900/50 shadow-sm">
                                    <span class="block text-[11px] font-black text-indigo-400 uppercase tracking-widest mb-4 text-center">Bonificación Aplicada</span>
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-1">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-indigo-400 font-bold">$</span>
                                            <input type="number" step="0.01" wire:model="form.bonificacion" class="w-full pl-7 border-indigo-100 dark:border-indigo-800 dark:bg-slate-900 rounded-xl py-3 text-lg font-bold text-indigo-600" />
                                        </div>
                                        <div class="relative flex-1">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-indigo-400 font-bold">%</span>
                                            <input type="number" step="0.01" wire:model="form.porcentaje" class="w-full pr-7 border-indigo-100 dark:border-indigo-800 dark:bg-slate-900 rounded-xl py-3 text-lg font-bold text-indigo-600" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">03. Fidelización</h3>
                            <p class="text-sm text-slate-500">Puntos y beneficios extra.</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Puntaje Mín.</label>
                                    <input type="number" wire:model="form.puntaje_minimo" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3 px-4 font-bold" />
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pts. Mensuales</label>
                                    <input type="number" wire:model="form.puntos_mensuales" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3 px-4 font-bold" />
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pts. Campaña</label>
                                    <input type="number" wire:model="form.puntos_por_campania" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3 px-4 font-bold" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Descr. del Puntaje</label>
                                    <textarea wire:model="form.puntaje_minimo_descripcion" rows="2" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Detalle de Beneficios</label>
                                    <textarea wire:model="form.beneficios" rows="2" class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl py-3"></textarea>
                                </div>
                            </div>
                        </div>
                    </section>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-4">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">04. Atributos</h3>
                            <p class="text-sm text-slate-500">Información técnica personalizada.</p>
                            <button type="button" wire:click="addDatoRow" class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-900 hover:opacity-90 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Añadir Atributo
                            </button>
                        </div>
                        <div class="lg:col-span-2">
                            <div class="space-y-3">
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $form['datos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-3 rounded-2xl border border-slate-200 dark:border-slate-700 group transition-all hover:border-indigo-300">
                                        <div class="flex-1 grid grid-cols-2 gap-4">
                                            <input type="text" wire:model="form.datos.<?php echo e($index); ?>.key" placeholder="Propiedad" class="border-none focus:ring-0 text-sm font-bold text-slate-900 dark:text-white bg-transparent" />
                                            <input type="text" wire:model="form.datos.<?php echo e($index); ?>.value" placeholder="Valor" class="border-none focus:ring-0 text-sm text-slate-600 dark:text-slate-400 bg-transparent border-l border-slate-100 dark:border-slate-700 pl-4" />
                                        </div>
                                        <button type="button" wire:click="removeDatoRow(<?php echo e($index); ?>)" class="p-2 text-slate-300 hover:text-red-500 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-8 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl text-slate-400 text-sm italic">
                                        Sin atributos adicionales definidos.
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            <div class="px-10 py-6 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col sm:flex-row gap-4 sm:justify-end items-center">
                <button
                    type="button"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-2xl text-sm font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
                    @click="open = false; $wire.closeModals()"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    form="editReglaForm"
                    class="w-full sm:w-auto px-10 py-3.5 rounded-2xl text-sm font-extrabold bg-amber-500 text-white hover:bg-amber-600 shadow-xl shadow-amber-200 dark:shadow-none transition-all flex items-center justify-center gap-2 transform active:scale-95"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Actualizar Regla
                </button>
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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/9bcae2ad942b560054b571b5af4df8da.blade.php ENDPATH**/ ?>