# Guía operativa de reportes financieros

## Objetivo
Concentrar la lectura financiera por cierre para líderes y coordinadoras con filtros de territorio y catálogo.

## Endpoints API (auth:api)
- `GET /api/reportes/lideres`
- `GET /api/reportes/coordinadoras`
- `GET /api/reportes/cierres`
- `GET /api/reportes/lideres/{liderId}/timeline`
- `GET /api/reportes/comparativa/export?formato=csv|xlsx`
- `POST /api/reportes/cierres/{cierre}/aplicar-descuentos`

## Filtros admitidos
- `zona_id`
- `departamento_id`
- `catalogo_id`
- `cierre_id`
- `estado`

## Flujo recomendado
1. Ejecutar resumen por cierre para detectar desvíos de balance.
2. Filtrar por zona/departamento para revisión territorial.
3. Abrir timeline individual de la líder.
4. Exportar comparativa para comité financiero.
5. Aplicar descuentos pendientes sobre el cierre destino cuando corresponda.
