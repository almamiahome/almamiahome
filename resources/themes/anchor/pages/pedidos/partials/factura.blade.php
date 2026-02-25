<div
    class="fixed inset-0 z-40 flex items-center justify-center"
    x-cloak
    x-show="showInvoice"
    x-transition
>
    <div class="absolute inset-0 bg-black/40" @click="closeInvoice()"></div>

    <div class="relative z-50 max-w-6xl w-full px-4">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-3 border-b bg-gray-50">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">
                        Vista previa de factura
                    </p>
                    <p class="text-sm text-gray-700 mt-0.5">
                        Pedido <span class="font-semibold" x-text="selected?.codigo"></span>
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="printInvoice()"
                        class="px-3 py-1.5 text-xs font-semibold rounded bg-emerald-600 text-white hover:bg-emerald-700">
                        Imprimir
                    </button>
                    <button @click="closeInvoice()"
                        class="px-3 py-1.5 text-xs font-semibold rounded bg-white border border-gray-300 hover:bg-gray-50">
                        Cerrar
                    </button>
                </div>
            </div>

            {{-- FACTURA A4 --}}
            <div class="p-8 flex justify-center overflow-auto">
                <div class="invoice-a4 bg-white border border-gray-200 rounded-xl px-8 py-8 text-sm text-gray-800"
                     x-ref="invoicePrintable">

                    {{-- TITULO --}}
                    <div class="flex justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Alma Mía Fragancias</h2>
                            <p class="text-xs text-gray-500">Comprobante interno</p>
                        </div>

                        <div class="text-right text-xs text-gray-600">
                            <p class="font-semibold">
                                Pedido <span x-text="selected?.codigo"></span>
                            </p>
                            <p>Fecha: <span x-text="selected?.fecha"></span></p>
                            <p>Mes: <span x-text="selected?.mes"></span></p>
                            <p>Estado: <span class="font-semibold" x-text="selected?.estado"></span></p>
                        </div>
                    </div>

                    {{-- CONTACTOS --}}
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <template x-for="(rol, label) in {
                            'Líder': selected?.datos_pedido?.lider,
                            'Vendedora': selected?.datos_pedido?.vendedora,
                            'Responsable': selected?.datos_pedido?.responsable
                        }">
                            <div class="border border-dashed rounded-xl p-3">
                                <p class="text-[10px] uppercase text-gray-500 font-semibold" x-text="label"></p>
                                <p class="text-sm font-semibold" x-text="rol?.nombre ?? '-'"></p>
                                <p class="text-xs" x-text="rol?.direccion ?? '-'"></p>
                                <p class="text-xs text-gray-600" x-text="rol?.zona ?? '-'"></p>
                            </div>
                        </template>
                    </div>

                    {{-- TABLA ARTICULOS --}}
                    <table class="w-full text-xs border-collapse mb-6">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="px-2 py-2 text-left">Producto</th>
                                <th class="px-2 py-2 text-left">Descripción</th>
                                <th class="px-2 py-2 text-right">Cant.</th>
                                <th class="px-2 py-2 text-right">P. Cat.</th>
                                <th class="px-2 py-2 text-right">Unit.</th>
                                <th class="px-2 py-2 text-right">Subtotal</th>
                                <th class="px-2 py-2 text-right">Pts.</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-if="selected?.articulos?.length === 0">
                                <tr><td colspan="7" class="py-4 text-center text-gray-400">
                                    No hay artículos cargados.
                                </td></tr>
                            </template>

                            <template x-for="item in selected?.articulos" :key="item.descripcion">
                                <tr class="border-b">
                                    <td class="px-2 py-1.5" x-text="item.producto"></td>
                                    <td class="px-2 py-1.5 text-gray-600" x-text="item.descripcion"></td>
                                    <td class="px-2 py-1.5 text-right" x-text="item.cantidad"></td>
                                    <td class="px-2 py-1.5 text-right">$ <span x-text="formatMoney(item.precio_catalogo)"></span></td>
                                    <td class="px-2 py-1.5 text-right">$ <span x-text="formatMoney(item.precio_unitario)"></span></td>
                                    <td class="px-2 py-1.5 text-right">$ <span x-text="formatMoney(item.subtotal)"></span></td>
                                    <td class="px-2 py-1.5 text-right" x-text="item.puntos"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    {{-- TOTALES --}}
                    <div class="grid grid-cols-2 gap-6 text-xs">
                        <div>
                            <p><strong>Total unidades:</strong> <span x-text="selected?.unidades"></span></p>
                            <p><strong>Total puntos:</strong> <span x-text="selected?.total_puntos"></span></p>

                            <div class="mt-3">
                                <p class="text-[10px] uppercase text-gray-500 font-semibold">Observaciones</p>
                                <p class="whitespace-pre-line mt-1" x-text="selected?.observaciones ?? '—'"></p>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <p class="flex justify-between">
                                <span>Subtotal catálogo:</span>
                                <span class="font-semibold">$ <span x-text="formatMoney(selected?.total_catalogo)"></span></span>
                            </p>

                            <p class="flex justify-between">
                                <span>Gastos administrativos:</span>
                                <span class="font-semibold">$ <span x-text="formatMoney(selected?.total_gastos)"></span></span>
                            </p>

                            <p class="flex justify-between">
                                <span>Ganancias estimadas:</span>
                                <span class="font-semibold">$ <span x-text="formatMoney(selected?.total_ganancias)"></span></span>
                            </p>

                            <p class="flex justify-between border-t pt-2 mt-2 text-lg font-bold text-emerald-700">
                                <span>Total a pagar:</span>
                                <span>$ <span x-text="formatMoney(selected?.total_a_pagar)"></span></span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 text-[10px] text-gray-500 text-center border-t">
                        Comprobante interno. No es válido como factura fiscal.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
