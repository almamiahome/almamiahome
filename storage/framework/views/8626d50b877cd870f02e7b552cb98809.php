<?php

use App\Models\Catalogo;
use App\Models\CatalogoPagina;
use App\Models\CatalogoPaginaProducto;
use App\Models\Categoria;
use App\Models\Category;
use App\Models\CierreCampana;
use App\Models\Cobro;
use App\Models\Forms;
use App\Models\GastoAdministrativo;
use App\Models\MetricaLiderCampana;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\PedidoArticulo;
use App\Models\Post;
use App\Models\Producto;
use App\Models\PuntajeRegla;
use App\Models\PremioRegla;
use App\Models\RangoLider;
use App\Models\RepartoCompra;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

?>


<div class="w-full"><!-- ÚNICO ROOT ELEMENT PARA LIVEWIRE/VOLT -->
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::app.container','data' => ['class' => 'space-y-8']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'space-y-8']); ?>
            
            

            
            <section class="flex flex-col gap-6 lg:min-h-[760px]">
                <div class="flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 sm:px-6">
                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-indigo-700">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-100">
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-chats-teardrop-duotone'); ?>
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
                            </span>
                            Conversación activa
                        </div>
                        <div class="flex items-center gap-2 text-[11px] text-slate-500">
                            <span class="hidden sm:inline">Historial de <?php echo e($maxTurnosUi); ?> turnos</span>
                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'inline-flex items-center gap-2 rounded-full px-3 py-1 font-semibold ring-1',
                                'bg-emerald-50 text-emerald-700 ring-emerald-100' => $conexionOk,
                                'bg-rose-50 text-rose-700 ring-rose-100' => ! $conexionOk,
                            ]); ?>">
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'h-2 w-2 rounded-full',
                                    'bg-emerald-500' => $conexionOk,
                                    'bg-rose-500' => ! $conexionOk,
                                ]); ?>"></span>
                                <?php echo e($conexionOk ? 'Conexión a la base activa' : 'Sin conexión a la base'); ?>

                            </span>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col bg-gradient-to-b from-slate-50 via-white to-slate-50">
                        <div
                            x-data="{ scroll() { const el = this.$refs.panel; if (el) { el.scrollTop = el.scrollHeight; } }}"
                            x-init="scroll()"
                            x-on:livewire:load.window="scroll()"
                            x-on:refresh-chat.window="scroll()"
                            class="flex-1 overflow-y-auto px-4 py-6 sm:px-6"
                            x-ref="panel">
                            <div class="space-y-4 pb-4">
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $conversacion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $turno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <!--[if BLOCK]><![endif]--><?php if($turno['rol'] === 'usuario'): ?>
                                        <div class="flex justify-end">
                                            <div class="max-w-3xl space-y-2 text-right">
                                                <div class="flex items-center justify-end gap-2 text-[11px] text-slate-500">
                                                    <span><?php echo e($turno['hora'] ?? '--:--'); ?></span>
                                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-600 text-sm font-semibold text-white shadow-sm ring-2 ring-indigo-200">
                                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-user-duotone'); ?>
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
<?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="inline-flex max-w-3xl flex-col gap-2 rounded-2xl bg-indigo-600 px-4 py-3 text-left text-sm text-white shadow-sm ring-1 ring-indigo-500/30">
                                                    <div class="leading-relaxed"><?php echo nl2br(e($turno['texto'])); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-white shadow-sm ring-2 ring-slate-300">
                                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-robot-duotone'); ?>
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
<?php endif; ?>
                                            </div>
                                            <div class="max-w-3xl space-y-2">
                                                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                                    <span class="font-semibold text-slate-700">Agente</span>
                                                    <span><?php echo e($turno['hora'] ?? '--:--'); ?></span>
                                                    <!--[if BLOCK]><![endif]--><?php if(! empty($turno['latencia'])): ?>
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-timer-duotone'); ?>
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
<?php endif; ?> <?php echo e($turno['latencia']); ?> ms
                                                        </span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if(! empty($turno['modelo'])): ?>
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200">
                                                            <?php echo e($turno['modelo']); ?>

                                                        </span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>

                                                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                    'rounded-2xl px-4 py-3 text-sm shadow-sm ring-1',
                                                    'bg-amber-50 text-amber-900 ring-amber-200' => $turno['alerta'] ?? false,
                                                    'bg-slate-100/80 text-slate-900 ring-slate-200' => ! ($turno['alerta'] ?? false),
                                                ]); ?>">
                                                    <div class="prose prose-sm max-w-none leading-relaxed text-slate-800">
                                                        <?php echo \Illuminate\Support\Str::markdown($turno['texto'] ?? ''); ?>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="flex h-full items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white/60 p-6 text-sm text-slate-500">
                                        Iniciá la conversación con una consulta en lenguaje natural.
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <div
                                    wire:loading.flex
                                    wire:target="consultar"
                                    class="flex items-center gap-3 rounded-2xl bg-white px-4 py-3 text-sm text-slate-700 shadow-sm ring-1 ring-slate-200">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-dots-three-outline-vertical-duotone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5 animate-pulse text-indigo-500']); ?>
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
                                    <span>El agente está pensando...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="border-t border-slate-100 bg-white/95 px-4 py-3 backdrop-blur sm:px-6">
                        <form wire:submit.prevent="consultar" class="space-y-4">
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span>Preguntá en lenguaje natural. El agente responde con el contexto de datos cargado.</span>
                                <span class="hidden sm:inline">Tiempo máximo 20s</span>
                            </div>

                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col gap-3">
                                    <label class="block">
                                        <span class="sr-only">Pregunta</span>
                                        <textarea
                                            id="pregunta"
                                            wire:model.defer="pregunta"
                                            rows="4"
                                            class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200"
                                            placeholder="Escribí tu consulta para que el agente responda con los datos ya extraídos de los modelos."></textarea>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['pregunta'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-1 text-sm text-rose-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </label>

                                    <div class="flex flex-col gap-2 text-sm font-semibold sm:flex-row sm:items-center sm:justify-start">
                                        <button
                                            type="submit"
                                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'inline-flex items-center justify-center gap-2 rounded-full px-4 py-2 text-white shadow-sm transition focus:outline-none focus:ring-2',
                                                'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-300' => $conexionOk,
                                                'cursor-not-allowed bg-slate-200 text-slate-500 ring-1 ring-slate-300' => ! $conexionOk,
                                            ]); ?>"
                                            wire:loading.attr="disabled"
                                            <?php if(! $conexionOk): echo 'disabled'; endif; ?>>
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-sparkle-duotone'); ?>
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
<?php endif; ?>
                                            <span wire:loading.remove>Enviar</span>
                                            <span wire:loading>Consultando…</span>
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-4 py-2 text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50"
                                            wire:click="limpiar">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-eraser-duotone'); ?>
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
<?php endif; ?>
                                            Limpiar campos
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-white shadow-sm ring-1 ring-slate-700 transition hover:bg-slate-800"
                                            wire:click="limpiarChat">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-chat-circle-duotone'); ?>
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
<?php endif; ?>
                                            Limpiar chat
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-2 text-sm font-semibold text-slate-700">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $ejemplos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ejemplo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button
                                            type="button"
                                            class="ejemplo flex w-full items-start gap-2 rounded-xl bg-slate-100 px-3 py-2 text-left ring-1 ring-slate-200 transition hover:bg-slate-200"
                                            wire:click="usarEjemplo(<?php echo \Illuminate\Support\Js::from($ejemplo)->toHtml() ?>)">
                                            <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-magic-wand-duotone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-0.5 h-4 w-4']); ?>
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
                                            <span><?php echo e($ejemplo); ?></span>
                                        </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!--[if BLOCK]><![endif]--><?php if($estado): ?>
                                    <p class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-700">
                                        <?php echo e($estado); ?>

                                    </p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            
            <section class="flex flex-col gap-6 rounded-3xl bg-gradient-to-r from-indigo-50 via-white to-sky-50 p-8 shadow-sm ring-1 ring-indigo-100">
                <div class="space-y-3">
                    <p class="inline-flex items-center gap-2 rounded-full bg-indigo-100 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-indigo-700 ring-1 ring-indigo-200">
                        Agente inteligente
                    </p>
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-semibold text-slate-900">
                                Consultá con Gemini usando el contexto de datos actual
                            </h1>
                            <p class="max-w-3xl text-slate-700">
                                El asistente resume la información existente en la base (productos, pedidos, campañas y personas)
                                y responde tus preguntas solo con esos datos. Ideal para validar hipótesis rápidas sobre ventas,
                                campañas y desempeño sin ejecutar SQL.
                            </p>
                            <div class="flex flex-wrap gap-3 text-sm text-indigo-800">
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 ring-1 ring-indigo-100">
                                    🔒 Acceso seguro: requiere sesión iniciada
                                </span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 ring-1 ring-indigo-100">
                                    ⚙️ Motor: Gemini
                                </span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 ring-1 ring-indigo-100">
                                    🔑 Rol actual: <?php echo e($rolActual ?: 'Sin rol asignado'); ?>

                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 rounded-2xl bg-white/80 p-4 shadow-sm ring-1 ring-indigo-100">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="text-sm font-semibold text-slate-800">Pautas de seguridad</h2>
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm ring-1 ring-indigo-300 transition hover:bg-indigo-700"
                                    wire:click="probarConexionGemini"
                                    wire:loading.attr="disabled">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-plug-charging-duotone'); ?>
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
                                    <span wire:loading.remove>Probar conexión</span>
                                    <span wire:loading>Probando…</span>
                                </button>
                            </div>
                            <ul class="space-y-2 text-sm text-slate-700">
                                <li class="flex gap-2">
                                    <span class="text-indigo-500">•</span>
                                    Gemini no ejecuta SQL: responde solo con los datos ya cargados desde los modelos.
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-indigo-500">•</span>
                                    Agregá contexto de negocio (fechas, roles, zonas) para guiar la respuesta.
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-indigo-500">•</span>
                                    Si el dato no está disponible, el agente avisará la limitación en vez de inventar resultados.
                                </li>
                            </ul>
                            <!--[if BLOCK]><![endif]--><?php if($estadoGemini): ?>
                                <p class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                                    <?php echo e($estadoGemini); ?>

                                </p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </section>

            
            <section class="space-y-4">
                <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100" <?php if(false): ?> open <?php endif; ?>>
                    <summary class="flex cursor-pointer items-center justify-between text-lg font-semibold text-slate-900">
                        <span>Datos cargados desde los modelos</span>
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-caret-down-duotone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5 transition group-open:rotate-180']); ?>
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
                    </summary>
                    <p class="mt-2 text-sm text-slate-600">
                        Gemini recibe un resumen fresco de las colecciones principales (máx. <?php echo e($maxMuestras); ?> ítems por modelo).
                    </p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-800">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $resumenDatos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clave => $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php ($muestras = collect($detalle['muestras'] ?? $detalle['recientes'] ?? [])); ?>
                            <li class="rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200">
                                <p class="font-semibold text-slate-900">
                                    <?php echo e(\Illuminate\Support\Str::headline($clave)); ?>

                                </p>
                                <p class="text-slate-600">
                                    Total: <?php echo e($detalle['total'] ?? 0); ?> registros
                                </p>
                                <!--[if BLOCK]><![endif]--><?php if($muestras->isNotEmpty()): ?>
                                    <p class="mt-2 text-xs text-slate-500">Ejemplos utilizados en el contexto:</p>
                                    <pre class="mt-1 max-h-40 overflow-y-auto rounded-xl bg-white px-3 py-2 text-[11px] leading-relaxed text-slate-800 ring-1 ring-slate-200"><?php echo e(json_encode($muestras->take(2), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                </details>

                <details class="group rounded-2xl bg-slate-900 p-6 text-white shadow-sm ring-1 ring-slate-800" <?php if(false): ?> open <?php endif; ?>>
                    <summary class="flex cursor-pointer items-center justify-between text-lg font-semibold">
                        <span>Tips para mejores respuestas</span>
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('phosphor-caret-down-duotone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5 transition group-open:rotate-180']); ?>
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
                    </summary>
                    <ul class="mt-3 space-y-2 text-sm text-slate-100">
                        <li class="flex gap-2">
                            <span class="text-sky-300">•</span>
                            Indicá períodos o campañas específicas para acotar el análisis.
                        </li>
                        <li class="flex gap-2">
                            <span class="text-sky-300">•</span>
                            Pedí KPIs clave (totales, promedios, variaciones) que te interesen.
                        </li>
                        <li class="flex gap-2">
                            <span class="text-sky-300">•</span>
                            Si segmentás, mencioná rol, zona o estado para filtrar el contexto cargado.
                        </li>
                        <li class="flex gap-2">
                            <span class="text-sky-300">•</span>
                            Si falta un dato, el agente lo indicará: evitá pedir operaciones fuera del contexto.
                        </li>
                    </ul>
                    <p class="mt-4 text-xs text-slate-200">
                        El agente no ejecuta consultas directas: solo usa la fotografía de datos recopilada antes de cada respuesta.
                    </p>
                </details>
            </section>
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
</div>
<?php /**PATH /home/unquxtyh/public_html/storage/framework/views/f77538d26d37221483971c3323d5f050.blade.php ENDPATH**/ ?>