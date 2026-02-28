<?php
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Str;

middleware('auth');
name('productos.masivo');

new class extends Component {
    public $input_text = '';

    public function saveMasivo()
    {
        $lineas = collect(explode("\n", $this->input_text))
            ->map(fn($l) => trim($l))
            ->filter();

        foreach ($lineas as $linea) {
            [$nombre, $precio, $puntos, $categoria_nombre] = array_pad(explode('|', $linea), 4, null);

            $nombre = trim($nombre);
            $precio = $precio ? (float) trim($precio) : 0;
            $puntos = $puntos ? (int) trim($puntos) : 0;
            $categoria_nombre = trim($categoria_nombre);

            if (!$nombre) continue;

            $producto = Producto::create([
                'nombre' => $nombre,
                'precio' => $precio,
                'puntos_por_unidad' => $puntos,
                'activo' => true,
            ]);

            if ($categoria_nombre) {
                $categoria = Categoria::firstOrCreate(
                    ['slug' => Str::slug($categoria_nombre)],
                    ['nombre' => $categoria_nombre]
                );
                $producto->categorias()->sync([$categoria->id]);
            }
        }

        session()->flash('message', 'Productos creados correctamente.');
        $this->input_text = '';
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicHJvZHVjdG9zLm1hc2l2byIsInBhdGgiOiJyZXNvdXJjZXNcL3RoZW1lc1wvYW5jaG9yXC9wYWdlc1wvcHJvZHVjdG9zXC9tYXNpdm8uYmxhZGUucGhwIn0=", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-1144139766-0', $__slots ?? [], get_defined_vars());

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
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/productos/masivo.blade.php ENDPATH**/ ?>