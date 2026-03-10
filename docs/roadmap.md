# Roadmap de implementación de `sistema.txt` (Alma Mia)

## 1) Objetivo general
Implementar la versión objetivo del sistema comercial de Alma Mia, pasando del estado actual (módulos de productos, catálogo, premios de liderazgo parciales y cobros/pagos iniciales) a una plataforma integral que administre:

- estructura anual de catálogos y cierres,
- premios de revendedoras,
- premios de líderes,
- pagos diferidos y balance,
- vistas avanzadas por zona/departamento.

## 2) Estado actual vs versión objetivo

### Estado actual (resumen)
- Ya existe base para premios de liderazgo (`rangos_lideres`, `premio_reglas`, `repartos_compras`, `metricas_lider_campana`).
- Ya existe base para pagos/cobros diferidos (`pagos`, `cobros`, trazabilidad en `pedidos`).
- Existe operación de productos, catálogos y pedidos.
- No está modelada de forma completa la estructura anual oficial (catálogo 1..4 + 3 cierres cada uno) como eje de toda la lógica.
- No están implementados de punta a punta los premios de revendedora ni todos los premios avanzados de líder definidos en `sistema.txt`.

### Versión objetivo (según `sistema.txt`)
- Catálogos anuales (4) con cierres mensuales (3 por catálogo).
- Premios revendedora:
  - 3 pedidos consecutivos.
  - continuidad y ventas por puntos canjeables.
- Premios líder:
  - actividad, retención, altas, cobranza, crecimiento, reparto, plus de crecimiento, por unidades.
- Finanzas diferidas:
  - saldo a pagar/cobrar, balance, deudas y descuentos a cierres futuros.
- Analítica y operación:
  - filtros por zonas/departamentos,
  - vista líderes avanzada,
  - vista líder individual.

---

## 3) Plan detallado en 20 pasos (con subtareas)

### Paso 1. Definición funcional final y glosario único
**Subtareas**
- Consolidar términos oficiales: revendedora, líder, coordinadora, catálogo, cierre, campaña, actividad, altas, retención.
- Validar reglas ambiguas de `sistema.txt` (ej.: límites exactos de rangos compartidos como 8-11).
- Aprobar diccionario de negocio para evitar interpretaciones distintas entre módulos.

### Paso 2. Modelar estructura anual comercial
**Subtareas**
- Diseñar entidades: `catalogos`, `cierres_campana` (si aplica ampliar la actual), `periodos`.
- Definir relación catálogo → 3 cierres.
- Definir reglas de calendario (enero-marzo, abril-junio, etc.).

### Paso 3. Diseño de estados operativos por cierre
**Subtareas**
- Definir estados: planificado, abierto, en liquidación, cerrado.
- Definir hitos de corte (carga pedidos, revisión cobranzas, liquidación premios).
- Registrar bitácora de transición por cierre.

### Paso 4. Migraciones de estructura temporal
**Subtareas**
- Crear migraciones para tablas/campos nuevos de catálogo-cierre.
- Agregar índices por año, catálogo y cierre para consultas masivas.
- Documentar impacto de migración y rollback.

### Paso 5. Actualización de modelos y relaciones base
**Subtareas**
- Actualizar modelos en `app/Models` con fillable/casts/relaciones nuevas.
- Conectar `pedidos`, `pagos`, `cobros`, métricas y premios al cierre.
- Agregar scopes reutilizables por `anio`, `catalogo_numero`, `cierre_numero`.

### Paso 6. Seeder maestro de estructura anual
**Subtareas**
- Sembrar catálogos y cierres de un año completo.
- Definir convención de códigos (`CAT-2026-1`, `CAMP-2026-01`, etc.).
- Integrar siembra con seeders oficiales (`AlmamiaSeeder`, `ProductosSeeder`).

### Paso 7. Motor de premios revendedora: 3 pedidos consecutivos
**Subtareas**
- Crear tabla de seguimiento por revendedora y cierre.
- Implementar lógica de racha por catálogo (3 cierres).
- Registrar premio entregado en primer cierre del catálogo siguiente.

### Paso 8. Motor de puntos: continuidad y ventas
**Subtareas**
- Crear cartera de puntos por revendedora (`ganados`, `canjeados`, `vencimiento`).
- Parametrizar escalas por unidades (10/15/20 y valores asociados).
- Generar historial de movimientos auditable.

