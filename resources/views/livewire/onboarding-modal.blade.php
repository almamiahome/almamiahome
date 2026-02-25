<div
    x-data="{
        step: 1,
        maxStep: 3,
        show: @entangle('show').live,
        init() {
            if (this.show) {
                this.enforceLightMode();
            }
        },
        enforceLightMode() {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        },
    }"
    x-init="init()"
    x-effect="show ? enforceLightMode() : null"
>
    @if($show)
        @php
            // Arrays JSON desde settings (Wave helpers)
            $departamentos = json_decode(setting('almamia.departamentos.mendoza') ?? '[]', true) ?? [];
            $zonas = json_decode(setting('almamia.zona.mendoza') ?? '[]', true) ?? [];

            if (!is_array($departamentos)) {
                $departamentos = [];
            }
            if (!is_array($zonas)) {
                $zonas = [];
            }
        @endphp

        <div class="fixed inset-0 z-[9999] flex items-center justify-center overflow-y-auto px-4 py-8 sm:py-12">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm"></div>

            <div class="relative z-10 w-full max-w-3xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-zinc-900 sm:p-8 md:max-h-[90vh] md:overflow-y-auto">
                {{-- HEADER --}}
                <div class="mb-6 text-center">
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#294395] sm:text-sm">
                        Paso <span x-text="step"></span> de <span x-text="maxStep"></span>
                    </p>
                    <h2 class="mt-3 text-2xl font-bold text-slate-900 dark:text-white sm:text-3xl">
                        Bienvenida a AlmaMia
                    </h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 sm:text-base">
                        Necesitamos algunos datos básicos para personalizar tu experiencia y conectarte con tu zona.
                    </p>
                </div>

                {{-- INDICADOR DE PASOS --}}
                <div class="mb-6 space-y-3">
    {{-- Barra de progreso --}}
    <div class="relative h-2 w-full rounded-full bg-slate-200/80 overflow-hidden">
        <div
            class="absolute inset-y-0 left-0 rounded-full bg-[#d54794] transition-all duration-300 ease-out"
            :style="{ width: (step / maxStep * 100) + '%' }"
        ></div>
    </div>

    {{-- Etiquetas y números de paso --}}
    <div class="flex items-center justify-between text-[11px] sm:text-xs font-medium text-slate-500">
        <div class="flex flex-col items-start">
            <span :class="step >= 1 ? 'text-[#294395]' : ''">
                Paso 1
            </span>
            <span class="hidden sm:inline text-[11px] text-slate-400">
                Datos personales
            </span>
        </div>

        <div class="flex flex-col items-center">
            <span :class="step >= 2 ? 'text-[#294395]' : ''">
                Paso 2
            </span>
            <span class="hidden sm:inline text-[11px] text-slate-400">
                Dirección
            </span>
        </div>

        <div class="flex flex-col items-end">
            <span :class="step >= 3 ? 'text-[#294395]' : ''">
                Paso 3
            </span>
            <span class="hidden sm:inline text-[11px] text-slate-400">
                Rol y red
            </span>
        </div>
    </div>
