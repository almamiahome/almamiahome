<form {{ $attributes->twMerge('space-y-4') }} method="POST">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
            <input id="name" name="name" type="text" required class="mt-1 w-full px-4 py-2 border border-primary-200 dark:border-zinc-700 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-zinc-800 dark:text-gray-200">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo</label>
            <input id="email" name="email" type="email" required class="mt-1 w-full px-4 py-2 border border-primary-200 dark:border-zinc-700 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-zinc-800 dark:text-gray-200">
        </div>
    </div>
    <div>
        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mensaje</label>
        <textarea id="message" name="message" rows="4" required class="mt-1 w-full px-4 py-2 border border-primary-200 dark:border-zinc-700 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-zinc-800 dark:text-gray-200"></textarea>
    </div>
    <x-elements.button size="lg" class="bg-primary-600 hover:bg-primary-700 text-white">Enviar</x-elements.button>
</form>