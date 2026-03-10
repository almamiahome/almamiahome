# Base de datos del proyecto: estado actual y expansión requerida por `sistema.txt`

## Estado actual (implementado)

### Núcleo comercial
- `users`
- `pedidos` (incluye `estado_pago`, `comprobante_pago_path`, `comprobante_pago_subido_en`)
- `productos`
- `catalogo_pagina_productos`

### Premios de liderazgo
- `rangos_lideres`
- `premio_reglas`
- `repartos_compras`
- `metricas_lider_campana`

### Cobros/pagos
- `pagos`
- `cobros`

## Campos nuevos recomendados para lograr el objetivo

### En tablas existentes

#### `pedidos`
- `catalogo_id` (FK)
- `cierre_id` (FK)
- `unidades_facturables` (int)
- `unidades_auxiliares` (int)

#### `metricas_lider_campana`
- `retencion_ok` (bool)
- `plus_crecimiento_ok` (bool)
- `premio_retencion` (decimal)
- `premio_plus_crecimiento` (decimal)
- `objetivo_proximo_cierre` (int)
- `actividad_cierre_anterior` (int)

#### `pagos` / `cobros`
- `liquidacion_cierre_id` (FK nullable)
- `componente` (string)

#### `users`
- `zona_id` (FK nullable)
- `departamento_id` (FK nullable)

### Tablas nuevas recomendadas
- `catalogos`
- `revendedora_rachas`
- `revendedora_puntos`
- `tienda_premios`
- `canjes_premios`
- `liquidaciones_cierre`
- `descuentos_futuros`
- `zonas`
- `departamentos`

## Objetivo de la expansión
- Cubrir premios de revendedora y de líderes definidos en `sistema.txt`.
- Consolidar pagos diferidos y balance por cierre.
- Permitir administración por territorios (zona/departamento).
- Dejar trazabilidad clara y auditable para cada cierre/campaña.
