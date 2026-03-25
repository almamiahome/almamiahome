# Skills V2 por categoría (Alma Mía)

Las skills definen capacidades reutilizables para ejecutar trabajo repetible y auditable.

## Skill 1: Análisis funcional comercial
- **Objetivo:** convertir requerimientos en historias ejecutables con reglas claras.
- **Entradas:** requerimiento, rol impactado, catálogo/cierre, métricas objetivo.
- **Salidas:** historia priorizada, criterios de aceptación, riesgos.
- **Validaciones:**
  - no ambigüedades en términos,
  - regla con ejemplo numérico,
  - impacto en BD/modelos/documentación identificado.
- **Reutilización por categoría:**
  - Premios y puntos,
  - Jerarquías comerciales,
  - Estados de pedido.

## Skill 2: Diseño de datos y migraciones seguras
- **Objetivo:** generar cambios estructurales con rollback y mínima ruptura.
- **Entradas:** entidad nueva/campo, volumen estimado, consultas críticas.
- **Salidas:** diseño de tabla/campos/índices, plan de migración y reversión.
- **Validaciones:**
  - consistencia de llaves foráneas,
  - índices para filtros de campaña/cierre,
  - compatibilidad con seeders oficiales.
- **Reutilización por categoría:**
  - Catálogos y cierres,
  - Pedidos/cobranzas,
  - Métricas y liquidaciones.

## Skill 3: Implementación de reglas de cálculo
- **Objetivo:** codificar reglas comerciales en servicios claros y testeables.
- **Entradas:** fórmula oficial, datos de entrada, condiciones de excepción.
- **Salidas:** servicio de cálculo, resultado auditable, motivos de cumplimiento/no cumplimiento.
- **Validaciones:**
  - pruebas de caso feliz y casos borde,
  - trazabilidad por cierre,
  - idempotencia en recálculos.
- **Reutilización por categoría:**
  - Puntos,
  - Bonificaciones,
  - Premios de líder y revendedora.

## Skill 4: UI minimalista estilo liquid glass
- **Objetivo:** construir pantallas sobrias, legibles y enfocadas en tarea.
- **Entradas:** flujo principal, acciones clave, datos mínimos requeridos.
- **Salidas:** layout mínimo, componentes reutilizables, microcopys claros.
- **Validaciones:**
  - máximo 1 acción primaria por bloque,
  - densidad visual baja (sin widgets redundantes),
  - contraste y legibilidad en claro/oscuro.
- **Reutilización por categoría:**
  - Paneles,
  - Reportes,
  - Modales de detalle.

## Skill 5: QA automatizado y regresión
- **Objetivo:** prevenir regresiones en lógica crítica de negocio.
- **Entradas:** cambios de código, datos de prueba, criterios de aceptación.
- **Salidas:** suite de pruebas, reporte de ejecución, matriz de cobertura crítica.
- **Validaciones:**
  - `php artisan test` en verde,
  - cobertura sobre cálculos críticos,
  - pruebas de transición de estados.
- **Reutilización por categoría:**
  - Finanzas,
  - Pedidos,
  - Premios y jerarquías.

## Skill 6: Documentación operativa y técnica
- **Objetivo:** alinear implementación con documentación viva.
- **Entradas:** diff de código, cambios de negocio, resultados QA.
- **Salidas:** actualización de README/docs/notas de versión.
- **Validaciones:**
  - cambios de negocio documentados,
  - ejemplo práctico por regla nueva,
  - impacto en BD y modelos explícito.
- **Reutilización por categoría:**
  - Entregas semanales,
  - Cambios de alcance,
  - Cierres de fase.

