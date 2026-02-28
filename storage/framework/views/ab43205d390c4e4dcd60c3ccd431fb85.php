<?php

use function Laravel\Folio\name;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

?>


<?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['class' => 'py-4 max-w-[1600px] antialiased']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'py-4 max-w-[1600px] antialiased']); ?>
    
    <style>
        .os-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(203, 213, 225, 0.4);
        }
        .dark .os-glass {
            background: rgba(15, 15, 20, 0.85);
            border: 1px solid rgba(63, 63, 70, 0.4);
        }
        .os-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .os-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.1);
        }
        .filter-chip-active {
            background-color: #4f46e5;
            color: white;
            box-shadow: 0 4px 10px -2px rgba(79, 70, 229, 0.4);
        }
        
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
    
    

    <div class="flex flex-col gap-4">
        
        <div class="os-glass rounded-3xl p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-cpu-chip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-7 w-7 text-white']); ?>
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
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Administrador de Extensiones</h1>
                    <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-tighter opacity-70">Sistema Operativo Central • Alma Mía</p>
                </div>
            </div>

            
            <div class="flex items-center gap-3">
                <div class="relative group flex-1 lg:flex-none">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-magnifying-glass'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400']); ?>
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
                    <input type="text" placeholder="Buscar módulo o función..." 
                           class="w-full lg:w-72 pl-10 pr-4 py-2.5 text-sm bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                </div>
                <div class="h-10 w-px bg-slate-200 dark:bg-zinc-800 mx-2 hidden lg:block"></div>
                <button class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-xl transition-colors">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-arrow-down-tray'); ?>
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
                    Reporte Técnico
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 mt-2">
            
            <aside class="w-full lg:w-72 space-y-6">
                
                <nav class="os-glass rounded-[2rem] p-3 space-y-1">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = [
                        'mejoras' => ['nombre' => 'Explorar Catálogo', 'icon' => 'heroicon-o-rectangle-group'],
                        'modulos-activos' => ['nombre' => 'Sistemas Activos', 'icon' => 'heroicon-o-check-badge'],
                        'en-curso' => ['nombre' => 'En Desarrollo', 'icon' => 'heroicon-o-beaker']
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button wire:click="$set('tabActiva', '<?php echo e($id); ?>')"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all <?php echo e($tabActiva === $id 
                                ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' 
                                : 'text-slate-500 hover:bg-slate-50 dark:text-zinc-400 dark:hover:bg-zinc-800/50'); ?>">
                            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $item['icon']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                            <?php echo e($item['nombre']); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </nav>

                
                <!--[if BLOCK]><![endif]--><?php if($tabActiva === 'mejoras'): ?>
                <div class="px-2">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-2">Filtrar por Categoría</h3>
                    <div class="flex flex-wrap gap-2">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['Ventas', 'Finanzas', 'Logística', 'IA', 'Marketing', 'Estrategia']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-zinc-800 text-[11px] font-bold text-slate-600 dark:text-zinc-400 hover:border-indigo-500 transition-all">
                                <?php echo e($cat); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </aside>

            
            <main class="flex-1 min-h-[600px]">
                
                <!--[if BLOCK]><![endif]--><?php if($tabActiva === 'mejoras'): ?>
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $this->mejoras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mejora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="os-glass os-card rounded-[2rem] p-6 flex flex-col border-transparent hover:border-indigo-500/30">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="p-3.5 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600">
                                        <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $mejora['icono'] ?? 'heroicon-o-puzzle-piece'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-7 w-7']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Versión 1.0</span>
                                        <span class="mt-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-zinc-800 text-[9px] font-bold text-slate-500 uppercase"><?php echo e($mejora['estado']); ?></span>
                                    </div>
                                </div>
                                
                                <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight mb-2 tracking-tight"><?php echo e($mejora['titulo']); ?></h3>
                                <p class="text-xs font-bold text-indigo-600/70 uppercase tracking-wide mb-4"><?php echo e($mejora['subtitulo']); ?></p>
                                
                                <p class="text-sm text-slate-600 dark:text-zinc-400 line-clamp-3 leading-relaxed mb-6">
                                    <?php echo e($mejora['descripcion']); ?>

                                </p>

                                <div class="mt-auto pt-5 border-t border-slate-100 dark:border-zinc-800/50 flex items-center justify-between">
                                    <div class="flex gap-1">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = array_slice($mejora['etiquetas'] ?? [], 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="text-[9px] font-black text-slate-400 uppercase">#<?php echo e($tag); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <button wire:click="verMejora('<?php echo e($mejora['id']); ?>')" 
                                            class="px-4 py-2 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-black transition-transform active:scale-95">
                                        Detalles
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($tabActiva === 'modulos-activos'): ?>
                    <div class="os-glass rounded-[2.5rem] overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 dark:bg-zinc-900/50">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Identificador de Sistema</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Estado Operativo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $modulosActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors cursor-pointer">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="h-10 w-10 rounded-xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center shadow-sm">
                                                    <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $modulo['icon'] ?? 'heroicon-o-check-badge'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5 text-indigo-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="font-black text-slate-900 dark:text-white"><?php echo e($modulo['titulo']); ?></p>
                                                    <p class="text-xs text-slate-500 font-medium"><?php echo e(Str::limit($modulo['descripcion'], 60)); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-2">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                <span class="text-xs font-bold text-emerald-600 uppercase tracking-tighter">Activo y Seguro</span>
                                            </div>
                                        </td>
                                        
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($tabActiva === 'en-curso'): ?>
                    <div class="grid gap-4">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->mejorasEnCurso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mejora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="os-glass rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8">
                                <div class="flex-1 w-full text-center md:text-left">
                                    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight"><?php echo e($mejora['titulo']); ?></h3>
                                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest">Compilando Módulo</span>
                                    </div>
                                    <p class="text-sm text-slate-500 font-medium mb-6 leading-relaxed"><?php echo e($mejora['descripcion']); ?></p>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase mb-1">
                                            <span>Progreso de implementación</span>
                                            <span>45%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 dark:bg-zinc-800 h-3 rounded-full overflow-hidden p-0.5">
                                            <div class="bg-indigo-600 h-full rounded-full shadow-[0_0_15px_rgba(79,70,229,0.5)] transition-all duration-1000" style="width: 45%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden lg:block w-48 text-center border-l border-slate-100 dark:border-zinc-800 pl-8">
                                    <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Entrega estimada</p>
                                    <p class="text-lg font-black text-slate-900 dark:text-white">Marzo 2024</p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </main>
        </div>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($this->detalleMejora): ?>
        <div class="fixed inset-0 z-[99099] flex items-center justify-center p-4 md:p-10">
            
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity" wire:click="cerrarModal"></div>
            
            
            <div class="relative w-full max-w-6xl bg-white dark:bg-zinc-900 rounded-[3rem] shadow-[0_30px_100px_-20px_rgba(0,0,0,0.5)] border border-white/20 overflow-hidden flex flex-col max-h-[95vh] os-card">
                
                
                <div class="flex items-center justify-between p-8 bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center gap-6">
                        <div class="h-16 w-16 bg-indigo-600 rounded-[1.5rem] flex items-center justify-center shadow-xl shadow-indigo-500/30">
                            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $this->detalleMejora['icono'] ?? 'heroicon-o-cpu-chip'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-9 w-9 text-white']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter"><?php echo e($this->detalleMejora['titulo']); ?></h2>
                                <span class="px-3 py-1 rounded-lg bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-[10px] font-black uppercase">Core v1.0</span>
                            </div>
                            <p class="text-sm font-bold text-indigo-600/70 uppercase mt-1 tracking-widest"><?php echo e($this->detalleMejora['subtitulo']); ?></p>
                        </div>
                    </div>
                    <button wire:click="cerrarModal" class="p-4 rounded-full hover:bg-slate-200 dark:hover:bg-zinc-800 transition-all text-slate-400 active:scale-90">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-x-mark'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-7 w-7']); ?>
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

                <div class="flex-1 overflow-y-auto p-10 lg:p-14">
                    <div class="grid lg:grid-cols-12 gap-16">
                        
                        
                        <div class="lg:col-span-8 space-y-12">
                            <section>
                                <label class="text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-4 block">Ficha Descriptiva</label>
                                <p class="text-xl text-slate-600 dark:text-zinc-300 leading-relaxed font-medium">
                                    <?php echo e($this->detalleMejora['descripcion']); ?>

                                </p>
                            </section>

                            <section>
                                <label class="text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-6 block">Previsualización del Flujo</label>
                                <div class="grid gap-6">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->detalleMejora['ejemplos_html']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $html): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="p-8 rounded-[2rem] bg-slate-50 dark:bg-zinc-950/50 border border-slate-100 dark:border-zinc-800 group shadow-sm hover:shadow-md transition-shadow">
                                            <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed">
                                                <?php echo $html; ?>

                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </section>
                        </div>

                        
                        <div class="lg:col-span-4 space-y-8">
                            <div class="p-8 rounded-[2.5rem] bg-slate-900 dark:bg-black border border-white/5 space-y-8 shadow-2xl">
                                <div>
                                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Arquitectura de Licencia</label>
                                    <p class="mt-2 text-base font-bold text-white flex items-center gap-3">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-check-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6 w-6 text-indigo-400']); ?>
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
                                        <?php echo e($this->detalleMejora['licencia']); ?>

                                    </p>
                                </div>

                                <hr class="border-white/10">

                                <div>
                                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4 block">Modelos de Implementación</label>
                                    <div class="space-y-4">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->detalleMejora['precios']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:border-indigo-500/50 transition-colors group">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-[10px] font-black uppercase text-indigo-300"><?php echo e($p['concepto']); ?></span>
                                                    <span class="text-[9px] px-2 py-0.5 rounded bg-indigo-500 text-white font-black uppercase"><?php echo e($p['periodicidad']); ?></span>
                                                </div>
                                                <p class="text-2xl font-black text-white tracking-tighter"><?php echo e($p['monto']); ?></p>
                                                <p class="text-[10px] mt-2 font-medium text-slate-400 leading-tight"><?php echo e($p['detalle']); ?></p>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>

                                <button class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm shadow-2xl shadow-indigo-500/40 transition-all active:scale-95 uppercase tracking-widest">
                                    Solicitar Activación
                                </button>
                                
                                <p class="text-[9px] text-center text-slate-500 font-bold uppercase tracking-tighter">Sujeto a validación técnica de infraestructura</p>
                            </div>
                        </div>
                    </div>
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
<?php /**PATH /home/unquxtyh/public_html/storage/framework/views/9ca0be3e8398ae641431394cb933167f.blade.php ENDPATH**/ ?>