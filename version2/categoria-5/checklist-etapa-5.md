# Checklist ejecutable — Etapa 5 (Versión 2)

## Objetivo operativo
Digitalizar el flujo de liderazgo en arquitectura Folio/Volt (`anchor`) con trazabilidad por cierre y liquidación auditable.

## Estado base verificado

- Etapa 4 T17/T18: ✅ cumplidas.
- Etapa 4 T19: ⚠️ en curso por límite de memoria de entorno.
- Etapa 4 T20: ⛔ bloqueada hasta cierre de T19.

## Evidencias concretas por ítem (archivo/servicio/prueba)

### A) Reglas de negocio (5.1 a 5.5)

- [x] **Motor único con módulos de cálculo**  
  Evidencia: `app/Services/PremiosLiderCalculator.php` (módulos actividad, retención, altas, cobranzas, crecimiento, reparto y plus/unidades).
- [x] **Persistencia auditable del cálculo**  
  Evidencia: `MetricaLiderCampana::updateOrCreate()` con `datos.evidencia.reglas_aplicadas` en `PremiosLiderCalculator`.
- [x] **Consolidado de total a cobrar**  
  Evidencia: campo `premio_total` integrado y almacenado en `metricas_lider_campana`.
- [~] **Idempotencia con versión de regla para Etapa 5**  
  Evidencia parcial: `VERSION_CALCULO = 'v3_modular_etapa_3'`; falta versionado explícito de cierre Etapa 5.
- [~] **Cobertura de bordes Etapa 5 (salto, plus, umbral unidades)**  
  Evidencia parcial: existe cobertura heredada funcional; falta batería integral de pruebas desbloqueada por T19.

### B) Vistas operativas (5.6 a 5.8)

- [x] **Seguimiento de cierres con filtros completos**  
  Evidencia: `resources/themes/anchor/pages/lideres/seguimiento-cierres/index.blade.php` (zona, departamento, catálogo, cierre y líder).
- [x] **Desglose por cierre seleccionado (no agregado global)**  
  Evidencia: consulta `Pedido` filtrada por `catalogo_id` + `cierre_id` + agrupación por `vendedora_id` en la página de seguimiento.
- [x] **KPIs operativos Etapa 5 desde `MetricaLiderCampana`**  
  Evidencia: KPIs actividad, crecimiento, plus, unidades y total en páginas de seguimiento/liquidación.
- [x] **Liquidación auditable por selección explícita**  
  Evidencia: `resources/themes/anchor/pages/lideres/liquidacion/index.blade.php` con filtros `catalogo`, `cierre desde`, `cierre hasta`.
- [x] **Página de entrada operativa Etapa 5**  
  Evidencia: `resources/themes/anchor/pages/lideres/panel-etapa-5/index.blade.php` con enlaces a seguimiento y liquidación.
- [x] **Middleware explícito en páginas `anchor`**  
  Evidencia: `middleware(['auth', closure con can('view_backend')])` en las tres páginas de liderazgo.

### C) Validación integral (5.9)

- [ ] **Suite integral en verde**  
  Evidencia requerida: ejecución exitosa de `php artisan test` sin errores de memoria.
- [~] **Ejecución realizada en entorno actual**  
  Evidencia: comando ejecutado con bloqueo de memoria (registrado en acta técnica de continuidad `2026-03-26`).

### D) Cierre documental (5.10)

- [x] **Matriz de trazabilidad actualizada por tarea**  
  Evidencia: `docs/roadmap-etapas/matriz-trazabilidad-v2.md` con estados `completo/parcial/faltante` y separación heredado/definitivo.
- [x] **Acta técnica de continuidad y fuente única de diagnóstico**  
  Evidencia: `docs/notas-tecnicas/2026-03-26-acta-continuidad-etapa-5.md`.
- [ ] **Acta final con hash validado posterior a T19**  
  Evidencia requerida: acta de cierre final luego de `php artisan test` exitoso.

## Criterio de “Etapa 5 Cumplida”

Solo se marca como cumplida cuando:

1. Reglas 5.1–5.5 estén en `completo` sin dependencia heredada pendiente,
2. Vistas 5.6–5.8 estén operativas y verificadas,
3. QA integral 5.9 esté en verde,
4. Documentación 5.10 cierre con hash final validado.
