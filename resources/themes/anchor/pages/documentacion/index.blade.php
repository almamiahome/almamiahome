<?php
    /**
     * Documentación general del sistema Alma Mia Fragancias.
     *
     * Describe la arquitectura, los módulos clave y la navegación del
     * sidebar para que el equipo comprenda cómo se organiza la plataforma.
     */

    use function Laravel\Folio\{name};
    name('documentacion');
?>

<x-layouts.marketing>
    <x-container class="py-12 sm:py-20 space-y-12">
        {{-- Portada --}}
        <div class="space-y-4">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-primary-600">Documentación interna</p>
            <h1 class="text-4xl md:text-5xl font-bold text-primary-700">Guía completa del sistema Alma Mia</h1>
            <p class="text-base md:text-lg text-gray-600 dark:text-gray-400 max-w-4xl">
                Esta página resume la arquitectura del proyecto, explica cada área funcional y detalla
                el funcionamiento del menú lateral (sidebar) para todos los roles: vendedora, líder,
                coordinadora y administradora. Es el punto de partida para nuevas incorporaciones al
                equipo de desarrollo o de operaciones.
            </p>
        </div>

        {{-- Arquitectura y tecnologías --}}
        <div class="grid gap-8 lg:grid-cols-12">
            <div class="lg:col-span-7 space-y-4">
                <h2 class="text-3xl font-semibold text-primary-700">Arquitectura técnica</h2>
                <p class="text-gray-600 dark:text-gray-400">
                    La plataforma está construida sobre Laravel 12 y se apoya en Wave como base SaaS.
                    Utiliza la pila TALL (TailwindCSS, Alpine.js, Livewire 3 y Volt) y ruteo basado en
                    archivos con Laravel Folio. Los roles y permisos se gestionan con Spatie Permission
                    y la autenticación se refuerza con 2FA, JWT para API y Stripe para facturación.
                </p>
                <ul class="list-disc pl-6 space-y-2 text-gray-600 dark:text-gray-400">
                    <li><strong>Backend:</strong> Laravel 12 + Wave, control de roles con Spatie.</li>
                    <li><strong>Frontend:</strong> TailwindCSS y componentes Blade del tema Anchor.</li>
                    <li><strong>Interactividad:</strong> Livewire 3 y Volt para vistas reactivas.</li>
                    <li><strong>Ruteo:</strong> Laravel Folio (sin archivos en <code>routes/</code>).</li>
                    <li><strong>Panel administrativo:</strong> Filament para gestión interna avanzada.</li>
                    <li><strong>Datos:</strong> MySQL/MariaDB con migraciones y seeders propios.</li>
                </ul>
            </div>
            <div class="lg:col-span-5">
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <h3 class="text-xl font-semibold text-primary-700 mb-3">Árbol de directorios clave</h3>
                    <ul class="text-sm space-y-2 text-gray-700 dark:text-gray-300">
                        <li><code>resources/themes/anchor</code>: Tema principal (layouts, componentes y páginas Folio).</li>
                        <li><code>app/Livewire</code> y <code>app/Volt</code>: componentes interactivos.</li>
                        <li><code>app/Models</code>: modelos con relaciones, fillables y casts.</li>
                        <li><code>database/migrations</code> y <code>database/seeders</code>: estructura y datos base.</li>
                        <li><code>config/wave.php</code>: ajustes de tema, color primario y opciones SaaS.</li>
                        <li><code>docs/</code>: notas funcionales y referencias internas.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Roles y alcance funcional --}}
        <div class="space-y-4">
            <h2 class="text-3xl font-semibold text-primary-700">Roles disponibles</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-4xl">
                El modelo de negocio se organiza en la jerarquía Vendedora → Líder → Coordinadora,
                junto con un rol Administrador para la operación central. Cada perfil habilita
                distintas secciones del sidebar y permisos específicos.
            </p>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="p-5 border rounded-xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-primary-700">Vendedora</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Accede a su catálogo, crea pedidos
                        propios y consulta su historial.</p>
                </div>
                <div class="p-5 border rounded-xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-primary-700">Líder</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Gestiona su red de vendedoras,
                        genera pedidos para el grupo y consulta reportes de zona.</p>
                </div>
                <div class="p-5 border rounded-xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-primary-700">Coordinadora</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Supervisa líderes, valida cierres
                        y visualiza resúmenes globales.</p>
                </div>
                <div class="p-5 border rounded-xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-primary-700">Administrador</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Administra catálogo, campañas,
                        finanzas, puntaje y configuraciones generales.</p>
                </div>
            </div>
        </div>

        {{-- Sidebar detallado --}}
        <div class="space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-semibold text-primary-700">Navegación lateral (sidebar)</h2>
                    <p class="text-gray-600 dark:text-gray-400 max-w-3xl">
                        El sidebar se encuentra en <code>resources/themes/anchor/components/app/sidebar.blade.php</code> y
                        controla la visibilidad de enlaces según el rol autenticado. A continuación se detallan sus
                        entradas principales y el objetivo de cada una.
                    </p>
                </div>
                <div class="p-4 border rounded-xl bg-primary-50 text-primary-800 border-primary-200 shadow-sm max-w-lg">
                    <p class="text-sm font-medium">Tip de edición</p>
                    <p class="text-sm">Los textos e iconos se definen mediante componentes <code>&lt;x-app.sidebar-link&gt;</code>
                        y <code>&lt;x-app.sidebar-dropdown&gt;</code>. Cada bloque está envuelto en directivas
                        <code>@role</code> o <code>@hasanyrole</code> para restringir permisos.</p>
                </div>
            </div>

            <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="px-4 py-3 font-semibold text-sm text-zinc-700 dark:text-zinc-200">Elemento</div>
                    <div class="px-4 py-3 font-semibold text-sm text-zinc-700 dark:text-zinc-200">Descripción</div>
                    <div class="px-4 py-3 font-semibold text-sm text-zinc-700 dark:text-zinc-200">Roles con acceso</div>
                </div>
                <div class="divide-y divide-zinc-200 dark:divide-zinc-700 text-sm text-gray-700 dark:text-gray-300">
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Escritorio</div>
                        <div class="px-4 py-3">Tablero inicial con métricas personales y accesos rápidos.</div>
                        <div class="px-4 py-3">Todas las usuarias autenticadas.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Mis Pedidos / Crear Pedido / Catálogo</div>
                        <div class="px-4 py-3">Gestión directa del catálogo, creación de pedidos y consulta del historial propio.</div>
                        <div class="px-4 py-3">Vendedora y Líder.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Vendedoras</div>
                        <div class="px-4 py-3">Listado y seguimiento de la red de vendedoras.</div>
                        <div class="px-4 py-3">Líder y Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Líderes</div>
                        <div class="px-4 py-3">Administración de líderes, asignación de zonas y métricas.</div>
                        <div class="px-4 py-3">Coordinadora y Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Coordinadoras</div>
                        <div class="px-4 py-3">Gestión de coordinadoras y estructura superior.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Zona Líder / Zona Coordinadora</div>
                        <div class="px-4 py-3">Espacios operativos para gestionar indicadores y cierres de su zona.</div>
                        <div class="px-4 py-3">Líder o Coordinadora según corresponda.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Perfil</div>
                        <div class="px-4 py-3">Edición de datos personales, seguridad y preferencias.</div>
                        <div class="px-4 py-3">Todas las usuarias autenticadas.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Incorporar</div>
                        <div class="px-4 py-3">Alta de nuevas vendedoras y líderes según jerarquía.</div>
                        <div class="px-4 py-3">Líder, Coordinadora y Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Catálogo (Administración)</div>
                        <div class="px-4 py-3">Dropdown con edición de catálogo, categorías, productos, stock y rótulos.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Ventas</div>
                        <div class="px-4 py-3">Dropdown para campañas y seguimiento de pedidos corporativos.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Finanzas</div>
                        <div class="px-4 py-3">Dropdown con gastos administrativos, pagos y cobros.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Crecimiento</div>
                        <div class="px-4 py-3">Dropdown para puntaje, rangos y bonos por rol.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Cierre General</div>
                        <div class="px-4 py-3">Reportes de cierre global y resúmenes por rol.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Notificaciones</div>
                        <div class="px-4 py-3">Centro de alertas y mensajes internos.</div>
                        <div class="px-4 py-3">Todas las usuarias autenticadas.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div class="px-4 py-3 font-medium">Agente / Ajustes / Novedades / Editor</div>
                        <div class="px-4 py-3">Herramientas administrativas para IA interna, configuración de perfil Wave,
                            changelog y editor de plantillas.</div>
                        <div class="px-4 py-3">Administrador.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Módulos funcionales --}}
        <div class="space-y-4">
            <h2 class="text-3xl font-semibold text-primary-700">Módulos principales</h2>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm space-y-3">
                    <h3 class="text-xl font-semibold text-primary-700">Catálogo y productos</h3>
                    <p class="text-gray-600 dark:text-gray-400">Administración completa del catálogo oficial: categorías,
                        productos, stock y rótulos. Los administradores pueden editar desde el dropdown
                        "Catálogo"; las vendedoras consumen la versión pública en "Catálogo".</p>
                </div>
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm space-y-3">
                    <h3 class="text-xl font-semibold text-primary-700">Pedidos y campañas</h3>
                    <p class="text-gray-600 dark:text-gray-400">Permite crear pedidos, asociarlos a campañas activas y
                        seguir su ciclo (creación, envío y facturación). Las líderes pueden consolidar pedidos
                        de su red y las coordinadoras supervisan los cierres.</p>
                </div>
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm space-y-3">
                    <h3 class="text-xl font-semibold text-primary-700">Crecimiento y bonos</h3>
                    <p class="text-gray-600 dark:text-gray-400">El módulo de crecimiento controla las reglas de puntaje,
                        rangos y bonos diferenciados por rol (líderes y coordinadoras). Los reportes de cierre
                        general consolidan estos cálculos.</p>
                </div>
                <div class="p-6 border rounded-2xl bg-white/70 dark:bg-zinc-900/70 border-zinc-200 dark:border-zinc-700 shadow-sm space-y-3">
                    <h3 class="text-xl font-semibold text-primary-700">Finanzas y operaciones</h3>
                    <p class="text-gray-600 dark:text-gray-400">La sección de finanzas centraliza gastos administrativos,
                        pagos y cobros. Complementa la facturación Stripe provista por Wave y sirve de respaldo
                        operativo para el equipo interno.</p>
                </div>
            </div>
        </div>

        {{-- Personalización del tema --}}
        <div class="space-y-4">
            <h2 class="text-3xl font-semibold text-primary-700">Personalización visual</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-4xl">
                El color primario del tema se define en <code>config/wave.php</code> con la clave
                <code>primary_color</code>. Para adaptar la identidad de marca se recomienda:
            </p>
            <ul class="list-disc pl-6 space-y-2 text-gray-600 dark:text-gray-400">
                <li>Actualizar el valor de <code>primary_color</code> para propagarlo a botones y acentos.</li>
                <li>Reemplazar los SVG en <code>resources/views/components/logo.blade.php</code> y
                    <code>logo-icon.blade.php</code> por la versión corporativa.</li>
                <li>Sustituir los favicon en <code>public/wave/favicon.png</code> y
                    <code>public/wave/favicon-dark.png</code>.</li>
                <li>Usar clases de Tailwind en los componentes de <code>resources/themes/anchor/components</code>
                    para mantener consistencia sin tocar el core de Wave.</li>
            </ul>
        </div>

        {{-- Buenas prácticas --}}
        <div class="space-y-4">
            <h2 class="text-3xl font-semibold text-primary-700">Buenas prácticas de desarrollo</h2>
            <ol class="list-decimal pl-6 space-y-2 text-gray-600 dark:text-gray-400">
                <li>Registrar cambios estructurales con migraciones y mantener seeders oficiales sincronizados.</li>
                <li>Actualizar la documentación funcional en <code>docs/</code> y el README cuando haya cambios de negocio.</li>
                <li>Evitar modificaciones en <code>/wave/</code>; priorizar extensiones en <code>app/</code> y
                    <code>resources/themes/</code>.</li>
                <li>Antes de subir cambios ejecutar <code>php artisan test</code> y validar funcionalidades críticas.</li>
            </ol>
        </div>
    </x-container>
</x-layouts.marketing>