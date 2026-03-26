# Matriz maestra CRUD v2

Este documento centraliza el estado funcional y técnico de los recursos críticos de la fase v2 de **Alma Mia Fragancias**.

- **Stack objetivo**: Folio/Volt en `resources/themes/anchor/pages/<recurso>/`.
- **Objetivo**: cerrar brechas de CRUD, permisos por rol y validaciones de negocio.
- **Convención de estado**:
  - `Completo`: listado + crear + editar + permisos + validaciones implementadas.
  - `Parcial`: existe parte del flujo, faltan vistas, validaciones o permisos.
  - `Pendiente`: no implementado.

## Matriz principal

| Recurso | Tabla BD | Modelo | Listado | Crear | Editar | Permisos (rol) | Estado |
|---|---|---|---|---|---|---|---|
| `catalogos` | `catalogos` | `App\\Models\\Catalogo` | `resources/themes/anchor/pages/catalogos/index.blade.php` | `resources/themes/anchor/pages/catalogos/create.blade.php` | `resources/themes/anchor/pages/catalogos/edit.blade.php` | `admin`, `coordinadora` | Parcial |
| `cierres_campana` | `cierres_campana` | `App\\Models\\CierreCampana` | `resources/themes/anchor/pages/cierres_campana/index.blade.php` | `resources/themes/anchor/pages/cierres_campana/create.blade.php` | `resources/themes/anchor/pages/cierres_campana/edit.blade.php` | `admin`, `coordinadora` | Pendiente |
| `puntaje_reglas` | `puntaje_reglas` | `App\\Models\\PuntajeRegla` | `resources/themes/anchor/pages/puntaje_reglas/index.blade.php` | `resources/themes/anchor/pages/puntaje_reglas/create.blade.php` | `resources/themes/anchor/pages/puntaje_reglas/edit.blade.php` | `admin` | Pendiente |
| `revendedora_rachas` | `revendedora_rachas` | `App\\Models\\RevendedoraRacha` | `resources/themes/anchor/pages/revendedora_rachas/index.blade.php` | `resources/themes/anchor/pages/revendedora_rachas/create.blade.php` | `resources/themes/anchor/pages/revendedora_rachas/edit.blade.php` | `admin`, `coordinadora`, `lider` | Pendiente |
| `revendedora_puntos` | `revendedora_puntos` | `App\\Models\\RevendedoraPunto` | `resources/themes/anchor/pages/revendedora_puntos/index.blade.php` | `resources/themes/anchor/pages/revendedora_puntos/create.blade.php` | `resources/themes/anchor/pages/revendedora_puntos/edit.blade.php` | `admin`, `coordinadora`, `lider`, `vendedora` (solo lectura propia) | Parcial |
| `tienda_premios` | `tienda_premios` | `App\\Models\\TiendaPremio` | `resources/themes/anchor/pages/tienda_premios/index.blade.php` | `resources/themes/anchor/pages/tienda_premios/create.blade.php` | `resources/themes/anchor/pages/tienda_premios/edit.blade.php` | `admin`, `coordinadora` | Pendiente |
| `canjes_premios` | `canjes_premios` | `App\\Models\\CanjePremio` | `resources/themes/anchor/pages/canjes_premios/index.blade.php` | `resources/themes/anchor/pages/canjes_premios/create.blade.php` | `resources/themes/anchor/pages/canjes_premios/edit.blade.php` | `admin`, `coordinadora`, `lider`, `vendedora` (crear propio) | Pendiente |
| `rangos_lideres` | `rangos_lideres` | `App\\Models\\RangoLider` | `resources/themes/anchor/pages/rangos_lideres/index.blade.php` | `resources/themes/anchor/pages/rangos_lideres/create.blade.php` | `resources/themes/anchor/pages/rangos_lideres/edit.blade.php` | `admin` | Pendiente |
| `premio_reglas` | `premio_reglas` | `App\\Models\\PremioRegla` | `resources/themes/anchor/pages/premio_reglas/index.blade.php` | `resources/themes/anchor/pages/premio_reglas/create.blade.php` | `resources/themes/anchor/pages/premio_reglas/edit.blade.php` | `admin`, `coordinadora` | Pendiente |
| `metricas_lider_campana` | `metricas_lider_campana` | `App\\Models\\MetricaLiderCampana` | `resources/themes/anchor/pages/metricas_lider_campana/index.blade.php` | `resources/themes/anchor/pages/metricas_lider_campana/create.blade.php` | `resources/themes/anchor/pages/metricas_lider_campana/edit.blade.php` | `admin`, `coordinadora`, `lider` (lectura propia) | Pendiente |
| `liquidaciones_cierre` | `liquidaciones_cierre` | `App\\Models\\LiquidacionCierre` | `resources/themes/anchor/pages/liquidaciones_cierre/index.blade.php` | `resources/themes/anchor/pages/liquidaciones_cierre/create.blade.php` | `resources/themes/anchor/pages/liquidaciones_cierre/edit.blade.php` | `admin`, `coordinadora` | Pendiente |
| `descuentos_futuros` | `descuentos_futuros` | `App\\Models\\DescuentoFuturo` | `resources/themes/anchor/pages/descuentos_futuros/index.blade.php` | `resources/themes/anchor/pages/descuentos_futuros/create.blade.php` | `resources/themes/anchor/pages/descuentos_futuros/edit.blade.php` | `admin`, `coordinadora` | Pendiente |
| `zonas` | `zonas` | `App\\Models\\Zona` | `resources/themes/anchor/pages/zonas/index.blade.php` | `resources/themes/anchor/pages/zonas/create.blade.php` | `resources/themes/anchor/pages/zonas/edit.blade.php` | `admin`, `coordinadora` | Parcial |
| `departamentos` | `departamentos` | `App\\Models\\Departamento` | `resources/themes/anchor/pages/departamentos/index.blade.php` | `resources/themes/anchor/pages/departamentos/create.blade.php` | `resources/themes/anchor/pages/departamentos/edit.blade.php` | `admin`, `coordinadora` | Parcial |

