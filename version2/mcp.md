# MCP V2: gates automáticos por fase

Este esquema MCP define puertas de control para pasar de una fase a otra sin comprometer calidad técnica ni lógica comercial.

## 1) Gates automáticos

### Gate A — Datos y migraciones
**Objetivo:** no permitir avance funcional si la base de datos quedó inconsistente.

**Requisitos de aprobación:**
1. Migraciones nuevas aplican y revierten correctamente.
2. Seeders oficiales siguen operativos.
3. Modelos afectados actualizados (fillable, casts, relaciones).

**Bloquea avance si:**
- falta migración para cambio estructural,
- hay divergencia entre esquema y modelos,
- falla carga de seeders base.

---

### Gate B — Reglas y pruebas
**Objetivo:** garantizar que la lógica comercial no rompe comportamiento existente.

**Requisitos de aprobación:**
1. Pruebas críticas nuevas/ajustadas para la regla tocada.
2. Ejecución completa de `php artisan test`.
3. Evidencia de casos borde para cálculos monetarios/puntaje/estados.

**Bloquea avance si:**
- no existe cobertura en cambios críticos,
- test suite en rojo,
- regla sin evidencia numérica reproducible.

---

### Gate C — UX/UI minimalista (liquid glass)
**Objetivo:** asegurar “aplicación limpia”, sin ruido visual y con foco operativo.

**Requisitos de aprobación:**
1. Nuevos elementos son mínimos y reutilizan componentes.
2. Se respeta estética liquid glass con legibilidad clara.
3. Cada vista prioriza tarea principal (UI justa y necesaria).

**Bloquea avance si:**
- hay sobrecarga de tarjetas/widgets,
- existen acciones duplicadas o jerarquía visual confusa,
- no hay consistencia visual entre paneles.

---

### Gate D — Documentación y release
**Objetivo:** no cerrar fase sin trazabilidad funcional y técnica.

**Requisitos de aprobación:**
1. Documentación de negocio actualizada.
2. Impacto en BD/modelos/pruebas registrado.
3. Nota de versión con alcance, riesgos y rollback.

**Bloquea avance si:**
- cambio de negocio sin documentación,
- release sin checklist,
- no se deja evidencia de impacto por rol.

---

## 2) Matriz cruzada (Agente ↔ Skill ↔ MCP ↔ tareas del roadmap)

| Agente | Skill principal | Gate MCP dominante | Tareas roadmap (docs/roadmap.md) |
|---|---|---|---|
| Descubrimiento Funcional | Análisis funcional comercial | Gate D | Pasos 1, 3, 18 |
| Datos y Migraciones | Diseño de datos y migraciones seguras | Gate A | Pasos 2, 4, 6 |
| Dominio Comercial | Implementación de reglas de cálculo | Gate B | Pasos 7 al 17 |
| UX/UI Minimalista | UI minimalista estilo liquid glass | Gate C | Pasos 18 y 20 |
| Calidad y Pruebas | QA automatizado y regresión | Gate B | Paso 19 |
| Documentación y Release | Documentación operativa y técnica | Gate D | Pasos 5, 19, 20 |

---

## 3) Flujo recomendado de fases con control MCP

1. **Fase Funcional:** Agente de Descubrimiento → Gate D preliminar.
2. **Fase Estructural:** Agente de Datos y Migraciones → Gate A.
3. **Fase Lógica:** Agente de Dominio Comercial → Gate B parcial.
4. **Fase UI:** Agente UX/UI Minimalista → Gate C.
5. **Fase QA:** Agente de Calidad → Gate B final.
6. **Fase Cierre:** Agente de Documentación y Release → Gate D final.

Si un gate falla, la fase no avanza y vuelve al agente responsable con observaciones puntuales.

