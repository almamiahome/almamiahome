<?php
    if(isset($seo)){
        $seo = (is_array($seo)) ? ((object)$seo) : $seo;
    }
?>
<?php if(isset($seo->title)): ?>
    <title><?php echo e($seo->title); ?></title>
<?php else: ?>
    <title><?php echo e(setting('site.title', 'ALMAMIA') . ' - ' . setting('site.description', 'PANEL DE CONTROL')); ?></title>
<?php endif; ?>

<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge"> <!-- † -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta name="url" content="<?php echo e(url('/')); ?>">

<?php if (isset($component)) { $__componentOriginal82e3f864bb766fbb95cb0a10b750823c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82e3f864bb766fbb95cb0a10b750823c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.favicon','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('favicon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82e3f864bb766fbb95cb0a10b750823c)): ?>
<?php $attributes = $__attributesOriginal82e3f864bb766fbb95cb0a10b750823c; ?>
<?php unset($__attributesOriginal82e3f864bb766fbb95cb0a10b750823c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82e3f864bb766fbb95cb0a10b750823c)): ?>
<?php $component = $__componentOriginal82e3f864bb766fbb95cb0a10b750823c; ?>
<?php unset($__componentOriginal82e3f864bb766fbb95cb0a10b750823c); ?>
<?php endif; ?>


<?php if(isset($seo->title) && isset($seo->description) && isset($seo->image)): ?>
    <meta property="og:title" content="<?php echo e($seo->title); ?>">
    <meta property="og:url" content="<?php echo e(Request::url()); ?>">
    <meta property="og:image" content="<?php echo e($seo->image); ?>">
    <meta property="og:type" content="<?php if(isset($seo->type)): ?><?php echo e($seo->type); ?><?php else: ?><?php echo e('article'); ?><?php endif; ?>">
    <meta property="og:description" content="<?php echo e($seo->description); ?>">
    <meta property="og:site_name" content="<?php echo e(setting('site.title')); ?>">

    <meta itemprop="name" content="<?php echo e($seo->title); ?>">
    <meta itemprop="description" content="<?php echo e($seo->description); ?>">
    <meta itemprop="image" content="<?php echo e($seo->image); ?>">

    <?php if(isset($seo->image_w) && isset($seo->image_h)): ?>
        <meta property="og:image:width" content="<?php echo e($seo->image_w); ?>">
        <meta property="og:image:height" content="<?php echo e($seo->image_h); ?>">
    <?php endif; ?>
<?php endif; ?>

<meta name="robots" content="index,follow">
<meta name="googlebot" content="index,follow">

<?php if(isset($seo->description)): ?>
    <meta name="description" content="<?php echo e($seo->description); ?>">
<?php endif; ?>

<?php echo \Filament\Support\Facades\FilamentAsset::renderStyles() ?>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

<?php echo app('Illuminate\Foundation\Vite')(['resources/themes/anchor/assets/css/app.css', 'resources/themes/anchor/assets/js/app.js']); ?>

<!-- <link rel="stylesheet" href="<?php echo e(asset('themes/anchor/assets/css/app.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/filament/admin/theme.css')); ?>"> 

<script>
tailwind.config = {
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        almamia: {
          50:  '#e0f2fe',
          100: '#bae6fd',
          200: '#7dd3fc',
          300: '#38bdf8',
          400: '#4FC3F7',  // Celeste principal
          500: '#0284C7',  // Azul AlmaMia
          600: '#0369a1',
          700: '#075985',
          800: '#0F172A',  // Azul profundo
          900: '#1E293B',  // Fondo dark UI
        },
        ui: {
          light: '#f8fafc',
          dark: '#0f172a',
          muted: '#94a3b8'
        }
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif']
      },
      borderRadius: {
        almamia: '1.25rem'
      }
    }
  }
}
</script>

<script src="https://cdn.tailwindcss.com"></script>
-->

<!-- Tailwind CDN con configuración personalizada -->
<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
    darkMode: 'class', // Solo se activa si la clase dark existe (no por sistema)
    theme: {
        extend: {
            colors: {
                almamia: {
                    pink: '#F47BBE',   // Rosado Almamia
                    blue: '#4FA3D9',   // Azul Almamia
                    light: '#FFF5FB',  // Fondo claro rosado suave
                    soft: '#F0F7FF'    // Fondo azul suave
                }
            }
        }
    }
}
</script>


<script>
/**
 * Reemplaza clases de color legadas por las del tema Almamia.
 * Ajusta el mapa según las variantes necesarias.
 */
const colorMap = {
  'text-blue-500': 'text-[color:var(--almamia-blue,#4FA3D9)]',
  'bg-blue-500': 'bg-[color:var(--almamia-blue,#4FA3D9)]',
  'hover:bg-blue-600': 'hover:bg-[color:var(--almamia-blue,#4FA3D9)]',
  'text-pink-500': 'text-[color:var(--almamia-pink,#F47BBE)]',
  'bg-pink-100': 'bg-[color:var(--almamia-light,#FFF5FB)]',
  'bg-gray-50': 'bg-[color:var(--almamia-soft,#F0F7FF)]'
};

document.addEventListener('DOMContentLoaded', () => {
  Object.entries(colorMap).forEach(([oldClass, newClass]) => {
    document.querySelectorAll('.' + oldClass.replace(/[:]/g, '\\:'))
      .forEach(el => el.classList.replace(oldClass, newClass));
  });
});
</script>

<?php
    $customCss = trim(setting('custom.css'));
?>
<?php if($customCss !== ''): ?>
    
    <style><?php echo $customCss; ?></style>
<?php endif; ?>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/partials/head.blade.php ENDPATH**/ ?>