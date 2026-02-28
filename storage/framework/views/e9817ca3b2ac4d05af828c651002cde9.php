<?php

use function Laravel\Folio\{middleware, name};
use App\Models\PuntajeRegla;
use App\Models\Categoria;
use Livewire\Volt\Component;

middleware('auth');
name('puntaje-reglas');

new class extends Component {
    public $reglas;
    public $categorias;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;

    public ?int $editingId = null;

    public array $form = [];

    public function mount(): void
    {
        $this->form = $this->defaultForm();
        $this->loadData();
    }

    protected function loadData(): void
    {
        $this->reglas = PuntajeRegla::with('categorias')->latest()->get();
        $this->categorias = Categoria::orderBy('nombre')->get();
    }

    protected function defaultForm(): array
    {
        return [
            'categoria_ids' => [],
            'min_unidades' => null,
            'max_unidades' => null,
            'descripcion' => '',
            'bonificacion' => null,
            'porcentaje' => null,
            'beneficios' => '',
            'puntaje_minimo' => null,
            'puntaje_minimo_descripcion' => '',
            'puntos_mensuales' => null,
            'puntos_por_campania' => null,
            'datos' => [],
        ];
    }

    public function openCreateModal(): void
    {
        $this->form = $this->defaultForm();
        $this->editingId = null;
        $this->showEditModal = false;
        $this->showCreateModal = true;
    }

    public function openEditModal(int $id): void
    {
        $regla = PuntajeRegla::with('categorias')->findOrFail($id);

        $this->form = [
            'categoria_ids' => $regla->categorias
                ->pluck('id')
                ->map(fn ($value) => (string) $value)
                ->toArray(),
            'min_unidades' => $regla->min_unidades,
            'max_unidades' => $regla->max_unidades,
            'descripcion' => $regla->descripcion ?? '',
            'bonificacion' => $regla->bonificacion,
            'porcentaje' => $regla->porcentaje,
            'beneficios' => $regla->beneficios ?? '',
            'puntaje_minimo' => $regla->puntaje_minimo,
            'puntaje_minimo_descripcion' => $regla->puntaje_minimo_descripcion ?? '',
            'puntos_mensuales' => $regla->puntos_mensuales,
            'puntos_por_campania' => $regla->puntos_por_campania,
            'datos' => collect($regla->datos ?? [])
                ->map(fn ($value, $key) => [
                    'key' => (string) $key,
                    'value' => is_scalar($value) ? (string) $value : json_encode($value),
                ])
                ->values()
                ->toArray(),
        ];

        $this->editingId = $regla->id;
        $this->showCreateModal = false;
        $this->showEditModal = true;
    }

    protected function rules(): array
    {
        return [
            'form.categoria_ids' => ['array'],
            'form.categoria_ids.*' => ['exists:categorias,id'],
            'form.min_unidades' => ['nullable', 'integer', 'min:0'],
            'form.max_unidades' => ['nullable', 'integer', 'min:0'],
            'form.descripcion' => ['nullable', 'string'],
            'form.bonificacion' => ['nullable', 'numeric'],
            'form.porcentaje' => ['nullable', 'numeric'],
            'form.beneficios' => ['nullable', 'string'],
            'form.puntaje_minimo' => ['nullable', 'integer', 'min:0'],
            'form.puntaje_minimo_descripcion' => ['nullable', 'string'],
            'form.puntos_mensuales' => ['nullable', 'integer', 'min:0'],
            'form.puntos_por_campania' => ['nullable', 'integer', 'min:0'],
            'form.datos' => ['nullable', 'array'],
            'form.datos.*.key' => ['nullable', 'string'],
            'form.datos.*.value' => ['nullable', 'string'],
        ];
    }

    protected function extractPayload(): array
    {
        $validated = $this->validate();
        $rawForm = $validated['form'];

        $categoriaIds = collect($rawForm['categoria_ids'] ?? [])
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->all();

        $datosRows = collect($rawForm['datos'] ?? [])
            ->filter(fn ($row) =>
                (($row['key'] ?? '') !== '') || (($row['value'] ?? '') !== '')
            )
            ->mapWithKeys(fn ($row) => [
                $row['key'] ?? '' => $row['value'] ?? '',
            ])
            ->toArray();

        $attributes = collect($rawForm)
            ->except(['categoria_ids', 'datos'])
            ->map(fn ($value) => $value === '' ? null : $value)
            ->toArray();

        $attributes['datos'] = ! empty($datosRows) ? $datosRows : null;

        return [
            'attributes' => $attributes,
            'categoria_ids' => $categoriaIds,
        ];
    }

    public function saveRegla(): void
    {
        $payload = $this->extractPayload();

        $regla = PuntajeRegla::create($payload['attributes']);
        $this->syncCategorias($regla, $payload['categoria_ids']);

        session()->flash('message', 'Regla creada correctamente.');

        $this->redirectRoute('puntaje-reglas', navigate: true);
    }

    public function updateRegla(): void
    {
        if (! $this->editingId) {
            return;
        }

        $payload = $this->extractPayload();

        $regla = PuntajeRegla::findOrFail($this->editingId);
        $regla->update($payload['attributes']);
        $this->syncCategorias($regla, $payload['categoria_ids']);

        session()->flash('message', 'Regla actualizada correctamente.');

        $this->redirectRoute('puntaje-reglas', navigate: true);
    }

    public function deleteRegla(int $id): void
    {
        $regla = PuntajeRegla::findOrFail($id);

        // Muchos-a-muchos: limpiar pivot y borrar regla
        $regla->categorias()->detach();
        $regla->delete();

        session()->flash('message', 'Regla eliminada correctamente.');

        $this->redirectRoute('puntaje-reglas', navigate: true);
    }

    protected function syncCategorias(PuntajeRegla $regla, array $categoriaIds): void
    {
        // Muchos-a-muchos: sincronizar tabla pivot
        $regla->categorias()->sync($categoriaIds);
    }

    public function addDatoRow(): void
    {
        $this->form['datos'][] = ['key' => '', 'value' => ''];
    }

    public function removeDatoRow(int $index): void
    {
        if (! isset($this->form['datos'][$index])) {
            return;
        }

        unset($this->form['datos'][$index]);
        $this->form['datos'] = array_values($this->form['datos']);
    }

    public function closeModals(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editingId = null;
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicHVudGFqZS1yZWdsYXMiLCJwYXRoIjoicmVzb3VyY2VzXC90aGVtZXNcL2FuY2hvclwvcGFnZXNcL3B1bnRhamUtcmVnbGFzXC9pbmRleC5ibGFkZS5waHAifQ==", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-1008782681-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/puntaje-reglas/index.blade.php ENDPATH**/ ?>