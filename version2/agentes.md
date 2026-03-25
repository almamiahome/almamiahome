# Agentes operativos V2 (Alma Mía)

Este documento define los agentes internos para ejecutar el roadmap V2 sin sobrecargar la interfaz ni la operación.

## Verificación del sistema actual (base para diseño minimalista)

Se confirma que el sistema ya cuenta con módulos operativos de pedidos, catálogo, premios de liderazgo parciales y cobranzas/pagos, y que la V2 busca completar estructura anual, premios avanzados y reportería. Además, existe una propuesta visual con enfoque **liquid glass** para panel modular V2.

**Conclusión de diseño para nuevos elementos:** todo alta de pantallas, bloques o widgets debe ser **minimalista**, con jerarquía clara y solo componentes imprescindibles para la tarea de negocio.

---

## Catálogo de agentes

### 1) Agente de Descubrimiento Funcional
- **Responsabilidad:** traducir requerimientos del negocio Alma Mía en historias, reglas y criterios de aceptación.
- **Input:** notas de negocio, `sistema.txt`, roadmap, feedback operativo.
- **Output:** especificación funcional breve por módulo (alcance, reglas, casos borde, métricas).
- **Trigger de ejecución:**
  - nueva funcionalidad de negocio,
  - cambio en jerarquía Vendedora → Líder → Coordinadora,
  - ajuste en puntos, premios o estados de pedido.

### 2) Agente de Datos y Migraciones
- **Responsabilidad:** diseñar/actualizar estructura de datos y plan de migración reversible.
- **Input:** especificación funcional aprobada + modelo actual de BD.
- **Output:** migraciones, impacto técnico, plan de rollback y checklist de seeders.
- **Trigger de ejecución:**
  - creación/alteración de tablas,
  - nuevos índices o constraints,
  - modificaciones que afecten campañas, pedidos, premios, cobranzas.

### 3) Agente de Dominio Comercial
- **Responsabilidad:** implementar reglas de negocio (puntajes, bonificaciones, actividad, retención, altas, cobranza, crecimiento).
- **Input:** reglas de negocio validadas + contratos de datos.
- **Output:** servicios/cálculos versionados, trazabilidad de fórmulas y evidencias por cierre.
- **Trigger de ejecución:**
  - cambio de fórmula,
  - nuevo premio,
  - ajuste de condiciones por rango o campaña.

### 4) Agente de UX/UI Minimalista (Liquid Glass)
- **Responsabilidad:** diseñar y validar la capa visual con criterio “UI justa y necesaria”.
- **Input:** flujo funcional, prioridades de uso, tokens/estilo visual.
- **Output:** especificación de interfaz mínima, componentes reutilizables y checklist visual.
- **Trigger de ejecución:**
  - nueva pantalla,
  - nuevo widget,
  - rediseño de paneles operativos.

### 5) Agente de Calidad y Pruebas
- **Responsabilidad:** asegurar cobertura automatizada de lógica crítica.
- **Input:** cambios de código, migraciones, reglas comerciales.
- **Output:** pruebas de unidad/feature, reporte de cobertura crítica y evidencia de ejecución.
- **Trigger de ejecución:**
  - cambios en cálculos monetarios o puntaje,
  - cambios de estado de pedido,
  - cambios de migraciones o seeders.

### 6) Agente de Documentación y Release
- **Responsabilidad:** mantener trazabilidad funcional/técnica/documental por cada entrega.
- **Input:** PR, cambios en BD/modelos/pruebas/UI.
- **Output:** docs actualizadas, notas de versión, resumen de impacto por rol.
- **Trigger de ejecución:**
  - todo cambio que afecte negocio,
  - toda salida a QA o producción.

