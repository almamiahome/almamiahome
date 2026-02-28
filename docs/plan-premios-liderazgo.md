# Plan de premios y rangos de liderazgo (plan real AlmaMia)

Este documento describe el plan oficial de premios de liderazgo de AlmaMia, los rangos válidos y cómo se registran sus reglas y repartos en base de datos.

## Tablas
- **`rangos_lideres`**: catálogo de rangos reales con sus límites de revendedoras, unidades mínimas y montos fijos de cada premio por rango. Solo almacena `nombre`, `revendedoras_minimas`, `revendedoras_maximas`, `unidades_minimas`, `premio_actividad`, `premio_unidades`, `premio_cobranzas`, `reparto_referencia` y timestamps; no se usan slugs ni metadatos de color.
- **`premio_reglas`**: reglas y condiciones por tipo de premio (actividad, altas, unidades, cobranzas, crecimiento) asociadas a cada rango y opcionalmente a un cierre de campaña.
- **`repartos_compras`**: valores fijos por compra 1C/2C/3C de cada revendedora.
- **`cierres_campana`**: referencia temporal de cada campaña (inicio/fin, estado) para calcular métricas.
- **`metricas_lider_campana`**: fotografía de métricas y premios de cada líder por campaña y rango.

### Campos de `metricas_lider_campana`

Cada registro de métricas conserva los indicadores calculados por campaña y los montos pagados por el plan. Las columnas clave son:

- `actividad_ok`, `altas_ok`, `unidades_ok`, `cobranzas_ok`, `crecimiento_ok`: banderas booleanas que indican si se cumplió cada componente del plan.
- `altas_pagadas_en_cierre`: detalle JSON por cuota y cierre con los pagos de altas.
- `cantidad_1c`, `cantidad_2c`, `cantidad_3c`: cantidades de revendedoras por tipo de compra.
- `monto_reparto_total`: suma de los repartos 1C/2C/3C calculados con los montos fijos del plan.
- `premio_actividad`, `premio_unidades`, `premio_cobranzas`, `premio_altas`, `premio_crecimiento`: montos individuales pagados por cada objetivo.
- `premio_total`: monto acumulado de todos los premios y repartos.
- `fecha_pago_equipo`: fecha en que el equipo canceló el pedido, útil para validar la ventana de 7 días del premio de cobranzas.

## Rangos válidos y montos fijos

| Rango | Revendedoras (mín–máx) | Unidades mínimas | Premio actividad | Premio unidades | Premio cobranzas | Reparto de referencia |
| --- | --- | --- | --- | --- | --- | --- |
| Perla | 5–8 | 50 | 6.000 | 5.000 | 4.000 | 350 |
| Aguamarina | 8–11 | 120 | 12.000 | 10.000 | 7.200 | 450 |
| Zafiro | 12–16 | 150 | 18.000 | 12.000 | 9.000 | 500 |
| Esmeralda | 17–24 | 200 | 25.000 | 16.000 | 12.000 | 550 |
| Rubí | 25–34 | 250 | 35.000 | 20.000 | 15.000 | 600 |
| Diamante | 35–45 | 350 | 45.000 | 25.000 | 20.000 | 800 |
| Diamante Rosa | 46–60 | 550 | 60.000 | 40.000 | 30.000 | 900 |
| Estrella | 61–80 | 700 | 80.000 | 50.000 | 40.000 | 1.000 |
| Ejecutiva | 81–100 | 850 | 100.000 | 65.000 | 50.000 | 1.100 |

> No existen rangos intermedios (Cristal, Plata, Oro, Platino, etc.). Tampoco se usan bonos base ni porcentajes de ponderación.

## Tipos de premio
- **Actividad**: se paga al alcanzar la cantidad de revendedoras activas dentro del rango (R mín–R máx). Monto fijo por rango.
- **Altas**: $2.200 por cada revendedora nueva presentada a partir de 3 altas. Se paga en 3 cierres consecutivos.
- **Unidades**: se habilita cuando el equipo llega al mínimo de unidades del rango. Monto fijo por rango.
- **Cobranzas**: se acredita si el equipo cancela el pedido completo dentro de los 7 días de recibirlo. Monto fijo por rango.
- **Crecimiento**: pago único al pasar de un rango al siguiente. Premio adicional único para Perla al alcanzar su primer objetivo.

