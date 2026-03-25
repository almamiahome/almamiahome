# Alma Mia Fragancias

## Resumen del sistema

Este repositorio contiene la plataforma interna de **Alma Mia Fragancias** para gestionar:

- catálogo comercial,
- jerarquías (Revendedora → Líder → Coordinadora),
- pedidos y comprobantes,
- premios y métricas de liderazgo,
- pagos/cobros diferidos por campaña.

La base tecnológica se mantiene sobre **Laravel + Wave + Filament + Folio + Livewire/Volt**.


## Dependencias

> Fuente de verdad: `composer.json` (backend) y `package.json` (frontend).  
> Última actualización de dependencias documentada: **25 de marzo de 2026**.

### Backend (PHP/Laravel)

| Paquete | Versión mínima | Propósito crítico |
|---|---:|---|
| php | 8.2 | Versión base del runtime para ejecutar Wave/Laravel en el proyecto. |
| laravel/framework | 12.18 | Núcleo del framework para rutas, Eloquent, migraciones, eventos y servicios. |
| devdojo/app | 0.11.0 | Componente base de Wave para estructura SaaS y funciones administrativas. |
| devdojo/auth | 1.0 | Gestión de autenticación integrada con Wave. |
| devdojo/themes | 0.0.11 | Soporte de temas y personalización visual en la base Wave. |
| filament/filament | 4.0 | Panel administrativo y recursos para operación interna. |
| filament/tables | 4.0 | Tablas avanzadas para listados, filtros y acciones en administración. |
| laravel/folio | 1.1 | Enrutamiento basado en archivos para mantener la convención del proyecto. |
| livewire/livewire | 3.5 | Componentes reactivos server-driven para formularios y flujos operativos. |
| ralphjsmit/livewire-urls | 1.5 | Manejo declarativo de estados/URLs en componentes Livewire. |
| spatie/laravel-permission | 6.12 | Roles y permisos para control de acceso por perfil comercial/administrativo. |
| tymon/jwt-auth | 2.2 | Autenticación JWT para integraciones API y sesiones tokenizadas. |
| stripe/stripe-php | 17.3 | Integración con Stripe para cobros/pagos cuando aplique. |
| lab404/laravel-impersonate | 1.7.5 | Suplantación controlada para soporte operativo y diagnóstico. |
| gehrisandro/tailwind-merge-laravel | 1.3 | Normalización de clases Tailwind en Blade para evitar conflictos visuales. |
| codeat3/blade-phosphor-icons | 2.0 | Iconografía en Blade para interfaz administrativa y pública. |

### Frontend (Node/Vite/Tailwind)

| Paquete | Versión mínima | Propósito crítico |
|---|---:|---|
| vite | 6.2 | Bundling y servidor de desarrollo para assets frontend. |
| laravel-vite-plugin | 1.0 | Integración oficial entre Vite y Laravel. |
| tailwindcss | 4.1.12 | Sistema de diseño utilitario del proyecto. |
| @tailwindcss/vite | 4.1.12 | Integración de Tailwind 4 con pipeline de Vite. |
| @tailwindcss/postcss | 4.1.12 | Plugin de Tailwind para el pipeline PostCSS. |
| @tailwindcss/forms | 0.5.10 | Estilos consistentes para formularios. |
| @tailwindcss/typography | 0.5.16 | Tipografía rica para contenido editorial y documentación. |
| alpinejs | 3.4.2 | Interactividad ligera en vistas Blade/Livewire. |
| axios | 1.8.2 | Cliente HTTP para llamadas asíncronas del frontend. |
| postcss | 8.4.38 | Transformación CSS en la cadena de build. |
| postcss-nesting | 12.1.1 | Soporte de anidado CSS compatible con PostCSS. |
| autoprefixer | 10.4.19 | Prefijos automáticos para compatibilidad entre navegadores. |

---

## Módulos actuales (implementados)

### 1) Gestión de usuarias y jerarquías

