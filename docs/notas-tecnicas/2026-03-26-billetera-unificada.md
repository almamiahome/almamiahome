# Nota técnica — 26/03/2026 — Billetera unificada y ledger auditable

## Resumen

Se incorpora una página de billetera dedicada y se centraliza el cálculo de indicadores en `BilleteraService`.

## Cambios técnicos

- Migración nueva `create_billetera_movimientos_table`.
- Modelo `BilleteraMovimiento`.
- Servicio `BilleteraService` con consolidación de saldos y movimientos.
- Integración de sincronización con:
  - `PremiosRevendedoraService`,
  - `PremiosLiderCalculator`,
  - `LiquidacionCierreService`.
- Vista nueva: `resources/themes/anchor/pages/billetera/index.blade.php`.

## Alcance funcional

El módulo entrega historial de créditos/débitos y KPIs solicitados para seguimiento operativo de premios y liquidaciones.
