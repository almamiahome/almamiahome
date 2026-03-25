# Etapa 4 — Finanzas, reportería, QA y despliegue

**Rango del roadmap:** pasos 17 al 20
**Objetivo:** cerrar circuito financiero, validar calidad y ejecutar salida productiva.

## Tarea 17. Módulo financiero integral
- Consolidar saldos por cierre (a pagar, a cobrar, deuda, balance).
- Implementar descuentos a cierres futuros.
- Exponer libro mayor por líder y coordinadora.

## Tarea 18. Reportería y vistas operativas
- Construir vista líderes con comparativas entre cierres.
- Construir vista individual con timeline de actividad/premios/pagos.
- Habilitar filtros por zona/departamento y exportables.

## Tarea 19. QA y validación de negocio
- Crear/ajustar pruebas en `tests/` para cálculos críticos.
- Probar casos borde y casos reales.
- Validar resultados con referentes de negocio.

## Tarea 20. Despliegue gradual y adopción
- Plan piloto, monitoreo y salida total.
- Capacitación operativa para perfiles internos.
- Definir métricas de éxito y soporte post-lanzamiento.

## Entregables implementados en esta iteración

### Finanzas
- Migraciones para `liquidaciones_cierre` y `descuentos_futuros` con auditoría y FK a `cierres_campana` + `users`.
- Servicio `LiquidacionCierreService` con cálculo de saldos, balance neto y aplicación automática de descuentos futuros.
- Idempotencia funcional y de base de datos para descuentos futuros por (`origen_liquidacion_id`, `cierre_destino_id`, `motivo`).

### Reportería
- Servicio `ReporteriaFinancieraService` con agregados por líder, coordinadora y cierre.
- Filtros operativos por `zona_id`, `departamento_id`, `catalogo_id`, `cierre_id` y estado.
- Endpoint de timeline individual y exportación comparativa en CSV/XLS (compatibilidad operativa).

### Segmentación geográfica
- Estructura `zonas` y `departamentos` con relación a `users`.
- Fixture reproducible de finanzas para validar escenarios de cierres y descuentos.
