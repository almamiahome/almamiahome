# Matriz de trazabilidad V2 (fases vs etapas)

## Objetivo

Vincular las **Fases 1..8** definidas en `sistema.txt` con las **Etapas 1..4** del roadmap operativo, con estado de avance y entregables técnicos por tarea.

Estados permitidos:
- **pendiente**
- **en curso**
- **completo**

---

## 1) Mapeo Fase → Etapa con estado

| Fase V2 (`sistema.txt`) | Alcance resumido | Etapa roadmap | Estado | Evidencia/nota |
|---|---|---|---|---|
| Fase 1 — Diagnóstico funcional | Reglas por rol, criterios de aceptación y diccionario | Etapa 1 | en curso | Base documentada en `etapa-1-fundaciones.md`; falta cierre de glosario final. |
| Fase 2 — Datos y migraciones | Tablas, índices, relaciones y reversibilidad | Etapa 1 | en curso | Migraciones V2 iniciales y ajustes de modelo ya incorporados. |
| Fase 3 — Campañas y pedidos base | 4 catálogos, 3 cierres, trazabilidad de pedidos | Etapa 1 + Etapa 2 | en curso | Seeder de calendario y pruebas iniciales disponibles. |
| Fase 4 — Finanzas diferidas | Saldos, deudas, balance y descuentos a futuro | Etapa 4 | en curso | Cobertura T17 extendida con pruebas de deuda acumulada, balance neto e idempotencia en `tests/Feature/LiquidacionCierreServiceTest.php`. |
| Fase 5 — Módulos de revendedoras | Tienda, rachas, continuidad y puntos | Etapa 2 | en curso | Flujo T7+T8+T9 implementado; falta corrida integral de suite en entorno con dependencias completas. |
| Fase 6 — Módulos de líderes | Actividad, retención, altas, cobranza, crecimiento, reparto, plus, unidades | Etapa 3 | en curso | Motor de cálculo con evidencia y campos de retención/plus ya presentes. |
| Fase 7 — Interfaz y reportería | Paneles, comparativas, filtros de gestión | Etapa 4 | en curso | Pruebas de regresión T18 añadidas para filtros zona/departamento y comparativas por cierre en `tests/Unit/ReporteriaFinancieraServiceTest.php`. |
| Fase 8 — Validación final | Checklist integral y decisión binaria de salida | Etapa 4 | en curso | Acta técnica de Etapa 4 publicada en `docs/notas-tecnicas/2026-03-25-acta-validacion-etapa-4.md` con evidencia de ejecución. |

---

## 2) Entregables técnicos por tarea (Etapas 1..4)

> Referencia de tipos: **migración, modelo, seeder, servicio, prueba, documentación**.

### Etapa 1 — Fundaciones funcionales y estructurales

| Tarea | Migración | Modelo | Seeder | Servicio | Prueba | Documentación |
|---|---|---|---|---|---|---|
| T1. Glosario y definición funcional | — | — | — | — | — | Diccionario de negocio y criterios por rol. |
| T2. Estructura anual comercial | Ajustes `catalogos`/`cierres_campana` | `Catalogo`, `CierreCampana` | `CalendarioComercialSeeder` | — | Cobertura de generación determinística | Especificación de estructura anual. |
| T3. Estados operativos por cierre | Historial de estados (si aplica) | `CierreCampanaHistorialEstado` | — | `CierreCampanaStateMachine` | Transiciones válidas/invalidas y bitácora | Matriz de transiciones permitidas. |
| T4. Migraciones temporales | Índices y FK por año/catálogo/cierre | Actualización de casts/fillables | Seeders compatibles con V2 | — | Pruebas de compatibilidad de seeders | Notas de rollback/impacto. |
| T5. Relaciones base | Ajustes relacionales según dominio | Pedidos/pagos/cobros/métricas por cierre | Seeders de relación base | Servicios de consulta base | Pruebas de relación y consulta | Guía de integración de relaciones. |

