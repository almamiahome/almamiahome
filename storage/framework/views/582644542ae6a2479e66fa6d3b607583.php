<?php

use function Laravel\Folio\{middleware, name};
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

middleware('auth');
name('perfil');

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $dni = '';
    public string $whatsapp = '';
    public string $direccion = '';
    public string $departamento = '';
    public string $zona = '';

    public array $departamentos = [];
    public array $zonas = [];

    public function mount(): void
    {
        $user = auth()->user();

        $this->name = $user?->name ?? '';
        $this->email = $user?->email ?? '';
        $this->dni = (string) ($user?->profile('dni') ?? '');
        $this->whatsapp = (string) ($user?->profile('whatsapp') ?? '');
        $this->direccion = (string) ($user?->profile('direccion') ?? '');
        $this->departamento = (string) ($user?->profile('departamento') ?? '');
        $this->zona = (string) ($user?->profile('zona') ?? '');

        $this->departamentos = $this->decodeOptions(setting('almamia.departamentos.mendoza'));
        $this->zonas = $this->decodeOptions(setting('almamia.zona.mendoza'));
    }

    protected function decodeOptions($value): array
    {
        $decoded = json_decode($value ?? '[]', true);
        if (! is_array($decoded)) return [];
        return array_values(array_filter($decoded, static fn ($item) => filled($item)));
    }

    public function save(): void
    {
        $user = auth()->user();
        if (! $user) return;

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'dni' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'departamento' => ['nullable', 'string', 'max:255'],
            'zona' => ['nullable', 'string', 'max:255'],
        ], [], [
            'name' => 'nombre',
            'email' => 'correo',
            'dni' => 'DNI',
            'whatsapp' => 'WhatsApp',
            'direccion' => 'dirección',
            'departamento' => 'departamento',
            'zona' => 'zona',
        ]);

        $user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
        ])->save();

        $user->setProfileKeyValue('dni', $this->dni);
        $user->setProfileKeyValue('whatsapp', $this->whatsapp);
        $user->setProfileKeyValue('direccion', $this->direccion);
        $user->setProfileKeyValue('departamento', $this->departamento);
        $user->setProfileKeyValue('zona', $this->zona);

        session()->flash('perfil_guardado', '¡Tu perfil ha sido actualizado!');
    }
};
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicGVyZmlsIiwicGF0aCI6InJlc291cmNlc1wvdGhlbWVzXC9hbmNob3JcL3BhZ2VzXC9wZXJmaWxcL2luZGV4LmJsYWRlLnBocCJ9", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-1777550998-0', $__slots ?? [], get_defined_vars());

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
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/perfil/index.blade.php ENDPATH**/ ?>