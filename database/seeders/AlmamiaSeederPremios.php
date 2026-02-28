<?php

namespace Database\Seeders;

use App\Models\RangoLider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlmamiaSeederPremios extends Seeder
{
    public function run(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        DB::table('metricas_lider_campana')->truncate();
        DB::table('repartos_compras')->truncate();
        DB::table('premio_reglas')->truncate();
        DB::table('rangos_lideres')->truncate();
        DB::table('cierres_campana')->truncate();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $cierreBaseId = DB::table('cierres_campana')->insertGetId([
            'nombre' => 'Campaña Base Premios',
            'codigo' => 'CAMP-BASE',
            'estado' => 'configurada',
            'datos' => json_encode([
                'nota' => 'Cierre referencial para precargar el plan de premios Alma Mia.',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rangos = [
            ['nombre' => 'Perla', 'revendedoras_minimas' => 5, 'revendedoras_maximas' => 8, 'unidades_minimas' => 50, 'premio_actividad' => 6000, 'premio_unidades' => 5000, 'premio_cobranzas' => 4000, 'reparto_referencia' => 350],
            ['nombre' => 'Aguamarina', 'revendedoras_minimas' => 8, 'revendedoras_maximas' => 11, 'unidades_minimas' => 120, 'premio_actividad' => 12000, 'premio_unidades' => 10000, 'premio_cobranzas' => 7200, 'reparto_referencia' => 450],
            ['nombre' => 'Zafiro', 'revendedoras_minimas' => 12, 'revendedoras_maximas' => 16, 'unidades_minimas' => 150, 'premio_actividad' => 18000, 'premio_unidades' => 12000, 'premio_cobranzas' => 9000, 'reparto_referencia' => 500],
            ['nombre' => 'Esmeralda', 'revendedoras_minimas' => 17, 'revendedoras_maximas' => 24, 'unidades_minimas' => 200, 'premio_actividad' => 25000, 'premio_unidades' => 16000, 'premio_cobranzas' => 12000, 'reparto_referencia' => 550],
            ['nombre' => 'Rubí', 'revendedoras_minimas' => 25, 'revendedoras_maximas' => 34, 'unidades_minimas' => 250, 'premio_actividad' => 35000, 'premio_unidades' => 20000, 'premio_cobranzas' => 15000, 'reparto_referencia' => 600],
            ['nombre' => 'Diamante', 'revendedoras_minimas' => 35, 'revendedoras_maximas' => 45, 'unidades_minimas' => 350, 'premio_actividad' => 45000, 'premio_unidades' => 25000, 'premio_cobranzas' => 20000, 'reparto_referencia' => 800],
            ['nombre' => 'Diamante Rosa', 'revendedoras_minimas' => 46, 'revendedoras_maximas' => 60, 'unidades_minimas' => 550, 'premio_actividad' => 60000, 'premio_unidades' => 40000, 'premio_cobranzas' => 30000, 'reparto_referencia' => 900],
            ['nombre' => 'Estrella', 'revendedoras_minimas' => 61, 'revendedoras_maximas' => 80, 'unidades_minimas' => 700, 'premio_actividad' => 80000, 'premio_unidades' => 50000, 'premio_cobranzas' => 40000, 'reparto_referencia' => 1000],
            ['nombre' => 'Ejecutiva', 'revendedoras_minimas' => 81, 'revendedoras_maximas' => 100, 'unidades_minimas' => 850, 'premio_actividad' => 100000, 'premio_unidades' => 65000, 'premio_cobranzas' => 50000, 'reparto_referencia' => 1100],
        ];

        $rangosInsertados = [];
        foreach ($rangos as $rango) {
            $rangoId = RangoLider::query()->insertGetId([
                'nombre' => $rango['nombre'],
                'revendedoras_minimas' => $rango['revendedoras_minimas'],
                'revendedoras_maximas' => $rango['revendedoras_maximas'],
                'unidades_minimas' => $rango['unidades_minimas'],
                'premio_actividad' => $rango['premio_actividad'],
                'premio_unidades' => $rango['premio_unidades'],
                'premio_cobranzas' => $rango['premio_cobranzas'],
                'reparto_referencia' => $rango['reparto_referencia'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $rangosInsertados[$rango['nombre']] = array_merge($rango, [
                'id' => $rangoId,
            ]);
        }

        foreach ($rangosInsertados as $rango) {
            $componentes = [
                'actividad' => [
                    'umbral_minimo' => $rango['revendedoras_minimas'],
                    'umbral_maximo' => $rango['revendedoras_maximas'],
                    'monto' => $rango['premio_actividad'],
                ],
                'altas' => [
                    'umbral_minimo' => 3,
                    'monto' => 2200,
                    'datos' => [
                        'monto_por_alta' => 2200,
                        'cuotas' => 3,
                    ],
                ],
                'unidades' => [
                    'umbral_minimo' => $rango['unidades_minimas'],
                    'monto' => $rango['premio_unidades'],
                ],
                'cobranzas' => [
                    'umbral_minimo' => 7,
                    'monto' => $rango['premio_cobranzas'],
                    'datos' => [
                        'dias_limite' => 7,
                    ],
                ],
            ];

            foreach ($componentes as $tipo => $config) {
                DB::table('premio_reglas')->insert([
                    'rango_lider_id' => $rango['id'],
                    'campana_id' => $cierreBaseId,
                    'tipo' => $tipo,
                    'umbral_minimo' => $config['umbral_minimo'],
                    'umbral_maximo' => $config['umbral_maximo'] ?? null,
                    'monto' => $config['monto'],
                    'datos' => json_encode($config['datos'] ?? []),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $crecimientos = [
            ['desde' => 'Perla', 'hasta' => 'Perla', 'monto' => 25000, 'primer_objetivo' => true],
            ['desde' => 'Perla', 'hasta' => 'Aguamarina', 'monto' => 25000],
            ['desde' => 'Aguamarina', 'hasta' => 'Zafiro', 'monto' => 30000],
            ['desde' => 'Zafiro', 'hasta' => 'Esmeralda', 'monto' => 40000],
            ['desde' => 'Esmeralda', 'hasta' => 'Rubí', 'monto' => 50000],
            ['desde' => 'Rubí', 'hasta' => 'Diamante', 'monto' => 60000],
            ['desde' => 'Diamante', 'hasta' => 'Diamante Rosa', 'monto' => 70000],
            ['desde' => 'Diamante Rosa', 'hasta' => 'Estrella', 'monto' => 80000],
            ['desde' => 'Estrella', 'hasta' => 'Ejecutiva', 'monto' => 100000],
        ];

        foreach ($crecimientos as $crecimiento) {
            $rangoOrigen = $rangosInsertados[$crecimiento['desde']] ?? null;
            $rangoDestino = $rangosInsertados[$crecimiento['hasta']] ?? null;

            if (! $rangoOrigen) {
                continue;
            }

            DB::table('premio_reglas')->insert([
                'rango_lider_id' => $rangoOrigen['id'],
                'campana_id' => $cierreBaseId,
                'tipo' => 'crecimiento',
                'umbral_minimo' => $rangoDestino['revendedoras_minimas'] ?? $rangoOrigen['revendedoras_minimas'],
                'umbral_maximo' => $rangoDestino['revendedoras_maximas'] ?? null,
                'monto' => $crecimiento['monto'],
                'datos' => json_encode([
                    'rango_destino' => $crecimiento['hasta'],
                    'primer_objetivo' => $crecimiento['primer_objetivo'] ?? false,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $repartos = [
            ['tipo_compra' => '1C', 'monto_por_revendedora' => 500],
            ['tipo_compra' => '2C', 'monto_por_revendedora' => 700],
            ['tipo_compra' => '3C', 'monto_por_revendedora' => 1000],
        ];

        foreach ($repartos as $reparto) {
            DB::table('repartos_compras')->insert([
                'tipo_compra' => $reparto['tipo_compra'],
                'monto_por_revendedora' => $reparto['monto_por_revendedora'],
                'descripcion' => sprintf('Monto fijo por revendedora en su %s.', strtolower($reparto['tipo_compra'])),
                'datos' => json_encode([
                    'monto_base' => $reparto['monto_por_revendedora'],
                    'porcentaje_lider' => $reparto['porcentaje_lider'] ?? null,
                    'porcentaje_revendedora' => $reparto['porcentaje_revendedora'] ?? null,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
