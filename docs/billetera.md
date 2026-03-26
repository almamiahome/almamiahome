# Módulo de Billetera (Alma Mia Fragancias)

## Objetivo funcional

Centralizar en una sola vista y servicio los saldos de puntos/dinero de cada usuaria, con trazabilidad por campaña/cierre y detalle auditable de cada crédito o débito.

## Componentes implementados

- **Servicio:** `app/Services/BilleteraService.php`
- **Vista (Folio + Volt):** `resources/themes/anchor/pages/billetera/index.blade.php`
- **Ledger unificado:** tabla `billetera_movimientos`
- **Modelo ledger:** `app/Models/BilleteraMovimiento.php`

## Regla funcional consolidada

El resumen de billetera expone:

1. saldo actual,
2. saldo a cobrar del mes vigente,
3. saldo proyectado al siguiente cierre,
4. puntaje ganado por período,
5. rango actual,
6. faltante para próximo rango,
7. clasificación actual a premios (sí/no + regla).

La fuente se centraliza en `BilleteraService`, y se sincroniza automáticamente cuando se recalculan:

- puntos de revendedora,
- premios de liderazgo,
- liquidaciones financieras.

## Impacto en base de datos y modelos

### Tabla nueva: `billetera_movimientos`

Campos clave:

- `user_id`
- `catalogo_id`
- `cierre_id`
- `liquidacion_cierre_id`
- `tipo_saldo` (`dinero` / `puntos`)
- `naturaleza` (`credito` / `debito`)
- `monto`
- `puntos`
- `origen`
- `detalle`
- `fecha_movimiento`
- `idempotencia_clave` (única por usuaria)
- `referencia_type` / `referencia_id` (trazabilidad polimórfica)

### Sincronización desde servicios de negocio

- `PremiosRevendedoraService`: registra en ledger cada movimiento de puntos.
- `PremiosLiderCalculator`: registra premio total por cierre en ledger monetario.
- `LiquidacionCierreService`: registra balance neto liquidado en ledger monetario.

## Ejemplo práctico

Si una líder cierra campaña con:

- saldo a cobrar: 45.000,
- saldo a pagar: 30.000,
- deuda arrastrada: 5.000,
- descuento aplicado: 2.000,

el balance neto resultante (12.000) queda en `liquidaciones_cierre` y también en `billetera_movimientos`, permitiendo consultar historial consolidado en `/billetera`.
