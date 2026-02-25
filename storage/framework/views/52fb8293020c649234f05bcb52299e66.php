<?php

use function Laravel\Folio\{middleware, name};
use App\Models\User;
use Livewire\Volt\Component;

middleware('auth');
name('lideres');

new class extends Component {
    public $lideres;
    public $usuariosDisponibles;

    public bool $showAddModal = false;

    public array $form = [];

    public function mount(): void
    {
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    protected function loadData(): void
    {
        // Solo usuarios con rol lider
        $this->lideres = User::role('lider')
            ->with('roles')
            ->orderBy('name')
            ->get();

        // Solo vendedoras que NO tienen rol lider (para asignar)
        $this->usuariosDisponibles = User::role('vendedora')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'lider');
            })
            ->orderBy('name')
            ->get();
    }

    protected function defaultForm(): array
    {
        return [
            'user_id' => null,
        ];
    }

    public function openAddModal(): void
    {
        $this->form = $this->defaultForm();
        $this->showAddModal = true;
    }

    protected function rules(): array
    {
        return [
            'form.user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function addLider(): void
    {
        $validated = $this->validate();

        /** @var \App\Models\User $user */
        $user = User::findOrFail($validated['form']['user_id']);

        // Asignar rol lider
        $user->assignRole('lider');

        session()->flash('message', 'Lider asignado correctamente.');

        $this->showAddModal = false;
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    public function removeLider(int $userId): void
    {
        /** @var \App\Models\User $user */
        $user = User::findOrFail($userId);

        // Quitar rol lider
        $user->removeRole('lider');

        session()->flash('message', 'Lider eliminado correctamente.');

        $this->loadData();
    }

    public function closeModal(): void
    {
        $this->showAddModal = false;
        $this->form = $this->defaultForm();
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoibGlkZXJlcyIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvbGlkZXJlc1wvaW5kZXguYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-3266788052-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/lideres/index.blade.php ENDPATH**/ ?>