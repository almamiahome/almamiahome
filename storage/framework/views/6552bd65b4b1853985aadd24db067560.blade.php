<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Pedido;
use Livewire\Volt\Component;
use Barryvdh\DomPDF\Facade\Pdf;

?>


    <x-app.container>

        <h1 class="text-2xl font-bold mb-6">Rótulos de pedidos</h1>

        {{-- Filtros de mes y año --}}
        <div class="flex flex-wrap items-end gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                <select
                    wire:model="mes"
                    class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    @for ($m = 1; $m <= 12; $m++)
                        @php
                            $value = str_pad($m, 2, '0', STR_PAD_LEFT);
                            $nombreMes = \Carbon\Carbon::createFromDate(2000, $m, 1)->locale('es')->monthName;
                        @endphp
                        <option value="{{ $value }}">{{ ucfirst($nombreMes) }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                <select
                    wire:model="anio"
                    class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    @for ($y = now()->year - 3; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="ml-auto flex items-center gap-3">
                <p class="text-sm text-gray-600">
                    Límite de bulto por rótulo:
                    <span class="font-semibold">{{ $limiteBulto }}</span>
                </p>

                <button onclick="window.print()" type="button" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold shadow hover:bg-indigo-700 transition">
                    Imprimir
                </button>

            </div>
        </div>

        {{-- Vista en A4: 4 columnas x 13 filas por página --}}
        <div class="print-area border rounded-xl bg-white p-4 shadow-sm">

            @php
                $pages = array_chunk($rotulos, 30); // 4 columnas x 13 filas
            @endphp

            @forelse($pages as $pageIndex => $pageRotulos)
                <div class="{{ $pageIndex > 0 ? 'page-break' : '' }} mb-6 last:mb-0">

                    <div class="grid grid-cols-3 gap-0 border border-gray-300">
                        @foreach($pageRotulos as $i => $rotulo)
                            <div class="border border-gray-300 h-24 px-2 py-1 text-[11px] leading-tight flex flex-col justify-between">
                                <div>
                                    <p><span class="font-semibold">Revendedora:</span> {{ $rotulo['vendedora'] }}</p>
                                    <p><span class="font-semibold">Líder:</span> {{ $rotulo['lider'] }}</p>
                                </div>
                                <p><span class="font-semibold">Bulto N°:</span> {{ $rotulo['numero'] }}</p>
                            </div>
                        @endforeach

                        {{-- Completar celdas vacías hasta 52 para mantener el formato --}}
                        @for ($empty = count($pageRotulos); $empty < 52; $empty++)
                            <div class="border border-gray-300 h-24 px-2 py-1"></div>
                        @endfor
                    </div>

                </div>
            @empty
                <p class="text-sm text-gray-500">
                    No hay pedidos con rótulos para el período seleccionado.
                </p>
            @endforelse

        </div>

           <style>
            /* Ocultar toda la UI al imprimir */
                @media print {
        
                /* Ocultar todo excepto la grilla */
                body * {
                    visibility: hidden !important;
                }
        
                /* Mostrar solo el contenedor de la grilla */
                .print-area, .print-area * {
                    visibility: visible !important;
                }
        
                /* Ubicar la grilla en la parte superior */
                .print-area {
                    position: absolute !important;
                    top: 0;
                    left: 0;
                    width: 100%;
                }
            }
        </style>


    </x-app.container>
    