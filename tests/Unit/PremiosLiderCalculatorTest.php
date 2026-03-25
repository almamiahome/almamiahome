<?php

namespace Tests\Unit;

use App\Models\CierreCampana;
use App\Models\Catalogo;
use App\Models\MetricaLiderCampana;
use App\Models\RangoLider;
use App\Models\User;
use App\Services\PremiosLiderCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PremiosLiderCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_calcula_actividad_lider_y_persiste_evidencia_completa(): void
    {
        $catalogo = Catalogo::query()->create([
            'nombre' => 'Catálogo 2026-1',
            'anio' => 2026,
            'numero' => 1,
        ]);

        $cierre = CierreCampana::query()->create([
            'catalogo_id' => $catalogo->id,
            'nombre' => 'Campaña 2026-01',
            'codigo' => 'CAMP-2026-01',
            'numero_cierre' => 1,
            'fecha_inicio' => '2026-01-01',
            'fecha_cierre' => '2026-01-21',
            'fecha_liquidacion' => '2026-01-31',
            'estado' => CierreCampana::ESTADO_ABIERTO,
        ]);

        $rango = RangoLider::query()->create([
            'nombre' => 'Perla',
            'revendedoras_minimas' => 5,
            'revendedoras_maximas' => 8,
            'unidades_minimas' => 50,
            'premio_actividad' => 6000,
            'premio_unidades' => 5000,
            'premio_cobranzas' => 4000,
        ]);

        $lider = User::factory()->create();

        $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
            'rango' => $rango,
            'revendedoras_activas' => 6,
            'unidades' => 60,
            'altas' => 3,
            'crecimiento_logrado' => true,
            'retencion_lograda' => true,
            'plus_crecimiento_logrado' => true,
            'fecha_pago_equipo' => '2026-01-28',
            'objetivo_proximo_cierre' => 9,
            'actividad_cierre_anterior' => 6,
        ]);

        $evidencia = data_get($metrica->datos, 'evidencia', []);

        $this->assertTrue($metrica->actividad_ok);
        $this->assertTrue($metrica->unidades_ok);
        $this->assertTrue($metrica->cobranzas_ok);
        $this->assertSame(PremiosLiderCalculator::VERSION_CALCULO, data_get($evidencia, 'version_calculo'));
        $this->assertCount(7, data_get($evidencia, 'reglas_aplicadas', []));
        $this->assertSame(9, data_get($evidencia, 'insumos.objetivo_proximo_cierre'));
    }

    public function test_aplica_corte_de_fecha_en_cobranzas_y_recalculo_es_idempotente(): void
    {
        $catalogo = Catalogo::query()->create([
            'nombre' => 'Catálogo 2026-2',
            'anio' => 2026,
            'numero' => 2,
        ]);

        $cierre = CierreCampana::query()->create([
            'catalogo_id' => $catalogo->id,
            'nombre' => 'Campaña 2026-04',
            'codigo' => 'CAMP-2026-04',
            'numero_cierre' => 1,
            'fecha_inicio' => '2026-04-01',
            'fecha_cierre' => '2026-04-21',
            'fecha_liquidacion' => '2026-04-30',
            'estado' => CierreCampana::ESTADO_ABIERTO,
        ]);

        $rango = RangoLider::query()->create([
            'nombre' => 'Rubí',
            'revendedoras_minimas' => 25,
            'revendedoras_maximas' => 34,
            'unidades_minimas' => 250,
            'premio_actividad' => 35000,
            'premio_unidades' => 20000,
            'premio_cobranzas' => 15000,
        ]);

        $lider = User::factory()->create();
        $servicio = app(PremiosLiderCalculator::class);

        $servicio->calcular($cierre, $lider, [
            'rango_lider_id' => $rango->id,
            'revendedoras_activas' => 30,
            'unidades' => 260,
            'fecha_pago_equipo' => '2026-04-29',
            'altas' => 1,
        ]);

        $metrica = $servicio->calcular($cierre, $lider, [
            'rango_lider_id' => $rango->id,
            'revendedoras_activas' => 30,
            'unidades' => 265,
            'fecha_pago_equipo' => '2026-04-30',
            'altas' => 1,
        ]);

        $this->assertFalse($metrica->cobranzas_ok);
        $this->assertSame(1, MetricaLiderCampana::query()->count());
        $this->assertSame(265, data_get($metrica->datos, 'unidades'));
    }
}
