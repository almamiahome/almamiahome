<?php

namespace Tests\Feature;

use App\Models\CierreCampana;
use App\Models\MetricaLiderCampana;
use App\Models\User;
use App\Services\CierreCampanaStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CierreCampanaStateMachineTest extends TestCase
{
    use RefreshDatabase;

    public function test_registra_transicion_valida_y_guarda_historial(): void
    {
        $usuario = User::factory()->create();

        $cierre = CierreCampana::query()->create([
            'nombre' => 'Campaña CSM',
            'codigo' => 'CSM-001',
            'estado' => CierreCampana::ESTADO_ABIERTO,
            'fecha_inicio' => now()->subDays(5)->toDateString(),
            'fecha_cierre' => now()->subDay()->toDateString(),
            'fecha_liquidacion' => now()->toDateString(),
        ]);

        MetricaLiderCampana::query()->create([
            'cierre_campana_id' => $cierre->id,
            'lider_id' => $usuario->id,
            'actividad_ok' => true,
            'unidades_ok' => true,
            'cobranzas_ok' => true,
            'premio_total' => 0,
        ]);

        $service = app(CierreCampanaStateMachine::class);

        $service->transicionar($cierre, CierreCampana::ESTADO_LIQUIDACION, $usuario, 'Paso a liquidación');
        $service->transicionar($cierre->fresh(), CierreCampana::ESTADO_CERRADO, $usuario, 'Cierre final');

        $this->assertDatabaseHas('cierre_campana_historial_estados', [
            'cierre_campana_id' => $cierre->id,
            'estado_anterior' => CierreCampana::ESTADO_ABIERTO,
            'estado_nuevo' => CierreCampana::ESTADO_LIQUIDACION,
            'usuario_id' => $usuario->id,
            'motivo' => 'Paso a liquidación',
        ]);

        $this->assertDatabaseHas('cierre_campana_historial_estados', [
            'cierre_campana_id' => $cierre->id,
            'estado_anterior' => CierreCampana::ESTADO_LIQUIDACION,
            'estado_nuevo' => CierreCampana::ESTADO_CERRADO,
            'usuario_id' => $usuario->id,
            'motivo' => 'Cierre final',
        ]);
    }

    public function test_bloquea_cierre_invalido_si_no_esta_en_liquidacion(): void
    {
        $this->expectException(ValidationException::class);

        $usuario = User::factory()->create();

        $cierre = CierreCampana::query()->create([
            'nombre' => 'Campaña inválida',
            'codigo' => 'CSM-002',
            'estado' => CierreCampana::ESTADO_ABIERTO,
            'fecha_inicio' => now()->subDays(5)->toDateString(),
            'fecha_cierre' => now()->subDay()->toDateString(),
            'fecha_liquidacion' => now()->toDateString(),
        ]);

        app(CierreCampanaStateMachine::class)->transicionar(
            $cierre,
            CierreCampana::ESTADO_CERRADO,
            $usuario,
            'Intento inválido'
        );
    }
}
