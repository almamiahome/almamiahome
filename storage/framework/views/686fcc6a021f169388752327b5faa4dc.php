<section 
    class="relative top-0 flex flex-col items-center justify-center w-full min-h-screen px-6 pt-24 overflow-hidden lg:min-h-[80vh] lg:px-12 xl:px-20"
>
    <!-- Fondo: mujer empoderada + velo para legibilidad -->
    <div class="absolute inset-0 -z-20">
        <img 
            src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1600&q=80" 
            alt="Mujer empoderada" 
            class="object-cover w-full h-full"
        >
    </div>
    <div class="absolute inset-0 bg-white/80 backdrop-blur-[2px] -z-10"></div>

    <!-- Manchitas de color con la nueva paleta -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-16 left-10 w-36 h-36 bg-[#d7539a]/30 blur-3xl rounded-full"></div>
        <div class="absolute bottom-10 right-16 w-40 h-40 bg-[#294395]/20 blur-3xl rounded-full"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center w-full gap-10 lg:flex-row lg:gap-16 lg:items-start">
        <!-- Columna izquierda -->
        <div class="flex flex-col w-full max-w-2xl gap-6 lg:w-1/2">
            <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold tracking-wide uppercase rounded-full text-[#294395] bg-[#d7539a]/15">
                Alma Mía · Red de Revendedoras
            </div>

            <h1 class="text-4xl font-bold leading-tight tracking-tight text-center text-[#294395] sm:text-5xl lg:text-left">
                Emprendé con Alma Mía y convertite en la revendedora que inspira a tu comunidad
            </h1>

            <p class="text-lg leading-relaxed text-center text-[#294395]/85 lg:text-left">
                Generá ingresos compartiendo fragancias que enamoran, con capacitaciones constantes, catálogos digitales y el respaldo de una marca creada para mujeres emprendedoras como vos.
            </p>

            <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center lg:justify-start">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['tag' => 'a','href' => '/register','size' => 'lg','class' => 'w-full sm:w-auto bg-[#d7539a] hover:bg-[#c34888] border-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','href' => '/register','size' => 'lg','class' => 'w-full sm:w-auto bg-[#d7539a] hover:bg-[#c34888] border-0']); ?>
                    Postulate hoy
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['tag' => 'a','href' => '#beneficios','size' => 'lg','color' => 'secondary','class' => 'w-full sm:w-auto border border-[#294395]/40 text-[#294395] bg-white/80 hover:bg-[#294395]/5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','href' => '#beneficios','size' => 'lg','color' => 'secondary','class' => 'w-full sm:w-auto border border-[#294395]/40 text-[#294395] bg-white/80 hover:bg-[#294395]/5']); ?>
                    Conocé los beneficios
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

            <div class="flex flex-wrap items-center gap-4 text-sm text-[#294395]/80">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-white rounded-full shadow-sm text-[#d7539a]">✔</span>
                    Entregas rápidas y stock garantizado
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-white rounded-full shadow-sm text-[#d7539a]">✔</span>
                    Bonos por desempeño y por referidas
                </div>
            </div>
        </div>

        <!-- Columna derecha: pasos -->
        <div class="relative flex justify-center w-full lg:w-1/2">
            <div class="absolute -inset-6 bg-gradient-to-tr from-[#d7539a]/12 via-white to-[#294395]/10 rounded-3xl blur-xl"></div>

            <div class="relative w-full max-w-xl p-6 bg-white border shadow-xl rounded-3xl border-[#d7539a]/30">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-semibold uppercase text-[#294395]">Tu próxima meta</p>
                    <span class="px-3 py-1 text-xs font-semibold text-white rounded-full bg-[#d7539a]/90">
                        Programa Revendedoras
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-[#d7539a]/12 text-[#d7539a] rounded-2xl">
                            1
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#294395]">Registrate sin costo</h3>
                            <p class="text-sm text-[#294395]/80">
                                Completá tu registro online y recibí tu kit digital con catálogos y material listo para empezar a vender.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-[#d7539a]/12 text-[#d7539a] rounded-2xl">
                            2
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#294395]">Hacé tu primer pedido</h3>
                            <p class="text-sm text-[#294395]/80">
                                Accedé a descuentos preferenciales y envíos ágiles para que siempre tengas stock disponible.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-[#d7539a]/12 text-[#d7539a] rounded-2xl">
                            3
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#294395]">Crecé acompañada</h3>
                            <p class="text-sm text-[#294395]/80">
                                Sumate a las capacitaciones, conectá con líderes y construí tu propio equipo para escalar tus ingresos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/components/marketing/sections/hero.blade.php ENDPATH**/ ?>