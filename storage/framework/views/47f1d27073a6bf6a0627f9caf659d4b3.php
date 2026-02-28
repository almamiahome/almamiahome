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

<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e1fe0a65a45e979f68b20da5606ecdcc::layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoic2V0dGluZ3MuY3VzdG9tLWNzcyIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvc2V0dGluZ3NcL2N1c3RvbS1jc3MuYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-819337924-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/settings/custom-css.blade.php ENDPATH**/ ?>