### Etapa 2 — Ejecución comercial y premios de revendedora

| Tarea | Migración | Modelo | Seeder | Servicio | Prueba | Documentación |
|---|---|---|---|---|---|---|
| T6. Seeder maestro anual | — | — | Seeder anual integrado | — | Verificación de idempotencia y conteos | Convención de códigos y calendario operativo. |
| T7. Motor 3 pedidos consecutivos | `revendedora_rachas` | `RevendedoraRacha` | Fixtures por catálogo/cierre | Motor de rachas | Casos 3 cierres consecutivos y recálculo | Reglas de racha y entrega de premio. |
| T8. Motor de puntos continuidad/ventas | `revendedora_puntos` | `RevendedoraPunto` | Fixtures de movimientos | Motor de puntaje y vigencia | Acumulación/canje/vencimiento + bordes | Tabla de escalas y vigencias por cierre. |
| T9. Tienda de premios/canje | `tienda_premios`, `canjes_premios` | `TiendaPremio`, `CanjePremio` | Catálogo base de tienda | Flujo de canje/aprobación | Pruebas de saldo y estados de canje | Flujo operativo de canje. |
| T10. Premio líder por actividad | Ajustes de `metricas_lider_campana` | `MetricaLiderCampana`, `RangoLider` | Fixtures de métricas | `PremiosLiderCalculator` | Evidencia auditable y corte de fecha | Regla funcional de actividad. |

### Etapa 3 — Premios de liderazgo avanzados

| Tarea | Migración | Modelo | Seeder | Servicio | Prueba | Documentación |
|---|---|---|---|---|---|---|
| T11. Retención | Columnas/flags de retención | Métrica líder (campos retención) | Casos por rango RUBÍ+ | Regla de comparación vs cierre anterior | Casos cumple/no cumple | Criterios de retención por rango. |
| T12. Altas | Campos/cuotas si aplica | Métrica/entidad de cuotas | Escenarios de 3 cierres | Cálculo de cuotas y +3 altas | Pago en cuotas y excepciones | Política de altas y liquidación. |
| T13. Cobranza | Campos de control de fecha/estado | Métrica con trazabilidad de cobro | Escenarios de corte temporal | Validación de cobranza | Límite de fecha y bordes | Regla “en tiempo y forma”. |
| T14. Crecimiento | Restricciones anti-duplicado | Historial de saltos (si aplica) | Escenarios de salto de rango | Cálculo de salto único | Idempotencia de crecimiento | Matriz de saltos y montos. |
| T15. Reparto | Parámetros de montos por tipo | `RepartoCompra` y relación | Seeder de montos referencia | Cálculo de reparto | Bandas min/max y consistencia | Norma de reparto por rango. |
| T16. Plus crecimiento + unidades | Campos de objetivo/plus | Métrica líder (objetivo/unidades) | Casos objetivos próximos | Cálculo de plus y unidades | Cumplimiento por objetivo | Reglas de objetivo próximo cierre. |

### Etapa 4 — Finanzas, reportería, QA y despliegue

| Tarea | Migración | Modelo | Seeder | Servicio | Prueba | Documentación |
|---|---|---|---|---|---|---|
| T17. Módulo financiero integral | Saldos, deudas, descuentos | Entidades financieras por cierre | Datos de saldos base | Liquidación/conciliación | ✅ Pruebas Feature: deuda acumulada, descuentos futuros, balance neto e idempotencia de reproceso (`LiquidacionCierreServiceTest`) | Evidencia y acta en `docs/notas-tecnicas/2026-03-25-acta-validacion-etapa-4.md`. **Estado: completo** |
| T18. Reportería y vistas | Índices para filtros/reportes | DTOs o vistas de agregado | Seeders de datos de reporte | Servicios de agregación | ✅ Pruebas Unit de filtros por zona/departamento, comparativas entre cierres y conciliación por líder/coordinadora (`ReporteriaFinancieraServiceTest`) | Evidencia y acta en `docs/notas-tecnicas/2026-03-25-acta-validacion-etapa-4.md`. **Estado: completo** |
| T19. QA y validación negocio | — | — | Fixtures de regresión | — | ⚠️ `php artisan test` ejecutado en entorno con dependencias instaladas; bloqueado por límite de memoria de 128MB durante bootstrap de íconos | Acta técnica en `docs/notas-tecnicas/2026-03-25-acta-validacion-etapa-4.md`. **Estado: en curso** |
| T20. Despliegue y adopción | — | — | Datos de piloto | Runbooks operativos | Checklists de salida | Plan de despliegue/capacitación. |

