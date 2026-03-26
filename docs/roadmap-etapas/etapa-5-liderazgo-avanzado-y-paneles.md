# Etapa 5 — Liderazgo avanzado, vistas operativas y cierre funcional V2

**Rango del roadmap:** tareas 5.1 a 5.10 (según `version2/resumen.md`).  
**Objetivo:** consolidar el flujo completo de líderes (crecimiento, reparto, plus, unidades), con interfaces Volt/Folio listas para operar en la arquitectura clásica del sistema.

## Verificación de entrada (dependencia de Etapa 4)

- **T17:** ✅ Cumple (módulo financiero integral con pruebas dedicadas).
- **T18:** ✅ Cumple (reportería base y filtros validados).
- **T19:** ⚠️ Pendiente por bloqueo de memoria en ejecución integral.
- **T20:** ⚠️ Pendiente por depender de T19.

### Dictamen de inicio Etapa 5

Se puede **iniciar Etapa 5 en modo implementación funcional controlada** (diseño y construcción de páginas + reglas de negocio), manteniendo el cierre productivo bloqueado hasta resolver T19/T20.

## Estructura de páginas (Folio/Volt) para Etapa 5

> Regla de arquitectura: cada carpeta representa su URL.

- `resources/themes/anchor/pages/lideres/seguimiento-cierres/index.blade.php` → `/lideres/seguimiento-cierres`
- `resources/themes/anchor/pages/lideres/liquidacion/index.blade.php` → `/lideres/liquidacion`

Ambas páginas deben declarar explícitamente middleware en el bloque PHP de Folio.

## Checklist operativo Etapa 5

| ID | Objetivo | Estado (completo/parcial/faltante) | Cobertura | Evidencia actual | Falta para cerrar |
|---|---|---|---|---|---|
| 5.1 | Crecimiento por cambio de nivel | parcial | Heredada Etapa 3 | `PremiosLiderCalculator::moduloCrecimiento()` + `persistirHistorialSaltoRango()` | Versionado de regla Etapa 5 + prueba dedicada de salto único |
| 5.2 | Reparto por nivel | parcial | Heredada Etapa 3 | `moduloReparto()` + `calcularRepartoTotal()` con `RepartoCompra` | Pruebas por bandas min/max orientadas a Etapa 5 |
| 5.3 | Plus de crecimiento | parcial | Heredada + avance Etapa 5 | `moduloPlusUnidades()` con `objetivo_proximo_cierre` y `premio_plus_crecimiento` | Evidencia histórica del objetivo y validación integral |
| 5.4 | Premio por unidades | completo | Cierre definitivo Etapa 5 (servicio) | Validación `unidades_minimas` y persistencia en `MetricaLiderCampana` | Solo mantener regresión en T19 |
| 5.5 | Total consolidado a cobrar | completo | Cierre definitivo Etapa 5 (servicio) | `premio_total` consolidado con trazabilidad en `datos.evidencia` | Validación final de entorno en T19 |
| 5.6 | Filtros zona/departamento/catálogo/cierre | completo | Cierre definitivo Etapa 5 (vista) | `/lideres/seguimiento-cierres` con filtros completos | Prueba de integración posterior a desbloqueo T19 |
| 5.7 | Vista líder avanzada | completo | Cierre definitivo Etapa 5 (vista) | KPIs + desglose por cierre seleccionado en seguimiento | Exportable opcional según alcance |
| 5.8 | Vista líder individual | completo | Cierre definitivo Etapa 5 (vista) | `/lideres/liquidacion` con selección explícita de catálogo/rango | Ajustes de UX no bloqueantes |
| 5.9 | E2E reportería + premios | faltante | Sin cierre por entorno | `php artisan test` aún bloqueado por memoria | Resolver T19 y ejecutar suite completa |
| 5.10 | Documentación y handoff final | parcial | En construcción | Acta continuidad + matriz + checklist actualizados | Acta final con hash posterior a T19 |

## Instrucciones claras para resolver pendientes

1. **Cerrar motor de cálculo (5.1–5.5)**
   - Centralizar reglas de crecimiento, reparto, plus y unidades en un único servicio de dominio.
   - Guardar trazabilidad por cierre (`datos`/metadatos) para auditoría.
   - Asegurar idempotencia por clave `(lider_id, cierre_id, versión_regla)`.

2. **Completar paneles Volt/Folio (5.7–5.8)**
   - Mantener páginas dentro de `resources/themes/anchor/pages/lideres/...` para no romper la arquitectura clásica.
   - Declarar `middleware(['auth', ...])` en cada página Blade Volt.
   - Exponer comparativas por catálogo/cierre con estructura similar a las planillas actuales.

3. **Validación técnica (5.9)**
   - Resolver entorno para ejecutar `php artisan test` completo.
   - Añadir pruebas para casos de bordes: cambio de rango, retención no cumplida, objetivo de plus fallido y umbral de unidades.

4. **Cierre documental (5.10)**
   - Actualizar matriz de trazabilidad y acta técnica con hash de commit validado.
   - Publicar instructivo de operación para coordinadoras/líderes con ejemplos reales de cálculo.

## Criterio de salida de Etapa 5

Etapa 5 se considera cerrada únicamente cuando:

- 5.1–5.8 estén en estado ✅ con evidencia técnica y funcional.
- 5.9 esté ✅ con suite integral ejecutada sin bloqueo de entorno.
- 5.10 esté ✅ con documentación de operación y traspaso final.
