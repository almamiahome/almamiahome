# Acta técnica de continuidad Etapa 5 — 2026-03-26

## Documento fuente único de diagnóstico

A partir de esta fecha, **este documento consolida el diagnóstico de entorno y el seguimiento de continuidad** de Etapa 4/5.

- Acta previa de referencia histórica: `docs/notas-tecnicas/2026-03-25-acta-validacion-etapa-4.md`.
- Estado oficial vigente: **usar esta acta como fuente principal**.

## Diagnóstico de entorno consolidado

| Ítem | Resultado | Fecha |
|---|---|---|
| Dependencias PHP (`composer install --ignore-platform-req=ext-sodium`) | Ejecutado previamente con éxito | 2026-03-25 |
| Suite integral (`php artisan test`) | Bloqueada por límite de memoria de 128MB en bootstrap de íconos | 2026-03-25 y 2026-03-26 |
| T19 (QA integral) | **Parcial / en curso** por limitación de entorno | 2026-03-26 |
| T20 (despliegue/adopción) | **No iniciar cierre definitivo** mientras T19 no cumpla | 2026-03-26 |

### Regla explícita de dependencia T19 → T20

- Si T19 está en estado de solo testeo o bloqueado por entorno, **T20 no se cierra**.
- T20 solo puede pasar a `completo` con evidencia binaria de T19 en verde.

## Auditoría funcional de `PremiosLiderCalculator` contra Etapa 5 (5.1–5.5)

Archivo auditado: `app/Services/PremiosLiderCalculator.php`.

| Criterio Etapa 5 | Estado | Evidencia técnica | Dictamen |
|---|---|---|---|
| 5.1 Crecimiento por cambio de nivel | **Parcial** | `moduloCrecimiento()` + `persistirHistorialSaltoRango()` guardan salto por cierre; falta versionado explícito de regla Etapa 5 | Cobertura heredada Etapa 3, no cierre definitivo Etapa 5 |
| 5.2 Reparto por nivel | **Parcial** | `moduloReparto()` usa cantidades 1C/2C/3C y `calcularRepartoTotal()` con `RepartoCompra`; falta validación explícita por banda en paneles Etapa 5 | Cobertura heredada Etapa 3 |
| 5.3 Plus de crecimiento | **Parcial** | `moduloPlusUnidades()` usa `objetivo_proximo_cierre` y flag `plus_crecimiento_ok`; falta evidencia de persistencia histórica de objetivo por cierre en documentación final | Cobertura heredada con avance Etapa 5 |
| 5.4 Premio por unidades | **Completo (funcional base)** | `unidades_ok` y `premio_unidades` según `rango->unidades_minimas`; integrado en payload de métrica | Cobertura operativa disponible |
| 5.5 Total consolidado a cobrar | **Completo (servicio)** | `premio_total` integra actividad, altas, cobranzas, crecimiento, retención, reparto, plus y unidades en una sola salida auditable | Cierre técnico del servicio logrado |

## Optimización de planificación (mitad de tareas)

Plan anterior operativo: 8 tareas técnicas separadas.

Plan optimizado: **4 macro-tareas** (50% menos pasos), manteniendo trazabilidad:

1. **Macro A — Auditoría + trazabilidad documental**  
   Incluye actualización de matriz, checklist y separación heredado/definitivo.
2. **Macro B — Seguimiento de cierres (Volt/Folio)**  
   Filtros de zona/departamento/catálogo/cierre + desglose por cierre seleccionado.
3. **Macro C — Liquidación auditable (Volt/Folio)**  
   Selección explícita de catálogo/rango de cierres + KPIs Etapa 5 consistentes con `MetricaLiderCampana`.
4. **Macro D — Validación final y bloqueo de salida**  
   Ejecutar `php artisan test`, registrar resultado, y mantener T20 bloqueado hasta T19 en verde.

## Evidencias de continuidad incorporadas en esta iteración

- `resources/themes/anchor/pages/lideres/seguimiento-cierres/index.blade.php`
- `resources/themes/anchor/pages/lideres/liquidacion/index.blade.php`
- `resources/themes/anchor/pages/lideres/panel-etapa-5/index.blade.php`
- `docs/roadmap-etapas/matriz-trazabilidad-v2.md`
- `version2/categoria-5/checklist-etapa-5.md`

## Criterio de siguiente actualización

Actualizar esta acta en cada cambio de alguno de estos puntos:
- resultado de `php artisan test` integral,
- reglas del servicio de premios líder,
- estructura de páginas operativas de liderazgo,
- estados T19/T20 en matriz de trazabilidad.
