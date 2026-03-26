# Checklist ejecutable — Etapa 5 (Versión 2)

## Objetivo operativo
Convertir las planillas actuales de liderazgo en un flujo digital completo, intuitivo y auditable, sin romper la arquitectura clásica del proyecto (Folio/Volt + estructura por carpetas URL).

## Estado base verificado

- Etapa 4 T17/T18: ✅ cumplidas.
- Etapa 4 T19/T20: ⚠️ pendientes por entorno de pruebas integral.
- Etapa 5: ✅ iniciada con páginas base operativas en `lideres/`.

## Checklist por frente

### A) Reglas de negocio (5.1 a 5.5)
- [ ] Implementar servicio único para crecimiento, reparto, plus y unidades.
- [ ] Asegurar idempotencia por `(lider_id, cierre_campana_id, version_regla)`.
- [ ] Registrar traza de cálculo en `datos` para auditoría funcional.
- [ ] Consolidar `total_a_cobrar` con desglose de componentes.
- [ ] Cubrir bordes: salto múltiple, objetivo no cumplido, umbral exacto de unidades.

### B) Vistas operativas (5.6 a 5.8)
- [ ] Mantener carpetas en `resources/themes/anchor/pages/lideres/...`.
- [ ] Declarar middleware en cada Blade Volt (`auth` + permiso operativo).
- [ ] Completar filtros por zona/departamento/catálogo/cierre.
- [ ] Adaptar visual de tabla al formato de planilla actual (cierres por catálogo, unidades y auxiliares).
- [ ] Exponer liquidación individual con total final y componentes.

### C) Validación integral (5.9)
- [ ] Ejecutar `php artisan test` completo en entorno sin bloqueo de memoria.
- [ ] Añadir evidencia de pruebas de integración para paneles de Etapa 5.
- [ ] Verificar consistencia entre totales de panel y totales de motor.

### D) Cierre documental (5.10)
- [ ] Actualizar `docs/roadmap-etapas/matriz-trazabilidad-v2.md` con estado final.
- [ ] Actualizar acta técnica de validación con hash del commit final.
- [ ] Registrar guía operativa para líderes y coordinadoras con ejemplos reales.

## Páginas Folio/Volt creadas en el inicio de Etapa 5

- `resources/themes/anchor/pages/lideres/seguimiento-cierres/index.blade.php`
- `resources/themes/anchor/pages/lideres/liquidacion/index.blade.php`

## Criterio de “Etapa 5 Cumplida”

Solo se marca como cumplida cuando:

1. Reglas 5.1–5.5 validadas con pruebas,
2. Vistas 5.6–5.8 operativas con middleware correcto,
3. QA integral 5.9 en verde,
4. Documentación 5.10 cerrada con evidencia.
