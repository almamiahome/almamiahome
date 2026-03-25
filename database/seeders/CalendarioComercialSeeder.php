<?php

namespace Database\Seeders;

use App\Models\Catalogo;
use App\Models\CierreCampana;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CalendarioComercialSeeder extends Seeder
{
    public function run(): void
    {
        $anio = (int) now()->year;

        for ($numeroCatalogo = 1; $numeroCatalogo <= 4; $numeroCatalogo++) {
            $catalogo = Catalogo::query()->updateOrCreate(
                [
                    'anio' => $anio,
                    'numero' => $numeroCatalogo,
                ],
                [
                    'nombre' => sprintf('CAT-%d-%d', $anio, $numeroCatalogo),
                    'descripcion' => sprintf('Catálogo comercial %d/%d con cierres oficiales.', $numeroCatalogo, $anio),
                ]
            );

            for ($numeroCierre = 1; $numeroCierre <= 3; $numeroCierre++) {
                $indiceCampana = (($numeroCatalogo - 1) * 3) + $numeroCierre;

                $fechaInicio = Carbon::create($anio, $indiceCampana, 1)->startOfDay();
                $fechaCierre = $fechaInicio->copy()->addDays(19);
                $fechaLiquidacion = $fechaCierre->copy()->addDays(10);
                $codigoCampania = sprintf('CAMP-%d-%02d', $anio, $indiceCampana);

                CierreCampana::query()->updateOrCreate(
                    ['codigo' => $codigoCampania],
                    [
                        'catalogo_id' => $catalogo->id,
                        'nombre' => sprintf('Campaña %02d/%d', $indiceCampana, $anio),
                        'numero_cierre' => $numeroCierre,
                        'fecha_inicio' => $fechaInicio->toDateString(),
                        'fecha_cierre' => $fechaCierre->toDateString(),
                        'fecha_liquidacion' => $fechaLiquidacion->toDateString(),
                        'estado' => CierreCampana::ESTADO_PLANIFICADO,
                        'datos' => [
                            'codigo_catalogo' => sprintf('CAT-%d-%d', $anio, $numeroCatalogo),
                            'oficial' => true,
                            'base' => true,
                            'origen' => 'calendario_comercial',
                        ],
                    ]
                );
            }
        }
    }
}
