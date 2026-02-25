<?php
    use function Laravel\Folio\{name};
    name('changelogs');

    $logs = \Wave\Changelog::orderBy('created_at', 'desc')->paginate(10);

    // use a dynamic layout based on whether or not the user is authenticated
    $layout = ((auth()->guest()) ? 'layouts.marketing' : 'layouts.app');
?>

<x-dynamic-component 
	:component="$layout"
>

    
    <x-app.container>
        <x-card class="lg:p-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <x-app.heading
                    title="Changelog"
                    description="This is your application changelog where users can visit to stay in the loop about your latest updates and improvements."
                    class="flex-1"
                />

                <x-almamia.modal-popup title="Nueva entrada de changelog" class="w-full sm:w-auto">
                    <x-slot name="trigger">
                        <x-elements.button class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white" size="lg">
                            Nueva entrada
                        </x-elements.button>
                    </x-slot>

                    <form x-data="{ guardado: false }" @submit.prevent="guardado = true" class="space-y-4">
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Título</label>
                            <input id="titulo" type="text" name="titulo" required class="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800" placeholder="Actualización de rendimiento" />
                        </div>

                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Descripción corta</label>
                            <input id="descripcion" type="text" name="descripcion" class="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800" placeholder="Resumen de la mejora" />
                        </div>

                        <div>
                            <label for="detalle" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Detalle</label>
                            <textarea id="detalle" name="detalle" rows="4" class="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800" placeholder="Describe los cambios realizados"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="button" class="px-4 py-2 text-sm font-medium transition-colors rounded-lg text-zinc-700 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-100 dark:hover:bg-zinc-600" @click="guardado = false">Limpiar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white transition-colors rounded-lg bg-primary-600 hover:bg-primary-700">Guardar entrada</button>
                        </div>

                        <p x-show="guardado" x-transition class="p-3 text-sm font-medium text-green-800 rounded-lg bg-green-100 border border-green-200" style="display: none;">
                            Datos listos para enviar. Integra el guardado en backend para registrar la nueva entrada.
                        </p>
                    </form>
                </x-almamia.modal-popup>
            </div>

        <div class="max-w-full mt-8 prose-sm prose dark:prose-invert">
                @foreach($logs as $changelog)
                    <div class="flex flex-col items-start space-y-3 lg:flex-row lg:space-y-0 lg:space-x-5">
                        <div class="flex-shrink-0 px-2 py-1 text-xs translate-y-1 rounded-full bg-zinc-100 dark:bg-zinc-600">
                            <time datetime="{{ Carbon\Carbon::parse($changelog->created_at)->toIso8601String() }}" class="ml-1">{{ Carbon\Carbon::parse($changelog->created_at)->toFormattedDateString() }}</time>
                        </div>
                        <div class="relative">
                            <a href="{{ route('changelog', ['changelog' => $changelog->id]) }}" class="text-xl no-underline hover:underline" wire:navigate>{{ $changelog->title }}</a>
                            <div class="mx-auto mt-5 prose-sm prose text-zinc-600 dark:text-zinc-300">
                                {!! $changelog->body !!}
                            </div>
                            @if(!$loop->last)
                                <hr class="block my-10 border-dashed">
                            @endif
                        </div>
                    </div>
                    
                @endforeach
            </div>
        </x-card>

    </x-app.container>

</x-dynamic-component>