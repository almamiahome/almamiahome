# Cobros y pagos por campaña

Este módulo incorpora un registro explícito para pagos a vendedoras asociados a cada pedido y los cobros (bonos) que reciben líderes y coordinadoras. Los montos se programan al **mes siguiente de la campaña** para reflejar la liquidación diferida de bonos.

## Tablas nuevas
- **`pagos`**: registra pagos a la vendedora de cada pedido, con su monto, estado y mes de pago programado.
- **`cobros`**: almacena los bonos de líderes y coordinadoras, opcionalmente vinculados a un pedido y con la misma lógica de programación al mes siguiente.

### Campos clave
- `mes_campana`: mes en el que se generó el pedido o la campaña que origina el bono.
- `mes_pago_programado`: se autocompleta al **mes siguiente** si no se define explícitamente al crear el registro.
- `estado`: `pendiente`, `programado`, `pagado` o `cancelado` (por defecto queda en `pendiente`).
- `monto`: total del pago o cobro esperado.
- `fecha_pago`: fecha real de pago cuando se confirma.

## Reglas funcionales
- Cada **pago** está vinculado a un `pedido` y a la `vendedora` que lo originó.
- Cada **cobro** puede dirigirse a un `líder` o a una `coordinadora`, y opcionalmente enlazarse al `pedido` que generó el bono.
- Si el registro se crea con `mes_campana` pero sin `mes_pago_programado`, el sistema agenda el pago en el **próximo mes de campaña**.

## Ejemplo de programación
- Pedido en campaña **2025-03** → `mes_pago_programado` queda en **2025-04**.
- Bono de liderazgo generado en **2025-06** → se programa el cobro para **2025-07**.

## Funciones nuevas
- `Pago::calcularMesPago(string $mesCampana)` y `Cobro::calcularMesPago(string $mesCampana)` generan el mes siguiente en formato `YYYY-MM` y se usan en los eventos `creating` para completar automáticamente `mes_pago_programado`.
- Los hooks `creating` en ambos modelos también establecen `estado` en `pendiente` cuando no se envía explícitamente, de modo que las validaciones de estado siempre tengan un valor inicial coherente.

## Integración con el modelo de datos
- `Pedido` expone relaciones `pagos()` y `cobros()` para ver la liquidación asociada.
- `User` incluye `pagosRegistrados()` (como vendedora), `cobrosComoLider()` y `cobrosComoCoordinadora()` para navegar los bonos.

## Flujo de comprobante de pago en pedidos
- Se agregan en `pedidos` los campos `estado_pago`, `comprobante_pago_path` y `comprobante_pago_subido_en`.
- Estados disponibles para verificación por líder:
  - `sin_pago`: pedido todavía sin comprobante.
  - `pendiente_verificacion_lider`: la vendedora adjuntó comprobante y queda pendiente revisión.
  - `verificado`: líder validó el pago.
  - `rechazado`: líder observó o rechazó el comprobante.
- Cuando se adjunta un nuevo comprobante, el sistema mueve automáticamente el pedido a `pendiente_verificacion_lider` para que la líder lo revise.

### Ejemplo práctico
1. La vendedora abre un pedido sin pago y carga un archivo JPG/PDF como comprobante.
2. El pedido cambia a `pendiente_verificacion_lider` y queda visible para control.
3. La líder revisa y actualiza el `estado_pago` a `verificado` o `rechazado`.

### Impacto en BD y modelos
- **BD**: migración `2026_02_28_120000_add_comprobante_pago_to_pedidos_table.php` añade campos de estado y trazabilidad del comprobante.
- **Modelo `Pedido`**: se actualizan `fillable` y `casts` para persistir correctamente los nuevos datos.

## Liquidaciones por cierre y descuentos futuros (Etapa 4)

Se agregan dos tablas de auditoría financiera:

- `liquidaciones_cierre`: guarda por líder/coordinadora y cierre los campos `saldo_a_cobrar`, `saldo_a_pagar`, `deuda_arrastrada`, `descuento_aplicado` y `balance_neto`.
- `descuentos_futuros`: registra descuentos/bonos para aplicar a cierres destino, con idempotencia por (`origen_liquidacion_id`, `cierre_destino_id`, `motivo`).

### Reglas de cálculo

El servicio `LiquidacionCierreService` calcula por cierre y líder:

- `saldo_a_cobrar`: suma de cobros vigentes del cierre.
- `saldo_a_pagar`: suma de pagos asociados a pedidos de su red para el cierre.
- `deuda_arrastrada`: remanente histórico de cierres anteriores.
- `descuento_aplicado`: descuentos futuros ya aplicados al cierre destino.
- `balance_neto`: `saldo_a_cobrar - saldo_a_pagar - deuda_arrastrada + descuento_aplicado`.

También persiste `detalle_json` con trazabilidad del cálculo para auditoría interna.

### Ejemplo real de cierre

Cierre `2026-C1` (líder #2):

- saldo a cobrar: `125.000`
- saldo a pagar: `49.000`
- deuda arrastrada: `8.000`
- descuento aplicado: `0`
- balance neto: `68.000`

Luego se genera un descuento futuro por `12.000` al cierre `2026-C2` con motivo `bonus_buen_cobro`; al ejecutar la aplicación automática del cierre destino, el descuento pasa a estado `aplicado` y aumenta el balance neto del destino.
