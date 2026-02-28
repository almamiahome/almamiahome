<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class OnboardingModal extends Component
{
    public bool $show = false;

    public string $name = '';

    public string $dni = '';
    
    public string $direccion = '';

    public string $departamento = '';

    public string $zona = '';
    
    public string $whatsapp = '';

    public string $role = '';

    public string $lider_id = '';

    public string $coordinadora_id = '';
    
    public array $lideresDisponibles = [];

    public array $coordinadorasDisponibles = [];

    /**
     * Datos ya resueltos del líder / coordinadora según el ID cargado
     * (solo para mostrar nombre e ID abajo del input).
     */
    public ?array $liderSeleccionado = null;

    public ?array $coordinadoraSeleccionada = null;


    public function mount(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $this->name = (string) ($user->name ?? '');
        $this->dni = (string) $user->profile('dni');
        $this->direccion = (string) $user->profile('direccion');
        $this->departamento = (string) $user->profile('departamento');
        $this->zona = (string) $user->profile('zona');
        $this->whatsapp = (string) $user->profile('whatsapp');

        $currentRole = (string) $user->roles()->pluck('name')->first();
        $this->role = in_array($currentRole, ['vendedora', 'lider'], true) ? $currentRole : '';

        $this->lider_id = $user->lider_id ? (string) $user->lider_id : '';
        $this->coordinadora_id = $user->coordinadora_id ? (string) $user->coordinadora_id : '';
        
        $this->lideresDisponibles = User::role('lider')
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn ($lider) => [
                'id' => (string) $lider->id,
                'name' => $lider->name,
            ])
            ->all();

        $this->coordinadorasDisponibles = User::role('coordinadora')
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn ($coordinadora) => [
                'id' => (string) $coordinadora->id,
                'name' => $coordinadora->name,
            ])
            ->all();

        // Si ya tiene líder / coordinadora cargados, precargamos sus datos
        if ($this->lider_id !== '') {
            $this->updatedLiderId($this->lider_id);
        }

        if ($this->coordinadora_id !== '') {
            $this->updatedCoordinadoraId($this->coordinadora_id);
        }

        $this->show = $user->needsOnboarding();
    }

    /**
     * Cuando cambia el ID de líder, resolvemos el usuario y guardamos
     * nombre + id en $liderSeleccionado para mostrarlo debajo del input.
     */
    public function updatedLiderId($value): void
    {
        $this->liderSeleccionado = null;

        if ($value === '' || $value === null) {
            return;
        }

        $user = User::role('lider')->find((int) $value);

        if ($user) {
            $this->liderSeleccionado = [
                'id' => (string) $user->id,
                'name' => $user->name,
            ];
        }
    }

    /**
     * Igual que arriba, pero para coordinadora.
     */
    public function updatedCoordinadoraId($value): void
    {
        $this->coordinadoraSeleccionada = null;

        if ($value === '' || $value === null) {
            return;
        }

        $user = User::role('coordinadora')->find((int) $value);

        if ($user) {
            $this->coordinadoraSeleccionada = [
                'id' => (string) $user->id,
                'name' => $user->name,
            ];
        }
    }

    public function save(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'departamento' => ['required', 'string', 'max:255'],
            'zona' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::in(['vendedora', 'lider'])],
            'lider_id' => ['nullable', 'integer', 'exists:users,id'],
            'coordinadora_id' => ['nullable', 'integer', 'exists:users,id'],
        ];

        if ($this->role === 'vendedora') {
            $rules['lider_id'][] = 'required';
        }

        if ($this->role === 'lider') {
            $rules['coordinadora_id'][] = 'required';
        }

        $this->validate($rules, [], [
            'name' => 'nombre',
            'dni' => 'DNI',
            'direccion' => 'dirección',
            'departamento' => 'departamento',
            'zona' => 'zona',
            'whatsapp' => 'WhatsApp',
            'role' => 'rol',
            'lider_id' => 'ID de líder',
            'coordinadora_id' => 'ID de coordinadora',
        ]);

        $liderId = $this->role === 'vendedora' ? (int) $this->lider_id : null;
        $coordinadoraId = $this->role === 'lider' ? (int) $this->coordinadora_id : null;

        $user->forceFill([
            'name' => $this->name,
            'lider_id' => $liderId,
            'coordinadora_id' => $coordinadoraId,
        ])->save();

        $user->setProfileKeyValue('dni', $this->dni);
        $user->setProfileKeyValue('direccion', $this->direccion);
        $user->setProfileKeyValue('departamento', $this->departamento);
        $user->setProfileKeyValue('zona', $this->zona);
        $user->setProfileKeyValue('whatsapp', $this->whatsapp);

        $user->syncRoles([$this->role]);

        $this->show = false;

        session()->flash('onboarding_saved', 'Guardamos tus datos correctamente.');
    }

    public function render()
    {
        return view('livewire.onboarding-modal');
    }
}
