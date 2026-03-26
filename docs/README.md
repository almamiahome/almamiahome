# Documentación interna de Alma Mia Fragancias

Esta carpeta centraliza la documentación funcional y técnica del proyecto.

## Estado de la carpeta

Se mantiene un índice principal (`README.md`) para facilitar acceso a documentos vigentes.

## Documentos disponibles

- `cobros-y-pagos.md`: reglas de negocio, tablas y flujo de liquidación de cobros/pagos por campaña.
- `plan-premios-liderazgo.md`: plan real de rangos, premios y cálculo operativo de liderazgo.
- `roadmap.md`: plan de implementación de `sistema.txt` en 20 pasos con subtareas y calendario mensual.
- `base-de-datos.md`: estructura actual y estructura objetivo de base de datos, con campos nuevos sugeridos.
- `propuesta.md`: propuesta integral de alcance, enfoque y entregables.
- `presupuesto.md`: presupuesto por módulo con foco en beneficios y ahorro de tiempo.
- `revision-reporte.md`: revisión histórica de PRs (desde el inicio) explicadas en términos simples.
- `roadmap-etapas/`: roadmap dividido por etapas, cada una en su archivo `.md`.

## Criterio de uso

- Cualquier cambio de lógica comercial debe reflejarse aquí.
- Mantener siempre redacción en español neutro.

## Notas de versión internas

- 2026-02-28: en pedidos se habilitó la carga de comprobante de pago para casos sin pago y se añadió el estado `pendiente_verificacion_lider` para revisión de la líder.
- 2026-03-10: se incorporan roadmap, propuesta, presupuesto, reporte de PRs y documento de base de datos para implementar `sistema.txt`.

- 2026-03-11: se agregó `db.html` en la raíz del proyecto con mapa conceptual de la base de datos (actual en gris oscuro y versión 2 en naranja), se amplió `revision-reporte.md` con histórico desde PR #15 y se separó el roadmap por etapas en `docs/roadmap-etapas/`.

- 2026-03-26: la tienda de premios de revendedora quedó operativa de extremo a extremo (consulta real, filtros por estado/stock/búsqueda, canje con validaciones y auditoría mínima en `datos`).
