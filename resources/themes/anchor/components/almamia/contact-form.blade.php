<form {{ $attributes->twMerge('space-y-4') }} method="POST">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nombre</label>
            <input id="name" name="name" type="text" required class="mt-1 w-full px-4 py-2 liquid-glass-input">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Correo</label>
            <input id="email" name="email" type="email" required class="mt-1 w-full px-4 py-2 liquid-glass-input">
        </div>
    </div>
    <div>
        <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Mensaje</label>
        <textarea id="message" name="message" rows="4" required class="mt-1 w-full px-4 py-2 liquid-glass-input"></textarea>
    </div>
    <x-elements.button size="lg" class="bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 text-white border-0">Enviar</x-elements.button>
</form>