<form {{ $attributes->twMerge('flex flex-col sm:flex-row items-center gap-4') }}>
    <input type="email" name="email" placeholder="Ingresa tu correo" class="flex-1 px-4 py-2 border border-primary-200 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-200" required>
    <x-elements.button size="lg" class="bg-primary-600 hover:bg-primary-700 text-white">
        {{ $button ?? 'Suscribirse' }}
    </x-elements.button>
</form>