# Version2 - Roadmap de implementación

Este roadmap organiza la implementación de los módulos solicitados para Alma Mia con foco en:

- estructura anual de catálogo/cierres,
- pagos diferidos y balance,
- premios de revendedoras,
- premios de líderes,
- filtros operativos por zona/departamento.

---

## 1. Objetivo de Version2

Construir una versión operativa y auditable que permita:

- medir resultados por cierre/campaña,
- calcular premios de forma consistente,
- proyectar y controlar pagos del cierre siguiente,
- visualizar desempeño por jerarquía comercial (Revendedora → Líder → Coordinadora).

---

## 2. Alcance funcional por módulos

## 2.1 Núcleo temporal

### Estructura anual y cierres/campañas
- Reglas del calendario:
  - Catálogo 1: Ene-Feb-Mar
  - Catálogo 2: Abr-May-Jun
  - Catálogo 3: Jul-Ago-Sep
  - Catálogo 4: Oct-Nov-Dic
- Cada catálogo contiene 3 cierres obligatorios.
- El sistema debe validar continuidad y correlatividad de cierres.

### Estructura de pagos diferidos
- Saldo a pagar y saldo a cobrar por cierre.
- Balance neto por líder/coordinadora.
- Registro de deudas y descuentos aplicados a cierres futuros.
- Trazabilidad completa del movimiento origen → aplicación.

### Filtro operativo
- Segmentación por zona y departamento.
- Vista líderes avanzada (ranking, alertas, evolución).
- Vista líder individual (detalle de actividad, cobranza, altas, premios y saldos).

## 2.2 Módulos de revendedoras

- Tienda de premios con canje por puntos acumulados.
- Premio por 3 pedidos consecutivos dentro del catálogo.
- Premio continuidad y ventas por puntos:
  - 10 U = 100 puntos
  - 15 U = 150 puntos
  - 20 U = 250 puntos
- Envío de premio en primer cierre del catálogo siguiente.

## 2.3 Módulos de líderes

- Premio actividad por rango.
- Premio retención (desde Rubí): compara cierre actual vs cierre anterior.
- Premio altas por 3 cierres consecutivos.
- Premio cobranza por pago en tiempo y forma.
- Premio crecimiento (pago único por ascenso de nivel).
- Premio reparto (monto por actividad según rango).
- Plus de crecimiento (sobre total a cobrar por porcentaje y cumplimiento de objetivo).
- Premio por unidades mínimas por rango.

---

## 3. Dependencias de implementación

1. **Migraciones y modelo de datos** (base obligatoria).
2. **Servicios de cálculo de premios** (reglas centralizadas).
3. **Procesos de cierre** (ejecución mensual).
4. **Panel de seguimiento y reportes** (operación).
5. **Automatización de liquidaciones** (pagos/cobros diferidos).

---

## 4. Entregables esperados

- Migraciones V2 ejecutables en entorno limpio.
- Seeders base para rangos, reglas y calendario anual.
- Servicios de cálculo con pruebas automatizadas.
- Panel administrativo con vistas por jerarquía.
- Documentación funcional + técnica actualizada.

---

## 5. Plan mensual en 15 tareas

1. Diseñar y validar modelo de datos V2 (tablas nuevas + ajustes).
2. Crear migraciones de calendario catálogo/cierres.
3. Crear migraciones de objetivos por líder/cierre.
4. Crear migraciones de actividad de equipo por cierre.
5. Crear migraciones de premios de líderes por cierre.
6. Crear migraciones de premios de revendedoras por cierre y puntos.
7. Crear migraciones de saldos diferidos por pedido/cierre.
8. Actualizar modelos Eloquent y relaciones (`app/Models`).
9. Implementar seeders oficiales con datos base de reglas y rangos.
10. Implementar servicio de cálculo de premios de revendedoras.
11. Implementar servicio de cálculo de premios de líderes.
12. Implementar lógica de retención, crecimiento y plus de crecimiento.
13. Construir vistas de seguimiento (zonas, departamentos, líder individual).
14. Añadir pruebas automatizadas críticas (`php artisan test`) para cálculos y cierres.
15. Actualizar documentación final (README + docs de módulos + notas internas).

---

## 6. Criterios de aceptación

- Cada cierre tiene resultados reproducibles para los mismos datos de entrada.
- No hay premios duplicados por misma líder/revendedora/cierre.
- Los saldos diferidos son trazables y conciliables con el balance.
- Los reportes coinciden con reglas de negocio documentadas.
- Todas las pruebas críticas pasan antes de aprobar PR.
