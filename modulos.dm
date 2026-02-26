# Módulos del tema Anchor (resumen segmentado)

## 1) Base del tema
- `resources/themes/anchor/theme.json`: define metadatos y configuración del tema Anchor.
- `resources/themes/anchor/page.blade.php`: plantilla base de render para páginas Folio.
- `resources/themes/anchor/theme.jpg`: imagen de vista previa del tema.

## 2) Activos frontend
- `resources/themes/anchor/assets/css/app.css`: estilos globales del tema.
- `resources/themes/anchor/assets/css/component-styles.css`: estilos de componentes reutilizables.
- `resources/themes/anchor/assets/js/app.js`: comportamiento JS principal (interacciones UI).

## 3) Layouts
- `components/layouts/app.blade.php`: layout autenticado (panel interno).
- `components/layouts/marketing.blade.php`: layout público/comercial.
- `components/layouts/empty.blade.php`: layout mínimo para vistas especiales.

## 4) Componentes de aplicación (`components/app`)
- `sidebar.blade.php`, `sidebar-link.blade.php`, `sidebar-dropdown.blade.php`: navegación lateral y jerarquía de menú.
- `user-menu.blade.php`, `light-dark-toggle.blade.php`: utilidades de sesión y apariencia.
- `heading.blade.php`, `container.blade.php`, `container-full.blade.php`, `dashboard-card.blade.php`: estructura visual del panel.
- `alert.blade.php`, `message-for-admin.blade.php`, `message-for-subscriber.blade.php`, `settings-layout.blade.php`: mensajes y composición contextual.

## 5) Componentes de elementos (`components/elements`)
- `button`, `input`, `label`, `checkbox`, `select` (si aplica por vistas): controles de formulario.
- `card`, `container`, `placeholder`, `icon`, `heading-description`: bloques de interfaz reutilizables.
- `link`, `back-button`, `settings-sidebar-link`, `code-inline`: navegación y soporte visual.

## 6) Componentes de marketing
- `components/marketing/elements/header.blade.php`: cabecera pública.
- `components/marketing/elements/heading.blade.php`: títulos y subtítulos del sitio.
- `components/marketing/sections/hero|features|pricing|testimonials.blade.php`: secciones de landing.

## 7) Componentes Alma Mia (`components/almamia`)
- `hero-banner`, `call-to-action`, `newsletter-signup`: captación y conversión.
- `product-card`, `category-card`, `card-horizontal`, `badge`, `rating-stars`: catálogo y presentación de productos.
- `filter-sidebar`, `filter-horizontal`, `tab-navigation`, `table-basic`: exploración y visualización de datos.
- `accordion`, `modal-popup`, `image-carousel`, `stats-counter`, `testimonial-card`, `icon-box`, `breadcrumb`, `pricing-table`, `contact-form`: UI especializada de marca.

## 8) Parciales (`partials`)
- `head`, `footer`, `footer-scripts`, `header-app`: esqueleto común HTML/JS.
- `menus/*`: menús de navegación móvil/escritorio para marketing y app.
- `blog/*`, `pagination.blade.php`, `notifications.blade.php`, `toast.blade.php`: piezas reutilizables por módulo.
- `payment-form`, `cancel*`, `reactivate`, `switch-plans-modal`: flujo de suscripción/facturación.
- `dev_bar.blade.php`, `changelogs.blade.php`: utilidades de desarrollo y cambios.

## 9) Páginas Folio (`pages`)
- `index`, `pricing`, `blog`, `changelog`: páginas públicas.
- `dashboard`, `pedidos`, `mis-pedidos`, `catalogo`, `productos`, `categorias`, `stock`, `rotulos`: operación comercial.
- `campanas`, `puntaje-reglas`, `rangos`, `bono-lideres`, `bono-coordinadoras`: crecimiento y recompensas.
- `pagos`, `cobros`, `gastos`, `resumen-*`, `crecimiento-cierre-general`: finanzas y cierres.
- `vendedoras`, `lideres`, `coordinadoras`, `incorporar`, `usuarios`: gestión de red comercial.
- `agente`, `editor`, `notificaciones`, `perfil`, `settings/*`: soporte, configuración y comunicación.
- `documentacion/index.blade.php`: guía funcional/técnica de referencia interna.
- `mejoras/index.php`, `marketplace/index.php`: módulos Volt orientados a catálogo de mejoras y marketplace.

## 10) Correos
- `emails/verify-email.blade.php`: plantilla de verificación de correo.

## 11) Datos auxiliares de mejoras
- `pages/mejoras/items/*.json`: catálogo de mejoras y automatizaciones sin depender de base de datos.

## Nota operativa
Este archivo resume el propósito de los archivos por segmentos para facilitar onboarding técnico y navegación rápida del tema Anchor.

## 12) Cómo utilizar `components/layouts/empty.blade.php`
Uso recomendado (muy breve):
- Se usa cuando una vista necesita estructura mínima, sin sidebar, sin cabecera de panel y sin bloques de marketing.
- Ideal para pantallas especiales: impresión, pantallas embebidas, estados intermedios o vistas utilitarias.

Patrón sugerido:
1. En la página Folio/Blade, envolver el contenido con `<x-layouts.empty>`.
2. Renderizar solo el bloque necesario de la funcionalidad (formulario, mensaje o componente puntual).
3. Agregar estilos puntuales en la vista si hace falta centrar o acotar ancho, sin cargar estructura extra.

Ejemplo rápido:
```blade
<x-layouts.empty>
    <main class="mx-auto max-w-md py-10">
        <h1 class="text-xl font-semibold">Pantalla mínima</h1>
        <p class="text-sm text-zinc-600">Contenido sin navegación lateral.</p>
    </main>
</x-layouts.empty>
```

## 13) Cómo funcionan `components/` y `elements/`
Resumen operativo:
- `components/` agrupa bloques reutilizables Blade por dominio (`app`, `marketing`, `almamia`, `elements`).
- `components/elements/` contiene piezas atómicas (botón, input, label, card, icono, etc.).
- Los componentes de mayor nivel (`app/*`, `marketing/*`, `almamia/*`) se construyen combinando elementos atómicos.

Flujo de uso:
1. Seleccionar layout (`app`, `marketing` o `empty`).
2. Armar secciones con componentes de dominio (`x-app.*`, `x-marketing.*`, `x-almamia.*`).
3. Dentro de esos bloques, reutilizar `x-elements.*` para mantener consistencia visual y reducir duplicación.

Regla práctica:
- Si el bloque resuelve una sola función visual (ej: botón/campo), va en `elements`.
- Si compone varias piezas con lógica de presentación (ej: sidebar, hero, card de producto), va en su dominio dentro de `components`.

