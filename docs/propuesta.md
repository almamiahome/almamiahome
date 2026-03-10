# Propuesta de implementación integral de `sistema.txt`

## Resumen ejecutivo
Esta propuesta transforma el sistema actual de Alma Mia en una plataforma completa de gestión comercial por campañas, con control de premios para revendedoras y líderes, liquidación financiera diferida y reportería operativa por territorio.

El enfoque prioriza:
- exactitud de cálculo,
- trazabilidad para auditoría,
- facilidad de uso para administración,
- escalabilidad para próximos ciclos comerciales.

## Alcance funcional (módulos)
1. Estructura anual y cierres/campañas.
2. Pagos diferidos y balance por cierre.
3. Filtros y vistas por zonas/departamentos.
4. Tienda de premios revendedora.
5. Premio revendedora por pedidos consecutivos.
6. Premio revendedora por continuidad y ventas.
7. Premio líder por actividad.
8. Premio líder por retención.
9. Premio líder por altas.
10. Premio líder por cobranza.
11. Premio líder por crecimiento.
12. Premio líder por reparto.
13. Premio líder plus de crecimiento.
14. Premio líder por unidades.

## Enfoque de implementación
- Se construye sobre la versión actual, reutilizando tablas y servicios existentes.
- Se evita romper operación actual: despliegue en etapas.
- Cada regla crítica se implementa con pruebas automatizadas y casos de negocio.
- Se documenta cada módulo para que cualquier persona del equipo pueda operarlo.

## Riesgos y mitigación
- **Riesgo**: ambigüedad en reglas comerciales.
  - **Mitigación**: mesa funcional semanal + acta de validación.
- **Riesgo**: diferencias entre cálculo esperado y real.
  - **Mitigación**: fixtures reales + comparación contra planillas de negocio.
- **Riesgo**: sobrecarga operativa al salir en producción.
  - **Mitigación**: salida por piloto + soporte en ventana de estabilización.

## Entregables
- Migraciones y modelos actualizados.
- Servicios de cálculo y liquidación.
- Interfaces de administración y consulta.
- Documentación funcional/técnica actualizada en `docs/`.
- Reporte de pruebas y checklist de liberación.