---

## 3) Prerrequisitos explícitos de Etapa 3 dependientes de Etapa 2

La Etapa 3 **no debe cerrarse** sin estos prerequisitos de Etapa 2:

1. **Calendario y cierres confiables (T6)**  
   Necesario para comparar cierre actual vs cierre anterior en retención/cobranza.

2. **Rachas operativas de revendedora (T7)**  
   Aporta trazabilidad de continuidad para reglas avanzadas y auditoría cruzada.

3. **Ledger de puntos con vigencia (T8)**  
   Base de consistencia para premios que dependen de comportamiento histórico e idempotencia.

4. **Flujo de tienda/canje estabilizado (T9)**  
   Requisito para validar impactos financieros y conciliación de beneficios.

5. **Premio líder por actividad con evidencia (T10)**  
   Es la base técnica del cálculo auditable que luego extiende retención, crecimiento, plus y unidades.

---

## 4) Criterio de actualización de esta matriz

Actualizar esta matriz cada vez que cambie cualquiera de estos componentes:
- migraciones del dominio comercial,
- modelos vinculados a catálogo/cierre/premios,
- seeders oficiales o fixtures reproducibles,
- servicios de cálculo,
- pruebas de reglas críticas,
- documentación funcional del roadmap.

## 5) Checklist de salida Etapa 2 (T6–T10)

| Tarea | Idempotencia | Trazabilidad | Auditoría | Consistencia de saldos | Estado |
|---|---|---|---|---|---|
| T6 | Seeder anual re-ejecutable sin duplicados por código/cierre. | Códigos de catálogo y cierre determinísticos. | Conteos verificables por año y catálogo. | N/A | ⏳ |
| T7 | Reproceso por `(user_id, catalogo_id, cierre_id)` sin duplicar rachas. | Registro de origen, motivo y saldo de racha. | Estados `activa/premiada/reiniciada` auditables por cierre. | N/A | ⏳ |
| T8 | Movimientos de puntos con clave de idempotencia por operación. | Ledger con `origen`, `motivo`, `datos` y `saldo_posterior`. | Historial de acumulación/canje/vencimiento persistente. | Saldo por sumatoria = saldo posterior final. | ⏳ |
| T9 | Canje con transacción y descuento único por operación. | Relación canje ↔ premio ↔ cierre ↔ usuario. | Fecha de canje, estado y saldo final persistidos. | Canje bloqueado si saldo o stock no alcanzan. | ⏳ |
| T10 | Re-cálculo por `updateOrCreate` en métrica líder. | Evidencia de reglas aplicadas y versión de cálculo. | Fecha corte cobranza + insumos usados en cálculo. | Premios parciales y total consistentes por cierre. | ⏳ |


## 6) Checklist de salida Etapa 4 (T17–T20)

