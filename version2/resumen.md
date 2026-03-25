# Resumen estratégico V2

## Propósito general
Alinear la operación de **Alma Mía Fragancias** con el modelo comercial real del negocio (catálogos, cierres, jerarquías y premios), asegurando trazabilidad entre datos, lógica, interfaz y documentación.

## Tabla única de planificación V2

> Convención de esfuerzo: **Bajo (B)**, **Medio (M)**, **Alto (A)**.  
> Convención de paralelo: **Sí** = puede ejecutarse en lote con otras tareas del mismo sprint; **No** = bloquea o depende de un hito previo.

| Tarea | Impacto | Esfuerzo | Dependencia principal | ¿Paralelo? |
|---|---|---|---|---|
| 1.1 | Alto (descubrimiento de reglas base) | M | Ninguna | No |
| 1.2 | Alto (estados operativos de pedido) | M | 1.1 | No |
| 1.3 | Alto (diseño dominio campaña/cierre) | A | 1.2 | No |
| 1.4 | Alto (migraciones base) | A | 1.3 | No |
| 1.5 | Alto (modelos núcleo) | M | 1.4 | No |
| 1.6 | Medio (datos semilla base) | M | 1.4 | Sí |
| 1.7 | Alto (cálculo objetivo próximo cierre) | A | 1.5, 1.6 | No |
| 1.8 | Medio (trazabilidad auxiliares) | M | 1.7 | Sí |
| 1.9 | Alto (integración pedidos/cierre) | A | 1.7, 1.8 | No |
| 1.10 | Alto (documentación y rollback base) | B | 1.9 | No |
| 2.1 | Alto (saldo a pagar/cobrar) | A | 1.10 | No |
| 2.2 | Alto (deuda acumulada) | A | 2.1 | No |
| 2.3 | Alto (ledger financiero) | A | 2.2 | No |
| 2.4 | Alto (balance y corte) | A | 2.3 | No |
| 2.5 | Alto (descuentos futuros) | A | 2.4 | No |
| 2.6 | Medio (estados cobranza) | M | 2.4 | Sí |
| 2.7 | Medio (reportes deuda) | M | 2.5, 2.6 | Sí |
| 2.8 | Alto (pruebas mora/recuperación) | M | 2.5, 2.6 | Sí |
| 2.9 | Alto (integración finanzas con pedidos) | A | 2.7, 2.8 | No |
| 2.10 | Alto (documentación financiera) | B | 2.9 | No |
| 3.1 | Medio (contrato tienda premios) | M | 2.10 | No |
| 3.2 | Medio (catálogo canje) | M | 3.1 | Sí |
| 3.3 | Alto (3 pedidos consecutivos) | A | 3.1 | No |
| 3.4 | Alto (continuidad y ventas) | A | 3.3 | No |
| 3.5 | Alto (persistencia de puntos) | A | 3.2, 3.4 | No |
| 3.6 | Medio (vigencia por catálogo) | M | 3.5 | Sí |
| 3.7 | Alto (emisión de premio) | M | 3.5, 3.6 | No |
| 3.8 | Alto (casos borde continuidad) | M | 3.6, 3.7 | Sí |
| 3.9 | Alto (canje y bloqueo saldo) | M | 3.5, 3.7 | Sí |
| 3.10 | Alto (documentación revendedoras) | B | 3.8, 3.9 | No |
| 4.1 | Medio (rangos y montos actividad) | M | 3.10 | No |
| 4.2 | Alto (retención) | A | 4.1 | No |
| 4.3 | Alto (altas consecutivas) | A | 4.1 | Sí |
| 4.4 | Alto (cobranza en tiempo/forma) | A | 4.2 | Sí |
| 4.5 | Alto (elegibilidad por rango) | A | 4.2, 4.3 | No |
| 4.6 | Alto (persistencia métricas líderes) | A | 4.5 | No |
| 4.7 | Alto (cálculo monetario líder) | A | 4.6 | No |
| 4.8 | Alto (regresión por rango) | M | 4.6, 4.7 | Sí |
| 4.9 | Alto (integración historial cierres) | A | 4.7, 4.8 | No |
| 4.10 | Alto (documentación líderes) | B | 4.9 | No |
| 5.1 | Alto (crecimiento por nivel) | A | 4.10 | No |
| 5.2 | Alto (reparto por nivel) | A | 5.1 | Sí |
| 5.3 | Alto (plus de crecimiento) | A | 5.1 | Sí |
| 5.4 | Alto (premio por unidades) | A | 5.1 | Sí |
| 5.5 | Alto (consolidado total a cobrar) | A | 5.2, 5.3, 5.4 | No |
| 5.6 | Medio (filtros zona/departamento) | M | 5.5 | Sí |
| 5.7 | Alto (vista líder avanzada) | M | 5.5, 5.6 | Sí |
| 5.8 | Alto (vista líder individual) | M | 5.5, 5.6 | Sí |
| 5.9 | Alto (E2E reportería y premios) | A | 5.7, 5.8 | No |
| 5.10 | Alto (documentación final y handoff) | B | 5.9 | No |

