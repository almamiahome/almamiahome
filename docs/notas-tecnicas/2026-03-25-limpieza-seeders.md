# Notas técnicas - limpieza de seeders y migraciones (2026-03-25)

## Resumen

Se realizó una limpieza técnica para estabilizar migraciones y seeders en MySQL y SQLite.

## Cambios aplicados

- Se explicitó la relación `campana_id` entre `premio_reglas` y `cierres_campana` en los modelos Eloquent.
- Se reorganizó la migración `2026_02_28_000100_extender_hotspots_catalogo.php` para respetar el orden obligatorio de FK al cambiar nulabilidad de `producto_id`.
- En `ProductosSeeder` y `AlmamiaSeeder`:
  - se reemplazó el uso manual de `SET FOREIGN_KEY_CHECKS` por `Schema::disableForeignKeyConstraints()` / `Schema::enableForeignKeyConstraints()`;
  - se migró la carga masiva para insertar por bloques usando `DB::table()->insert()` a partir de dumps SQL procesados.
- Se retiró el seeder duplicado/histórico `DatabaseSeeder-----php` de `database/seeders` y se movió a `docs/legacy/` para trazabilidad.

## Impacto

- Menor dependencia de sintaxis SQL específica de motor.
- Mayor compatibilidad en ejecuciones de CI con SQLite.
- Menor riesgo de fallos por alteraciones de FK en MySQL durante cambios de esquema.
