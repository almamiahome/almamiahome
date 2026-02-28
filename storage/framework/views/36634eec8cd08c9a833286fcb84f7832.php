<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Laravel\Folio\{middleware, name};

middleware('auth');
name('usuarios');

new class extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingUserId = null;

    public array $form = [
        'name' => '',
        'email' => '',
        'dni' => '',
        'whatsapp' => '',
        'direccion' => '',
        'departamento' => '',
        'zona' => '',
    ];

    public array $departamentos = [];
    public array $zonas = [];

    public function mount(): void
    {
        $this->departamentos = $this->decodeOptions(setting('almamia.departamentos.mendoza') ?? '[]');
        $this->zonas = $this->decodeOptions(setting('almamia.zona.mendoza') ?? '[]');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function decodeOptions($value): array
    {
        $decoded = json_decode($value ?? '[]', true);
        return is_array($decoded) ? array_values(array_filter($decoded, fn($item) => filled($item))) : [];
    }

    public function getUsuariosProperty()
    {
        $search = trim($this->search);
        return User::query()
            ->with(['profileKeyValues'])
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->where(function (Builder $b) use ($search) {
                    $b->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereRaw('CAST(id AS CHAR) LIKE ?', ["%{$search}%"]);
                });
            })
            ->orderBy('name')
            ->paginate(10);
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($userId): void
    {
        $user = User::find($userId);
        if (!$user) return;

        $this->resetValidation();
        $this->isEditing = true;
        $this->editingUserId = $userId;
        $this->form = [
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'dni' => (string)($user->profile('dni') ?? ''),
            'whatsapp' => (string)($user->profile('whatsapp') ?? ''),
            'direccion' => (string)($user->profile('direccion') ?? ''),
            'departamento' => (string)($user->profile('departamento') ?? ''),
            'zona' => (string)($user->profile('zona') ?? ''),
        ];
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function saveUser(): void
    {
        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingUserId)],
        ]);

        if ($this->isEditing) {
            $user = User::find($this->editingUserId);
            $user->update(['name' => $this->form['name'], 'email' => $this->form['email']]);
        } else {
            $user = User::create([
                'name' => $this->form['name'],
                'email' => $this->form['email'],
                'password' => Hash::make(Str::random(12)),
            ]);
        }

        foreach (['dni', 'whatsapp', 'direccion', 'departamento', 'zona'] as $key) {
            $user->setProfileKeyValue($key, $this->form[$key] ?? '');
        }

        $this->showModal = false;
        session()->flash('success', 'Operación exitosa.');
    }

    private function resetForm(): void
    {
        $this->form = ['name' => '', 'email' => '', 'dni' => '', 'whatsapp' => '', 'direccion' => '', 'departamento' => '', 'zona' => ''];
        $this->editingUserId = null;
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoidXN1YXJpb3MiLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL3VzdWFyaW9zXC9pbmRleC5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-1406550702-0', $__slots ?? [], get_defined_vars());

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
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/usuarios/index.blade.php ENDPATH**/ ?>