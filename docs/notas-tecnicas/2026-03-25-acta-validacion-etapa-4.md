# Acta técnica de validación Etapa 4 — 2026-03-25

## Alcance validado
- T17: pruebas financieras de deuda acumulada, descuentos en cierres futuros, balance neto e idempotencia de reproceso.
- T18: pruebas de reportería para filtros por zona/departamento, comparativas entre cierres y conciliación por líder/coordinadora.
- T19: ejecución de suite integral en entorno con dependencias instaladas.

## Commit de referencia validado
- Commit base al momento de validar: `c8f4ccb`.

## Evidencia de pruebas (2026-03-25)
1. `composer install --no-interaction --prefer-dist`  
   - **Resultado:** fallo por ausencia de extensión `ext-sodium` en el entorno.
2. `composer install --no-interaction --prefer-dist --ignore-platform-req=ext-sodium`  
   - **Resultado:** instalación completada y autoload generado.
3. `php artisan test`  
   - **Resultado:** inicio correcto, pero ejecución interrumpida por `Allowed memory size of 134217728 bytes exhausted` durante el bootstrap de íconos (Blade Icons/Phosphor).
4. `php -d memory_limit=512M artisan test`  
   - **Resultado:** persiste el mismo límite de 128MB impuesto durante el arranque de la suite.

## Resultado binario de salida Etapa 4
- **T17:** Cumple (pruebas implementadas).
- **T18:** Cumple (pruebas implementadas).
- **T19:** No cumple (suite integral no finaliza por límite de memoria de entorno).
- **T20:** No cumple (depende del cierre exitoso de T19).

## Acción correctiva recomendada
1. Ejecutar la suite en un entorno con límite de memoria PHP >= 512MB sin sobreescrituras en runtime de Pest/PHPUnit.
2. Repetir `php artisan test` y adjuntar salida completa en esta misma nota.
3. Actualizar el commit de referencia en esta acta con el hash final validado.