- Modelo de usuarias con relaciones `lider_id` y `coordinadora_id`.
- Relación adicional `coordinadora_lider` para asignaciones múltiples.
- Base operativa para onboarding comercial y asignación de red.

### 2) Pedidos comerciales

- Tabla `pedidos` con:
  - código de pedido,
  - referencia a `catalogo_id` y `cierre_id` para trazabilidad comercial,
  - totales monetarios,
  - total de puntos y unidades facturables (auxiliares excluidos),
  - separación entre `unidades_facturables` y `unidades_auxiliares`,
  - estado operativo,
  - estado de pago,
  - comprobante de pago.
- Tabla `pedido_articulos` con detalle por producto (cantidad, precios, descuento, puntos, bulto y SKU).

### 3) Catálogo y puntaje

- `productos`, `categorias`, `categoria_producto`.
- `puntaje_reglas` + pivot `categoria_puntaje_regla`.
- `gastos_administrativos` para costos complementarios.

### 4) Catálogo visual (hotspots)

- `catalogos`, `catalogo_paginas`, `catalogo_pagina_productos`.
- Soporte de hotspots de grupo mediante `es_grupo` + `catalogo_hotspot_producto`.

### 5) Premios de líderes

- `rangos_lideres`.
- `premio_reglas`.
- `metricas_lider_campana`.
- `repartos_compras`.
- `cierres_campana`.

Este bloque ya cubre el cálculo base de actividad, altas, cobranzas, crecimiento, reparto y acumulados por campaña.

### 6) Pagos y cobros diferidos (base)

- `pagos` para liquidaciones de revendedoras.
- `cobros` para montos de líderes y coordinadoras.
- Estados de programación/pago para administración financiera inicial.

---

## Módulos a desarrollar en V2 (según `sistema.txt`)

### A) Estructura anual y cierres/campañas

Objetivo:

- formalizar calendario anual de 4 catálogos,
- 3 cierres por catálogo,
- trazabilidad completa del pedido contra cierre y liquidación.

### B) Estructura de pagos diferidos avanzada

Objetivo:

- saldo a pagar / a cobrar por cierre,
- balance neto,
- deuda acumulada,
- descuentos aplicables a cierres futuros,
- vista consolidada por líder/coordinadora.

### C) Filtros por territorio y vistas comerciales avanzadas

Objetivo:

- segmentación por zona y departamento,
- vista de líderes avanzada,
- vista individual de líder con historial y proyección.

### D) Módulos de revendedoras

- Tienda de premios.
- Premio por 3 pedidos consecutivos.
- Premio de continuidad y ventas por puntos acumulables.

### E) Módulos de líderes

- Premio actividad.
- Premio retención.
- Premio altas.
- Premio cobranza.
- Premio crecimiento.
- Premio reparto.
- Plus de crecimiento.
- Premio por unidades.

---

## Funciones importantes transversales (no atadas a un único módulo)

Estas funciones atraviesan todo el sistema y deben mantenerse como capacidades globales:

1. **Autenticación y seguridad**
   - registro, acceso, recuperación,
   - roles/permisos,
   - verificación y trazabilidad básica.

2. **Onboarding comercial**
   - alta guiada de usuaria,
   - asignación correcta de jerarquía,
   - validación de referente (líder/coordinadora).

3. **Motor de estados**
   - estado de pedido,
   - estado de pago/comprobante,
   - estado de campaña/cierre.

4. **Trazabilidad y auditoría**
   - historial de cálculos,
   - detalle JSON de métricas,
   - evidencia de comprobantes y fechas de pago.

5. **Reportería operativa**
   - indicadores por campaña,
   - control de actividad de red,
   - soporte para análisis y decisiones comerciales.

---

## Nota de alineación

- `db.html` quedó sincronizado con las tablas actuales de migraciones y con el backlog funcional V2 definido en `sistema.txt`.
- Este README concentra el inventario de módulos actuales, backlog V2 y funciones transversales para planificación técnica y comercial.
