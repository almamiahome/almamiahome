<x-filament-widgets::widget class="gap-5 fi-filament-info-widget">
    <x-filament::section>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-950 dark:text-white">Panel administrativo</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Resumen comercial con filtros por mes o rango de fechas.</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <label class="flex flex-col gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                    Tipo de filtro
                    <select wire:model.live="filtro" class="fi-input block w-full rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="mes">Por mes</option>
                        <option value="rango">Rango personalizado</option>
                    </select>
                </label>

                @if ($filtro === 'mes')
                    <label class="flex flex-col gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                        Mes
                        <input wire:model.live="mes" type="month" class="fi-input block w-full rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </label>
                @else
                    <label class="flex flex-col gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                        Desde
                        <input wire:model.live="fechaInicio" type="date" class="fi-input block w-full rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </label>

                    <label class="flex flex-col gap-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                        Hasta
                        <input wire:model.live="fechaFin" type="date" class="fi-input block w-full rounded-lg border-gray-300 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </label>

                    <div class="flex items-end">
                        <x-filament::button wire:click="limpiarRango" color="gray" icon="heroicon-m-x-mark" size="sm">
                            Limpiar fechas
                        </x-filament::button>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>

    <section class="mt-5 grid gap-5 md:grid-cols-3">
        <x-filament::section>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Unidades vendidas</p>
            <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ number_format($resumen['unidades_vendidas'], 0, ',', '.') }}</p>
        </x-filament::section>

        <x-filament::section>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total catálogo vendido</p>
            <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">${{ number_format($resumen['total_catalogo_vendido'], 2, ',', '.') }}</p>
        </x-filament::section>

        <x-filament::section>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total facturado</p>
            <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">${{ number_format($resumen['total_facturado'], 2, ',', '.') }}</p>
        </x-filament::section>
    </section>

    <section class="mt-5 grid gap-5 lg:grid-cols-2">
        <x-filament::section>
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Últimos pedidos</h3>
            </div>

            <div class="overflow-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs uppercase text-gray-500">
                        <tr>
                            <th class="pb-2">Código</th>
                            <th class="pb-2">Fecha</th>
                            <th class="pb-2">Vendedora</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($ultimosPedidos as $pedido)
                            <tr>
                                <td class="py-2 font-medium text-gray-900 dark:text-white">{{ $pedido->codigo_pedido }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-300">{{ $pedido->fecha ? \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y') : 'Sin fecha' }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-300">{{ $pedido->vendedora?->name ?? 'Sin vendedora' }}</td>
                                <td class="py-2 text-right font-medium text-gray-900 dark:text-white">${{ number_format((float) $pedido->total_a_pagar, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-sm text-gray-500">No hay pedidos para el filtro seleccionado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Últimos registros</h3>
            </div>

            <div class="overflow-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs uppercase text-gray-500">
                        <tr>
                            <th class="pb-2">Nombre</th>
                            <th class="pb-2">Correo</th>
                            <th class="pb-2">Alta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($ultimosRegistros as $registro)
                            <tr>
                                <td class="py-2 font-medium text-gray-900 dark:text-white">{{ $registro->name }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-300">{{ $registro->email }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-300">{{ $registro->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-sm text-gray-500">No hay registros recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </section>

    <section class="mt-5 grid gap-5 lg:grid-cols-2">
        <x-filament::section>
            <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Top 5 vendedoras (por ventas)</h3>
            <ol class="space-y-2 text-sm">
                @forelse($topVendedoras as $item)
                    <li class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-900/40">
                        <span class="font-medium text-gray-900 dark:text-white">{{ $item['nombre'] }}</span>
                        <span class="text-gray-600 dark:text-gray-300">${{ number_format($item['total_ventas'], 2, ',', '.') }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">Sin datos para el período seleccionado.</li>
                @endforelse
            </ol>
        </x-filament::section>

        <x-filament::section>
            <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Top 5 líderes (por ventas)</h3>
            <ol class="space-y-2 text-sm">
                @forelse($topLideres as $item)
                    <li class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-900/40">
                        <span class="font-medium text-gray-900 dark:text-white">{{ $item['nombre'] }}</span>
                        <span class="text-gray-600 dark:text-gray-300">${{ number_format($item['total_ventas'], 2, ',', '.') }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">Sin datos para el período seleccionado.</li>
                @endforelse
            </ol>
        </x-filament::section>
    </section>

    <x-filament::section class="mt-5">
        <div
            x-data="dashboardAccesosRapidos()"
            x-init="init()"
            class="space-y-3"
        >
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Accesos rápidos (arrastrables)</h3>
                <p class="text-xs text-gray-500">Podés reordenarlos arrastrando cada botón. Se guardan en este navegador.</p>
            </div>

            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                <template x-for="(item, index) in items" :key="item.href + index">
                    <a
                        :href="item.href"
                        draggable="true"
                        @dragstart="dragStart(index)"
                        @dragover.prevent
                        @drop="drop(index)"
                        class="cursor-move rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-primary-500 hover:text-primary-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                        x-text="item.label"
                    ></a>
                </template>
            </div>
        </div>
    </x-filament::section>

    <script>
        function dashboardAccesosRapidos() {
            return {
                items: [],
                dragIndex: null,
                storageKey: 'dashboard-accesos-rapidos-admin',
                init() {
                    const links = Array.from(document.querySelectorAll('aside .fi-sidebar-nav a[href]'))
                        .map((link) => ({
                            href: link.getAttribute('href'),
                            label: (link.textContent || '').trim(),
                        }))
                        .filter((item) => item.href && item.label)
                        .slice(0, 12);

                    const saved = localStorage.getItem(this.storageKey);

                    if (!saved) {
                        this.items = links;
                        return;
                    }

                    try {
                        const savedItems = JSON.parse(saved);
                        const byHref = new Map(links.map((item) => [item.href, item]));
                        const restored = savedItems
                            .map((item) => byHref.get(item.href))
                            .filter(Boolean);
                        const nuevos = links.filter((item) => !restored.some((r) => r.href === item.href));
                        this.items = [...restored, ...nuevos];
                    } catch (e) {
                        this.items = links;
                    }
                },
                dragStart(index) {
                    this.dragIndex = index;
                },
                drop(index) {
                    if (this.dragIndex === null || this.dragIndex === index) {
                        return;
                    }

                    const moved = this.items.splice(this.dragIndex, 1)[0];
                    this.items.splice(index, 0, moved);
                    this.dragIndex = null;
                    localStorage.setItem(this.storageKey, JSON.stringify(this.items));
                },
            }
        }
    </script>
</x-filament-widgets::widget>
