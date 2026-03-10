# Base de datos: estructura actual vs estructura objetivo (`sistema.txt`)

Este documento resume, de forma visual y entendible, cómo está la base de datos hoy y qué campos/tablas se deben agregar para cubrir todos los módulos pedidos.

## 1) Mapa visual (alto nivel)

```text
USUARIOS / ROLES
 └─ users (revendedora, líder, coordinadora)

OPERACIÓN COMERCIAL
 ├─ pedidos
 ├─ productos
 ├─ catalogo_pagina_productos
 └─ (catálogos/cierres: parcial)

PREMIOS LIDERAZGO
 ├─ rangos_lideres
 ├─ premio_reglas
 ├─ repartos_compras
 └─ metricas_lider_campana

COBROS Y PAGOS
 ├─ pagos
 └─ cobros
```

---

## 2) Estructura actual (resumen funcional)

### Tablas ya presentes y relevantes
- `users`: personas y jerarquías operativas.
- `pedidos`: operación de compra, con estado de pago/comprobante.
- `pagos` y `cobros`: esquema inicial de liquidación diferida.
- `rangos_lideres`, `premio_reglas`, `repartos_compras`, `metricas_lider_campana`: núcleo de premios de líderes ya modelado.
- `productos` y `catalogo_pagina_productos`: soporte de catálogo comercial.

### Limitaciones actuales para `sistema.txt`
- Falta normalizar el ciclo **catálogo anual (4) + 3 cierres** como entidad central.
- No hay un módulo completo para premios de revendedora por racha + puntos + canje.
- Faltan entidades para balance financiero consolidado y descuentos a cierres futuros.
- Falta una capa robusta para zonas/departamentos en reportería operativa.

---

## 3) Estructura objetivo (tablas nuevas sugeridas)

## 3.1 Calendario comercial

### `catalogos`
- `id`
- `anio` (smallint)
- `numero_catalogo` (tinyint, 1-4)
- `mes_inicio` (tinyint)
- `mes_fin` (tinyint)
- `estado` (planificado, activo, cerrado)
- `created_at`, `updated_at`

### `cierres_campana` (ampliar o normalizar la existente)
- `id`
- `catalogo_id` (FK)
- `numero_cierre` (tinyint, 1-3)
- `codigo` (ej.: `CAMP-2026-01`)
- `fecha_inicio`
- `fecha_fin`
- `fecha_liquidacion`
- `estado` (abierto, en_revision, liquidado, cerrado)
- `created_at`, `updated_at`

## 3.2 Premios de revendedora

### `revendedora_rachas`
- `id`
- `user_id` (FK revendedora)
- `catalogo_id` (FK)
- `cierres_consecutivos_cumplidos` (0-3)
- `objetivo_unidades_por_cierre`
- `premio_1_otorgado` (bool)
- `premio_2_otorgado` (bool)
- `premio_3_otorgado` (bool)
- `fecha_entrega_programada`
- `created_at`, `updated_at`

### `revendedora_puntos`
- `id`
- `user_id` (FK)
- `cierre_id` (FK)
- `tipo_movimiento` (acumulacion, canje, ajuste)
- `puntos`
- `descripcion`
- `saldo_posterior`
- `vencen_en` (nullable)
- `created_at`, `updated_at`

### `tienda_premios` y `canjes_premios`
- `tienda_premios`: `id`, `nombre`, `puntos_requeridos`, `stock`, `activo`, timestamps.
- `canjes_premios`: `id`, `user_id`, `premio_id`, `puntos_debitados`, `estado`, `fecha_entrega`, timestamps.

## 3.3 Finanzas y balance diferido

### `liquidaciones_cierre`
- `id`
- `cierre_id` (FK)
- `lider_id` (FK user)
- `coordinadora_id` (FK user, nullable)
- `saldo_a_cobrar`
- `saldo_a_pagar`
- `deuda_arrastrada`
- `descuento_aplicado`
- `balance_neto`
- `detalle_json` (componentes del cálculo)
- `created_at`, `updated_at`

### `descuentos_futuros`
- `id`
- `origen_liquidacion_id` (FK)
- `cierre_destino_id` (FK)
- `monto`
- `motivo`
- `estado` (pendiente, aplicado, anulado)
- `created_at`, `updated_at`

## 3.4 Segmentación geográfica

### `zonas` y `departamentos`
- `zonas`: `id`, `nombre`, `codigo`, timestamps.
- `departamentos`: `id`, `zona_id`, `nombre`, `codigo`, timestamps.

### Nuevos campos en `users`
- `zona_id` (FK nullable)
- `departamento_id` (FK nullable)

---

## 4) Campos nuevos a crear sobre tablas existentes

### `pedidos` (sugeridos)
- `cierre_id` (FK): vincula cada pedido al cierre oficial.
- `catalogo_id` (FK): trazabilidad histórica directa.
- `unidades_facturables` (int): excluye auxiliares/contratapa si corresponde.
- `unidades_auxiliares` (int): separado para control.

### `metricas_lider_campana` (sugeridos)
- `retencion_ok` (bool)
- `plus_crecimiento_ok` (bool)
- `premio_retencion` (decimal)
- `premio_plus_crecimiento` (decimal)
- `objetivo_proximo_cierre` (int)
- `actividad_cierre_anterior` (int)

### `pagos` / `cobros` (sugeridos)
- `liquidacion_cierre_id` (FK nullable)
- `componente` (actividad, altas, unidades, cobranzas, crecimiento, reparto, plus)

---

## 5) Diferencia visual rápida: actual vs nueva

```text
ACTUAL
pedidos -> pagos/cobros
liderazgo -> metricas_lider_campana (parcial reglas nuevas)

NUEVA
catalogos -> cierres_campana -> pedidos -> liquidaciones_cierre
                                 -> revendedora_rachas
                                 -> revendedora_puntos -> canjes
liquidaciones_cierre -> cobros/pagos -> descuentos_futuros
users -> zonas/departamentos
```

---

## 6) Beneficio de esta estructura
- Permite liquidar premios y finanzas por cierre sin ambigüedad.
- Deja trazabilidad auditable por persona, campaña y componente de premio.
- Habilita reportes por jerarquía y territorio.
- Reduce errores manuales en cálculo y pago diferido.
