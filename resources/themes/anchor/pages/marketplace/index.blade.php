<?php

use function Laravel\Folio\name;
use App\Models\Catalogo;
use App\Models\CierreCampana;
use App\Models\TiendaPremio;
use App\Services\PremiosRevendedoraService;
use Livewire\Volt\Component;

\Laravel\Folio\middleware('auth');
name('marketplace');

new class extends Component {
    public string $search = '';

    public ?int $catalogoId = null;

    public ?int $cierreId = null;

    public array $catalogos = [];

    public array $cierres = [];

    public ?string $mensajeExito = null;

    public ?string $mensajeError = null;

    public int $saldoActual = 0;

    public function mount(): void
    {
        $this->hidratarCatalogos();
        $this->hidratarCierres();
        $this->actualizarSaldoActual();
    }

    public function updatedCatalogoId(): void
    {
        $this->cierreId = null;
        $this->hidratarCierres();
        $this->limpiarFeedback();
    }

    public function updatedCierreId(): void
    {
        $this->limpiarFeedback();
        $this->actualizarSaldoActual();
    }

    protected function hidratarCatalogos(): void
    {
        $this->catalogos = Catalogo::query()
            ->orderByDesc('anio')
            ->orderByDesc('numero')
            ->get(['id', 'numero', 'anio'])
            ->map(fn ($catalogo) => [
                'id' => $catalogo->id,
                'label' => "Catálogo {$catalogo->numero} ({$catalogo->anio})",
            ])
            ->values()
            ->all();

        if (! collect($this->catalogos)->contains(fn ($catalogo) => $catalogo['id'] === $this->catalogoId)) {
            $cierreActivo = $this->resolverCierreActivoPorFecha();
            $this->catalogoId = $cierreActivo?->catalogo_id ?? ($this->catalogos[0]['id'] ?? null);
        }
    }

    protected function hidratarCierres(): void
    {
        $this->cierres = CierreCampana::query()
            ->when($this->catalogoId, fn ($query) => $query->where('catalogo_id', $this->catalogoId))
            ->orderByDesc('fecha_inicio')
            ->get(['id', 'catalogo_id', 'codigo', 'numero_cierre', 'fecha_inicio', 'fecha_cierre'])
            ->map(fn ($cierre) => [
                'id' => $cierre->id,
                'label' => "{$cierre->codigo} · Cierre {$cierre->numero_cierre}",
            ])
            ->values()
            ->all();

        if (! collect($this->cierres)->contains(fn ($cierre) => $cierre['id'] === $this->cierreId)) {
            $cierreActivo = $this->resolverCierreActivoPorFecha($this->catalogoId);
            $this->cierreId = $cierreActivo?->id ?? ($this->cierres[0]['id'] ?? null);
        }
    }

    protected function resolverCierreActivoPorFecha(?int $catalogoId = null): ?CierreCampana
    {
        $fechaActual = now()->toDateString();

        return CierreCampana::query()
            ->when($catalogoId, fn ($query) => $query->where('catalogo_id', $catalogoId))
            ->whereDate('fecha_inicio', '<=', $fechaActual)
            ->whereDate('fecha_cierre', '>=', $fechaActual)
            ->orderByDesc('fecha_inicio')
            ->first()
            ?: CierreCampana::query()
                ->when($catalogoId, fn ($query) => $query->where('catalogo_id', $catalogoId))
                ->whereDate('fecha_inicio', '<=', $fechaActual)
                ->orderByDesc('fecha_inicio')
                ->first();
    }

    protected function cierreContexto(): ?CierreCampana
    {
        if ($this->cierreId) {
            $cierre = CierreCampana::query()->find($this->cierreId);
            if ($cierre) {
                return $cierre;
            }
        }

        return $this->resolverCierreActivoPorFecha($this->catalogoId);
    }

    public function actualizarSaldoActual(): void
    {
        $cierre = $this->cierreContexto();

        if (! $cierre) {
            $this->saldoActual = 0;

            return;
        }

        $this->saldoActual = app(PremiosRevendedoraService::class)->saldoPuntos(auth()->user(), $cierre->catalogo);
    }

    public function canjear(int $premioId): void
    {
        $this->limpiarFeedback();

        $cierre = $this->cierreContexto();

        if (! $cierre) {
            $this->mensajeError = 'No se encontró un cierre activo para procesar el canje.';

            return;
        }

        $premio = TiendaPremio::query()->find($premioId);

        if (! $premio) {
            $this->mensajeError = 'El premio seleccionado ya no está disponible.';

            return;
        }

        if ($premio->estado !== 'publicado') {
            $this->mensajeError = 'El premio seleccionado no está publicado para canje.';

            return;
        }

        try {
            app(PremiosRevendedoraService::class)->ejecutarCanje(
                auth()->user(),
                $premio,
                $cierre,
                [
                    'origen_ui' => 'marketplace',
                    'timestamp_ui' => now()->toIso8601String(),
                    'premio_mostrado' => [
                        'id' => $premio->id,
                        'nombre' => $premio->nombre,
                        'descripcion' => $premio->descripcion,
                        'puntos_requeridos' => $premio->puntos_requeridos,
                        'stock_antes' => $premio->stock,
                        'estado' => $premio->estado,
                    ],
                ],
            );

            $this->mensajeExito = 'Canje realizado con éxito. Ya puedes revisar el estado en tu historial.';
            $this->actualizarSaldoActual();
        } catch (\InvalidArgumentException $e) {
            $mensaje = $e->getMessage();

            if (str_contains(strtolower($mensaje), 'saldo insuficiente')) {
                $this->mensajeError = 'No tienes puntos suficientes para este canje.';

                return;
            }

            if (str_contains(strtolower($mensaje), 'sin stock')) {
                $this->mensajeError = 'Este premio ya no tiene stock disponible.';

                return;
            }

            $this->mensajeError = $mensaje;
        } catch (\Throwable $e) {
            report($e);
            $this->mensajeError = 'Ocurrió un error inesperado al intentar canjear el premio.';
        }
    }

    public function getProductsProperty()
    {
        return TiendaPremio::query()
            ->where('estado', 'publicado')
            ->where('stock', '>', 0)
            ->when($this->catalogoId, fn ($query) => $query->where(function ($inner) {
                $inner->where('catalogo_id', $this->catalogoId)
                    ->orWhereNull('catalogo_id');
            }))
            ->when($this->cierreId, fn ($query) => $query->where(function ($inner) {
                $inner->where('cierre_id', $this->cierreId)
                    ->orWhereNull('cierre_id');
            }))
            ->when(trim($this->search) !== '', function ($query) {
                $termino = '%' . trim($this->search) . '%';

                $query->where(function ($inner) use ($termino) {
                    $inner->where('nombre', 'like', $termino)
                        ->orWhere('descripcion', 'like', $termino);
                });
            })
            ->orderBy('puntos_requeridos')
            ->orderBy('nombre')
            ->get();
    }

    protected function limpiarFeedback(): void
    {
        $this->mensajeExito = null;
        $this->mensajeError = null;
    }
};
?>

