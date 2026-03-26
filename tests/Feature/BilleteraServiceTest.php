<?php

use App\Models\CierreCampana;
use App\Models\Cobro;
use App\Models\PuntajeRegla;
use App\Models\RevendedoraPunto;
use App\Models\User;
use App\Services\BilleteraService;
use App\Services\LiquidacionCierreService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('construye resumen consolidado con movimientos, saldo y clasificación de premios', function () {
    $lider = User::factory()->create([
        'name' => 'Líder Billetera',
        'coordinadora_id' => null,
    ]);

    $cierreActual = CierreCampana::query()->create([
        'nombre' => 'Cierre actual',
        'codigo' => '2026-C1',
        'numero_cierre' => 1,
        'fecha_inicio' => now()->subDays(2)->toDateString(),
        'fecha_cierre' => now()->addDays(3)->toDateString(),
    ]);

    CierreCampana::query()->create([
        'nombre' => 'Cierre siguiente',
        'codigo' => '2026-C2',
        'numero_cierre' => 2,
        'catalogo_id' => $cierreActual->catalogo_id,
        'fecha_inicio' => now()->addDays(4)->toDateString(),
        'fecha_cierre' => now()->addDays(10)->toDateString(),
    ]);

    RevendedoraPunto::query()->create([
        'user_id' => $lider->id,
        'catalogo_id' => $cierreActual->catalogo_id,
        'cierre_id' => $cierreActual->id,
        'estado' => 'confirmado',
        'puntos' => 150,
        'origen' => 'test',
        'motivo' => 'Acumulación inicial',
        'saldo_posterior' => 150,
    ]);

    RevendedoraPunto::query()->create([
        'user_id' => $lider->id,
        'catalogo_id' => $cierreActual->catalogo_id,
        'cierre_id' => $cierreActual->id,
        'estado' => 'canjeado',
        'puntos' => -30,
        'origen' => 'test',
        'motivo' => 'Canje parcial',
        'saldo_posterior' => 120,
    ]);

    PuntajeRegla::query()->create([
        'descripcion' => 'Rango Bronce',
        'puntaje_minimo' => 100,
        'puntaje_minimo_descripcion' => 'Nivel base de premios',
    ]);

    Cobro::query()->create([
        'lider_id' => $lider->id,
        'mes_campana' => $cierreActual->codigo,
        'monto' => 4500,
        'estado' => 'pagado',
    ]);

    app(LiquidacionCierreService::class)->liquidarPorLider($cierreActual, $lider);

    $resumen = app(BilleteraService::class)->construirResumen($lider);

    expect($resumen['saldo_puntos_actual'])->toBe(120)
        ->and($resumen['saldo_a_cobrar_mes_vigente'])->toBe(4500.0)
        ->and($resumen['rango_actual'])->toBe('Rango Bronce')
        ->and($resumen['clasificacion_premios']['clasifica'])->toBeTrue()
        ->and($resumen['movimientos'])->not->toBeEmpty();
});
