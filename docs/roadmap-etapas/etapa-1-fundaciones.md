# Etapa 1 — Fundaciones funcionales y estructurales

**Rango del roadmap:** pasos 1 al 5
**Objetivo:** definir reglas oficiales y dejar lista la base técnica del calendario comercial.

## Tarea 1. Glosario y definición funcional final
- Consolidar términos oficiales del negocio (revendedora, líder, coordinadora, catálogo, cierre).
- Resolver reglas ambiguas de `sistema.txt`.
- Acordar diccionario único para que desarrollo, administración y documentación hablen el mismo idioma.

## Tarea 2. Modelado de estructura anual comercial
- Diseñar entidades para catálogos y cierres.
- Definir relación de 4 catálogos por año y 3 cierres por catálogo.
- Alinear calendario real (inicio/cierre/liquidación) con operación.

## Tarea 3. Estados operativos por cierre
- Definir estados de trabajo del cierre (planificado, abierto, en liquidación, cerrado).
- Establecer hitos de corte para pedidos, cobranza y premios.
- Documentar transiciones permitidas para evitar cierres inconsistentes.

## Tarea 4. Migraciones de estructura temporal
- Crear o ampliar migraciones para soportar el esquema calendario-cierre.
- Agregar índices para consultas por año/catálogo/cierre.
- Dejar claros escenarios de rollback y su impacto.

## Tarea 5. Actualización de modelos y relaciones base
- Ajustar modelos `app/Models` (fillable, casts, relaciones, scopes).
- Relacionar pedidos, pagos, cobros y métricas con cierres.
- Estandarizar scopes de consulta para reportes.
