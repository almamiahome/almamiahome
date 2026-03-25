<?php

namespace Tests\Unit;

use App\Models\CierreCampana;
use App\Models\Catalogo;
use App\Models\LiderAltaCuota;
use App\Models\LiderSaltoRangoHistorial;
use App\Models\PremioRegla;
use App\Models\RangoLider;
use App\Models\User;
use App\Services\PremiosLiderCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PremiosLiderCalculatorT11T16UnitTest extends TestCase
{
    use RefreshDatabase;

    protected function contextoBase(): array
    {
        $catalogo = Catalogo::query()->create(['nombre' => 'Catálogo Unit', 'anio' => 2026, 'numero' => 3]);
        $cierre = CierreCampana::query()->create([
            'catalogo_id' => $catalogo->id,
            'nombre' => 'Campaña 2026-06',
            'codigo' => 'CAMP-2026-06',
            'numero_cierre' => 2,
            'fecha_inicio' => '2026-06-01',
            'fecha_cierre' => '2026-06-21',
            'fecha_liquidacion' => '2026-06-30',
            'estado' => CierreCampana::ESTADO_ABIERTO,
        ]);
        $rango = RangoLider::query()->create([
            'nombre' => 'Rubí',
            'revendedoras_minimas' => 10,
            'revendedoras_maximas' => 30,
            'unidades_minimas' => 120,
            'premio_actividad' => 10000,
            'premio_unidades' => 7000,
            'premio_cobranzas' => 5000,
        ]);

        foreach (['retencion' => 5000, 'crecimiento' => 12000, 'plus_crecimiento' => 3500] as $tipo => $monto) {
            PremioRegla::query()->create([
                'rango_lider_id' => $rango->id,
                'tipo' => $tipo,
                'monto' => $monto,
                'cuotas' => 1,
            ]);
        }

        return [$cierre, $rango, User::factory()->create()];
    }

    public function test_T11_retencion_cumple_si_actividad_actual_supera_anterior(): void
    {
        [$cierre, $rango, $lider] = $this->contextoBase();

        $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
            'rango' => $rango,
            'revendedoras_activas' => 14,
            'actividad_cierre_anterior' => 12,
            'unidades' => 125,
        ]);

        $this->assertTrue($metrica->retencion_ok);
        $this->assertSame(5000.0, $metrica->premio_retencion);
    }

    public function test_T12_altas_persiste_cuotas_idempotentes(): void
    {
        [$cierre, $rango, $lider] = $this->contextoBase();

        $datos = [
            'rango' => $rango,
            'revendedoras_activas' => 12,
            'unidades' => 125,
            'altas' => 3,
            'altas_pagadas_en_cierre' => [
                ['cierre_codigo' => 'CAMP-2026-06', 'altas' => 3, 'cuota' => 1, 'monto_pagado' => 2200],
                ['cierre_codigo' => 'CAMP-2026-07', 'altas' => 3, 'cuota' => 2, 'monto_pagado' => 2200],
                ['cierre_codigo' => 'CAMP-2026-08', 'altas' => 3, 'cuota' => 3, 'monto_pagado' => 2200],
            ],
        ];

        $servicio = app(PremiosLiderCalculator::class);
        $servicio->calcular($cierre, $lider, $datos);
        $servicio->calcular($cierre, $lider, $datos);

        $this->assertSame(3, LiderAltaCuota::query()->count());
    }

    public function test_T13_cobranza_aplica_corte_de_siete_dias(): void
    {
        [$cierre, $rango, $lider] = $this->contextoBase();

        $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
            'rango' => $rango,
            'revendedoras_activas' => 14,
            'unidades' => 125,
            'fecha_pago_equipo' => '2026-07-05',
        ]);

        $this->assertFalse($metrica->cobranzas_ok);
    }

    public function test_T14_crecimiento_registra_historial_de_salto(): void
    {
        [$cierre, $rango, $lider] = $this->contextoBase();

        app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
            'rango' => $rango,
            'rango_anterior_id' => $rango->id,
            'revendedoras_activas' => 15,
            'unidades' => 125,
            'crecimiento_logrado' => true,
        ]);

        $this->assertSame(1, LiderSaltoRangoHistorial::query()->count());
    }

    public function test_T15_reparto_calcula_monto_esperado(): void
    {
        [$cierre, $rango, $lider] = $this->contextoBase();

        $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
            'rango' => $rango,
            'revendedoras_activas' => 14,
            'unidades' => 125,
            'cantidad_1c' => 4,
            'cantidad_2c' => 2,
            'cantidad_3c' => 1,
        ]);

        $this->assertSame(4400.0, $metrica->monto_reparto_total);
    }

    public function test_T16_plus_y_unidades_cumplen_por_objetivo(): void
    {
        [$cierre, $rango, $lider] = $this->contextoBase();

        $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
            'rango' => $rango,
            'revendedoras_activas' => 14,
            'unidades' => 130,
            'objetivo_proximo_cierre' => 120,
        ]);

        $this->assertTrue($metrica->plus_crecimiento_ok);
        $this->assertTrue($metrica->unidades_ok);
    }
}
