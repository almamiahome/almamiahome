# Alma Mia Fragancias

## Resumen del sistema

Este repositorio contiene la plataforma interna de **Alma Mia Fragancias** para gestionar:

- catálogo comercial,
- jerarquías (Revendedora → Líder → Coordinadora),
- pedidos y comprobantes,
- premios y métricas de liderazgo,
- pagos/cobros diferidos por campaña.

La base tecnológica se mantiene sobre **Laravel + Wave + Filament + Folio + Livewire/Volt**.

---

## Módulos actuales (implementados)

### 1) Gestión de usuarias y jerarquías

- Modelo de usuarias con relaciones `lider_id` y `coordinadora_id`.
- Relación adicional `coordinadora_lider` para asignaciones múltiples.
- Base operativa para onboarding comercial y asignación de red.

### 2) Pedidos comerciales

- Tabla `pedidos` con:
  - código de pedido,
  - totales monetarios,
  - total de puntos y unidades,
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
