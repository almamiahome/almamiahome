<?php
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use App\Models\Categoria;
use Illuminate\Support\Str;

middleware('auth');
name('categorias.masivo');

new class extends Component {
    public $input_text = '';

    public function saveMasivo()
    {
        $lines = collect(explode("\n", $this->input_text))
            ->map(fn($line) => trim($line))
            ->filter();

        foreach ($lines as $nombre) {
            $slug = Str::slug($nombre);
            Categoria::firstOrCreate(
                ['slug' => $slug],
                ['nombre' => $nombre]
            );
        }

        session()->flash('message', 'Categorías creadas correctamente.');
        $this->input_text = '';
    }
};
?>

<x-layouts.app>
    @volt('categorias.masivo')
        <x-app.container>
            <x-elements.back-button
                text="Volver al listado"
                :href="route('categorias')"
                class="mb-4"
            />

            <x-app.heading
                title="Creación masiva de categorías"
                description="Pegá una lista de nombres, uno por línea. Los slugs se crearán automáticamente."
                :border="false"
            />

            @if (session()->has('message'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-300 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="saveMasivo" class="space-y-4 max-w-2xl">
                <div>
                    <label for="input_text" class="block mb-2 text-sm font-medium text-gray-700">
                        Nombres de categorías
                    </label>
                    <textarea
                        id="input_text"
                        rows="10"
                        wire:model.defer="input_text"
                        placeholder="Ejemplo:
Ropa
Calzado
Accesorios
Perfumería"
                        class="w-full border-gray-300 rounded-md shadow-xs focus:ring focus:ring-indigo-200"
                    ></textarea>
                </div>

                <x-button type="submit">
                    Crear categorías
                </x-button>
            </form>
        </x-app.container>
    @endvolt
</x-layouts.app>
