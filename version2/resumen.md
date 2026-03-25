# Resumen estratégico V2

## Visión
Consolidar una plataforma comercial escalable para **Alma Mia Fragancias**, capaz de soportar crecimiento de red, reglas de negocio cambiantes por campaña y trazabilidad punta a punta (pedido → cierre → liquidación → premios).

## Objetivo V2
Implementar una arquitectura funcional y de datos que permita operar campañas con menor riesgo operativo, mayor auditabilidad y despliegues incrementales sin detener la operación actual.

## Problemas que resuelve V2
1. **Desalineación parcial entre estructura y evolución de datos**: existen múltiples migraciones evolutivas para dominios críticos (pedidos, métricas, premios), lo que requiere consolidación por contexto para reducir deuda técnica.
2. **Riesgo de deriva en modelos**: el dominio comercial creció en entidades (Pedido, PuntajeRegla, MetricaLiderCampana, CierreCampana, etc.) y necesita estandarizar contratos de relaciones, casts y eventos para mantener consistencia.
3. **Seeders con propósito mixto**: coexisten seeders operativos, de fixtures y de soporte histórico; V2 propone separar explícitamente semilla base, semilla de pruebas y semilla de demo.
4. **Cobertura de pruebas funcionales insuficiente para negocio crítico**: hoy hay cobertura limitada en rutas/home y comprobante de pago; V2 requiere cobertura formal para puntajes, jerarquías, estados de pedido, cierres y bonificaciones.

## Problemas detectados explícitos (base actual)

### Migraciones
- Evolución distribuida y acumulativa de tablas críticas (`pedidos`, `metricas_lider_campana`, `premio_reglas`, hotspots), dificultando lectura histórica y rollback por módulo.
- Convención de nombres heterogénea (crear/agregar/actualizar/simplificar) que complica ubicar rápidamente cambios por bounded context.

### Modelos
- Alta densidad de modelos de negocio con responsabilidades potencialmente cruzadas (catálogo, pedidos, premios, métricas), sin una guía de contratos V2 documentada.
- Necesidad de matriz explícita de `fillable`, `casts` y relaciones obligatorias por entidad para evitar regresiones al ampliar lógica comercial.

### Seeders
- Presencia de archivo atípico `DatabaseSeeder-----php`, indicio de artefacto técnico a depurar en V2.
- Mezcla de seeders de negocio, infraestructura Wave y fixtures de pruebas en un mismo espacio lógico.

### Pruebas
- Suite actual acotada para el dominio: predominan pruebas de rutas/base y una prueba específica de comprobante de pago.
- Falta de pruebas de integración para cálculo de puntos/bonificaciones, jerarquía comercial y cierres de campaña.

## Principios V2
1. **Compatibilidad progresiva**: ningún corte disruptivo en operación comercial.
2. **Trazabilidad total**: cada regla de negocio debe ser auditable y reproducible.
3. **Un único origen de verdad por dominio**: contratos claros entre migraciones, modelos, seeders y pruebas.
4. **Documentación viva**: todo cambio comercial debe nacer con documentación y pruebas.
5. **Separación por contextos**: catálogo, pedidos, campañas, premios y finanzas evolucionan con fronteras claras.

## Estrategia de migración (paralela e incremental)
1. **Fase paralela (shadow mode)**
   - Introducir módulos V2 sin retirar V1.
   - Duplicar cálculo en procesos críticos (puntajes/cierres) y comparar resultados.
2. **Fase incremental por dominio**
   - Migrar primero contextos de bajo riesgo (catálogo/parametría), luego pedidos, después premios y cierres.
   - Activar feature flags por módulo para controlar exposición.
3. **Fase de conmutación controlada**
   - Definir checklist de salida por dominio.
   - Ejecutar ventana de cambio por campaña/cierre para minimizar impacto.
4. **Fase de retiro V1**
   - Eliminar código/dependencias obsoletas sólo tras evidencias de estabilidad.

## Riesgos y mitigaciones
- **Riesgo: divergencia entre cálculos V1 y V2.**  
  **Mitigación:** validación dual automática por campaña y alarmas por desviación.
- **Riesgo: cambios de esquema impactan operación diaria.**  
  **Mitigación:** migraciones backward-compatible y despliegues fuera de ventanas críticas.
- **Riesgo: seeders inconsistentes entre entornos.**  
  **Mitigación:** separar seeders `base`, `testing` y `demo` con contratos de ejecución.
- **Riesgo: baja cobertura de pruebas en lógica crítica.**  
  **Mitigación:** política de PR con pruebas obligatorias por dominio comercial.

## Decisiones arquitectónicas (ADR breve)

### ADR-001: Migración paralela con feature flags
- **Decisión:** coexistencia V1/V2 hasta validar equivalencia funcional.
- **Motivo:** minimizar riesgo operativo en campañas activas.
- **Consecuencia:** mayor complejidad temporal de mantenimiento, compensada por menor probabilidad de caída.

### ADR-002: Contratos de dominio por contexto
- **Decisión:** definir contratos explícitos por contexto (migraciones-modelos-seeders-pruebas).
- **Motivo:** evitar deriva de negocio entre capas técnicas.
- **Consecuencia:** mayor disciplina documental y revisión técnica por PR.

### ADR-003: Seeders separados por finalidad
- **Decisión:** dividir semilla en `base`, `testing`, `demo`.
- **Motivo:** reproducibilidad y consistencia entre ambientes.
- **Consecuencia:** pipeline de CI más claro y menor riesgo de datos incorrectos en producción.

### ADR-004: Cobertura mínima por flujo crítico
- **Decisión:** exigir pruebas de integración para puntajes, pedidos, cierres y bonificaciones antes de habilitar V2 por dominio.
- **Motivo:** proteger reglas comerciales de alto impacto.
- **Consecuencia:** incremento inicial del esfuerzo de QA con fuerte reducción de regresiones.
