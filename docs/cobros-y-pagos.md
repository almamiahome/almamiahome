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
