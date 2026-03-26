# Checklist operativo — Inicio de Etapa 5 (Versión 2)

> Fecha de actualización: **2026-03-26**.

## 1) Verificación de cierre de Etapa 4 (gate de entrada)

### Resultado de validación
- **T17 (finanzas diferidas):** ✅ Cumplido con cobertura automatizada.
- **T18 (reportería base):** ✅ Cumplido con cobertura automatizada.
- **T19 (QA integral):** ❌ No cumplido por bloqueo de entorno (`composer install` no completa por restricciones de plataforma PHP/extensiones).
- **T20 (salida operativa):** ❌ No cumplido porque depende de T19.

### Dictamen
La **Etapa 4 no está cerrada al 100%** para salida productiva, pero sí deja habilitado el trabajo de diseño y construcción de la **Etapa 5** en arquitectura clásica (Folio + Volt) sin romper operación actual.

---

## 2) Objetivo de Etapa 5 (alcance real)

Implementar y dejar listas para operación las capacidades avanzadas de liderazgo y reportería:

1. crecimiento,
2. reparto,
3. plus por crecimiento,
4. premio por unidades,
5. consolidado total a cobrar,
6. filtros por zona/departamento,
7. vista líder avanzada,
8. vista líder individual.

---

## 3) Estado actual de páginas (Folio/Volt)

Se habilitan páginas base para Etapa 5 respetando la arquitectura vigente:

- `/lideres/avanzado` → carpeta `resources/themes/anchor/pages/lideres/avanzado/index.blade.php`
- `/lideres/individual` → carpeta `resources/themes/anchor/pages/lideres/individual/index.blade.php`
- `/lideres/filtros` → carpeta `resources/themes/anchor/pages/lideres/filtros/index.blade.php`

Todas se declaran con middleware en la propia página Blade (Folio):

- `middleware('auth')`
- `name('...')`

---

## 4) Checklist de ejecución Etapa 5 (actualizado)

### Gate A — Estructura y navegación
- [x] Se definió estructura de carpetas compatible con Folio.
- [x] Se declararon middleware y nombre de ruta en cada página nueva.
- [x] Se mantuvo arquitectura clásica (sin modificar `routes/`).
- [ ] Se agregaron enlaces de navegación desde menú principal para acceso operativo.

### Gate B — Dominio y cálculo
- [ ] Regla 5.1 (crecimiento por nivel) implementada y documentada con ejemplo numérico.
- [ ] Reglas 5.2/5.3/5.4 (reparto, plus, unidades) implementadas con auditoría por cierre.
- [ ] Regla 5.5 (consolidado total a cobrar) con desglose visible por concepto.
- [ ] Pruebas automáticas específicas para categoría 5 creadas/actualizadas.

### Gate C — Reportería y UX operativa
- [x] Páginas base de reportería avanzada inicializadas.
- [ ] Filtro zona/departamento conectado a datos reales.
- [ ] Vista líder avanzada con KPIs y alertas de desvío.
- [ ] Vista individual con detalle por cierre y trazabilidad de premios.

### Gate D — QA y salida
- [ ] `composer install` exitoso en entorno objetivo (PHP 8.2/8.3/8.4 + extensiones requeridas).
- [ ] `php artisan test` ejecutado completo en verde.
- [ ] Acta de cierre Etapa 5 publicada con evidencia de comandos, pruebas y rollback.

---

## 5) Instrucciones claras para resolver pendientes

1. **Desbloquear entorno técnico (prioridad máxima):**
   - Ejecutar con PHP soportado por `composer.lock` (8.2/8.3/8.4).
   - Habilitar `ext-sodium` en CLI.
   - Reintentar `composer install` y registrar evidencia.

2. **Conectar páginas Etapa 5 a servicios reales:**
   - Definir servicio de agregación por cierre para líderes (KPIs + premios).
   - Exponer DTOs para vista avanzada e individual.
   - Integrar filtros de zona/departamento en consultas.

3. **Cerrar cobertura de pruebas:**
   - Unit tests para reglas 5.1–5.5.
   - Feature tests para navegación, filtros y visualización de consolidado.
   - Regresión cruzada con cierres históricos.

4. **Actualizar documentación de negocio:**
   - Registrar reglas finales en `README.md` y en documentos de `version2/categoria-5/`.
   - Incluir ejemplo práctico por cada premio avanzado.

5. **No declarar “Etapa 5 completada” hasta cumplir gate D.**

---

## 6) Criterio de progreso para siguientes iteraciones

Se considera “Etapa 5 en ejecución controlada” cuando:
- Gate A = 100%,
- Gate B >= 50%,
- Gate C >= 50%,
- y existe plan de desbloqueo activo para Gate D.