### Premios por crecimiento
- Perla → Aguamarina: 25.000
- Aguamarina → Zafiro: 30.000
- Zafiro → Esmeralda: 40.000
- Esmeralda → Rubí: 50.000
- Rubí → Diamante: 60.000
- Diamante → Diamante Rosa: 70.000
- Diamante Rosa → Estrella: 80.000
- Estrella → Ejecutiva: 100.000
- Perla (primer objetivo): 25.000

## Reparto 1C/2C/3C
- **1C**: $500 por revendedora
- **2C**: $700 por revendedora
- **3C**: $1.000 por revendedora

Los montos son fijos y no dependen del rango. Los campos de porcentaje se mantienen solo como metadatos opcionales para la app.

## Precarga de datos
1. Ejecutar migraciones:
   ```bash
   php artisan migrate
   ```
2. Sembrar la configuración base:
   ```bash
   php artisan db:seed --class=AlmamiaSeederPremios
   ```

## Fixture de métrica completa
- Archivo: `database/seeders/fixtures/metrica_lider_campana_ejemplo.json`.
- Propósito: dataset de prueba con todos los campos relevantes de `metricas_lider_campana`, útil para validar cálculos de repartos y premios.

Ejemplo resumido:
```json
{
  "actividad_ok": true,
  "altas_pagadas_en_cierre": [
    { "cierre_codigo": "CAMP-101", "altas": 3, "cuota": 1, "monto_pagado": 2200 }
  ],
  "cantidad_1c": 4,
  "cantidad_2c": 2,
  "cantidad_3c": 1,
  "monto_reparto_total": 4400,
  "premio_actividad": 6000,
  "premio_unidades": 5000,
  "premio_cobranzas": 4000,
  "premio_altas": 6600,
  "premio_crecimiento": 25000,
  "premio_total": 51000,
  "datos": {
    "nota": "Fixture de referencia para probar cálculos de métricas y repartos reales."
  }
}
```

## Lógica de cálculo (resumen funcional)
- **Actividad**: validar que la líder alcance entre R mín y R máx de revendedoras activas.
- **Altas**: contar altas nuevas; cada una paga $2.200 distribuido en 3 cierres consecutivos.
- **Unidades**: habilitado cuando el total de unidades de la campaña supera el mínimo del rango.
- **Cobranzas**: verificar pago total del pedido del equipo dentro de 7 días corridos desde la recepción.
- **Crecimiento**: pagar solo una vez al cruzar al rango siguiente según la tabla de premios.
- **Repartos 1C/2C/3C**: multiplicar el monto fijo por la cantidad de revendedoras en cada tipo de compra.

Todos los montos y umbrales registrados en BD son valores reales del plan de AlmaMia y deben usarse sin porcentajes adicionales.

## Servicio de cálculo automático
El servicio `App\Services\PremiosLiderCalculator` centraliza el cálculo por campaña usando los valores reales precargados en BD. Recibe la campaña, líder y los datos de actividad (revendedoras activas, unidades), cobranzas, altas y compras 1C/2C/3C, y guarda los resultados en `metricas_lider_campana`.

Ejemplo resumido de `altas_pagadas_en_cierre` que genera el servicio para 4 altas en la campaña `CAMP-123`:

```json
[
  {"cierre_codigo": "CAMP-123", "altas": 4, "cuota": 1, "monto_pagado": 2933.33},
  {"cierre_codigo": "CAMP-123 +1", "altas": 4, "cuota": 2, "monto_pagado": 2933.33},
  {"cierre_codigo": "CAMP-123 +2", "altas": 4, "cuota": 3, "monto_pagado": 2933.33}
]
```

El monto de reparto total se calcula siempre como `(1C×500)+(2C×700)+(3C×1000)` usando las cantidades de compras registradas.
