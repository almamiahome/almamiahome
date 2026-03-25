<?php

use App\Models\CierreCampana;
use App\Models\Catalogo;
use App\Models\LiderAltaCuota;
use App\Models\LiderSaltoRangoHistorial;
use App\Models\PremioRegla;
use App\Models\RangoLider;
use App\Models\User;
use App\Services\PremiosLiderCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function contextoEtapa3(): array
{
    $catalogo = Catalogo::query()->create(['nombre' => 'Catálogo Feature', 'anio' => 2026, 'numero' => 4]);
    $cierre = CierreCampana::query()->create([
        'catalogo_id' => $catalogo->id,
        'nombre' => 'Campaña 2026-07',
        'codigo' => 'CAMP-2026-07',
        'numero_cierre' => 3,
        'fecha_inicio' => '2026-07-01',
        'fecha_cierre' => '2026-07-21',
        'fecha_liquidacion' => '2026-07-31',
        'estado' => CierreCampana::ESTADO_ABIERTO,
    ]);
    $rango = RangoLider::query()->create([
        'nombre' => 'Esmeralda',
        'revendedoras_minimas' => 12,
        'revendedoras_maximas' => 40,
        'unidades_minimas' => 130,
        'premio_actividad' => 12000,
        'premio_unidades' => 8000,
        'premio_cobranzas' => 5500,
    ]);
    foreach (['retencion' => 4200, 'crecimiento' => 10000, 'plus_crecimiento' => 2800] as $tipo => $monto) {
        PremioRegla::query()->create(['rango_lider_id' => $rango->id, 'tipo' => $tipo, 'monto' => $monto, 'cuotas' => 1]);
    }

    return [$cierre, $rango, User::factory()->create()];
}

it('T11 valida retención en escenario feature auditable', function () {
    [$cierre, $rango, $lider] = contextoEtapa3();

    $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
        'rango' => $rango,
        'revendedoras_activas' => 20,
        'actividad_cierre_anterior' => 18,
        'unidades' => 132,
    ]);

    expect($metrica->retencion_ok)->toBeTrue();
});

it('T12 registra cuotas de altas en flujo feature', function () {
    [$cierre, $rango, $lider] = contextoEtapa3();

    app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
        'rango' => $rango,
        'revendedoras_activas' => 20,
        'unidades' => 132,
        'altas' => 3,
    ]);

    expect(LiderAltaCuota::query()->count())->toBe(3);
});

it('T13 invalida cobranza fuera de corte en flujo feature', function () {
    [$cierre, $rango, $lider] = contextoEtapa3();

    $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
        'rango' => $rango,
        'revendedoras_activas' => 20,
        'unidades' => 132,
        'fecha_pago_equipo' => '2026-08-10',
    ]);

    expect($metrica->cobranzas_ok)->toBeFalse();
});

it('T14 registra historial de crecimiento en flujo feature', function () {
    [$cierre, $rango, $lider] = contextoEtapa3();

    app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
        'rango' => $rango,
        'rango_anterior_id' => $rango->id,
        'revendedoras_activas' => 20,
        'unidades' => 132,
        'crecimiento_logrado' => true,
    ]);

    expect(LiderSaltoRangoHistorial::query()->count())->toBe(1);
});

it('T15 calcula reparto en flujo feature', function () {
    [$cierre, $rango, $lider] = contextoEtapa3();

    $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
        'rango' => $rango,
        'revendedoras_activas' => 20,
        'unidades' => 132,
        'cantidad_1c' => 3,
        'cantidad_2c' => 2,
        'cantidad_3c' => 1,
    ]);

    expect($metrica->monto_reparto_total)->toBe(3900.0);
});

it('T16 evalúa plus y unidades en flujo feature', function () {
    [$cierre, $rango, $lider] = contextoEtapa3();

    $metrica = app(PremiosLiderCalculator::class)->calcular($cierre, $lider, [
        'rango' => $rango,
        'revendedoras_activas' => 20,
        'unidades' => 140,
        'objetivo_proximo_cierre' => 135,
    ]);

    expect($metrica->plus_crecimiento_ok)->toBeTrue()
        ->and($metrica->unidades_ok)->toBeTrue();
});
