<?php

use App\Services\PedidoCartService;
use Livewire\Volt\Component;
use function Laravel\Folio\name;

name('catalogo.publico');

new class extends Component {
    public array $paginas_catalogo = [];

    public function mount(PedidoCartService $pedidoCartService): void
    {
        $this->paginas_catalogo = $pedidoCartService->obtenerPaginasCatalogo();
    }
};

?>

<x-layouts.marketing
    :seo="[
        'title' => 'Catálogo Público | '.setting('site.title', 'Alma Mía Fragancias'),
        'description' => 'Explora el catálogo público de Alma Mía Fragancias.',
        'image' => url('/og_image.png'),
        'type' => 'website',
    ]"
>
    @volt('catalogo.publico')
    <x-container class="py-8 sm:py-12">
        <div
            x-data="catalogoPublico(@js($this->paginas_catalogo))"
            x-on:keydown.arrow-left.window="paginaAnterior()"
            x-on:keydown.arrow-right.window="paginaSiguiente()"
            class="mx-auto max-w-2xl space-y-6"
        >
            <div class="space-y-2 px-2">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Alma Mía Fragancias</p>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Catálogo público</h1>
                <p class="text-sm text-slate-600">Visualiza todas las páginas del catálogo en formato revista.</p>
            </div>

            <div
                x-show="zoom"
                x-transition:opacity
                class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/95 p-4 backdrop-blur-md"
                @click="zoom = false"
                x-cloak
            >
                <button class="absolute right-6 top-6 rounded-full bg-white/10 p-3 text-white transition hover:bg-white/20" aria-label="Cerrar zoom">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="flex h-full w-full items-center justify-center" @click.stop>
                    <img :src="zoomImage" class="max-h-full max-w-full rounded-xl object-contain shadow-2xl" alt="Imagen ampliada del catálogo">
                </div>
            </div>

            <template x-if="paginas.length === 0">
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                    El catálogo aún no tiene páginas publicadas.
                </div>
            </template>

            <template x-if="paginas.length > 0">
                <div class="space-y-6">
                    <div class="relative group">
                        <div
                            x-ref="slider"
                            @scroll.debounce.50ms="sincronizarScroll"
                            @touchstart="handleTouchStart($event)"
                            @touchend="handleTouchEnd($event)"
                            @mousedown="handleMouseDown($event)"
                            @mouseup="handleMouseUp($event)"
                            class="no-scrollbar relative flex snap-x snap-mandatory overflow-x-auto rounded-[2rem] bg-slate-100 shadow-xl ring-1 ring-slate-200"
                        >
                            <template x-for="(pagina, index) in paginas" :key="pagina.id">
                                <div class="relative flex min-w-full snap-start snap-always items-center justify-center overflow-hidden" :class="claseAnimacionPagina(index)">
                                    <div class="relative w-full aspect-[1061/1500] bg-white">
                                        <img :src="pagina.imagen_path" class="absolute inset-0 h-full w-full select-none object-cover" :alt="`Página ${pagina.numero}`">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="mb-8 flex items-center justify-between gap-4 rounded-3xl border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-2">
                            <button @click="paginaAnterior()" class="rounded-2xl bg-slate-50 p-3 text-slate-600 transition-all hover:bg-indigo-50 hover:text-indigo-600 active:scale-90" aria-label="Página anterior">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <div class="rounded-2xl bg-slate-50 px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-500">
                                <span x-text="paginaActiva + 1"></span> / <span x-text="paginas.length"></span>
                            </div>
                            <button @click="paginaSiguiente()" class="rounded-2xl bg-slate-50 p-3 text-slate-600 transition-all hover:bg-indigo-50 hover:text-indigo-600 active:scale-90" aria-label="Página siguiente">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>

                        <button @click="toggleZoom(paginas[paginaActiva].imagen_path)" class="flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all hover:bg-indigo-700 active:scale-95">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                            <span>Zoom</span>
                        </button>
                    </div>

                    <div class="relative">
                        <div class="mb-3 flex items-center justify-between px-2">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Páginas</h3>
                        </div>

                        <div
                            x-ref="thumbs"
                            data-thumbnails-track
                            @mousedown="iniciarArrastreMiniaturas($event)"
                            @mousemove="arrastrarMiniaturas($event)"
                            @mouseup="finalizarArrastreMiniaturas()"
                            @mouseleave="finalizarArrastreMiniaturas()"
                            @touchstart="iniciarArrastreMiniaturas($event)"
                            @touchmove="arrastrarMiniaturas($event)"
                            @touchend="finalizarArrastreMiniaturas()"
                            class="no-scrollbar flex snap-x gap-4 overflow-x-auto pb-4">
                            <template x-for="(pagina, index) in paginas" :key="`thumb-${pagina.id}`">
                                <button @click="irAPagina(index)" :data-thumb-index="index" class="aspect-[1061/1500] w-20 flex-shrink-0 snap-start overflow-hidden rounded-xl border-4 shadow-sm transition-all" :class="paginaActiva === index ? 'scale-105 border-indigo-500 shadow-indigo-100' : 'border-white opacity-50 hover:opacity-100'">
                                    <img :src="pagina.imagen_path" class="h-full w-full object-cover" :alt="`Miniatura página ${pagina.numero}`">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </x-container>

    <script>
        window.catalogoPublico = function (paginas) {
            return {
                paginas: paginas ?? [],
                paginaActiva: 0,
                zoom: false,
                zoomImage: '',
                animandoPasoPagina: false,
                direccionPasoPagina: null,
                paginaSaliente: null,
                temporizadorAnimacionPagina: null,
                touchStartX: null,
                touchStartY: null,
                mouseDown: false,
                mouseStartX: null,
                mouseStartY: null,
                draggingThumbs: false,
                thumbsStartX: 0,
                thumbsScrollLeft: 0,

                sincronizarScroll(event) {
                    const slider = event?.target ?? this.$refs.slider;
                    if (!slider) return;

                    const maxIndex = Math.max((this.paginas?.length ?? 1) - 1, 0);
                    const index = Math.round(slider.scrollLeft / slider.clientWidth);
                    this.paginaActiva = Math.min(maxIndex, Math.max(0, index));
                    this.sincronizarMiniaturaActiva();
                },

                irAPagina(index) {
                    if ((this.paginas ?? []).length === 0) return;

                    const boundedIndex = Math.min(this.paginas.length - 1, Math.max(0, Number(index)));
                    if (boundedIndex === this.paginaActiva) return;

                    this.iniciarAnimacionPaso(boundedIndex);
                    this.paginaActiva = boundedIndex;

                    const slider = this.$refs.slider;
                    if (slider) {
                        slider.scrollTo({
                            left: slider.clientWidth * boundedIndex,
                            behavior: 'smooth',
                        });
                    }

                    this.sincronizarMiniaturaActiva();
                },

                paginaSiguiente() {
                    this.irAPagina(this.paginaActiva + 1);
                },

                paginaAnterior() {
                    this.irAPagina(this.paginaActiva - 1);
                },

                iniciarAnimacionPaso(destino) {
                    const direccion = destino > this.paginaActiva ? 'adelante' : 'atras';

                    this.animandoPasoPagina = true;
                    this.direccionPasoPagina = direccion;
                    this.paginaSaliente = this.paginaActiva;

                    if (this.temporizadorAnimacionPagina) {
                        clearTimeout(this.temporizadorAnimacionPagina);
                    }

                    this.temporizadorAnimacionPagina = setTimeout(() => {
                        this.animandoPasoPagina = false;
                        this.direccionPasoPagina = null;
                        this.paginaSaliente = null;
                        this.temporizadorAnimacionPagina = null;
                    }, 520);
                },

                claseAnimacionPagina(index) {
                    if (!this.animandoPasoPagina || this.paginaSaliente !== index) {
                        return 'catalogo-pagina';
                    }

                    return this.direccionPasoPagina === 'adelante'
                        ? 'catalogo-pagina catalogo-pagina--sale-adelante'
                        : 'catalogo-pagina catalogo-pagina--sale-atras';
                },

                toggleZoom(path) {
                    this.zoomImage = path;
                    this.zoom = !this.zoom;
                },

                handleTouchStart(event) {
                    if (!event.touches || !event.touches.length) return;
                    this.touchStartX = event.touches[0].clientX;
                    this.touchStartY = event.touches[0].clientY;
                },

                handleTouchEnd(event) {
                    if (this.touchStartX === null) return;

                    const touch = event.changedTouches?.[0] ?? null;
                    if (!touch) {
                        this.touchStartX = null;
                        this.touchStartY = null;
                        return;
                    }

                    const deltaX = touch.clientX - this.touchStartX;
                    const deltaY = touch.clientY - this.touchStartY;

                    if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
                        if (deltaX < 0) {
                            this.paginaSiguiente();
                        } else {
                            this.paginaAnterior();
                        }
                    }

                    this.touchStartX = null;
                    this.touchStartY = null;
                },

                handleMouseDown(event) {
                    this.mouseDown = true;
                    this.mouseStartX = event.clientX;
                    this.mouseStartY = event.clientY;
                },

                handleMouseUp(event) {
                    if (!this.mouseDown) return;

                    const deltaX = event.clientX - this.mouseStartX;
                    const deltaY = event.clientY - this.mouseStartY;
                    this.mouseDown = false;
                    this.mouseStartX = null;
                    this.mouseStartY = null;

                    if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
                        if (deltaX < 0) {
                            this.paginaSiguiente();
                        } else {
                            this.paginaAnterior();
                        }
                    }
                },

                iniciarArrastreMiniaturas(event) {
                    const thumbs = this.$refs.thumbs;
                    if (!thumbs) return;

                    this.draggingThumbs = true;
                    this.thumbsStartX = this.obtenerPuntoX(event) - thumbs.offsetLeft;
                    this.thumbsScrollLeft = thumbs.scrollLeft;
                },

                arrastrarMiniaturas(event) {
                    if (!this.draggingThumbs) return;

                    const thumbs = this.$refs.thumbs;
                    if (!thumbs) return;

                    const x = this.obtenerPuntoX(event) - thumbs.offsetLeft;
                    const walk = x - this.thumbsStartX;
                    thumbs.scrollLeft = this.thumbsScrollLeft - walk;
                },

                finalizarArrastreMiniaturas() {
                    this.draggingThumbs = false;
                },

                obtenerPuntoX(event) {
                    if (event.touches?.length) {
                        return event.touches[0].pageX;
                    }

                    return event.pageX;
                },

                sincronizarMiniaturaActiva() {
                    const thumbs = this.$refs.thumbs;
                    if (!thumbs) return;

                    const selector = `[data-thumb-index="${this.paginaActiva}"]`;
                    const activeThumb = thumbs.querySelector(selector);
                    activeThumb?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'center',
                    });
                },
            };
        };
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .catalogo-pagina {
            transform-origin: left center;
            transition: transform 420ms cubic-bezier(.22, .61, .36, 1), box-shadow 420ms ease, opacity 320ms ease;
            will-change: transform, opacity;
        }

        .catalogo-pagina--sale-adelante {
            transform: perspective(1400px) rotateY(-18deg) translateX(-2.5%) scale(0.985);
            box-shadow: -20px 0 38px -18px rgba(15, 23, 42, 0.4), 0 26px 38px -30px rgba(15, 23, 42, 0.38);
        }

        .catalogo-pagina--sale-atras {
            transform-origin: right center;
            transform: perspective(1400px) rotateY(18deg) translateX(2.5%) scale(0.985);
            box-shadow: 20px 0 38px -18px rgba(15, 23, 42, 0.4), 0 26px 38px -30px rgba(15, 23, 42, 0.38);
        }

        @media (max-width: 768px), (prefers-reduced-motion: reduce) {
            .catalogo-pagina {
                transform-origin: center;
                transition: transform 260ms ease, opacity 220ms ease;
            }

            .catalogo-pagina--sale-adelante,
            .catalogo-pagina--sale-atras {
                transform: translateX(-2%) scale(0.994);
                opacity: 0.82;
                box-shadow: none;
            }
        }
    </style>
    @endvolt
</x-layouts.marketing>
