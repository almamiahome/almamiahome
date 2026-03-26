# Etapa 5 — Líderes avanzados y reportería operativa

> Actualizado: **2026-03-26**.

## Objetivo

Activar la capa final de gestión para liderazgo, manteniendo la arquitectura clásica de la aplicación (Folio + Volt + Blade), sin romper módulos existentes.

## Verificación de entrada (dependiente de Etapa 4)

- T17 ✅ cumplido.
- T18 ✅ cumplido.
- T19 ❌ pendiente por entorno (bloqueo en instalación/ejecución completa de pruebas).
- T20 ❌ pendiente por dependencia directa de T19.

**Decisión:** se habilita inicio técnico de Etapa 5 en modalidad controlada (sin declarar salida productiva).

## Entregables iniciales de Etapa 5

1. Páginas base Folio/Volt para reportería avanzada:
   - `/lideres/avanzado`
   - `/lideres/individual`
   - `/lideres/filtros`
2. Middleware declarado en cada página Blade:
   - `middleware('auth')`
3. Checklist operativo consolidado:
   - `version2/categoria-5/checklist-etapa-5.md`

## Pendientes obligatorios para cierre

1. Implementar reglas de negocio 5.1–5.5 con evidencia numérica por cierre.
2. Conectar filtros zona/departamento a datos reales.
3. Completar pruebas Unit/Feature específicas de categoría 5.
4. Desbloquear entorno para ejecutar `composer install` y `php artisan test` sin errores de plataforma.
5. Emitir acta de cierre de Etapa 5 con estado binario (APROBADO / NO APROBADO).
