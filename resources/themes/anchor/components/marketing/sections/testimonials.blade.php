<section id="beneficios" class="py-16 border-t bg-white border-[#d7539a]/20 sm:py-24">
    <x-container>
        <x-marketing.elements.heading
            level="h2"
            title="Todo lo que necesitas para vender con confianza"
            description="Desde capacitación continua hasta herramientas digitales, Alma Mía te acompaña en cada paso para que tu emprendimiento crezca de forma sostenible."
        />

        <div class="grid grid-cols-1 gap-6 mt-12 lg:grid-cols-3">

            <!-- Capacitación -->
            <div class="p-6 transition border rounded-2xl bg-[#d7539a]/10 border-[#d7539a]/30 shadow-sm hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white rounded-full shadow-sm">
                        <x-phosphor-megaphone-simple class="w-6 h-6 text-[#d7539a]" />
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-[#d7539a] bg-white rounded-full shadow-sm">Capacitación</span>
                </div>
                <h3 class="text-xl font-semibold text-[#294395]">Formación y comunidad</h3>
                <p class="mt-3 text-sm leading-relaxed text-[#294395]/80">
                    Accedé a talleres en línea, guías de producto y acompañamiento de líderes para mejorar tus ventas.
                </p>
            </div>

            <!-- Catálogo -->
            <div class="p-6 transition border rounded-2xl bg-white border-[#d7539a]/30 shadow-sm hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#d7539a]/10 rounded-full shadow-sm">
                        <x-phosphor-shopping-bag-open class="w-6 h-6 text-[#d7539a]" />
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-[#d7539a] bg-[#d7539a]/10 rounded-full shadow-sm">Catálogo</span>
                </div>
                <h3 class="text-xl font-semibold text-[#294395]">Productos listos para enamorar</h3>
                <p class="mt-3 text-sm leading-relaxed text-[#294395]/80">
                    Fragancias con stock asegurado, kits de inicio y descuentos escalonados para que tu margen crezca.
                </p>
            </div>

            <!-- Soporte -->
            <div class="p-6 transition border rounded-2xl bg-white border-[#d7539a]/30 shadow-sm hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#d7539a]/10 rounded-full shadow-sm">
                        <x-phosphor-phone-call class="w-6 h-6 text-[#d7539a]" />
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-[#d7539a] bg-[#d7539a]/10 rounded-full shadow-sm">Soporte</span>
                </div>
                <h3 class="text-xl font-semibold text-[#294395]">Acompañamiento cercano</h3>
                <p class="mt-3 text-sm leading-relaxed text-[#294395]/80">
                    Atención personalizada para tus pedidos, garantías claras y materiales promocionales siempre actualizados.
                </p>
            </div>
        </div>
    </x-container>
</section>



<section class="w-full">
    <x-marketing.elements.heading 
        level="h2" 
        title="Estructura de roles comerciales" 
        description="Cada rango cumple un rol clave dentro del crecimiento y la organización del equipo de ventas." 
    />

    <ul role="list" class="grid grid-cols-1 gap-12 py-12 mx-auto max-w-2xl lg:max-w-none lg:grid-cols-3">

        <!-- VENDEDORA -->
        <li>
            <figure class="flex flex-col justify-between h-full">
                <blockquote>
                    <p class="text-sm sm:text-base font-medium text-[#294395]/80">
                        La Vendedora es quien inicia el vínculo con el cliente, presenta los productos, genera confianza y concreta las ventas. 
                        Es el primer contacto directo con el público y la base del crecimiento comercial.
                    </p>
                </blockquote>

                <figcaption class="flex flex-col justify-between mt-6">
                    <img 
                        alt="Vendedora" 
                        src="https://images.unsplash.com/photo-1596495577886-d920f1fb7238?auto=format&fit=crop&w=300&q=80" 
                        class="object-cover rounded-full grayscale size-14"
                    >
                    <div class="mt-4">
                        <div class="font-medium text-[#294395]">Vendedora</div>
                        <div class="mt-1 text-sm text-[#d7539a]">
                            Contacto directo y motor de las ventas
                        </div>
                    </div>
                </figcaption>
            </figure>
        </li>

        <!-- LÍDER -->
        <li>
            <figure class="flex flex-col justify-between h-full">
                <blockquote>
                    <p class="text-sm sm:text-base font-medium text-[#294395]/80">
                        La Líder acompaña, guía y potencia a su equipo de vendedoras. Supervisa objetivos, brinda apoyo estratégico 
                        y se asegura de que cada integrante alcance su máximo rendimiento.
                    </p>
                </blockquote>

                <figcaption class="flex flex-col justify-between mt-6">
                    <img 
                        alt="Líder" 
                        src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=300&q=80" 
                        class="object-cover rounded-full grayscale size-14"
                    >
                    <div class="mt-4">
                        <div class="font-medium text-[#294395]">Líder</div>
                        <div class="mt-1 text-sm text-[#d7539a]">
                            Coordinación y acompañamiento de equipos
                        </div>
                    </div>
                </figcaption>
            </figure>
        </li>

        <!-- COORDINADORA -->
        <li>
            <figure class="flex flex-col justify-between h-full">
                <blockquote>
                    <p class="text-sm sm:text-base font-medium text-[#294395]/80">
                        La Coordinadora supervisa la estructura general, define estrategias comerciales y optimiza los procesos 
                        de crecimiento. Su rol es clave para la organización y expansión del sistema.
                    </p>
                </blockquote>

                <figcaption class="flex flex-col justify-between mt-6">
                    <img 
                        alt="Coordinadora" 
                        src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=300&q=80" 
                        class="object-cover rounded-full grayscale size-14"
                    >
                    <div class="mt-4">
                        <div class="font-medium text-[#294395]">Coordinadora</div>
                        <div class="mt-1 text-sm text-[#d7539a]">
                            Gestión estratégica y liderazgo general
                        </div>
                    </div>
                </figcaption>
            </figure>
        </li>

    </ul>
</section>
