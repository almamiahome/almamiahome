<?php

use function Laravel\Folio\{middleware, name};
use App\Models\Departamento;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Volt\Component;

middleware('auth');
name('rotulos');

new class extends Component {
    public const CAPACIDAD_PAGINA = 52;

    public $mes;
    public $anio;
    public $rotulos = [];
    public $limiteBulto;
    public $lider_id = '';
    public $zona_id = '';
    public $departamento_id = '';
    public $zona_texto = '';
    public $departamento_texto = '';
    public $lideres = [];
    public $zonas = [];
    public $departamentos = [];

    public function mount(): void
    {
        $now = now();

        $this->mes  = $now->format('m');
        $this->anio = $now->format('Y');
        $this->limiteBulto = (float) (setting('almamia.cantidad.bulto') ?? 9);
        $this->lideres = User::role('lider')->orderBy('name')->get(['id', 'name']);
        $this->zonas = Zona::orderBy('nombre')->get(['id', 'nombre']);
        $this->departamentos = Departamento::orderBy('nombre')->get(['id', 'nombre']);

        $this->loadRotulos();
    }

    public function updatedMes(): void { $this->loadRotulos(); }
    public function updatedAnio(): void { $this->loadRotulos(); }
    public function updatedLiderId(): void { $this->loadRotulos(); }
    public function updatedZonaId(): void { $this->loadRotulos(); }
    public function updatedDepartamentoId(): void { $this->loadRotulos(); }
    public function updatedZonaTexto(): void { $this->loadRotulos(); }
    public function updatedDepartamentoTexto(): void { $this->loadRotulos(); }

    public function loadRotulos(): void
    {
        $this->rotulos = [];

        $query = Pedido::with(['vendedora', 'lider.zona', 'lider.departamento', 'articulos'])
            ->whereYear('fecha', $this->anio)
            ->whereMonth('fecha', $this->mes);

        if (! empty($this->lider_id)) {
            $query->where('lider_id', (int) $this->lider_id);
        }

        if (! empty($this->zona_id)) {
            $query->whereHas('lider', fn (Builder $q) => $q->where('zona_id', (int) $this->zona_id));
        }

        if (! empty($this->departamento_id)) {
            $query->whereHas('lider', fn (Builder $q) => $q->where('departamento_id', (int) $this->departamento_id));
        }

        if (! empty($this->zona_texto)) {
            $textoZona = trim($this->zona_texto);
            $query->whereHas('lider', function (Builder $q) use ($textoZona) {
                $q->whereHas('zona', fn (Builder $z) => $z->where('nombre', 'like', '%'.$textoZona.'%'))
                    ->orWhereHas('profileKeyValues', fn (Builder $kv) => $kv->where('key', 'zona')->where('value', 'like', '%'.$textoZona.'%'));
            });
        }

        if (! empty($this->departamento_texto)) {
            $textoDepartamento = trim($this->departamento_texto);
            $query->whereHas('lider', function (Builder $q) use ($textoDepartamento) {
                $q->whereHas('departamento', fn (Builder $d) => $d->where('nombre', 'like', '%'.$textoDepartamento.'%'))
                    ->orWhereHas('profileKeyValues', fn (Builder $kv) => $kv->where('key', 'departamento')->where('value', 'like', '%'.$textoDepartamento.'%'));
            });
        }

        $pedidos = $query->orderBy('fecha')->get();

        foreach ($pedidos as $pedido) {
            $totalBulto = (float) $pedido->articulos->sum('bulto');
            $cantidadRotulos = max(1, (int) ceil($totalBulto / $this->limiteBulto));

            for ($i = 1; $i <= $cantidadRotulos; $i++) {
                $this->rotulos[] = [
                    'vendedora' => $pedido->vendedora?->name ?? '',
                    'lider' => $pedido->lider?->name ?? '',
                    'numero' => $i,
                ];
            }
        }
    }

    public function exportPdf()
    {
        $rotulos = $this->rotulos;
        $limiteBulto = $this->limiteBulto;
        $mes = $this->mes;
        $anio = $this->anio;
        $capacidadPagina = self::CAPACIDAD_PAGINA;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'pages.rotulos.imprimir',
            compact('rotulos', 'limiteBulto', 'mes', 'anio', 'capacidadPagina')
        )->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "rotulos_{$anio}_{$mes}.pdf"
        );
    }
};

?>

<x-layouts.app>
    @volt('rotulos')
    <x-app.container>

        <h1 class="mb-6 text-2xl font-bold">Rótulos de pedidos</h1>

        <div class="mb-6 flex flex-wrap items-end gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Mes</label>
                <select wire:model="mes" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                <label class="mb-1 block text-sm font-medium text-gray-700">Año</label>
                <select wire:model="anio" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @for ($y = now()->year - 3; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Líder</label>
                <select wire:model.live="lider_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas</option>
                    @foreach($lideres as $lider)
                        <option value="{{ $lider->id }}">{{ $lider->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Zona (ID)</label>
                <select wire:model.live="zona_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas</option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Departamento (ID)</label>
                <select wire:model.live="departamento_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Zona (texto)</label>
                <input type="text" wire:model.live.debounce.300ms="zona_texto" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Centro" />
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Departamento (texto)</label>
                <input type="text" wire:model.live.debounce.300ms="departamento_texto" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Capital" />
            </div>

            <div class="ml-auto flex items-center gap-3">
                <p class="text-sm text-gray-600">
                    Límite de bulto por rótulo: <span class="font-semibold">{{ $limiteBulto }}</span>
                </p>

                <button wire:click="exportPdf" type="button" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                    Descargar PDF
                </button>

                <button onclick="window.print()" type="button" class="inline-flex items-center rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-zinc-800 transition">
                    Imprimir
                </button>
            </div>
        </div>

        <div class="print-area rounded-xl border bg-white p-4 shadow-sm">
            @php
                $pages = array_chunk($rotulos, $this::CAPACIDAD_PAGINA);
            @endphp

            @forelse($pages as $pageIndex => $pageRotulos)
                <div class="{{ $pageIndex > 0 ? 'page-break' : '' }} mb-6 last:mb-0">
                    <div class="grid grid-cols-4 gap-0 border border-gray-300">
                        @foreach($pageRotulos as $rotulo)
                            <div class="flex h-24 flex-col justify-between border border-gray-300 px-2 py-1 text-[11px] leading-tight">
                                <div>
                                    <p><span class="font-semibold">Revendedora:</span> {{ $rotulo['vendedora'] }}</p>
                                    <p><span class="font-semibold">Líder:</span> {{ $rotulo['lider'] }}</p>
                                </div>
                                <p><span class="font-semibold">Bulto N°:</span> {{ $rotulo['numero'] }}</p>
                            </div>
                        @endforeach

                        @for ($empty = count($pageRotulos); $empty < $this::CAPACIDAD_PAGINA; $empty++)
                            <div class="h-24 border border-gray-300 px-2 py-1"></div>
                        @endfor
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No hay pedidos con rótulos para el período seleccionado.</p>
            @endforelse
        </div>

        <style>
            @media print {
                body * { visibility: hidden !important; }
                .print-area, .print-area * { visibility: visible !important; }
                .print-area {
                    position: absolute !important;
                    top: 0;
                    left: 0;
                    width: 100%;
                }
            }
        </style>

    </x-app.container>
    @endvolt
</x-layouts.app>
