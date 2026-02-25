<?php
    use Filament\Forms\Components\Textarea;
    use Livewire\Volt\Component;
    use function Laravel\Folio\name;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Schemas\Schema;
    use Filament\Notifications\Notification;
    use Wave\Setting;

    // Nombre de la página en Folio
    name('settings.custom-js');

    new class extends Component implements HasForms
    {
        use InteractsWithForms;

        public ?array $data = [];

        public function mount(): void
        {
            $user = auth()->user();

            // Solo rol admin
            if (! $user || ! $user->hasRole('admin')) {
                abort(403, 'Solo el rol administrador puede acceder a esta sección.');
            }

            // Rellenamos el formulario con el valor actual
            $this->form->fill();
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    Textarea::make('custom_js')
                        ->label('JavaScript personalizado')
                        ->rows(14)
                        ->placeholder('// Agrega tu JavaScript personalizado')
                        ->default(setting('custom.js')),
                ])
                ->statePath('data');
        }

        public function save(): void
        {
            $user = auth()->user();

            // Reforzamos la verificación de rol admin
            if (! $user || ! $user->hasRole('admin')) {
                abort(403, 'Solo el rol administrador puede guardar estos cambios.');
            }

            $state = $this->form->getState();
            $this->validate();

            Setting::updateOrCreate(
                ['key' => 'custom.js'],
                ['value' => $state['custom_js'] ?? '']
            );

            Notification::make()
                ->title('El JavaScript personalizado se guardó correctamente')
                ->success()
                ->send();
                
                    $this->js('window.location.reload()');

        }
    }

?>

<x-layouts.app>
    @volt('settings.custom-js')
        <div class="relative">
            <x-app.settings-layout
                title="JavaScript personalizado"
                description="Configura scripts personalizados que se inyectarán en el tema."
            >
                <form wire:submit="save" class="w-full max-w-4xl">
                    {{ $this->form }}
                    <div class="w-full pt-6 text-right">
                        <x-button type="submit">Guardar cambios</x-button>
                    </div>
                </form>
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
