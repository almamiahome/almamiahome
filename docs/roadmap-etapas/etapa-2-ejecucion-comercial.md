# Etapa 2 — Ejecución comercial y premios de revendedora

**Rango del roadmap:** pasos 6 al 10  
**Objetivo:** activar estructura anual y comenzar la lógica operativa de premios.  
**Estado actual:** en curso *(pendiente validación completa de `php artisan test` por limitación de entorno: falta extensión `ext-sodium`).* 

## Tarea 6. Seeder maestro anual
- Sembrar catálogos y cierres completos del año.
- Definir convención de códigos (ej.: `CAT-AAAA-N`, `CAMP-AAAA-NN`).
- Integrar la carga con `AlmamiaSeeder` y `ProductosSeeder`.

## Tarea 7. Motor de 3 pedidos consecutivos
- Crear seguimiento por revendedora y cierre.
- Calcular rachas por catálogo.
- Registrar entrega del premio en el cierre correspondiente.

## Tarea 8. Motor de puntos por continuidad y ventas
- Registrar puntos ganados, canjeados y vigencia.
- Parametrizar escalas por unidades.
- Mantener historial auditable de movimientos.

## Tarea 9. Tienda de premios para canje
- Definir catálogo de premios y stock lógico.
- Implementar flujo de canje y aprobación.
- Descontar puntos y registrar estado de entrega.

## Tarea 10. Premio líder por actividad
- Validar rangos y montos oficiales.
- Calcular cumplimiento por cierre.
- Persistir evidencia del cálculo para auditoría.

## Checklist de salida T6–T10 (medible)

- [ ] **Idempotencia T6:** ejecución repetida de seeders sin duplicar registros críticos por código y por cierre.
- [ ] **Idempotencia T7:** recálculo de rachas por cierre con `updateOrCreate` y cardinalidad estable por `(user_id, catalogo_id, cierre_id)`.
- [ ] **Trazabilidad T8:** cada movimiento de puntos registra origen, motivo, saldo posterior e identificador de idempotencia.
- [ ] **Auditoría T9:** cada canje aprobado conserva saldo posterior, fecha de canje y referencia de premio.
- [ ] **Consistencia de saldos T8+T9:** saldo final validado por suma del ledger y por `saldo_posterior` del último movimiento.
- [ ] **Auditoría T10:** evidencia estructurada por reglas aplicadas y versión de cálculo en `metricas_lider_campana.datos`.

> El estado puede cambiar a **completo** únicamente cuando el checklist quede en ✅ y la suite `php artisan test` pase íntegra en un entorno con dependencias completas.
