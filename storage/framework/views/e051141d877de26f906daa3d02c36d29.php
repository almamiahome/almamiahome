<?php
use function Laravel\Folio\{middleware, name};
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // Importante para subir archivos
use App\Models\Producto;
use App\Models\Categoria;

middleware('auth');
name('productos.create');

new class extends Component
{
    use WithFileUploads;

    #[Validate('required|max:255')]
    public $nombre = '';

    #[Validate('required|numeric|min:0')]
    public $precio = 0;

    #[Validate('required|integer|min:0')]
    public $puntos_por_unidad = 0;

    #[Validate('nullable|string|max:255')]
    public $sku = null;

    #[Validate('nullable|string|max:65535')]
    public $descripcion = null;

    #[Validate('nullable|integer|min:0')]
    public $stock_actual = null;

    #[Validate('nullable|boolean')]
    public $activo = true;

    #[Validate('nullable|string|max:255')]
    public $altura = null;

    #[Validate('nullable|string|max:255')]
    public $anchura = null;

    #[Validate('nullable|string|max:255')]
    public $profundidad = null;

    #[Validate('nullable|string|max:255')]
    public $bulto = null;

    // Cambio: Ahora acepta archivos de imagen
    #[Validate('nullable|image|max:2048')] 
    public $imagen = null;

    #[Validate('nullable|integer|exists:categorias,id')]
    public $categoria_id = null;

    public $categorias = [];

    public function mount()
    {
        $this->categorias = Categoria::orderBy('nombre')->get();
    }

    public function save()
    {
        $validated = $this->validate();

        // Lógica para guardar la imagen físicamente
        if ($this->imagen) {
            $validated['imagen'] = $this->imagen->store('productos', 'public');
        }

        $producto = Producto::create($validated);

        if ($this->categoria_id) {
            $producto->categorias()->sync([$this->categoria_id]);
        }

        session()->flash('message', 'Producto creado exitosamente.');
        $this->redirect(route('productos'));
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicHJvZHVjdG9zLmNyZWF0ZSIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvcHJvZHVjdG9zXC9jcmVhdGUuYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-4215819975-0', $__slots ?? [], get_defined_vars());

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
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/productos/create.blade.php ENDPATH**/ ?>