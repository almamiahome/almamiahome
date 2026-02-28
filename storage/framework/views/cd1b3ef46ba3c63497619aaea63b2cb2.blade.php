<?php

use Filament\Forms\Components\Textarea;

?>


        <div class="relative">
            <x-app.settings-layout
                title="CSS personalizado"
                description="Agrega o actualiza reglas CSS globales para el tema."
            >
                <form wire:submit="save" class="w-full max-w-4xl">
                    {{ $this->form }}
                    <div class="w-full pt-6 text-right">
                        <x-button type="submit">Guardar cambios</x-button>
                    </div>
                </form>
            </x-app.settings-layout>
        </div>
    