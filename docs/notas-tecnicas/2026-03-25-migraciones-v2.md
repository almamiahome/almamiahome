# Notas técnicas - propuesta de migraciones para versión 2

## Objetivo

Dejar preparado el esquema para Alma Mía v2 con foco en:

- rendimiento de cálculo de premios,
- trazabilidad de campañas,
- orden y metadatos de hotspots del catálogo,
- control reproducible de seeders.

## Migraciones agregadas

1. `2026_03_25_100000_v2_reforzar_indices_plan_premios.php`
   - Índices compuestos para acelerar consultas en `premio_reglas` y `metricas_lider_campana`.

2. `2026_03_25_100100_v2_crear_cierre_campana_historial_estados.php`
   - Nueva tabla de historial de estados de campañas.

3. `2026_03_25_100200_v2_extender_catalogo_hotspot_producto_con_orden.php`
   - Agrega `orden` y `datos` en hotspots para controlar layout dinámico en catálogo.

4. `2026_03_25_100300_v2_crear_control_ejecucion_seeders.php`
   - Tabla para registrar versión y fecha de ejecución de seeders.

5. `2026_03_25_170000_add_catalogo_cierre_unidades_to_pedidos_table.php`
   - Agrega a `pedidos` los campos `catalogo_id`, `cierre_id`, `unidades_facturables` y `unidades_auxiliares`.
   - Incluye claves foráneas, índices compuestos para consultas por líder/fecha y soporte de trazabilidad por campaña.
   - Regla funcional aplicada en carga de pedidos: las unidades auxiliares no suman a premios ni a unidades comerciales.

## Recomendación de despliegue

1. Ejecutar en staging con `php artisan migrate --pretend`.
2. Validar planes de ejecución SQL en MySQL (índices compuestos).
3. Correr `php artisan migrate:fresh --seed` en SQLite y MySQL dentro de CI.
