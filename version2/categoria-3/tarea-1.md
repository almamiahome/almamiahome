# Tarea 3.1 — Contrato funcional de tienda de premios

## Contexto de negocio
Esta tarea pertenece a **Categoría 3: Módulos de revendedoras**, alineada al plan de Version 2 definido desde `sistema.txt`.

## Objetivo de la tarea
Contrato funcional de tienda de premios. Con ello se contribuye al objetivo macro de la categoría: **Entregar tienda de premios y reglas de pedidos consecutivos y continuidad/ventas.**.

## Alcance técnico
- Definir contrato de datos, reglas y validaciones específicas de esta tarea.
- Mantener compatibilidad con operación actual (sin ruptura de flujos vigentes).
- Dejar trazabilidad funcional, técnica y de QA para cierre de fase.

## Subtareas mínimas
1. Levantar estado actual y dependencias directas.
2. Diseñar cambio funcional/técnico con impacto explícito.
3. Implementar en código/migraciones/modelos según corresponda.
4. Añadir pruebas automatizadas del caso feliz y casos borde.
5. Documentar evidencia y criterios de salida.

## Riesgos controlados
- Divergencia entre cálculo esperado y cálculo implementado.
- Inconsistencia de datos por cierres históricos incompletos.
- Falta de evidencia para aprobar gate de fase.

## Criterios de éxito
- Regla implementada y verificable por evidencia reproducible.
- Pruebas relacionadas en verde.
- Documentación en español con impacto funcional claro.

## Checklist de validación
- [ ] Se definió criterio funcional sin ambigüedad para esta tarea.
- [ ] Se registró impacto en datos/modelos (si aplica).
- [ ] Se añadieron o ajustaron pruebas automatizadas.
- [ ] Se ejecutaron comandos de validación y se guardó evidencia.
- [ ] Se documentó plan de rollback o corrección.

## Dependencias cruzadas
- `version2/resumen.md`
- `version2/mcp.md`
- `version2/skills.md`
- `sistema.txt`

## Prerrequisitos exactos de ejecución

- PHP **8.2.x** disponible en CLI y en cPanel (`php -v`).
- Composer **2.7+** instalado globalmente (`composer --version`).
- MySQL **8.0+** (o MariaDB compatible) accesible con credenciales válidas.
- Extensiones PHP habilitadas: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`.
- Permisos cPanel requeridos:
  - acceso a **Terminal**,
  - acceso a **MySQL Databases**,
  - permiso de escritura en `storage/` y `bootstrap/cache/`,
  - posibilidad de ejecutar `php artisan` desde la raíz del proyecto.

## Pasos numerados con comandos exactos, salida esperada y validación

| Paso | Comando exacto (sin Node/npm) | Output esperado | Validación manual | Validación automática |
|---|---|---|---|---|
| 1 | `php -v` | Versión PHP 8.2.x visible en consola. | Confirmar que la versión coincide con el entorno objetivo. | `php -r "exit(version_compare(PHP_VERSION, '8.2.0', '>=') ? 0 : 1);"` devuelve código 0. |
| 2 | `composer --version` | Composer 2.7+ reportado. | Verificar que no use binario roto o alias inválido. | `composer --version | grep -E 'Composer version 2\.(7|8|9|[1-9][0-9])'` sin error. |
| 3 | `php -m` | Lista de módulos PHP incluyendo extensiones críticas. | Revisar visualmente `pdo_mysql`, `mbstring`, `bcmath`, `xml`. | `php -m | grep -E '^(pdo_mysql|mbstring|bcmath|xml)$'` con todas presentes. |
| 4 | `php artisan config:clear && php artisan cache:clear` | Mensajes `Configuration cache cleared!` y `Application cache cleared!`. | Confirmar que no hubo excepciones en consola. | Código de salida 0 para ambos comandos. |
| 5 | `php artisan migrate --pretend` | SQL planificada sin aplicar cambios reales. | Validar que aparecen tablas/columnas esperadas de la tarea **3.1**. | Verificar que el comando termina en exit code 0. |
| 6 | `php artisan test --filter="Tarea3_1"` | Resultado de pruebas relacionadas (`PASS` esperado). | Revisar nombre de pruebas tocadas por la tarea. | Si no existen pruebas específicas, registrar explícitamente `No tests executed` y crear pendiente técnica. |
| 7 | `php artisan test` | Suite completa en verde (`PASS`). | Confirmar que no hay tests marcados como `Risky` o `Skipped` sin justificación. | Exit code 0 global. |
| 8 | `php artisan about` | Estado de app, drivers y conexión visible. | Verificar conexión MySQL activa y entorno correcto (`production` o `staging` según plan). | Confirmar presencia de sección `Environment` y `Drivers` en salida. |

## Criterio DONE objetivo (evidencia obligatoria)

La tarea **3.1** se considera **DONE** únicamente si existe evidencia verificable de:

1. **Comandos ejecutados**: transcripción o captura de los pasos 1 al 8 con fecha/hora.
2. **Archivos impactados**: listado concreto de archivos modificados en Git (`git status --short`).
3. **Resultado funcional**: prueba manual reproducible del flujo de negocio que cubre esta tarea.
4. **Resultado automático**: `php artisan test` en verde y, cuando aplique, prueba filtrada de la tarea.
5. **Estado de datos**: evidencia de esquema o datos (tabla/consulta) consistente con el cambio.

Sin estas 5 evidencias, la tarea queda en **NO DONE** y no debe pasar de fase.