## Dueño funcional, dependencias cruzadas y validaciones por formulario

> Todas las vistas de crear/editar deben implementar validación de servidor (Request/Volt actions) y validación de interfaz (mensajes en formulario).

| Recurso | Dueño funcional | Dependencias cruzadas | Validaciones de negocio mínimas |
|---|---|---|---|
| `catalogos` | Coordinación comercial | `puntaje_reglas`, `tienda_premios`, `cierres_campana` | nombre obligatorio; fecha_inicio <= fecha_fin; estado en `borrador/activo/cerrado`; no solapar catálogos activos. |
| `cierres_campana` | Administración + Finanzas comerciales | `catalogos`, `liquidaciones_cierre`, `revendedora_puntos` | `catalogo_id` existente y vigente; fecha_cierre dentro del rango del catálogo; estado en `abierto/en_revision/cerrado`; impedir doble cierre por catálogo. |
| `puntaje_reglas` | Operación comercial | `catalogos`, `revendedora_puntos`, `premio_reglas` | puntos >= 0; vigencia dentro de catálogo; tipo_regla válido; evitar reglas duplicadas por criterio y vigencia. |
| `revendedora_rachas` | Liderazgo comercial | `revendedora_puntos`, `catalogos` | racha_actual >= 0; reinicio solo al cerrar catálogo; una racha activa por revendedora por catálogo. |
| `revendedora_puntos` | Operación comercial + Liderazgo | `puntaje_reglas`, `canjes_premios`, `cierres_campana` | saldo_puntos >= 0; movimientos con signo y motivo válidos; bloquear ajustes manuales en campañas cerradas. |
| `tienda_premios` | Marketing comercial | `premio_reglas`, `canjes_premios`, `catalogos` | stock >= 0; costo_puntos >= 0; vigencia dentro de catálogo; estado en `activo/inactivo/agotado`. |
| `canjes_premios` | Atención comercial | `tienda_premios`, `revendedora_puntos`, `catalogos` | cantidad > 0; stock suficiente; puntos suficientes; no permitir canje fuera de vigencia del premio/catálogo. |
| `rangos_lideres` | Dirección comercial | `metricas_lider_campana`, `premio_reglas` | umbral_min >= 0; umbral_max >= umbral_min; nombre de rango único; no superposición de rangos activos. |
| `premio_reglas` | Dirección comercial + Marketing | `rangos_lideres`, `tienda_premios`, `puntaje_reglas` | condición válida por tipo de premio; metas no negativas; vigencia consistente con catálogo objetivo. |
| `metricas_lider_campana` | Inteligencia comercial | `catalogos`, `rangos_lideres`, `liquidaciones_cierre` | métricas monetarias y de puntos >= 0; líder debe pertenecer a zona/departamento válido; evitar recalcular sobre cierre confirmado sin autorización. |
| `liquidaciones_cierre` | Finanzas | `cierres_campana`, `metricas_lider_campana`, `descuentos_futuros` | total_bruto >= 0; descuentos >= 0; total_neto = bruto - descuentos ± ajustes; una liquidación final por cierre y líder. |
| `descuentos_futuros` | Finanzas + Cobranza | `liquidaciones_cierre`, `catalogos` | monto >= 0; fecha_aplicacion obligatoria; estado en `programado/aplicado/cancelado`; no duplicar concepto en mismo período para misma revendedora/líder. |
| `zonas` | Coordinación territorial | `departamentos`, `metricas_lider_campana` | nombre único por país; estado válido; impedir eliminar zona con departamentos activos asociados. |
| `departamentos` | Coordinación territorial | `zonas`, `metricas_lider_campana`, estructura de usuarias | nombre único por zona; zona_id obligatorio; estado válido; impedir eliminación con líderes/revendedoras activas. |

