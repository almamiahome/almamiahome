<?php

namespace Database\Seeders;

use App\Models\Catalogo;
use App\Models\CierreCampana;
use App\Models\RangoLider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlmamiaSeederPremios extends Seeder
{
    private const VERSION_CONTROL = 'v2.1';

    public function run(): void
    {
        if ($this->yaFueEjecutado()) {
            return;
        }

        DB::transaction(function (): void {
            $catalogoBaseId = Catalogo::query()->updateOrCreate(
                ['nombre' => 'Catálogo Base Premios V2'],
                [
                    'descripcion' => 'Catálogo de referencia para pruebas de campaña y cierres V2.',
                    'anio' => null,
                    'numero' => null,
                ]
            )->id;

            $cierreBaseId = $this->resolverCierreReferencia($catalogoBaseId);

            DB::table('metricas_lider_campana')
                ->where('cierre_campana_id', $cierreBaseId)
                ->delete();

            DB::table('premio_reglas')
                ->where('campana_id', $cierreBaseId)
                ->delete();

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
                $rangoModelo = RangoLider::query()->updateOrCreate(
                    ['nombre' => $rango['nombre']],
                    [
                        'revendedoras_minimas' => $rango['revendedoras_minimas'],
                        'revendedoras_maximas' => $rango['revendedoras_maximas'],
                        'unidades_minimas' => $rango['unidades_minimas'],
                        'premio_actividad' => $rango['premio_actividad'],
                        'premio_unidades' => $rango['premio_unidades'],
                        'premio_cobranzas' => $rango['premio_cobranzas'],
                        'reparto_referencia' => $rango['reparto_referencia'],
                    ]
                );

                $rangosInsertados[$rango['nombre']] = array_merge($rango, [
                    'id' => $rangoModelo->id,
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
                DB::table('repartos_compras')->updateOrInsert(
                    ['tipo_compra' => $reparto['tipo_compra']],
                    [
                        'monto_por_revendedora' => $reparto['monto_por_revendedora'],
                        'descripcion' => sprintf('Monto fijo por revendedora en su %s.', strtolower($reparto['tipo_compra'])),
                        'datos' => json_encode([
                            'monto_base' => $reparto['monto_por_revendedora'],
                            'porcentaje_lider' => $reparto['porcentaje_lider'] ?? null,
                            'porcentaje_revendedora' => $reparto['porcentaje_revendedora'] ?? null,
                        ]),
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            $this->registrarEjecucion($cierreBaseId);
        });
    }

    private function resolverCierreReferencia(int $catalogoBaseId): int
    {
        $cierre = CierreCampana::query()
            ->where('codigo', 'CAMP-BASE')
            ->orWhere('nombre', 'Campaña Base Premios')
            ->first();

        if (! $cierre) {
            $cierre = new CierreCampana();
            $cierre->codigo = 'CAMP-BASE';
        }

        $cierre->nombre = 'Campaña Base Premios';
        $cierre->catalogo_id = $catalogoBaseId;
        $cierre->numero_cierre = 1;
        $cierre->estado = CierreCampana::ESTADO_PLANIFICADO;
        $cierre->datos = [
            'nota' => 'Cierre referencial para precargar el plan de premios Alma Mia.',
        ];
        $cierre->save();

        return $cierre->id;
    }

    private function yaFueEjecutado(): bool
    {
        if (! Schema::hasTable('control_ejecucion_seeders')) {
            return false;
        }

        return DB::table('control_ejecucion_seeders')
            ->where('seeder', static::class)
            ->where('version', self::VERSION_CONTROL)
            ->exists();
    }

    private function registrarEjecucion(int $cierreBaseId): void
    {
        if (! Schema::hasTable('control_ejecucion_seeders')) {
            return;
        }

        DB::table('control_ejecucion_seeders')->updateOrInsert(
            [
                'seeder' => static::class,
                'version' => self::VERSION_CONTROL,
            ],
            [
                'ejecutado_en' => now(),
                'datos' => json_encode([
                    'cierre_referencia_codigo' => 'CAMP-BASE',
                    'cierre_referencia_id' => $cierreBaseId,
                ]),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