## Tareas desbloqueadoras (secuencia obligatoria)

### 1) DB primero (estructura y persistencia)
**IDs desbloqueadores:** 1.3, 1.4, 1.5, 1.6, 2.3, 3.5, 4.6, 5.5.  
Sin estas tareas, no se habilita la capa de dominio ni pruebas integrales.

### 2) Dominio después (reglas de negocio)
**IDs desbloqueadores:** 1.7, 2.1, 2.4, 2.5, 3.3, 3.4, 4.2, 4.5, 4.7, 5.1, 5.2, 5.3, 5.4.

### 3) UI al final (vistas y filtros)
**IDs finales:** 5.6, 5.7, 5.8, 5.9, 5.10.  
La UI no entra a desarrollo final sin datos estables y dominio validado.

## Ruta crítica V2

**Ruta crítica propuesta (sin holgura):**  
1.1 → 1.2 → 1.3 → 1.4 → 1.5 → 1.7 → 1.9 → 1.10 → 2.1 → 2.2 → 2.3 → 2.4 → 2.5 → 2.9 → 2.10 → 3.1 → 3.3 → 3.4 → 3.5 → 3.7 → 3.10 → 4.1 → 4.2 → 4.5 → 4.6 → 4.7 → 4.9 → 4.10 → 5.1 → 5.5 → 5.6 → 5.7 → 5.9 → 5.10.

## Lotes paralelos por sprint

### Sprint 1 (Base operativa)
- **Secuencial:** 1.1, 1.2, 1.3, 1.4, 1.5.
- **Paralelo controlado:** 1.6 con cierre en 1.7.
- **Cierre sprint:** 1.8, 1.9, 1.10.

### Sprint 2 (Finanzas diferidas)
- **Secuencial:** 2.1, 2.2, 2.3, 2.4, 2.5.
- **Paralelo:** 2.6 + 2.7 + 2.8.
- **Cierre sprint:** 2.9, 2.10.

### Sprint 3 (Revendedoras)
- **Secuencial:** 3.1, 3.3, 3.4, 3.5.
- **Paralelo:** 3.2 con 3.3; luego 3.6 + 3.8 + 3.9.
- **Cierre sprint:** 3.7, 3.10.

### Sprint 4 (Líderes base)
- **Secuencial:** 4.1, 4.2, 4.5, 4.6, 4.7.
- **Paralelo:** 4.3 + 4.4; luego 4.8.
- **Cierre sprint:** 4.9, 4.10.

### Sprint 5 (Líderes avanzados + UI + release)
- **Secuencial:** 5.1, 5.5, 5.9, 5.10.
- **Paralelo:** 5.2 + 5.3 + 5.4, luego 5.6 + 5.7 + 5.8.
- **Cierre sprint:** validación integral y handoff.