<x-layouts.app>
    @volt('marketplace')
        <x-app.container>
            <div class="flex flex-col mb-8 gap-4">
                <x-app.heading
                    title="Tienda de premios"
                    description="Canjea tus puntos por premios publicados con control de stock y validación en línea."
                    :border="false"
                />

                <div class="grid gap-3 lg:grid-cols-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Catálogo</label>
                        <select wire:model.live="catalogoId" class="mt-1 w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($catalogos as $catalogo)
                                <option value="{{ $catalogo['id'] }}">{{ $catalogo['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Cierre</label>
                        <select wire:model.live="cierreId" class="mt-1 w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            @forelse($cierres as $cierre)
                                <option value="{{ $cierre['id'] }}">{{ $cierre['label'] }}</option>
                            @empty
                                <option value="">Sin cierres disponibles</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-3 text-sm text-indigo-800">
                        <p class="text-xs uppercase tracking-wide">Saldo actual</p>
                        <p class="text-2xl font-black">{{ number_format($saldoActual, 0, ',', '.') }} pts</p>
                    </div>
                </div>

                <div class="relative w-full max-w-lg">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Buscar premios por nombre o descripción..."
                        class="w-full py-2 pl-10 pr-4 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                @if($mensajeExito)
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ $mensajeExito }}
                    </div>
                @endif

                @if($mensajeError)
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ $mensajeError }}
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($this->products as $product)
                    <div class="overflow-hidden bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div class="aspect-video bg-gradient-to-br from-indigo-100 via-violet-100 to-pink-100"></div>
                        <div class="p-4 space-y-2">
                            <h3 class="font-bold text-gray-900">{{ $product->nombre }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $product->descripcion ?: 'Sin descripción disponible.' }}</p>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p><span class="font-semibold">Puntos requeridos:</span> {{ number_format($product->puntos_requeridos, 0, ',', '.') }}</p>
                                <p><span class="font-semibold">Stock:</span> {{ $product->stock }}</p>
                                <p><span class="font-semibold">Estado:</span> {{ ucfirst($product->estado) }}</p>
                            </div>
                            <div class="flex items-center justify-between gap-2 pt-2">
                                <span class="text-xs text-slate-500">ID #{{ $product->id }}</span>
                                <x-button wire:click="canjear({{ $product->id }})" wire:loading.attr="disabled" wire:target="canjear({{ $product->id }})" class="text-xs">
                                    Canjear
                                </x-button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay premios disponibles</h3>
                        <p class="mt-1 text-sm text-gray-500">Revisa los filtros o vuelve más tarde para nuevos canjes.</p>
                    </div>
                @endforelse
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