| Tarea | Criterio binario (cumple/no cumple) | Evidencia obligatoria | Estado |
|---|---|---|---|
| T17 | Cumple si existen pruebas automatizadas de deuda acumulada, descuentos futuros, balance neto e idempotencia de reproceso sin duplicar impactos. | `tests/Feature/LiquidacionCierreServiceTest.php` + acta técnica de Etapa 4. | **Cumple** |
| T18 | Cumple si existen pruebas automatizadas de filtros por zona/departamento y comparativas entre cierres con conciliación por líder/coordinadora. | `tests/Unit/ReporteriaFinancieraServiceTest.php` + acta técnica de Etapa 4. | **Cumple** |
| T19 | Cumple si la suite integral `php artisan test` finaliza sin errores de entorno y con evidencia registrada. | Registro de ejecución integral en acta técnica. | **No cumple** (bloqueo de memoria en entorno actual). |
| T20 | Cumple si existe checklist operativo de salida, acta final con commit validado y dependencias de Etapa 5 desbloqueadas. | Matriz + `version2/resumen.md` + acta técnica de Etapa 4. | **No cumple** (bloqueado; T20 no cierra mientras T19 siga en testeo o con bloqueo de entorno). |

## 7) Trazabilidad Etapa 5 (T5.1–T5.10)

> Estados normalizados por tarea: **completo**, **parcial** o **faltante**.

| Tarea | Estado | Tipo de cobertura | Evidencia actual | Falta para cierre definitivo Etapa 5 |
|---|---|---|---|---|
| T5.1 Crecimiento por cambio de nivel | parcial | Cobertura heredada (Etapa 3) | `app/Services/PremiosLiderCalculator.php` (`moduloCrecimiento`, `persistirHistorialSaltoRango`) | Versionado explícito de regla Etapa 5 + prueba dedicada de salto único por cierre. |
| T5.2 Reparto por nivel | parcial | Cobertura heredada (Etapa 3) | `moduloReparto()` + `calcularRepartoTotal()` en `PremiosLiderCalculator` con soporte de `RepartoCompra` | Pruebas adicionales por bandas min/max con foco Etapa 5 y validación en panel operativo. |
| T5.3 Plus de crecimiento | parcial | Cobertura heredada + avance Etapa 5 | `moduloPlusUnidades()` usa `objetivo_proximo_cierre` y `premio_plus_crecimiento` | Evidencia de continuidad histórica del objetivo y pruebas funcionales en rango de cierres. |
| T5.4 Premio por unidades | completo | Cierre funcional del servicio | `moduloPlusUnidades()` valida `unidades_minimas` y persiste `premio_unidades` en `MetricaLiderCampana` | Mantener regresión automática al cerrar T19. |
| T5.5 Total consolidado a cobrar | completo | Cierre funcional del servicio | `premio_total` consolidado en `PremiosLiderCalculator` con trazabilidad en `datos.evidencia` | Pendiente solo validación integral de entorno (T19). |
| T5.6 Filtros zona/departamento/catálogo/cierre | completo | Cierre definitivo Etapa 5 | `resources/themes/anchor/pages/lideres/seguimiento-cierres/index.blade.php` | Añadir prueba de interfaz/integración cuando T19 quede estable. |
| T5.7 Vista líder avanzada | completo | Cierre definitivo Etapa 5 | Página Volt/Folio de seguimiento con middleware explícito y desglose por cierre seleccionado | Ajustes cosméticos no bloqueantes. |
| T5.8 Vista liquidación individual | completo | Cierre definitivo Etapa 5 | `resources/themes/anchor/pages/lideres/liquidacion/index.blade.php` con selección explícita de catálogo/rango de cierres | Añadir exportable si se incluye en alcance final. |
| T5.9 QA E2E reportería + premios | faltante | Sin cobertura integral vigente | Existe intento de `php artisan test`, bloqueado por memoria (ver acta técnica de continuidad 2026-03-26) | Resolver entorno y ejecutar suite completa en verde. |
| T5.10 Documentación y handoff final | parcial | En construcción | Matriz + checklist + acta de continuidad actualizadas | Acta final con hash validado posterior a T19 exitoso y guía operativa final. |

## 8) Regla de bloqueo T20 por dependencia de T19

- Si **T19** permanece en estado `parcial` o `faltante` por testeo/entorno, **T20** se mantiene bloqueada.
- El cierre de **T20** requiere evidencia explícita de ejecución completa de `php artisan test` sin bloqueo.
