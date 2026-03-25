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
| Fase 4 — Finanzas diferidas | Saldos, deudas, balance y descuentos a futuro | Etapa 4 | pendiente | Planificado en `etapa-4-finanzas-reporteria-y-salida.md`. |
| Fase 5 — Módulos de revendedoras | Tienda, rachas, continuidad y puntos | Etapa 2 | en curso | Estructura de tablas de rachas/puntos/canjes disponible; faltan cierres funcionales completos. |
| Fase 6 — Módulos de líderes | Actividad, retención, altas, cobranza, crecimiento, reparto, plus, unidades | Etapa 3 | en curso | Motor de cálculo con evidencia y campos de retención/plus ya presentes. |
| Fase 7 — Interfaz y reportería | Paneles, comparativas, filtros de gestión | Etapa 4 | pendiente | Definido como parte de reportería y salida operativa. |
| Fase 8 — Validación final | Checklist integral y decisión binaria de salida | Etapa 4 | pendiente | Requiere cierre de QA funcional/técnico y validación de negocio. |

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
| T17. Módulo financiero integral | Saldos, deudas, descuentos | Entidades financieras por cierre | Datos de saldos base | Liquidación/conciliación | Cierres financieros integrales | Manual financiero por rol. |
| T18. Reportería y vistas | Índices para filtros/reportes | DTOs o vistas de agregado | Seeders de datos de reporte | Servicios de agregación | Comparativas por cierre y filtros | Guía de reportería operativa. |
| T19. QA y validación negocio | — | — | Fixtures de regresión | — | Suite completa de regresión | Acta de validación funcional. |
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