## Criterio de permisos por página (Folio/Volt)

Implementar guardas por rol al cargar cada página (`index`, `create`, `edit`) y al ejecutar acciones:

- **admin**: acceso total a todos los recursos.
- **coordinadora**: acceso operativo a catálogos, cierres, puntos, premios, zonas/departamentos y reportes de su alcance.
- **lider**: acceso de lectura/control de su equipo en `revendedora_puntos`, `revendedora_rachas`, `metricas_lider_campana`; sin acceso a configuración global.
- **vendedora**: acceso restringido a información propia, especialmente `revendedora_puntos` y `canjes_premios` (crear/consultar propios).

Sugerencia técnica por página:

1. Resolver usuaria autenticada.
2. Validar rol antes de consultar datos.
3. Aplicar scope por territorio/jerarquía.
4. Mostrar respuesta 403 en accesos no permitidos.

## Plantilla mínima esperada por recurso en Folio/Volt

Cada recurso debe tener, como mínimo:

- `resources/themes/anchor/pages/<recurso>/index.blade.php`
- `resources/themes/anchor/pages/<recurso>/create.blade.php`
- `resources/themes/anchor/pages/<recurso>/edit.blade.php`

Si un flujo requiere variantes, se admite equivalente:

- `form.blade.php` compartido + páginas `create` y `edit`.
- componentes Volt para tabla/formulario reutilizable.

## Priorización de implementación recomendada

1. **Alta prioridad**: `catalogos`, `cierres_campana`, `puntaje_reglas`, `revendedora_puntos`, `canjes_premios`.
2. **Media prioridad**: `tienda_premios`, `premio_reglas`, `liquidaciones_cierre`, `descuentos_futuros`.
3. **Base territorial y liderazgo**: `zonas`, `departamentos`, `rangos_lideres`, `metricas_lider_campana`, `revendedora_rachas`.

---

Última actualización: 2026-03-26.
