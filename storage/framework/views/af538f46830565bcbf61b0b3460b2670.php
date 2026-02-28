<?php

use function Laravel\Folio\{middleware, name};
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Volt\Component;

middleware('auth');
name('incorporar');

new class extends Component {
    public ?User $currentUser = null;
    public string $currentRole = 'registered';

    public array $form = [];
    public bool $isEditing = false;
    public ?int $editingUserId = null;
    public ?string $editingUserName = null;

    /** @var Collection|array */
    public $usuarios;
    /** @var Collection|array */
    public $coordinadoras;
    /** @var Collection|array */
    public $lideres;
    /** @var Collection|array */
    public $coordinadorasDelLider;

    public array $tiposPermitidos = [];

    public function mount(): void
    {
        $this->currentUser = auth()->user();
        $this->currentRole = $this->currentUser?->getRoleNames()->first() ?? 'registered';
        $this->tiposPermitidos = $this->resolveAllowedTipos();
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    protected function resolveAllowedTipos(): array
    {
        return match ($this->currentRole) {
            'admin' => ['vendedora', 'lider', 'coordinadora'],
            'coordinadora' => ['lider'],
            'lider' => ['vendedora'],
            default => [],
        };
    }

    protected function defaultForm(): array
    {
        $tipo = $this->tiposPermitidos[0] ?? 'vendedora';

        return [
            'tipo' => $tipo,
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'dni' => '',
            'whatsapp' => '',
            'direccion' => '',
            'zona' => '',
            'departamento' => '',
            'lider_id' => $this->currentRole === 'lider' ? $this->currentUser?->id : null,
            'coordinadora_id' => $this->currentRole === 'coordinadora' ? $this->currentUser?->id : null,
        ];
    }

    protected function loadData(): void
    {
        $this->coordinadoras = User::role('coordinadora')->orderBy('name')->get();
        $this->lideres = User::role('lider')->orderBy('name')->get();
        $this->coordinadorasDelLider = $this->currentRole === 'lider'
            ? $this->currentUser?->coordinadoras()->orderBy('name')->get()
            : collect();

        if ($this->coordinadorasDelLider instanceof Collection
            && $this->coordinadorasDelLider->count() === 1
            && empty($this->form['coordinadora_id'])) {
            $this->form['coordinadora_id'] = $this->coordinadorasDelLider->first()->id;
        }

        $this->usuarios = $this->resolveUsuarios();
    }

    protected function resolveUsuarios()
    {
        return match ($this->currentRole) {
            'admin' => User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['vendedora', 'lider', 'coordinadora']);
                })
                ->with(['roles', 'lideres', 'coordinadoras'])
                ->orderBy('name')
                ->get(),
            'lider' => User::role('vendedora')
                ->where('lider_id', $this->currentUser?->id)
                ->with('roles')
                ->orderBy('name')
                ->get(),
            'coordinadora' => User::role('lider')
                ->where('coordinadora_id', $this->currentUser?->id)
                ->with('roles')
                ->orderBy('name')
                ->get(),
            default => collect(),
        };
    }

    public function updatedFormTipo(): void
    {
        if (($this->form['tipo'] ?? null) === 'coordinadora') {
            $this->form['lider_id'] = null;
            $this->form['coordinadora_id'] = null;
        }

        if (($this->form['tipo'] ?? null) === 'lider') {
            $this->form['lider_id'] = null;
        }

        $this->resetValidation();
    }

    protected function rules(): array
    {
        $passwordRules = $this->isEditing
            ? ['nullable', 'string', 'confirmed', Password::defaults()]
            : ['required', 'string', 'confirmed', Password::defaults()];

        $rules = [
            'form.tipo' => ['required', 'string', Rule::in($this->tiposPermitidos)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingUserId)],
            'form.password' => $passwordRules,
            'form.password_confirmation' => $this->isEditing ? ['nullable'] : ['required'],
            'form.dni' => ['nullable', 'string', 'max:255'],
            'form.whatsapp' => ['nullable', 'string', 'max:255'],
            'form.direccion' => ['nullable', 'string', 'max:255'],
            'form.zona' => ['nullable', 'string', 'max:255'],
            'form.departamento' => ['nullable', 'string', 'max:255'],
            'form.lider_id' => ['nullable', 'integer', 'exists:users,id'],
            'form.coordinadora_id' => ['nullable', 'integer', 'exists:users,id'],
        ];

        if (($this->form['tipo'] ?? null) === 'vendedora') {
            $rules['form.lider_id'] = ['required', 'integer', 'exists:users,id'];
            $rules['form.coordinadora_id'] = ['required', 'integer', 'exists:users,id'];
        }

        if (($this->form['tipo'] ?? null) === 'lider') {
            $rules['form.coordinadora_id'] = ['required', 'integer', 'exists:users,id'];
        }

        return $rules;
    }

    public function save()
    {
        if (empty($this->tiposPermitidos)) {
            $this->addError('form.tipo', 'No tenés permisos para incorporar usuarios.');
            return null;
        }

        if ($this->currentRole === 'lider'
            && ($this->form['tipo'] ?? null) === 'vendedora'
            && ($this->coordinadorasDelLider instanceof Collection && $this->coordinadorasDelLider->isEmpty())) {
            $this->addError('form.coordinadora_id', 'Necesitás vincular una coordinadora antes de poder incorporar vendedoras.');
            return null;
        }

        $validated = $this->validate($this->rules());
        $data = $validated['form'];
        $data['lider_id'] = $data['lider_id'] ?: null;
        $data['coordinadora_id'] = $data['coordinadora_id'] ?: null;

        $this->enforceRoleLimits($data);

        if ($this->isEditing) {
            if ($this->updateExistingUser($data)) {
                session()->flash('message', 'Usuario actualizado correctamente.');
            }
        } else {
            $this->createNewUser($data);
            session()->flash('message', 'Usuario creado correctamente.');
        }

        return redirect()->route('incorporar');
    }

    protected function enforceRoleLimits(array &$data): void
    {
        if ($this->currentRole === 'lider') {
            $data['tipo'] = 'vendedora';
            $data['lider_id'] = $this->currentUser?->id;
        }

        if ($this->currentRole === 'coordinadora') {
            $data['tipo'] = 'lider';
            $data['coordinadora_id'] = $this->currentUser?->id;
        }

        if ($data['tipo'] === 'coordinadora') {
            $data['lider_id'] = null;
            $data['coordinadora_id'] = null;
        }
    }

    protected function createNewUser(array $data): void
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $this->applyVinculos($user, $data);
        $user->save();

        $this->syncRoleByTipo($user, $data['tipo']);
        $this->syncCoordinadoraPivot($user, $data['tipo']);
        $this->persistProfileValues($user, $data);
    }

    protected function updateExistingUser(array $data): bool
    {
        if (! $this->editingUserId) {
            session()->flash('error', 'No se encontró el usuario seleccionado.');
            return false;
        }

        $user = $this->findUserForEdit($this->editingUserId);

        if (! $user) {
            session()->flash('error', 'No tenés permisos para editar este usuario.');
            return false;
        }

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $this->applyVinculos($user, $data);
        $user->save();

        $this->syncRoleByTipo($user, $data['tipo']);
        $this->syncCoordinadoraPivot($user, $data['tipo']);
        $this->persistProfileValues($user, $data);

        return true;
    }

    protected function applyVinculos(User $user, array $data): void
    {
        if ($data['tipo'] === 'vendedora') {
            $user->lider_id = $data['lider_id'];
            $user->coordinadora_id = $data['coordinadora_id'];
        } elseif ($data['tipo'] === 'lider') {
            $user->lider_id = null;
            $user->coordinadora_id = $data['coordinadora_id'];
        } else {
            $user->lider_id = null;
            $user->coordinadora_id = null;
        }
    }

    protected function syncRoleByTipo(User $user, string $tipo): void
    {
        $roleName = match ($tipo) {
            'coordinadora' => 'coordinadora',
            'lider' => 'lider',
            default => 'vendedora',
        };

        $user->syncRoles([$roleName]);
    }

    protected function syncCoordinadoraPivot(User $user, string $tipo): void
    {
        if ($tipo === 'lider' && $user->coordinadora_id) {
            $user->coordinadoras()->sync([$user->coordinadora_id]);
        } else {
            $user->coordinadoras()->detach();
        }
    }

    protected function persistProfileValues(User $user, array $data): void
    {
        foreach (['dni', 'whatsapp', 'direccion', 'zona', 'departamento'] as $key) {
            $user->setProfileKeyValue($key, $data[$key] ?? '');
        }
    }

    protected function findUserForEdit(int $userId): ?User
    {
        $query = User::query()->with('roles');

        if ($this->currentRole === 'lider') {
            $query->where('lider_id', $this->currentUser?->id);
        }

        if ($this->currentRole === 'coordinadora') {
            $query->where('coordinadora_id', $this->currentUser?->id);
        }

        return $query->find($userId);
    }

    public function startEdit(int $userId): void
    {
        if ($this->currentRole !== 'admin') {
            session()->flash('error', 'Solo el admin puede editar usuarios.');
            return;
        }

        $user = $this->findUserForEdit($userId);

        if (! $user) {
            session()->flash('error', 'No tenés permisos para editar este usuario.');
            return;
        }

        $tipo = $this->inferTipoDesdeRoles($user);

        if (! in_array($tipo, $this->tiposPermitidos, true)) {
            session()->flash('error', 'No podés editar este tipo de usuario.');
            return;
        }

        $this->isEditing = true;
        $this->editingUserId = $user->id;
        $this->editingUserName = $user->name;

        $this->form = [
            'tipo' => $tipo,
            'name' => $user->name,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
            'dni' => optional($user->profileKeyValue('dni'))->value ?? '',
            'whatsapp' => optional($user->profileKeyValue('whatsapp'))->value ?? '',
            'direccion' => optional($user->profileKeyValue('direccion'))->value ?? '',
            'zona' => optional($user->profileKeyValue('zona'))->value ?? '',
            'departamento' => optional($user->profileKeyValue('departamento'))->value ?? '',
            'lider_id' => $user->lider_id,
            'coordinadora_id' => $user->coordinadora_id,
        ];

        $this->resetValidation();
    }

    protected function inferTipoDesdeRoles(User $user): string
    {
        if ($user->hasRole('coordinadora')) {
            return 'coordinadora';
        }

        if ($user->hasRole('lider')) {
            return 'lider';
        }

        return 'vendedora';
    }

    public function cancelEdit(): void
    {
        $this->resetFormState();
    }

    protected function resetFormState(): void
    {
        $this->form = $this->defaultForm();
        $this->isEditing = false;
        $this->editingUserId = null;
        $this->editingUserName = null;
        $this->resetValidation();
        $this->resetErrorBag();
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiaW5jb3Jwb3JhciIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvaW5jb3Jwb3JhclwvaW5kZXguYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-3271186761-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/incorporar/index.blade.php ENDPATH**/ ?>