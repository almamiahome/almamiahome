# Etapa 2 — Ejecución comercial y premios de revendedora

**Rango del roadmap:** pasos 6 al 10
**Objetivo:** activar estructura anual y comenzar la lógica operativa de premios.

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
