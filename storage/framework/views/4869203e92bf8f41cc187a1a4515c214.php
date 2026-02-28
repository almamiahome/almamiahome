<?php

use function Laravel\Folio\name;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

?>


    <?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['class' => 'max-w-[1800px] py-8']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'max-w-[1800px] py-8']); ?>
        
        <style>
            .liquid-glass {
                background: rgba(255, 255, 255, 0.45);
                backdrop-filter: blur(25px) saturate(180%);
                -webkit-backdrop-filter: blur(25px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
            }
            .dark .liquid-glass {
                background: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4);
            }
            .glass-input {
                background: rgba(255, 255, 255, 0.5) !important;
                border: 1px solid rgba(148, 163, 184, 0.2) !important;
                transition: all 0.3s ease;
            }
            .dark .glass-input {
                background: rgba(30, 41, 59, 0.5) !important;
                border: 1px solid rgba(71, 85, 105, 0.3) !important;
            }
            .glass-input:focus {
                background: rgba(255, 255, 255, 0.8) !important;
                border-color: #6366f1 !important;
                ring: 2px ring rgba(99, 102, 241, 0.2);
            }
            /* Scrollbar personalizado */
            .custom-scroll::-webkit-scrollbar { width: 5px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.3); border-radius: 10px; }
            
            /* Añade esto a tu sección de estilos existente */
body {
    background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop');
    background-size: cover;
    background-position: center;
    background-attachment: fixed; /* Crucial para el efecto glass al hacer scroll */
    background-repeat: no-repeat;
}

/* Ajuste opcional: añade una capa de tinte para mejorar el contraste */
body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.1); /* Tinte claro para modo luz */
    z-index: -1;
}

