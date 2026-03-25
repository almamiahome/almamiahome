# Etapa 3 — Premios de liderazgo avanzados

**Rango del roadmap:** pasos 11 al 16  
**Objetivo:** completar reglas de premio líder para liquidación integral.

## Plan de implementación T11–T16

| Tarea | Alcance implementado | Estado |
|---|---|---|
| T11 Retención | Validación contra actividad del cierre anterior con evidencia en métricas. | completo |
| T12 Altas | Cálculo de cuotas y persistencia de historial en `lider_altas_cuotas`. | completo |
| T13 Cobranza | Regla de corte de 7 días respecto de `fecha_cierre` con prueba de bordes. | completo |
| T14 Crecimiento | Registro idempotente de salto de rango en `lider_saltos_rango_historial`. | completo |
| T15 Reparto | Módulo dedicado por tipo 1C/2C/3C y trazabilidad de monto total. | completo |
| T16 Plus/unidades | Módulo de objetivo próximo cierre + mínimo de unidades por rango. | completo |

## Tarea 11. Premio por retención
- Comparar actividad del cierre actual vs anterior.
- Aplicar regla desde rango RUBÍ en adelante.
- Guardar motivo de cumplimiento o rechazo.

## Tarea 12. Premio por altas
- Calcular altas válidas del cierre.
- Modelar pago en cuotas consecutivas.
- Parametrizar excepción o diferencial por +3 altas.

## Tarea 13. Premio por cobranza
- Definir criterio exacto de cobranza en tiempo y forma.
- Vincular a estados reales de pago.
- Liquidar por rango solo cuando cumple condiciones.

## Tarea 14. Premio por crecimiento
- Detectar salto de rango entre cierres.
- Pagar una sola vez por salto válido.
- Evitar duplicados en cierres posteriores.

## Tarea 15. Premio por reparto
- Calcular actividad por monto de referencia del rango.
- Guardar bandas mínima/máxima para control.
- Integrar resultado al cierre financiero siguiente.

## Tarea 16. Plus de crecimiento y premio por unidades
- Evaluar objetivo proyectado del próximo cierre.
- Aplicar porcentajes de plus por rango.
- Liquidar premio por unidades mínimas alcanzadas.
