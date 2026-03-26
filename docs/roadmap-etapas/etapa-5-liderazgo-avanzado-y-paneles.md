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

| ID | Objetivo | Estado | Evidencia actual | Falta para cerrar |
|---|---|---|---|---|
| 5.1 | Crecimiento por cambio de nivel | ⏳ En preparación | Definición funcional en `sistema.txt` y `version2/categoria-5/tarea-1.md` | Motor idempotente + prueba de salto único por cierre |
| 5.2 | Reparto por nivel | ⏳ En preparación | Tabla de montos y rangos documentada | Implementar cálculo por rango + pruebas de bandas min/max |
| 5.3 | Plus de crecimiento | ⏳ En preparación | Regla “objetivo próximo cierre” documentada | Persistir objetivo anterior + validación binaria del plus |
| 5.4 | Premio por unidades | ⏳ En preparación | Mínimos por rango definidos en `sistema.txt` | Regla parametrizable por rango + pruebas de umbral |
| 5.5 | Total consolidado a cobrar | ⏳ En preparación | Estructura de premios líder ya existe | Integrar 5.1–5.4 en un consolidado auditable |
| 5.6 | Filtros zona/departamento | 🟡 Parcial | Filtros base ya existen en reportería Etapa 4 | Conectar filtros a paneles de Etapa 5 |
| 5.7 | Vista líder avanzada | 🟡 Parcial | Página creada: `/lideres/seguimiento-cierres` | Completar KPIs y exportación operacional |
| 5.8 | Vista líder individual | 🟡 Parcial | Página creada: `/lideres/liquidacion` | Conectar liquidación real por cierre/rango |
| 5.9 | E2E reportería + premios | ⛔ Bloqueada | Sin suite integral estable por memoria | Resolver T19 y ejecutar suite completa |
| 5.10 | Documentación y handoff final | ⏳ En preparación | Base documental existente por etapas | Cerrar acta final con evidencia completa |

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
