@php
    if(isset($seo)){
        $seo = (is_array($seo)) ? ((object)$seo) : $seo;
    }
@endphp
@if(isset($seo->title))
    <title>{{ $seo->title }}</title>
@else
    <title>{{ setting('site.title', 'ALMAMIA') . ' - ' . setting('site.description', 'PANEL DE CONTROL') }}</title>
@endif

<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge"> <!-- † -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('/') }}">

<x-favicon></x-favicon>

{{-- Social Share Open Graph Meta Tags --}}
@if(isset($seo->title) && isset($seo->description) && isset($seo->image))
    <meta property="og:title" content="{{ $seo->title }}">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:image" content="{{ $seo->image }}">
    <meta property="og:type" content="@if(isset($seo->type)){{ $seo->type }}@else{{ 'article' }}@endif">
    <meta property="og:description" content="{{ $seo->description }}">
    <meta property="og:site_name" content="{{ setting('site.title') }}">

    <meta itemprop="name" content="{{ $seo->title }}">
    <meta itemprop="description" content="{{ $seo->description }}">
    <meta itemprop="image" content="{{ $seo->image }}">

    @if(isset($seo->image_w) && isset($seo->image_h))
        <meta property="og:image:width" content="{{ $seo->image_w }}">
        <meta property="og:image:height" content="{{ $seo->image_h }}">
    @endif
@endif

<meta name="robots" content="index,follow">
<meta name="googlebot" content="index,follow">

@if(isset($seo->description))
    <meta name="description" content="{{ $seo->description }}">
@endif

@filamentStyles
@livewireStyles
@vite(['resources/themes/anchor/assets/css/app.css', 'resources/themes/anchor/assets/js/app.js'])

<!-- <link rel="stylesheet" href="{{ asset('themes/anchor/assets/css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/filament/admin/theme.css') }}"> 

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

@php
    $customCss = trim(setting('custom.css'));
@endphp
@if($customCss !== '')
    {{-- Estilos personalizados gestionados por administradoras; el contenido debe cumplir las políticas internas de sanitización. --}}
    <style>{!! $customCss !!}</style>
@endif