.dark body::before {
    background: rgba(15, 23, 42, 0.4); /* Tinte oscuro para modo noche */
}
        </style>

        <div class="space-y-6">
            
            <header class="liquid-glass rounded-[2.5rem] p-6 flex flex-wrap items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-cpu-chip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-8 w-8 text-white']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Core Modules Builder</h1>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-500 dark:text-indigo-400">JSON Infrastructure v2.0</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <button wire:click="descartarCambios" class="px-6 py-2.5 rounded-2xl text-sm font-bold text-slate-600 hover:bg-white/50 dark:text-slate-300 transition-all">
                        Descartar
                    </button>
                    <button wire:click="guardar" class="px-8 py-2.5 rounded-2xl text-sm font-black bg-slate-900 text-white hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-500 shadow-xl shadow-indigo-500/10 transition-all active:scale-95">
                        Sincronizar Cambios
                    </button>
                </div>
            </header>

            
            <!--[if BLOCK]><![endif]--><?php if($mensajeExito || $mensajeError || $errorParseo): ?>
                <div class="grid gap-3">
                    <!--[if BLOCK]><![endif]--><?php if($mensajeExito): ?> <div class="liquid-glass border-emerald-500/50 rounded-2xl p-4 text-emerald-600 dark:text-emerald-400 text-sm font-bold flex items-center gap-2"><?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-check-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?> <?php echo e($mensajeExito); ?></div> <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if($mensajeError): ?> <div class="liquid-glass border-rose-500/50 rounded-2xl p-4 text-rose-600 text-sm font-bold flex items-center gap-2"><?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-x-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?> <?php echo e($mensajeError); ?></div> <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <div class="grid lg:grid-cols-12 gap-6">
                
                
                <aside class="lg:col-span-3 space-y-6">
                    
                    <div class="liquid-glass rounded-[2rem] p-5">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Archivos Fuente</h2>
                            <button wire:click="nuevoArchivo" class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-all">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                            </button>
                        </div>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto custom-scroll pr-2">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $archivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $archivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button wire:click="seleccionarArchivo('<?php echo e($archivo['nombre']); ?>')" 
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all <?php echo e($archivoSeleccionado === $archivo['nombre'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-white/40 dark:text-slate-400 dark:hover:bg-slate-800/40'); ?>">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-document-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4 opacity-70']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                    <span class="truncate"><?php echo e($archivo['nombre']); ?></span>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div class="liquid-glass rounded-[2rem] p-5">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Módulos Activos</h2>
                            <!--[if BLOCK]><![endif]--><?php if($archivoSeleccionado): ?>
                                <button wire:click="nuevoModulo" class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="space-y-1.5 max-h-[400px] overflow-y-auto custom-scroll pr-2">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indice => $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <button wire:click="seleccionarModulo(<?php echo e($indice); ?>)" 
                                    class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-bold transition-all <?php echo e($indiceModuloSeleccionado === $indice ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white/30 dark:bg-slate-800/30 text-slate-600 dark:text-slate-400 hover:border-emerald-500/50 border border-transparent'); ?>">
                                    <span class="truncate"><?php echo e($modulo['titulo'] ?? 'Módulo sin nombre'); ?></span>
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4 opacity-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-center py-4 text-xs font-medium text-slate-400 italic">Seleccione un archivo para ver módulos</p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </aside>

                
                <main class="lg:col-span-6 space-y-6">
                    <div class="liquid-glass rounded-[2.5rem] p-8">
                        <!--[if BLOCK]><![endif]--><?php if($indiceModuloSeleccionado !== null): ?>
                            <div class="space-y-8">
                                
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Título del Módulo</label>
                                        <input wire:model.live="form.titulo" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-bold" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Subtítulo / Lead</label>
                                        <input wire:model.live="form.subtitulo" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-bold" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Estado de Implementación</label>
                                        <select wire:model.live="form.estado" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-bold">
                                            <option value="instalado">✅ Instalado</option>
                                            <option value="en progreso">🚧 En progreso</option>
                                            <option value="pendiente">⏳ Pendiente</option>
                                            <option value="no instalado">❌ No instalado</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Identificador de Icono</label>
                                        <div class="flex gap-2">
                                            <input wire:model.live="form.icono" class="glass-input flex-1 rounded-2xl px-4 py-3 text-sm font-mono" />
                                            <button wire:click="abrirModalIcono" class="px-4 rounded-2xl bg-indigo-500/10 text-indigo-600 text-xs font-black uppercase hover:bg-indigo-500 hover:text-white transition-all">Explorar</button>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-slate-200 dark:border-slate-700/50">

                                
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['tipos_planes' => 'Arquitectura de Planes', 'etiquetas' => 'Keywords & SEO', 'categorias' => 'Taxonomía']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $titulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center px-2">
                                            <h3 class="text-xs font-black uppercase tracking-widest text-indigo-500"><?php echo e($titulo); ?></h3>
                                            <button wire:click="agregarItem('<?php echo e($campo); ?>')" class="flex items-center gap-1 text-[10px] font-black bg-indigo-500 text-white px-3 py-1 rounded-full uppercase">Nuevo</button>
                                        </div>
                                        <div class="grid gap-3">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ($form[$campo] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $valor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="flex gap-2 group">
                                                    <input wire:model.live="form.<?php echo e($campo); ?>.<?php echo e($i); ?>" class="glass-input flex-1 rounded-xl px-4 py-2 text-sm" />
                                                    <button wire:click="duplicarItem('<?php echo e($campo); ?>', <?php echo e($i); ?>)" class="p-2 opacity-0 group-hover:opacity-100 bg-slate-100 dark:bg-slate-800 rounded-xl transition-all"><?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-document-duplicate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?></button>
                                                    <button wire:click="eliminarItem('<?php echo e($campo); ?>', <?php echo e($i); ?>)" class="p-2 bg-rose-500/10 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all"><?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-trash'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?></button>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                                
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center px-2">
                                        <h3 class="text-xs font-black uppercase tracking-widest text-indigo-500">Bloques de Código (HTML)</h3>
                                        <button wire:click="agregarItem('ejemplos_html')" class="text-[10px] font-black bg-indigo-500 text-white px-3 py-1 rounded-full uppercase">Añadir Sandbox</button>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ($form['ejemplos_html'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ejemplo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="liquid-glass border-slate-200/50 rounded-2xl p-4 space-y-3">
                                            <input wire:model.live="form.ejemplos_html.<?php echo e($i); ?>.titulo" placeholder="Nombre del bloque..." class="glass-input w-full rounded-xl px-4 py-2 text-sm font-bold" />
                                            <textarea wire:model.live="form.ejemplos_html.<?php echo e($i); ?>.html" wire:keyup="actualizarPreview" rows="5" class="glass-input w-full rounded-xl px-4 py-3 text-xs font-mono" placeholder="<div class='...'></div>"></textarea>
                                            <div class="flex justify-end gap-2">
                                                <button wire:click="duplicarItem('ejemplos_html', <?php echo e($i); ?>)" class="text-[10px] font-bold px-3 py-1 bg-slate-200 dark:bg-slate-700 rounded-lg">Duplicar</button>
                                                <button wire:click="eliminarItem('ejemplos_html', <?php echo e($i); ?>)" class="text-[10px] font-bold px-3 py-1 bg-rose-500 text-white rounded-lg">Eliminar</button>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="h-96 flex flex-col items-center justify-center text-center space-y-4">
                                <div class="h-20 w-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-cursor-arrow-ripple'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-10 w-10']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                </div>
                                <p class="text-slate-500 font-medium">Seleccione un módulo para comenzar la edición</p>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </main>

                
                <aside class="lg:col-span-3 space-y-6">
                    <div class="liquid-glass rounded-[2rem] p-6 sticky top-8">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Live Preview (Sanitized)</h2>
                        </div>
                        
                        <div class="rounded-2xl bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 p-4 min-h-[500px] overflow-auto custom-scroll shadow-inner">
                            <!--[if BLOCK]><![endif]--><?php if($htmlPrevisualizacion): ?>
                                <?php echo $htmlPrevisualizacion; ?>

                            <?php else: ?>
                                <div class="h-full flex items-center justify-center italic text-slate-400 text-xs">Sin contenido HTML</div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700/50">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-[10px] font-black uppercase text-slate-400">Tokens personalizados</h3>
                                <button wire:click="abrirModalDatoPersonalizado" class="p-1 rounded bg-indigo-500 text-white"><?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-3 w-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?></button>
                            </div>
                            <div class="space-y-2">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ($form['dato_personalizado'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clave => $valor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex flex-col p-2 bg-indigo-500/5 rounded-lg border border-indigo-500/10">
                                        <span class="text-[9px] font-black text-indigo-500 truncate">{{ {{ $clave }} }}</span>
                                        <input wire:model.live="form.dato_personalizado.<?php echo e($clave); ?>" class="bg-transparent border-none p-0 text-xs font-bold focus:ring-0 text-slate-700 dark:text-slate-300" />
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        
        <!--[if BLOCK]><![endif]--><?php if($mostrarModalIcono): ?>
            <div class="fixed inset-0 z-50 backdrop-blur-md flex items-center justify-center p-6 bg-slate-950/20">
                <div class="liquid-glass rounded-[3rem] w-full max-w-2xl p-8 shadow-2xl border-white/50">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-xl font-black">Librería de Assets</h3>
                            <p class="text-xs text-slate-500">Seleccione un icono de Heroicons para su módulo</p>
                        </div>
                        <button wire:click="$set('mostrarModalIcono', false)" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-x-mark'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6 w-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                        </button>
                    </div>
                    
                    <input wire:model.live="busquedaIcono" placeholder="Filtrar por nombre (ej: star, user, home...)" class="glass-input w-full rounded-2xl px-6 py-4 mb-6 text-sm font-bold" />
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-80 overflow-y-auto custom-scroll pr-2 mb-6">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $iconosDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icono): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php if(str_contains($icono, $busquedaIcono)): ?>
                                <button wire:click="aplicarIcono('<?php echo e($icono); ?>')" class="flex items-center gap-3 p-3 rounded-2xl bg-white/40 dark:bg-slate-800/40 border border-transparent hover:border-indigo-500 transition-all text-left">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500 font-bold text-xs">
                                        Icon
                                    </div>
                                    <span class="text-xs font-bold truncate"><?php echo e($icono); ?></span>
                                </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="flex gap-4">
                        <input wire:model.live="iconoPersonalizado" placeholder="O pegue identificador manual..." class="glass-input flex-1 rounded-2xl px-4 py-3 text-sm font-mono" />
                        <button wire:click="aplicarIconoPersonalizado" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:shadow-lg hover:shadow-indigo-500/30 transition-all">Vincular</button>
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($mostrarModalDatoPersonalizado): ?>
            <div class="fixed inset-0 z-50 backdrop-blur-md flex items-center justify-center p-6 bg-slate-950/20">
                <div class="liquid-glass rounded-[3rem] w-full max-w-md p-8 shadow-2xl">
                    <h3 class="text-xl font-black mb-6">Nuevo Token Dinámico</h3>
                    <div class="space-y-4 mb-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Clave (Sin espacios)</label>
                            <input wire:model.live="nuevaClavePersonalizada" placeholder="ej: color_destacado" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-mono" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Valor Inicial</label>
                            <input wire:model.live="nuevoValorPersonalizado" placeholder="ej: #FF5500" class="glass-input w-full rounded-2xl px-4 py-3 text-sm font-bold" />
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="$set('mostrarModalDatoPersonalizado', false)" class="flex-1 py-3 font-bold text-slate-500">Cancelar</button>
                        <button wire:click="agregarDatoPersonalizado" class="flex-2 px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-indigo-500/20">Registrar Token</button>
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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/1eb731d57801c5bf15cdad7f9237dda6.blade.php ENDPATH**/ ?>