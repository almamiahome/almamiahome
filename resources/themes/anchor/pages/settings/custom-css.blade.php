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
    name('settings.custom-css');

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
                    Textarea::make('custom_css')
                        ->label('CSS personalizado')
                        ->rows(14)
                        ->placeholder('/* Agrega tu CSS personalizado */')
                        ->default(setting('custom.css')),
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
                ['key' => 'custom.css'],
                ['value' => $state['custom_css'] ?? '']
            );

            Notification::make()
                ->title('El CSS personalizado se guardó correctamente')
                ->success()
                ->send();
                
                    $this->js('window.location.reload()');


        }
    }

?>

<x-layouts.app>
    @volt('settings.custom-css')
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
    @endvolt
</x-layouts.app>
