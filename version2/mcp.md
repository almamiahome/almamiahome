# MCP V2: gates automáticos por fase

Este esquema MCP define puertas de control para pasar de una fase a otra sin comprometer calidad técnica ni lógica comercial.

## 1) Gates automáticos

### Gate A — Datos y migraciones
**Objetivo:** no permitir avance funcional si la base de datos quedó inconsistente.

**Tareas mapeadas (IDs):**
- 1.3, 1.4, 1.5, 1.6
- 2.2, 2.3
- 3.5
- 4.6
- 5.5

**Rutas reales asociadas:**
- `/version2/categoria-1/tarea-3.md`
- `/version2/categoria-1/tarea-4.md`
- `/version2/categoria-1/tarea-5.md`
- `/version2/categoria-1/tarea-6.md`
- `/version2/categoria-2/tarea-2.md`
- `/version2/categoria-2/tarea-3.md`
- `/version2/categoria-3/tarea-5.md`
- `/version2/categoria-4/tarea-6.md`
- `/version2/categoria-5/tarea-5.md`

**Evidencia mínima por gate:**
1. **Comando:** `php artisan migrate --pretend` y `php artisan migrate:status`.
2. **Archivo:** migración y modelo impactado en `database/migrations/*` y `app/Models/*`.
3. **Resultado:** tablas/columnas esperadas visibles y estado `Yes` en migraciones aplicadas.

**Bloquea avance si:**
- falta migración para cambio estructural,
- hay divergencia entre esquema y modelos,
- falla carga de seeders base.

---

### Gate B — Reglas y pruebas
**Objetivo:** garantizar que la lógica comercial no rompe comportamiento existente.

**Tareas mapeadas (IDs):**
- 1.7, 1.8, 1.9
- 2.1, 2.4, 2.5, 2.6, 2.8, 2.9
- 3.3, 3.4, 3.6, 3.7, 3.8, 3.9
- 4.2, 4.3, 4.4, 4.5, 4.7, 4.8, 4.9
- 5.1, 5.2, 5.3, 5.4, 5.9

**Rutas reales asociadas:**
- `/version2/categoria-1/tarea-7.md` a `/version2/categoria-1/tarea-9.md`
- `/version2/categoria-2/tarea-1.md`, `/version2/categoria-2/tarea-4.md` a `/version2/categoria-2/tarea-6.md`, `/version2/categoria-2/tarea-8.md`, `/version2/categoria-2/tarea-9.md`
- `/version2/categoria-3/tarea-3.md` a `/version2/categoria-3/tarea-4.md`, `/version2/categoria-3/tarea-6.md` a `/version2/categoria-3/tarea-9.md`
- `/version2/categoria-4/tarea-2.md` a `/version2/categoria-4/tarea-5.md`, `/version2/categoria-4/tarea-7.md` a `/version2/categoria-4/tarea-9.md`
- `/version2/categoria-5/tarea-1.md` a `/version2/categoria-5/tarea-4.md`, `/version2/categoria-5/tarea-9.md`

**Evidencia mínima por gate:**
1. **Comando:** `php artisan test` y, cuando aplique, `php artisan test --filter=<caso>`.
2. **Archivo:** pruebas en `tests/` más servicio/regla en `app/`.
3. **Resultado:** suite en verde y evidencia de al menos 1 caso borde por regla crítica.

**Bloquea avance si:**
- no existe cobertura en cambios críticos,
- test suite en rojo,
- regla sin evidencia numérica reproducible.

---

### Gate C — UX/UI minimalista (liquid glass)
**Objetivo:** asegurar “aplicación limpia”, sin ruido visual y con foco operativo.

**Tareas mapeadas (IDs):**
- 5.6, 5.7, 5.8

**Rutas reales asociadas:**
- `/version2/categoria-5/tarea-6.md`
- `/version2/categoria-5/tarea-7.md`
- `/version2/categoria-5/tarea-8.md`

**Evidencia mínima por gate:**
1. **Comando:** `php artisan test --filter=Feature` (o subconjunto UI si existe).
2. **Archivo:** vistas y componentes modificados en `resources/themes/anchor/`.
3. **Resultado:** vista operativa sin duplicidad de acciones y con legibilidad validada.

**Bloquea avance si:**
- hay sobrecarga de tarjetas/widgets,
- existen acciones duplicadas o jerarquía visual confusa,
- no hay consistencia visual entre paneles.

---

### Gate D — Documentación y release
**Objetivo:** no cerrar fase sin trazabilidad funcional y técnica.

**Tareas mapeadas (IDs):**
- 1.1, 1.2, 1.10
- 2.7, 2.10
- 3.1, 3.2, 3.10
- 4.1, 4.10
- 5.10

**Rutas reales asociadas:**
- `/version2/categoria-1/tarea-1.md`, `/version2/categoria-1/tarea-2.md`, `/version2/categoria-1/tarea-10.md`
- `/version2/categoria-2/tarea-7.md`, `/version2/categoria-2/tarea-10.md`
- `/version2/categoria-3/tarea-1.md`, `/version2/categoria-3/tarea-2.md`, `/version2/categoria-3/tarea-10.md`
- `/version2/categoria-4/tarea-1.md`, `/version2/categoria-4/tarea-10.md`
- `/version2/categoria-5/tarea-10.md`

**Evidencia mínima por gate:**
1. **Comando:** `git diff --name-only` y `php artisan test`.
2. **Archivo:** actualización en `/version2/*.md` y, si aplica, README/docs.
3. **Resultado:** alcance, riesgos, rollback y estado de aprobación explícitos.

**Bloquea avance si:**
- cambio de negocio sin documentación,
- release sin checklist,
- no se deja evidencia de impacto por rol.

---

## 2) Matriz cruzada (Agente ↔ Skill ↔ MCP ↔ tareas reales V2)

| Agente | Skill principal | Gate MCP dominante | Tareas reales (IDs y rutas) |
|---|---|---|---|
| Descubrimiento Funcional | Análisis funcional comercial | Gate D | 1.1, 1.2, 3.1 (`/version2/categoria-1/tarea-1.md`, `/version2/categoria-1/tarea-2.md`, `/version2/categoria-3/tarea-1.md`) |
| Datos y Migraciones | Diseño de datos y migraciones seguras | Gate A | 1.3, 1.4, 2.3, 3.5, 4.6, 5.5 |
| Dominio Comercial | Implementación de reglas de cálculo | Gate B | 1.7, 2.4, 2.5, 3.3, 4.5, 5.1 |
| UX/UI Minimalista | UI minimalista estilo liquid glass | Gate C | 5.6, 5.7, 5.8 |
| Calidad y Pruebas | QA automatizado y regresión | Gate B | 1.9, 2.8, 3.8, 4.8, 5.9 |
| Documentación y Release | Documentación operativa y técnica | Gate D | 1.10, 2.10, 3.10, 4.10, 5.10 |

---

## 3) Flujo recomendado de fases con control MCP

1. **Fase Funcional:** Descubrimiento funcional → Gate D preliminar.
2. **Fase Estructural:** Datos y migraciones → Gate A.
3. **Fase Lógica:** Dominio comercial → Gate B parcial.
4. **Fase UI:** UX/UI minimalista → Gate C.
5. **Fase QA:** Calidad y pruebas → Gate B final.
6. **Fase Cierre:** Documentación y release → Gate D final.

Si un gate falla, la fase no avanza y vuelve al agente responsable con observaciones puntuales y evidencia faltante.
