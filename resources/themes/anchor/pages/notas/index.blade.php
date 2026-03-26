<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

middleware(['auth', function ($request, $next) {
    if (! $request->user() || ! $request->user()->hasRole('admin')) {
        abort(403);
    }

    return $next($request);
}]);
name('notas');

new class extends Component {
    public array $notas = [];

    public ?string $notaSeleccionada = null;

    public array $form = [
        'titulo' => '',
        'contenido' => '',
        'etiquetas' => '',
    ];

    public string $mensaje = '';

    public function mount(): void
    {
        $this->cargarNotas();
        $this->notaSeleccionada = $this->notas[0]['id'] ?? null;
    }

    protected function rutaNotas(): string
    {
        return resource_path('themes/anchor/pages/notas/notas.json');
    }

    protected function cargarNotas(): void
    {
        if (! File::exists($this->rutaNotas())) {
            $this->notas = [];

            return;
        }

        $data = json_decode((string) File::get($this->rutaNotas()), true);
        $this->notas = is_array($data['notas'] ?? null) ? $data['notas'] : [];
    }

    protected function guardarNotas(): void
    {
        $json = json_encode([
            'notas' => array_values($this->notas),
            'actualizado_en' => now()->toDateTimeString(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            return;
        }

        File::put($this->rutaNotas(), $json.PHP_EOL);
    }

    public function seleccionar(string $id): void
    {
        $this->notaSeleccionada = $id;

        foreach ($this->notas as $nota) {
            if (($nota['id'] ?? null) === $id) {
                $this->form = [
                    'titulo' => (string) ($nota['titulo'] ?? ''),
                    'contenido' => (string) ($nota['contenido'] ?? ''),
                    'etiquetas' => implode(', ', $nota['etiquetas'] ?? []),
                ];
                break;
            }
        }
    }

    public function nuevaNota(): void
    {
        $this->notaSeleccionada = null;
        $this->form = ['titulo' => '', 'contenido' => '', 'etiquetas' => ''];
    }

    public function guardarNota(): void
    {
        $titulo = trim($this->form['titulo']);
        $contenido = trim($this->form['contenido']);

        if ($titulo === '' || $contenido === '') {
            $this->mensaje = 'Completá título y contenido para guardar la nota.';

            return;
        }

        $etiquetas = collect(explode(',', (string) $this->form['etiquetas']))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();

        if ($this->notaSeleccionada !== null) {
            foreach ($this->notas as &$nota) {
                if (($nota['id'] ?? '') !== $this->notaSeleccionada) {
                    continue;
                }

                $nota['titulo'] = $titulo;
                $nota['contenido'] = $contenido;
                $nota['etiquetas'] = $etiquetas;
                $nota['actualizado_en'] = now()->toDateTimeString();
                break;
            }
            unset($nota);
            $this->mensaje = 'Nota actualizada.';
        } else {
            $id = 'nota-'.Str::lower(Str::random(10));

            array_unshift($this->notas, [
                'id' => $id,
                'titulo' => $titulo,
                'contenido' => $contenido,
                'etiquetas' => $etiquetas,
                'creado_en' => now()->toDateTimeString(),
                'actualizado_en' => now()->toDateTimeString(),
            ]);

            $this->notaSeleccionada = $id;
            $this->mensaje = 'Nota creada.';
        }

        $this->guardarNotas();
    }

    public function eliminarNota(string $id): void
    {
        $this->notas = array_values(array_filter($this->notas, fn (array $nota) => ($nota['id'] ?? '') !== $id));

        if ($this->notaSeleccionada === $id) {
            $this->notaSeleccionada = $this->notas[0]['id'] ?? null;
            $this->seleccionar($this->notaSeleccionada ?? '');
        }

        $this->guardarNotas();
        $this->mensaje = 'Nota eliminada.';
    }
};
?>

<x-layouts.app>
    @volt('notas')
        <div class="mx-auto flex min-h-[85vh] max-w-[99%] gap-4 py-4">
            <aside class="w-80 shrink-0 rounded-2xl bg-white/95 p-4 shadow ring-1 ring-slate-200">
                <div class="mb-3 flex items-center justify-between">
                    <h1 class="text-lg font-black text-slate-900">Notas clave</h1>
                    <button wire:click="nuevaNota" class="rounded-lg bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">Nueva</button>
                </div>
                <p class="mb-3 text-xs text-slate-500">Incluye 15 notas iniciales para contexto del sistema.</p>
                <div class="space-y-2">
                    @foreach($notas as $nota)
                        <button wire:click="seleccionar('{{ $nota['id'] }}')" class="w-full rounded-lg border px-3 py-2 text-left text-xs {{ $notaSeleccionada === $nota['id'] ? 'border-indigo-400 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-700' }}">
                            <p class="font-semibold">{{ $nota['titulo'] }}</p>
                            <p class="mt-1 line-clamp-2 text-[11px] text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($nota['contenido'] ?? ''), 90) }}</p>
                        </button>
                    @endforeach
                </div>
            </aside>

            <section class="flex-1 rounded-2xl bg-white/95 p-4 shadow ring-1 ring-slate-200">
                <div class="grid h-full gap-4 lg:grid-cols-2">
                    <div class="space-y-3">
                        @if($mensaje !== '')
                            <div class="rounded-lg bg-emerald-50 px-3 py-2 text-xs text-emerald-700">{{ $mensaje }}</div>
                        @endif

                        <input wire:model="form.titulo" type="text" placeholder="Título"
                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                        <input wire:model="form.etiquetas" type="text" placeholder="Etiquetas (opcionales), separadas por coma"
                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                        <textarea wire:model="form.contenido" rows="26" placeholder="Markdown en pantalla completa"
                            class="w-full min-h-[68vh] rounded-xl border-slate-300 font-mono text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>

                        <div class="flex gap-2">
                            <button wire:click="guardarNota" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Guardar</button>
                            @if($notaSeleccionada)
                                <button wire:click="eliminarNota('{{ $notaSeleccionada }}')" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Eliminar</button>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h2 class="mb-2 text-sm font-bold uppercase tracking-wide text-slate-600">Vista previa markdown</h2>
                        <div class="prose prose-sm max-w-none overflow-y-auto text-slate-700">
                            {!! \Illuminate\Support\Str::markdown($form['contenido'] ?: 'Escribí contenido en markdown para previsualizarlo.') !!}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endvolt
</x-layouts.app>