### Paso 9. Tienda de premios revendedora
**Subtareas**
- Definir catálogo de premios canjeables y stock lógico.
- Implementar flujo de canje y aprobación.
- Impactar saldo de puntos y trazabilidad de entrega.

### Paso 10. Premio líder por actividad
**Subtareas**
- Validar rangos y montos oficiales.
- Tomar actividad del cierre actual y asignar rango.
- Registrar cálculo y evidencia en tabla de liquidación.

### Paso 11. Premio líder por retención
**Subtareas**
- Comparar actividad cierre actual vs cierre anterior.
- Aplicar regla “RUBÍ en adelante”.
- Registrar motivo de cumplimiento/no cumplimiento.

### Paso 12. Premio líder por altas
**Subtareas**
- Calcular altas del cierre.
- Modelar esquema de 3 cuotas consecutivas.
- Parametrizar regla especial de +3 altas (monto diferencial).

### Paso 13. Premio líder por cobranza
**Subtareas**
- Definir criterio exacto de “en tiempo y forma”.
- Vincular con `estado_pago` de pedidos y/o liquidación de equipo.
- Liquidar monto por rango solo si cumple condición.

### Paso 14. Premio líder por crecimiento
**Subtareas**
- Detectar cambio de rango entre cierres.
- Aplicar pago único por salto de nivel.
- Evitar doble pago por mismo salto.

### Paso 15. Premio líder por reparto
**Subtareas**
- Calcular actividad × monto por rango.
- Guardar resultado mínimo/máximo esperado para auditoría.
- Integrar al total a cobrar del cierre siguiente.

### Paso 16. Plus de crecimiento y premio por unidades
**Subtareas**
- Implementar regla de objetivo próximo cierre (`actividad + 3`).
- Definir tabla de porcentajes del plus por rango.
- Aplicar premio por unidades mínimas por rango.

### Paso 17. Módulo financiero integral (diferido y balance)
**Subtareas**
- Consolidar saldo a pagar, a cobrar, deuda y balance por cierre.
- Implementar descuentos a cierres futuros.
- Exponer libro mayor por líder/coordinadora.

### Paso 18. Reportería y vistas operativas
**Subtareas**
- Vista líderes avanzada (comparativas entre cierres, ranking, alertas).
- Vista líder individual (timeline de premios, actividad, deuda, pagos).
- Filtros por zonas/departamentos y exportables.

### Paso 19. QA, pruebas y validación de negocio
**Subtareas**
- Crear/ajustar pruebas de cálculo crítico en `tests/`.
- Preparar datasets representativos (casos borde y reales).
- Validar con casos manuales aprobados por negocio.

### Paso 20. Despliegue gradual y adopción
**Subtareas**
- Activar por etapas (piloto, monitoreo, despliegue total).
- Capacitación operativa para administración y liderazgo.
- Definir métricas de éxito y mesa de soporte post-salida.

---

## 4) Calendario de trabajo (1 mes)

> Se incluyen ambos escenarios solicitados: inicio **11/03** y alternativa **05/03**.

### Escenario A (inicio mañana 11/03)
- **Semana 1 (11/03 al 17/03)**: pasos 1 al 5.
- **Semana 2 (18/03 al 24/03)**: pasos 6 al 10.
- **Semana 3 (25/03 al 31/03)**: pasos 11 al 16.
- **Semana 4 (01/04 al 07/04)**: pasos 17 al 20.

### Escenario B (inicio 05/03)
- **Semana 1 (05/03 al 11/03)**: pasos 1 al 5.
- **Semana 2 (12/03 al 18/03)**: pasos 6 al 10.
- **Semana 3 (19/03 al 25/03)**: pasos 11 al 16.
- **Semana 4 (26/03 al 01/04)**: pasos 17 al 20.

## 5) Entregables mínimos por semana
- **Semana 1**: diseño funcional aprobado + migraciones base listas.
- **Semana 2**: premios revendedora operativos + actividad líder.
- **Semana 3**: premios líderes completos + integración financiera parcial.
- **Semana 4**: reportería, pruebas, documentación final y checklist de salida.
