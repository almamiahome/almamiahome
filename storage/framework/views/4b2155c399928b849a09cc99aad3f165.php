<?php
use function Laravel\Folio\{middleware, name};
use App\Models\Producto;
use App\Models\Categoria;
use Livewire\Volt\Component;
use Livewire\WithFileUploads; // Importante para la imagen
use Illuminate\Support\Facades\Storage;

middleware('auth');
name('productos');

new class extends Component {
    use WithFileUploads; // Habilitar subida de archivos

    public $productos = [];
    public $categorias = [];
    public $editing = false;
    public $producto_id;

    public $nombre;
    public $precio;
    public $puntos_por_unidad;
    public $activo = true;
    public $categoria_id;
    public $sku = null;
    public $descripcion = null;
    public $stock_actual = null;
    public $altura = null;
    public $anchura = null;
    public $profundidad = null;
    public $bulto = null;
    public $imagen = null; // Ruta actual en BD
    public $nueva_imagen;   // Propiedad temporal para el upload

    // Filtros y búsqueda
    public $search = '';
    public $filter_categoria = '';

    public function mount()
    {
        $this->categorias = Categoria::orderBy('nombre')->get();
        $this->loadProductos();
    }

    public function updated($field)
    {
        if (in_array($field, ['search', 'filter_categoria'])) {
            $this->loadProductos();
        }
    }

    public function loadProductos()
    {
        $query = Producto::with('categorias')->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->filter_categoria)) {
            $query->whereHas('categorias', function ($q) {
                $q->where('categorias.id', $this->filter_categoria);
            });
        }

        $this->productos = $query->get();
    }

    public function deleteProducto(Producto $producto)
    {
        $producto->delete();
        $this->loadProductos();
    }

    public function editProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $this->producto_id = $producto->id;
        $this->nombre = $producto->nombre;
        $this->precio = $producto->precio;
        $this->puntos_por_unidad = $producto->puntos_por_unidad;
        $this->activo = $producto->activo;
        $this->categoria_id = optional($producto->categorias->first())->id;
        $this->sku = $producto->sku;
        $this->descripcion = $producto->descripcion;
        $this->stock_actual = $producto->stock_actual;
        $this->altura = $producto->altura;
        $this->anchura = $producto->anchura;
        $this->profundidad = $producto->profundidad;
        $this->bulto = $producto->bulto;
        $this->imagen = $producto->imagen;
        $this->nueva_imagen = null; // Resetear el dropzone
        $this->editing = true;
    }

    public function saveProducto()
    {
        $this->validate([
            'nombre' => 'required|max:255',
            'precio' => 'required|numeric|min:0',
            'puntos_por_unidad' => 'required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'sku' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:65535',
            'stock_actual' => 'nullable|integer|min:0',
            'altura' => 'nullable|string|max:255',
            'anchura' => 'nullable|string|max:255',
            'profundidad' => 'nullable|string|max:255',
            'bulto' => 'nullable|string|max:255',
            'nueva_imagen' => 'nullable|image|max:2048', // Validación de imagen
            'activo' => 'nullable|boolean'
        ]);

        $producto = Producto::findOrFail($this->producto_id);

        // Lógica de guardado de imagen
        $rutaImagen = $this->imagen;
        if ($this->nueva_imagen) {
            $rutaImagen = $this->nueva_imagen->store('productos', 'public');
        }

        $producto->update([
            'nombre' => $this->nombre,
            'precio' => $this->precio,
            'puntos_por_unidad' => $this->puntos_por_unidad,
            'activo' => $this->activo,
            'sku' => $this->sku,
            'descripcion' => $this->descripcion,
            'stock_actual' => $this->stock_actual,
            'altura' => $this->altura,
            'anchura' => $this->anchura,
            'profundidad' => $this->profundidad,
            'bulto' => $this->bulto,
            'imagen' => $rutaImagen,
        ]);

        $producto->categorias()->sync($this->categoria_id ? [$this->categoria_id] : []);

        session()->flash('message', 'Producto actualizado correctamente.');
        $this->editing = false;
        $this->loadProductos();
    }

    public function closeModal()
    {
        $this->editing = false;
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
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoicHJvZHVjdG9zIiwicGF0aCI6InJlc291cmNlc1wvdGhlbWVzXC9hbmNob3JcL3BhZ2VzXC9wcm9kdWN0b3NcL2luZGV4LmJsYWRlLnBocCJ9", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-2431019359-0', $__slots ?? [], get_defined_vars());

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
<?php endif; ?><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/productos/index.blade.php ENDPATH**/ ?>