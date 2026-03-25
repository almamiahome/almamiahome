<?php

namespace Tests\Feature;

use App\Models\CierreCampana;
use App\Models\PremioRegla;
use App\Models\RangoLider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PremioReglasCampanaRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_regla_con_campana_id_aparece_en_la_relacion_premio_reglas(): void
    {
        $rango = RangoLider::query()->create([
            'nombre' => 'Rango prueba',
        ]);

        $cierre = CierreCampana::query()->create([
            'nombre' => 'Campaña prueba',
            'codigo' => 'CMP-TEST-001',
            'estado' => 'abierta',
        ]);

        $regla = PremioRegla::query()->create([
            'rango_lider_id' => $rango->id,
            'campana_id' => $cierre->id,
            'tipo' => 'bono_fijo',
            'umbral_minimo' => 1,
            'umbral_maximo' => 10,
            'monto' => 5000,
        ]);

        $cierre->load('premioReglas');

        $this->assertCount(1, $cierre->premioReglas);
        $this->assertTrue($cierre->premioReglas->contains('id', $regla->id));
    }
}