</div>


                <form wire:submit.prevent="save" class="space-y-5">
                    {{-- PASO 1: DATOS PERSONALES --}}
                    <div x-show="step === 1" x-cloak class="space-y-5">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-name">
                                Nombre completo
                            </label>
                            <input
                                id="onboarding-name"
                                type="text"
                                wire:model.defer="name"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Ej: Ana Pérez"
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-dni">
                                DNI
                            </label>
                            <input
                                id="onboarding-dni"
                                type="text"
                                wire:model.defer="dni"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Sin puntos ni espacios"
                            >
                            @error('dni')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-whatsapp">
                                WhatsApp
                            </label>
                            <input
                                id="onboarding-whatsapp"
                                type="text"
                                wire:model.defer="whatsapp"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Número con código de área"
                            >
                            @error('whatsapp')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- PASO 2: DIRECCIÓN --}}
                    <div x-show="step === 2" x-cloak class="space-y-5">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-direccion">
                                Dirección
                            </label>
                            <input
                                id="onboarding-direccion"
                                type="text"
                                wire:model.defer="direccion"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                placeholder="Calle y número"
                            >
                            @error('direccion')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Departamento --}}
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-departamento">
                                Departamento
                            </label>
                            <select
                                id="onboarding-departamento"
                                wire:model.defer="departamento"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                            >
                                <option value="">Elegí un departamento</option>
                                @foreach($departamentos as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('departamento')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Zona --}}
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-zona">
                                Zona
                            </label>
                            <select
                                id="onboarding-zona"
                                wire:model.defer="zona"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                            >
                                <option value="">Elegí una zona</option>
                                @foreach($zonas as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('zona')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- PASO 3: ROL + RED --}}
                    <div x-show="step === 3" x-cloak class="space-y-8">
                        {{-- ROL --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                ¿Qué rol querés ocupar?
                            </label>
                            <p class="mb-3 text-sm text-slate-500 dark:text-slate-400">
                                Elegí si te sumarás como vendedora o como líder. Esto nos permite mostrarte los módulos correctos.
                            </p>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label @class([
                                    'flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-[#294395]',
                                    'border-[#294395] bg-[#294395]/5 text-[#294395]' => $role === 'vendedora',
                                ])>
                                    <input
                                        type="radio"
                                        class="mt-1"
                                        name="onboarding-role"
                                        value="vendedora"
                                        wire:model.live="role"
                                    >
                                    <span>
                                        Vendedora
                                        <span class="block text-xs font-normal text-slate-500">Accedés al catálogo y cargás tus pedidos.</span>
                                    </span>
                                </label>
                                <label @class([
                                    'flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-[#294395]',
                                    'border-[#294395] bg-[#294395]/5 text-[#294395]' => $role === 'lider',
                                ])>
                                    <input
                                        type="radio"
                                        class="mt-1"
                                        name="onboarding-role"
                                        value="lider"
                                        wire:model.live="role"
                                    >
                                    <span>
                                        Líder
                                        <span class="block text-xs font-normal text-slate-500">Gestionás a tu red y acompañás sus ventas.</span>
                                    </span>
                                </label>
                            </div>
                            @error('role')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror

                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Podés cambiar tu rol más adelante junto con tu coordinadora si es necesario.
                            </p>
                        </div>

                        {{-- RED (LÍDER / COORDINADORA) --}}
                        <div class="space-y-5">
                            @if($role === 'vendedora')
                                <div class="space-y-3">
                                    <div id="lider-input-group">
                                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-lider">
                                            ID de tu líder
                                        </label>
                                        <input
                                            id="onboarding-lider"
                                            type="number"
                                            wire:model.live="lider_id"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                            placeholder="Ingresá el ID exacto de quien te acompaña"
                                        >
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            El ID debe existir en AlmaMia; solicitá a tu líder que te lo comparta.
                                        </p>

                                        @if($liderSeleccionado)
                                            <p class="mt-2 text-xs text-emerald-600 dark:text-emerald-400">
                                                Te estás uniendo con:
                                                <span class="font-semibold">{{ $liderSeleccionado['name'] }}</span>
                                                (ID {{ $liderSeleccionado['id'] }})
                                            </p>
                                        @elseif($lider_id)
                                            <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                                                No encontramos una líder con ese ID. Revisalo con tu líder antes de continuar.
                                            </p>
                                        @endif

                                        @error('lider_id')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div id="lider-select-group" class="hidden">
                                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-lider-select">
                                            Seleccioná a tu líder disponible
                                        </label>
                                        <select
                                            id="onboarding-lider-select"
                                            wire:model.defer="lider_id"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                        >
                                            <option value="">Elegí una líder</option>
                                            @foreach($lideresDisponibles as $lider)
                                                <option value="{{ $lider['id'] }}">{{ $lider['name'] }} (ID {{ $lider['id'] }})</option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            Usá este listado solo si verificaste el nombre de tu líder.
                                        </p>
                                        @error('lider_id')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            @if($role === 'lider')
                                <div class="space-y-3">
                                    <div id="coordinadora-input-group">
                                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-coordinadora">
                                            ID de tu coordinadora
                                        </label>
                                        <input
                                            id="onboarding-coordinadora"
                                            type="number"
                                            wire:model.live="coordinadora_id"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                            placeholder="Ingresá el ID exacto de tu coordinadora"
                                        >
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            Verificamos que exista para mantener la red actualizada.
                                        </p>

                                        @if($coordinadoraSeleccionada)
                                            <p class="mt-2 text-xs text-emerald-600 dark:text-emerald-400">
                                                Tu coordinadora es:
                                                <span class="font-semibold">{{ $coordinadoraSeleccionada['name'] }}</span>
                                                (ID {{ $coordinadoraSeleccionada['id'] }})
                                            </p>
                                        @elseif($coordinadora_id)
                                            <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                                                No encontramos una coordinadora con ese ID. Revisalo con tu coordinadora antes de continuar.
                                            </p>
                                        @endif

                                        @error('coordinadora_id')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div id="coordinadora-select-group" class="hidden">
                                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-200" for="onboarding-coordinadora-select">
                                            Seleccioná a tu coordinadora disponible
                                        </label>
                                        <select
                                            id="onboarding-coordinadora-select"
                                            wire:model.defer="coordinadora_id"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 focus:border-[#294395] focus:ring-2 focus:ring-[#294395]/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                        >
                                            <option value="">Elegí una coordinadora</option>
                                            @foreach($coordinadorasDisponibles as $coordinadora)
                                                <option value="{{ $coordinadora['id'] }}">{{ $coordinadora['name'] }} (ID {{ $coordinadora['id'] }})</option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            Usá este listado solo si verificaste el nombre de tu coordinadora.
                                        </p>
                                        @error('coordinadora_id')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            @if(!$role)
                                <p class="text-sm text-amber-600 dark:text-amber-400">
                                    Primero seleccioná tu rol para definir si necesitás líder o coordinadora.
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- BOTONES --}}
{{-- Mobile: Siguiente/Guardar arriba, Atrás abajo.
     Desktop: Atrás izquierda, Siguiente/Guardar derecha --}}
<div class="flex flex-col gap-3 pt-4 sm:flex-row-reverse sm:items-center sm:justify-between">
    {{-- Siguiente / Guardar (siempre rosados) --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        {{-- Siguiente --}}
        <button
            type="button"
            x-show="step < maxStep"
            x-cloak
            @click="if(step < maxStep) step++"
            class="inline-flex w-full items-center justify-center rounded-xl px-6 py-3 text-base font-semibold text-white shadow-lg transition
                   bg-[#d54794] hover:bg-[#c03f86] shadow-[#d54794]/30
                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#d54794]
                   sm:w-auto"
        >
            Siguiente
        </button>

        {{-- Guardar --}}
        <button
            type="submit"
            x-show="step === maxStep"
            x-cloak
            wire:loading.attr="disabled"
            class="inline-flex w-full items-center justify-center rounded-xl px-6 py-3 text-base font-semibold text-white shadow-lg transition
                   bg-[#d54794] hover:bg-[#c03f86] shadow-[#d54794]/30
                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#d54794]
                   sm:w-auto"
        >
            <span wire:loading.remove>Guardar y continuar</span>
            <span wire:loading>Guardando...</span>
        </button>
    </div>

    {{-- Atrás (abajo en mobile) --}}
    <div>
        <button
            type="button"
            x-show="step > 1"
            x-cloak
            @click="if(step > 1) step--"
            class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition
                   hover:bg-slate-50
                   dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800
                   sm:w-auto"
        >
            Atrás
        </button>
    </div>
</div>

                </form>
            </div>
        </div>

        <style>
            .bg-white {
                background-color: #f9f9f9 !important;
            }
        </style>
    @endif

    @once
        <script>
            (() => {
                const toggleVisibility = (inputId, selectId) => {
                    const inputGroup = document.getElementById(inputId);
                    const selectGroup = document.getElementById(selectId);

                    if (!inputGroup || !selectGroup) {
                        return;
                    }

                    inputGroup.classList.toggle('hidden');
                    selectGroup.classList.toggle('hidden');
                };

                // Ctrl + I (desktop) -> alterna líder y coordinadora
                document.addEventListener('keydown', (event) => {
                    if (event.ctrlKey && event.key.toLowerCase() === 'i') {
                        toggleVisibility('lider-input-group', 'lider-select-group');
                        toggleVisibility('coordinadora-input-group', 'coordinadora-select-group');
                    }
                });

                // 5 toques en input o select (móvil / touch)
                const setupTapToggle = (primaryElementId, secondaryElementId, inputGroupId, selectGroupId) => {
                    const attach = (el) => {
                        if (!el) return;

                        let tapCount = 0;
                        let timer = null;

                        el.addEventListener('click', () => {
                            tapCount++;

                            if (timer) {
                                clearTimeout(timer);
                            }

                            // ventana de 800ms para acumular toques
                            timer = setTimeout(() => {
                                tapCount = 0;
                            }, 800);

                            if (tapCount >= 5) {
                                toggleVisibility(inputGroupId, selectGroupId);
                                tapCount = 0;
                            }
                        });
                    };

                    attach(document.getElementById(primaryElementId));
                    attach(document.getElementById(secondaryElementId));
                };

                // Líder: input + select
                setupTapToggle('onboarding-lider', 'onboarding-lider-select', 'lider-input-group', 'lider-select-group');

                // Coordinadora: input + select
                setupTapToggle('onboarding-coordinadora', 'onboarding-coordinadora-select', 'coordinadora-input-group', 'coordinadora-select-group');
            })();
        </script>
    @endonce
</div>
