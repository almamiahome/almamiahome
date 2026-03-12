<div class="mx-auto w-full max-w-6xl space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Editor unificado de vCard</h1>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Configura campos generales por tipo de perfil y gestiona redes sociales con repeaters.</p>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="guardar" class="space-y-4">
        {{ $this->form }}

        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-300">
                Guardar configuración
            </button>
        </div>
    </form>
</div>
