<?php

namespace Database\Seeders;

use App\Models\CierreCampana;
use App\Models\MetricaLiderCampana;
use App\Models\RangoLider;
use App\Models\User;
use Illuminate\Database\Seeder;

class MetricasLiderCampanaTestSeeder extends Seeder
{
    public function run(): void
    {
        $lider = User::factory()->create([
            'name' => 'Líder Métricas QA',
            'email' => 'lider-metricas@example.com',
        ]);

        $cierres = CierreCampana::factory()->count(2)->sequence(
            ['codigo' => 'CAMP-BASE', 'nombre' => 'Campaña Base QA'],
            ['codigo' => 'CAMP-SUBIDA', 'nombre' => 'Campaña Crecimiento QA']
        )->create();

        $rangoBase = RangoLider::factory()->rango('Perla')->create();
        $rangoCrecido = RangoLider::factory()->rango('Aguamarina')->create();

        MetricaLiderCampana::factory()
            ->for($lider, 'lider')
            ->for($cierres[0], 'cierreCampana')
            ->for($rangoBase, 'rangoLider')
            ->create([
                'crecimiento_ok' => false,
                'cantidad_1c' => 6,
                'cantidad_2c' => 3,
                'cantidad_3c' => 1,
                'fecha_pago_equipo' => now()->subDays(2),
            ]);

        MetricaLiderCampana::factory()
            ->for($lider, 'lider')
            ->for($cierres[1], 'cierreCampana')
            ->for($rangoCrecido, 'rangoLider')
            ->create([
                'crecimiento_ok' => true,
                'cantidad_1c' => 8,
                'cantidad_2c' => 5,
                'cantidad_3c' => 2,
                'altas_pagadas_en_cierre' => [
                    ['cierre_codigo' => 'CAMP-SUBIDA', 'altas' => 4, 'cuota' => 1, 'monto_pagado' => 8800],
                ],
                'fecha_pago_equipo' => now()->subDays(1),
            ]);
    }
}
