<?php

use App\Services\PedidoCartService;
use Livewire\Volt\Component;
use function Laravel\Folio\name;

name('home');

new class extends Component {
    public array $paginas_catalogo = [];

    public function mount(PedidoCartService $pedidoCartService): void
    {
        $this->paginas_catalogo = array_slice($pedidoCartService->obtenerPaginasCatalogo(), 0, 3);
    }
};

?>

<x-layouts.marketing
    :seo="[
        'title'         => setting('site.title', 'Laravel Wave'),
        'description'   => setting('site.description', 'Software as a Service Starter Kit'),
        'image'         => url('/og_image.png'),
        'type'          => 'website'
    ]"
>
    @volt('home')
        <div>
            <x-marketing.sections.hero />

            <x-container class="py-12 border-t sm:py-24 border-zinc-200">
                <section class="space-y-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">Catálogo público</p>
                            <h2 class="text-2xl font-black tracking-tight text-zinc-900 sm:text-3xl">Descubrí nuestras fragancias destacadas</h2>
                            <p class="max-w-2xl text-sm text-zinc-600 sm:text-base">Explorá el catálogo completo en formato revista y compartilo con tus clientas desde cualquier dispositivo.</p>
                        </div>
                        <x-button href="/vercatalogo" tag="a" class="text-sm sm:text-base">Ver catálogo</x-button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        @forelse($this->paginas_catalogo as $pagina)
                            <a href="/vercatalogo" class="group overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                                <div class="aspect-[1061/1500] overflow-hidden bg-zinc-100">
                                    <img
                                        src="{{ $pagina['imagen_path'] ?? '' }}"
                                        alt="Vista previa de la página {{ $pagina['numero'] ?? '' }} del catálogo"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    >
                                </div>
                                <div class="flex items-center justify-between px-4 py-3 text-sm">
                                    <span class="font-semibold text-zinc-800">Página {{ $pagina['numero'] ?? 'sin número' }}</span>
                                    <span class="font-medium text-indigo-600">Abrir</span>
                                </div>
                            </a>
                        @empty
                            <div class="sm:col-span-3 rounded-2xl border border-dashed border-zinc-300 bg-white p-6 text-sm text-zinc-500">
                                El catálogo todavía no tiene páginas publicadas. Visitá más tarde para ver novedades.
                            </div>
                        @endforelse
                    </div>
                </section>
            </x-container>

            <x-container class="py-12 border-t sm:py-24 border-zinc-200">
                <x-marketing.sections.features />
            </x-container>

            <x-container class="py-12 border-t sm:py-24 border-zinc-200">
                <x-marketing.sections.testimonials />
            </x-container>

            <x-container class="py-12 border-t sm:py-24 border-zinc-200">
                <x-marketing.sections.pricing />
            </x-container>
        </div>
    @endvolt
</x-layouts.marketing>
