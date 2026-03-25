# Tarea 2.10 — Documentación financiera y plan de reversión

## Contexto de negocio
Esta tarea pertenece a **Categoría 2: Finanzas diferidas y cobranzas**, alineada al plan de Version 2 definido desde `sistema.txt`.

## Objetivo de la tarea
Documentación financiera y plan de reversión. Con ello se contribuye al objetivo macro de la categoría: **Consolidar saldos, deudas, balance y descuentos proyectados a cierres futuros.**.

## Alcance técnico
- Definir contrato de datos, reglas y validaciones específicas de esta tarea.
- Mantener compatibilidad con operación actual (sin ruptura de flujos vigentes).
- Dejar trazabilidad funcional, técnica y de QA para cierre de fase.

## Subtareas mínimas
1. Levantar estado actual y dependencias directas.
2. Diseñar cambio funcional/técnico con impacto explícito.
3. Implementar en código/migraciones/modelos según corresponda.
4. Añadir pruebas automatizadas del caso feliz y casos borde.
5. Documentar evidencia y criterios de salida.

## Riesgos controlados
- Divergencia entre cálculo esperado y cálculo implementado.
- Inconsistencia de datos por cierres históricos incompletos.
- Falta de evidencia para aprobar gate de fase.

## Criterios de éxito
- Regla implementada y verificable por evidencia reproducible.
- Pruebas relacionadas en verde.
- Documentación en español con impacto funcional claro.

## Checklist de validación
- [ ] Se definió criterio funcional sin ambigüedad para esta tarea.
- [ ] Se registró impacto en datos/modelos (si aplica).
- [ ] Se añadieron o ajustaron pruebas automatizadas.
- [ ] Se ejecutaron comandos de validación y se guardó evidencia.
- [ ] Se documentó plan de rollback o corrección.

## Dependencias cruzadas
- `version2/resumen.md`
- `version2/mcp.md`
- `version2/skills.md`
- `sistema.txt`
