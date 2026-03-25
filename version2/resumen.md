# Resumen estratégico V2

## Propósito general
Alinear la operación de **Alma Mía Fragancias** con el modelo comercial real del negocio (catálogos, cierres, jerarquías y premios), asegurando trazabilidad entre datos, lógica, interfaz y documentación.

## Alcance funcional de V2 (derivado de `sistema.txt`)
1. Estructura anual por catálogos y cierres/campañas.
2. Estructura de pagos diferidos (saldo a pagar / a cobrar / balance / deudas / descuentos futuros).
3. Filtrado por zonas y departamentos, vista de líderes avanzada e individual.
4. Módulos de revendedoras:
   - Tienda de premios,
   - Premio por pedidos consecutivos,
   - Premio de continuidad y ventas.
5. Módulos de líderes:
   - Actividad,
   - Retención,
   - Altas,
   - Cobranza,
   - Crecimiento,
   - Reparto,
   - Plus de crecimiento,
   - Premio por unidades.

## Etapas oficiales de V2

### Fase 1 — Diagnóstico y contrato funcional
- Consolidar reglas de `sistema.txt` en lenguaje operativo.
- Definir criterios de aceptación por módulo y por rol (Revendedora/Líder/Coordinadora).

### Fase 2 — Modelo de datos y migraciones
- Diseñar tablas, relaciones e índices por campaña/cierre.
- Garantizar migraciones reversibles y compatibles con operación vigente.

### Fase 3 — Implementación base de campañas y pedidos
- Activar estructura anual (4 catálogos x 3 cierres).
- Estandarizar estados de pedidos y base de cálculo por cierre.

### Fase 4 — Finanzas diferidas y cobranzas
- Implementar saldos a pagar/cobrar, balance y deudas.
- Aplicar descuentos a cierres futuros con trazabilidad.

### Fase 5 — Módulos de revendedoras
- Tienda de premios y canje por puntos.
- Pedidos consecutivos y continuidad/ventas por catálogo.

### Fase 6 — Módulos de líderes
- Premios de actividad, retención, altas, cobranza, crecimiento, reparto, plus y unidades.
- Evidencia reproducible por cierre y rango.

### Fase 7 — UX/UI, filtros y reportes
- Paneles minimalistas por tarea operativa.
- Filtros por zona/departamento y vistas avanzada/individual de líderes.

### Fase 8 — Validación final y salida a release
- Verificación integral de datos, lógica, QA y documentación.
- Estado final binario: **APROBADO** o **NO APROBADO**.
- Si algún ítem falla, activar **retorno de fase** con causa y acción correctiva documentada.

## Mapa de carpetas V2
- `version2/categoria-1/`: estructura anual, campañas, pedidos base y trazabilidad inicial.
- `version2/categoria-2/`: finanzas diferidas, balances, descuentos y cobranzas.
- `version2/categoria-3/`: módulos de revendedoras (tienda y premios por continuidad).
- `version2/categoria-4/`: módulos de líderes (actividad, retención, altas, cobranza).
- `version2/categoria-5/`: módulos de líderes avanzados + reportería y filtros.
- `version2/validacion-final.md`: checklist exacta de Fase 8 con evidencia.

## Regla de control de avance
Ninguna etapa puede cerrarse sin:
1. Evidencia de comandos ejecutados,
2. Evidencia de archivos impactados,
3. Resultado explícito (aprobado/no aprobado),
4. Registro de retorno de fase cuando aplique.
