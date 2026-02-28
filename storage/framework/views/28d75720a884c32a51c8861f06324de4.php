<?php

use function Laravel\Folio\{middleware, name};
use App\Models\User;
use Livewire\Volt\Component;

?>


    <?php if (isset($component)) { $__componentOriginal08db35abc15b88d7e891883ef0dd6bed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08db35abc15b88d7e891883ef0dd6bed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['class' => 'relative py-8','id' => 'coordinadoras-section-container']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'relative py-8','id' => 'coordinadoras-section-container']); ?>
        
        <style>
            #coordinadoras-section-container .custom-scroll-pink::-webkit-scrollbar {
                height: 12px;
                width: 10px;
            }
            #coordinadoras-section-container .custom-scroll-pink::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 10px;
            }
            #coordinadoras-section-container .custom-scroll-pink::-webkit-scrollbar-thumb {
                background: linear-gradient(90deg, #ec4899 0%, #f43f5e 100%);
                border-radius: 10px;
                border: 3px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 0 15px rgba(236, 72, 153, 0.4);
            }

            #coordinadoras-section-container .glass-card {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.8);
            }
            .dark #coordinadoras-section-container .glass-card {
                background: rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>

        
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[5%] left-[20%] w-[35%] h-[35%] rounded-full bg-pink-500/10 blur-[110px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[25%] h-[25%] rounded-full bg-rose-500/10 blur-[90px]"></div>
        </div>

        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6 px-2">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Coordinadoras</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium tracking-tight">Gestión de equipo estratégico y asignación de roles de coordinación.</p>
            </div>
            
            <button wire:click="openAddModal" class="w-full sm:w-auto px-6 py-4 bg-pink-600 text-white rounded-2xl font-black text-sm hover:bg-pink-700 shadow-xl shadow-pink-500/20 transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Agregar coordinadora
            </button>
        </div>

        
        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
            <div class="animate-in slide-in-from-top-4 duration-500 p-4 mb-8 mx-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 shadow-lg backdrop-blur-md font-bold text-sm">
                <div class="bg-emerald-500 p-1 rounded-full text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <div class="relative custom-scroll-pink overflow-x-auto rounded-[2.5rem]">
            <!--[if BLOCK]><![endif]--><?php if($coordinadoras->isEmpty()): ?>
                <div class="glass-card rounded-[2.5rem] p-20 flex flex-col items-center text-center shadow-2xl">
                    <div class="h-20 w-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">No hay coordinadoras</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-xs mt-2 font-medium">Asigna una líder para comenzar a gestionar el equipo estratégico.</p>
                </div>
            <?php else: ?>
                <div class="glass-card rounded-[2.5rem] shadow-2xl overflow-hidden min-w-[900px]">
                    <table class="w-full text-left border-separate border-spacing-0">
                        <thead class="bg-slate-950/5">
                            <tr class="text-slate-500">
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">ID Ref</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Información Personal</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Roles Adicionales</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Fecha Registro</th>
                                <th class="px-8 py-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/30">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $coordinadoras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coordinadora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="group hover:bg-white/40 transition-all duration-300">
                                    <td class="px-8 py-5 text-slate-400 font-mono text-xs">
                                        #<?php echo e($coordinadora->id); ?>

                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-800 dark:text-white tracking-tight text-lg leading-tight"><?php echo e($coordinadora->name ?? 'Sin nombre'); ?></span>
                                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider leading-none mt-1"><?php echo e($coordinadora->email); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-wrap gap-1.5">
                                            <?php $hasOtherRoles = false; ?>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $coordinadora->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <!--[if BLOCK]><![endif]--><?php if($role->name !== 'coordinadora'): ?>
                                                    <?php $hasOtherRoles = true; ?>
                                                    <span class="px-2.5 py-1 text-[9px] font-black uppercase rounded-lg bg-white/80 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-500 shadow-sm">
                                                        <?php echo e($role->name); ?>

                                                    </span>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if(!$hasOtherRoles): ?>
                                                <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Único rol</span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-bold text-slate-500 dark:text-slate-400">
                                            <?php echo e($coordinadora->created_at?->translatedFormat('d M, Y')); ?>

                                        </div>
                                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter leading-none mt-1"><?php echo e($coordinadora->created_at?->format('H:i')); ?> hs</div>
                                    </td>
                                    <td class="px-8 py-5 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            type="button"
                                            x-data
                                            @click="if (confirm('¿Quitar el rol de coordinadora a <?php echo e($coordinadora->name); ?>?')) { $wire.removeCoordinadora(<?php echo e($coordinadora->id); ?>) }"
                                            class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white transition-all border border-rose-100 shadow-sm active:scale-95"
                                        >
                                            Quitar Rango
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <!--[if BLOCK]><![endif]--><?php if($showAddModal): ?>
            <div 
                x-data="{ open: true }"
                x-show="open"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                x-cloak
            >
                <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-md" @click="open = false; $wire.closeModal()"></div>
                
                <div class="relative w-full max-w-lg bg-white/95 dark:bg-zinc-900/95 rounded-[3rem] p-10 shadow-2xl border border-white dark:border-white/10 animate-in zoom-in-95 duration-200">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter uppercase leading-none">Vincular Coordinadora</h2>
                            <p class="text-sm font-medium text-slate-500 mt-2 tracking-tight">Asigna una líder al equipo de gestión superior.</p>
                        </div>
                        <button @click="open = false; $wire.closeModal()" class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-400 hover:text-rose-500 transition-colors">✕</button>
                    </div>

                    <div class="space-y-6">
                        <!--[if BLOCK]><![endif]--><?php if($usuariosDisponibles->isEmpty()): ?>
                            <div class="p-8 rounded-[2rem] bg-pink-50 dark:bg-pink-900/10 border border-pink-100 dark:border-pink-500/20 text-center">
                                <p class="text-sm font-black text-pink-600 uppercase tracking-widest leading-tight">Sin candidatas disponibles</p>
                                <p class="text-[11px] font-bold text-slate-500 mt-2 leading-relaxed">No se encontraron líderes que no tengan ya el rol de coordinadora.</p>
                            </div>
                        <?php else: ?>
                            <form wire:submit.prevent="addCoordinadora" class="space-y-6">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-4">Seleccionar Líder</label>
                                    <div class="relative">
                                        <select
                                            wire:model="form.user_id"
                                            class="w-full bg-slate-100 dark:bg-zinc-800 border-none rounded-2xl py-4 px-6 font-bold text-slate-700 dark:text-white focus:ring-4 focus:ring-pink-500/10 transition-all outline-none appearance-none cursor-pointer"
                                        >
                                            <option value="">Buscar por nombre o email...</option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $usuariosDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>">
                                                    <?php echo e($user->name ?? 'ID: '.$user->id); ?> (<?php echo e($user->email); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="mt-2 ml-4 block text-[10px] font-black text-rose-500 uppercase tracking-tight"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <div class="p-5 bg-pink-50/50 dark:bg-pink-900/10 rounded-[1.5rem] border border-pink-100/50 dark:border-pink-500/10 flex gap-4">
                                    <div class="h-10 w-10 shrink-0 bg-pink-100 dark:bg-pink-800/30 rounded-xl flex items-center justify-center text-pink-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-[11px] text-pink-700 dark:text-pink-400 leading-relaxed font-bold uppercase tracking-tight">
                                        Al confirmar, el usuario mantendrá sus roles actuales y se le otorgará acceso a las herramientas de coordinación superior.
                                    </p>
                                </div>

                                <button 
                                    type="submit" 
                                    wire:loading.attr="disabled"
                                    class="w-full py-4 bg-pink-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-pink-500/30 hover:bg-pink-700 transition-all active:scale-95 disabled:opacity-50"
                                >
                                    <span wire:loading.remove>Confirmar Asignación</span>
                                    <span wire:loading>Procesando...</span>
                                </button>
                            </form>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <button @click="open = false; $wire.closeModal()" class="w-full py-3 text-sm font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">
                            Cancelar
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
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/b552ad4dcd04d7ff00627931ab42f85c.blade.php ENDPATH**/ ?>