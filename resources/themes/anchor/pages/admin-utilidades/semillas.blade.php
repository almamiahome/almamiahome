<?php

use Illuminate\Support\Facades\Artisan;
use Livewire\Volt\Component;
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

name('admin-utilidades.semillas');

new class extends Component {
    public ?string $resultado = null;

    public bool $ejecutando = false;

    public function ejecutarSemillaCompleta(): void
    {
        $this->ejecutando = true;

        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\DatabaseSeeder',
                '--force' => true,
            ]);

            $salida = trim(Artisan::output());
            $this->resultado = $salida !== ''
                ? "Carga de semillas completada.\n\n" . $salida
                : 'Carga de semillas completada correctamente.';
        } catch (\Throwable $exception) {
            $this->resultado = 'Error al ejecutar semillas: ' . $exception->getMessage();
        } finally {
            $this->ejecutando = false;
        }
    }
};
?>

<x-layouts.app>
    @volt('admin-utilidades.semillas')
        <x-app.container class="space-y-6">
            <div class="rounded-3xl border border-white/10 bg-slate-950/80 p-6 text-white shadow-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-fuchsia-300/80">Temporal</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight">Carga integral de datos semilla</h1>
                <p class="mt-2 text-sm text-slate-300">
                    Ejecuta <code class="rounded bg-white/10 px-2 py-1 text-xs">DatabaseSeeder</code> para poblar datos base del sistema.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button
                        wire:click="ejecutarSemillaCompleta"
                        wire:loading.attr="disabled"
                        class="rounded-2xl bg-gradient-to-r from-pink-500 to-violet-600 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg transition hover:scale-[1.02] disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="ejecutarSemillaCompleta">Ejecutar semilla completa</span>
                        <span wire:loading wire:target="ejecutarSemillaCompleta">Ejecutando...</span>
                    </button>

                    <a href="{{ url('/dashboard') }}" class="rounded-2xl border border-white/20 px-6 py-3 text-xs font-bold uppercase tracking-widest text-white/90 hover:bg-white/10">
                        Volver al dashboard
                    </a>
                </div>
            </div>

            @if($resultado)
                <section class="rounded-3xl border border-white/10 bg-slate-900/70 p-5">
                    <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-slate-300">Resultado</p>
                    <pre class="overflow-x-auto whitespace-pre-wrap text-xs text-slate-200">{{ $resultado }}</pre>
                </section>
            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
