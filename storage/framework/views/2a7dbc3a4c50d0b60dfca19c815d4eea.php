<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Categoria;
use App\Models\PuntajeRegla;
use Livewire\Volt\Component;

middleware('auth');
name('categorias');

new class extends Component {
    public $categorias;

    public $reglas = [];

    public $nombre;
    public $slug;
    public $categoria_ids = [];

    public $editing = false;
    public $categoria_id;

    public function mount()
    {
        $this->reloadCollections();
    }

    protected function reloadCollections(): void
    {
        $this->categorias = Categoria::with('puntajeReglas')->latest()->get();
        $this->reglas = PuntajeRegla::orderBy('descripcion')->get();
    }

    public function deleteCategoria(Categoria $categoria)
    {
        $categoria->delete();
        $this->reloadCollections();
    }

    public function editCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        $this->categoria_id = $categoria->id;
        $this->nombre = $categoria->nombre;
        $this->slug = $categoria->slug;
        $this->puntaje_regla_id = optional($categoria->puntajeRegla()->first())->id;
        $this->editing = true;
    }

    public function saveCategoria()
    {
        $this->validate([
            'nombre' => 'required|max:255',
            'slug' => 'nullable|max:255',
            'categoria_ids' => 'array',
            'categoria_ids.*' => 'exists:puntaje_reglas,id',
        ]);

        $categoria = Categoria::findOrFail($this->categoria_id);
        $categoria->update([
            'nombre' => $this->nombre,
            'slug' => $this->slug ?: null,
        ]);

        $categoria->puntajeRegla()->sync($this->puntaje_regla_id ? [$this->puntaje_regla_id] : []);

        session()->flash('message', 'Categoría actualizada correctamente.');

        $this->editing = false;
        $this->categoria_ids = [];
        $this->reloadCollections();
    }

    public function closeModal()
    {
        $this->editing = false;
        $this->categoria_ids = [];
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiY2F0ZWdvcmlhcyIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvY2F0ZWdvcmlhc1wvaW5kZXguYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-1318416046-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/categorias/index.blade.php ENDPATH**/ ?>