<?php

use App\Models\Catalogo;
use App\Models\CierreCampana;
use App\Models\TiendaPremio;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Laravel\Folio\{middleware, name};

middleware([
    'auth',
    function ($request, $next) {
        if (! $request->user() || ! $request->user()->hasRole('admin')) {
            abort(403, 'No tiene permisos para acceder a este módulo.');
        }

        return $next($request);
    },
]);

name('tienda-premios');

new class extends Component {
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $filtroEstado = '';
    public string $filtroCatalogoId = '';
    public string $filtroCierreId = '';
    public string $busqueda = '';

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingPremioId = null;

    public array $form = [
        'nombre' => '',
        'descripcion' => '',
        'puntos_requeridos' => '',
        'stock' => '',
        'estado' => 'despublicado',
        'catalogo_id' => '',
        'cierre_id' => '',
    ];

    public array $ajustesStock = [];
    public array $catalogos = [];
    public array $cierres = [];
    public array $estados = ['publicado', 'despublicado'];

    public function mount(): void
    {
        $this->catalogos = Catalogo::query()->orderByDesc('id')->get(['id', 'nombre'])->toArray();
        $this->cierres = CierreCampana::query()->orderByDesc('id')->get(['id', 'nombre'])->toArray();
    }

    public function updated($property): void
    {
        if (in_array($property, ['filtroEstado', 'filtroCatalogoId', 'filtroCierreId', 'busqueda'], true)) {
            $this->resetPage();
        }
    }

    public function getPremiosProperty()
    {
        return TiendaPremio::query()
            ->with(['catalogo:id,nombre', 'cierre:id,nombre'])
            ->when($this->filtroEstado !== '', fn (Builder $query) => $query->where('estado', $this->filtroEstado))
            ->when($this->filtroCatalogoId !== '', fn (Builder $query) => $query->where('catalogo_id', (int) $this->filtroCatalogoId))
            ->when($this->filtroCierreId !== '', fn (Builder $query) => $query->where('cierre_id', (int) $this->filtroCierreId))
            ->when(trim($this->busqueda) !== '', function (Builder $query): void {
                $term = '%'.trim($this->busqueda).'%';
                $query->where(function (Builder $inner) use ($term): void {
                    $inner->where('nombre', 'like', $term)
                        ->orWhere('descripcion', 'like', $term);
                });
            })
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $premioId): void
    {
        $premio = TiendaPremio::query()->find($premioId);
        if (! $premio) {
            return;
        }

        $this->resetValidation();
        $this->isEditing = true;
        $this->editingPremioId = $premio->id;
        $this->form = [
            'nombre' => (string) $premio->nombre,
            'descripcion' => (string) ($premio->descripcion ?? ''),
            'puntos_requeridos' => (string) $premio->puntos_requeridos,
            'stock' => (string) $premio->stock,
            'estado' => (string) $premio->estado,
            'catalogo_id' => (string) ($premio->catalogo_id ?? ''),
            'cierre_id' => (string) ($premio->cierre_id ?? ''),
        ];
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function savePremio(): void
    {
        $validated = $this->validate([
            'form.nombre' => 'required|string|max:255',
            'form.descripcion' => 'nullable|string',
            'form.puntos_requeridos' => 'required|integer|min:1',
            'form.stock' => 'required|integer|min:0',
            'form.estado' => 'required|string|in:publicado,despublicado',
            'form.catalogo_id' => 'nullable|integer|exists:catalogos,id',
            'form.cierre_id' => 'nullable|integer|exists:cierres_campana,id',
        ]);

        $payload = [
            'nombre' => $validated['form']['nombre'],
            'descripcion' => $validated['form']['descripcion'] ?: null,
            'puntos_requeridos' => (int) $validated['form']['puntos_requeridos'],
            'stock' => (int) $validated['form']['stock'],
            'estado' => $validated['form']['estado'],
            'catalogo_id' => $validated['form']['catalogo_id'] !== '' ? (int) $validated['form']['catalogo_id'] : null,
            'cierre_id' => $validated['form']['cierre_id'] !== '' ? (int) $validated['form']['cierre_id'] : null,
        ];

        if ($this->isEditing && $this->editingPremioId) {
            TiendaPremio::query()->whereKey($this->editingPremioId)->update($payload);
            session()->flash('success', 'Premio actualizado correctamente.');
        } else {
            TiendaPremio::query()->create($payload);
            session()->flash('success', 'Premio creado correctamente.');
        }

        $this->showModal = false;
    }

    public function publicar(int $premioId): void
    {
        TiendaPremio::query()->whereKey($premioId)->update(['estado' => 'publicado']);
        session()->flash('success', 'Premio publicado.');
    }

    public function despublicar(int $premioId): void
    {
        TiendaPremio::query()->whereKey($premioId)->update(['estado' => 'despublicado']);
        session()->flash('success', 'Premio despublicado.');
    }

    public function ajustarStock(int $premioId): void
    {
        $delta = (int) ($this->ajustesStock[$premioId] ?? 0);
        $premio = TiendaPremio::query()->find($premioId);

        if (! $premio) {
            return;
        }

        $stockResultante = $premio->stock + $delta;
        if ($stockResultante < 0) {
            $this->addError("ajustesStock.$premioId", 'El ajuste genera un stock negativo.');

            return;
        }

        $premio->update(['stock' => $stockResultante]);
        $this->ajustesStock[$premioId] = '';
        session()->flash('success', 'Stock ajustado correctamente.');
    }

    private function resetForm(): void
    {
        $this->form = [
            'nombre' => '',
            'descripcion' => '',
            'puntos_requeridos' => '',
            'stock' => '',
            'estado' => 'despublicado',
            'catalogo_id' => '',
            'cierre_id' => '',
        ];
        $this->editingPremioId = null;
    }
};
?>

<x-layouts.app>
    @volt('tienda-premios')
        <x-app.container class="space-y-6">
            <x-app.heading
                title="Tienda de premios"
                description="Gestión administrativa de premios, publicación y stock."
                :border="false"
            />

            @if (session()->has('success'))
                <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-3 rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm md:grid-cols-4 dark:border-zinc-800 dark:bg-zinc-900">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Estado</label>
                    <select wire:model.live="filtroEstado" class="w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="">Todos</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Catálogo</label>
                    <select wire:model.live="filtroCatalogoId" class="w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="">Todos</option>
                        @foreach ($catalogos as $catalogo)
                            <option value="{{ $catalogo['id'] }}">{{ $catalogo['nombre'] ?? ('Catálogo #'.$catalogo['id']) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Cierre</label>
                    <select wire:model.live="filtroCierreId" class="w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="">Todos</option>
                        @foreach ($cierres as $cierre)
                            <option value="{{ $cierre['id'] }}">{{ $cierre['nombre'] ?? ('Cierre #'.$cierre['id']) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Búsqueda</label>
                    <input wire:model.live.debounce.350ms="busqueda" type="text" placeholder="Nombre o descripción..." class="w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                    Premios encontrados: <span class="font-semibold">{{ $this->premios->total() }}</span>
                </p>

                <button wire:click="openCreateModal" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    Crear premio
                </button>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/50">
                        <tr class="text-left text-xs uppercase tracking-wide text-zinc-500">
                            <th class="px-4 py-3">Premio</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Catálogo / Cierre</th>
                            <th class="px-4 py-3">Puntos</th>
                            <th class="px-4 py-3">Stock</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($this->premios as $premio)
                            <tr wire:key="premio-{{ $premio->id }}">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $premio->nombre }}</p>
                                    <p class="line-clamp-2 text-xs text-zinc-500 dark:text-zinc-400">{{ $premio->descripcion ?: 'Sin descripción' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $premio->estado === 'publicado' ? 'bg-emerald-100 text-emerald-700' : 'bg-zinc-200 text-zinc-700' }}">
                                        {{ ucfirst($premio->estado) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-zinc-600 dark:text-zinc-300">
                                    <p>{{ $premio->catalogo?->nombre ?? 'Sin catálogo' }}</p>
                                    <p>{{ $premio->cierre?->nombre ?? 'Sin cierre' }}</p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-zinc-800 dark:text-zinc-200">{{ number_format((int) $premio->puntos_requeridos) }}</td>
                                <td class="px-4 py-3">
                                    <p class="mb-2 text-xs font-semibold text-zinc-700 dark:text-zinc-200">Actual: {{ $premio->stock }}</p>
                                    <div class="flex items-center gap-2">
                                        <input
                                            wire:model.defer="ajustesStock.{{ $premio->id }}"
                                            type="number"
                                            class="w-24 rounded-lg border-zinc-300 text-xs dark:border-zinc-700 dark:bg-zinc-950"
                                            placeholder="+/-"
                                        />
                                        <button wire:click="ajustarStock({{ $premio->id }})" class="rounded-lg border border-zinc-300 px-2 py-1 text-xs font-semibold hover:bg-zinc-100 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                            Ajustar
                                        </button>
                                    </div>
                                    @error("ajustesStock.$premio->id")
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <button wire:click="openEditModal({{ $premio->id }})" class="rounded-lg border border-zinc-300 px-2 py-1 text-xs font-semibold hover:bg-zinc-100 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                            Editar
                                        </button>
                                        @if ($premio->estado === 'publicado')
                                            <button wire:click="despublicar({{ $premio->id }})" class="rounded-lg bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700 hover:bg-amber-200">
                                                Despublicar
                                            </button>
                                        @else
                                            <button wire:click="publicar({{ $premio->id }})" class="rounded-lg bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-200">
                                                Publicar
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-zinc-500">No hay premios para los filtros seleccionados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $this->premios->links() }}
            </div>

            @if($showModal)
                <div class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
                    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                        <div wire:click="closeModal" class="fixed inset-0 bg-black/50 transition-opacity"></div>
                        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                        <div class="inline-block w-full transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:max-w-2xl sm:align-middle dark:bg-zinc-900">
                            <form wire:submit.prevent="savePremio">
                                <div class="space-y-4 px-5 py-4">
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $isEditing ? 'Editar premio' : 'Nuevo premio' }}</h3>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div class="md:col-span-2">
                                            <label class="text-sm font-medium">Nombre</label>
                                            <input type="text" wire:model.defer="form.nombre" class="mt-1 w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                                            @error('form.nombre') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="text-sm font-medium">Descripción</label>
                                            <textarea wire:model.defer="form.descripcion" rows="3" class="mt-1 w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950"></textarea>
                                            @error('form.descripcion') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium">Puntos requeridos</label>
                                            <input type="number" min="1" wire:model.defer="form.puntos_requeridos" class="mt-1 w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                                            @error('form.puntos_requeridos') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium">Stock</label>
                                            <input type="number" min="0" wire:model.defer="form.stock" class="mt-1 w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950" />
                                            @error('form.stock') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium">Estado</label>
                                            <select wire:model.defer="form.estado" class="mt-1 w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                                                <option value="publicado">Publicado</option>
                                                <option value="despublicado">Despublicado</option>
                                            </select>
                                            @error('form.estado') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium">Catálogo</label>
                                            <select wire:model.defer="form.catalogo_id" class="mt-1 w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                                                <option value="">Sin catálogo</option>
                                                @foreach ($catalogos as $catalogo)
                                                    <option value="{{ $catalogo['id'] }}">{{ $catalogo['nombre'] ?? ('Catálogo #'.$catalogo['id']) }}</option>
                                                @endforeach
                                            </select>
                                            @error('form.catalogo_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium">Cierre</label>
                                            <select wire:model.defer="form.cierre_id" class="mt-1 w-full rounded-lg border-zinc-300 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                                                <option value="">Sin cierre</option>
                                                @foreach ($cierres as $cierre)
                                                    <option value="{{ $cierre['id'] }}">{{ $cierre['nombre'] ?? ('Cierre #'.$cierre['id']) }}</option>
                                                @endforeach
                                            </select>
                                            @error('form.cierre_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2 border-t border-zinc-200 bg-zinc-50 px-5 py-3 dark:border-zinc-800 dark:bg-zinc-950/40">
                                    <button type="button" wire:click="closeModal" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold hover:bg-zinc-100 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
