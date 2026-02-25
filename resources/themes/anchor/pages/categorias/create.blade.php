<?php
    use function Laravel\Folio\{middleware, name};
    use Livewire\Attributes\Validate;
    use Livewire\Volt\Component;
    use App\Models\Categoria;
    use App\Models\PuntajeRegla;

    middleware('auth');
    name('categorias.create');

    new class extends Component {
        #[Validate('required|string|max:255')]
        public $nombre = '';

        #[Validate('nullable|string|max:255')]
        public $slug = '';

        public $categoria_ids = [];

        public $reglas = [];

        public function mount()
        {
            $this->reglas = PuntajeRegla::orderBy('descripcion')->get();
        }

        public function save()
        {
            $validated = $this->validate([
                'nombre' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255',
                'categoria_ids' => 'array',
                'categoria_ids.*' => 'exists:puntaje_reglas,id',
            ]);

            $categoria = Categoria::create([
                'nombre' => $validated['nombre'],
                'slug' => $validated['slug'] ?: null,
            ]);

            $categoria->puntajeRegla()->sync($validated['puntaje_regla_id'] ? [$validated['puntaje_regla_id']] : []);

            session()->flash('message', 'Categoría creada exitosamente.');
            $this->redirect('/categorias');
        }
    };
?>

<x-layouts.app>
    @volt('categorias.create')
        <x-app.container>
            <div class="flex items-center justify-between mb-5">
                <x-app.heading
                    title="Nueva Categoría"
                    description="Crear una nueva categoría"
                    :border="false"
                />
                <x-button tag="a" href="/categorias">Volver</x-button>
            </div>

            <form wire:submit="save" class="space-y-4 max-w-lg">
                <div>
                    <label for="nombre" class="block mb-2 text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="nombre" wire:model.live="nombre"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('nombre') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="slug" class="block mb-2 text-sm font-medium text-gray-700">Slug (opcional)</label>
                    <input type="text" id="slug" wire:model.live="slug"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('slug') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="categoria_ids" class="block mb-2 text-sm font-medium text-gray-700">Reglas de puntaje</label>
                    <select id="categoria_ids" wire:model.live="categoria_ids" multiple
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach($reglas as $regla)
                            <option value="{{ $regla->id }}">{{ \Illuminate\Support\Str::limit($regla->descripcion ?? 'Regla ' . $regla->id, 60) }}</option>
                        @endforeach
                    </select>
                    @error('categoria_ids') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    @error('categoria_ids.*') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <x-button type="submit">Guardar Categoría</x-button>
            </form>
        </x-app.container>
    @endvolt
</x-layouts.app>
