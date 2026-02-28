<form {{ $attributes->twMerge('flex flex-col sm:flex-row items-center gap-4') }}>
    <input type="email" name="email" placeholder="Ingresa tu correo" class="flex-1 px-4 py-2 liquid-glass-input" required>
    <x-elements.button size="lg" class="bg-gradient-to-r from-primary-500 to-secondary-600 hover:from-primary-600 hover:to-secondary-700 text-white border-0">
        {{ $button ?? 'Suscribirse' }}
    </x-elements.button>
</form>