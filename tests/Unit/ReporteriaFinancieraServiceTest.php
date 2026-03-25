<?php

use App\Models\CierreCampana;
use App\Models\Departamento;
use App\Models\LiquidacionCierre;
use App\Models\Zona;
use App\Services\ReporteriaFinancieraService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function crearUsuarioReporte(int $id, string $email, int $zonaId, int $departamentoId, ?int $coordinadoraId = null): void
{
    DB::table('users')->insert([
        'id' => $id,
        'name' => 'Usuario '.$id,
        'email' => $email,
        'password' => bcrypt('secret'),
        'username' => str($email)->before('@')->value(),
        'zona_id' => $zonaId,
        'departamento_id' => $departamentoId,
        'coordinadora_id' => $coordinadoraId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

it('T18 aplica filtros por zona y departamento en resumen de líder', function () {
    $zonaNorte = Zona::query()->create(['nombre' => 'Norte', 'codigo' => 'NORTE']);
    $zonaSur = Zona::query()->create(['nombre' => 'Sur', 'codigo' => 'SUR']);

    $deptoNorte = Departamento::query()->create(['zona_id' => $zonaNorte->id, 'nombre' => 'Norte A', 'codigo' => 'NORTE-A']);
    $deptoSur = Departamento::query()->create(['zona_id' => $zonaSur->id, 'nombre' => 'Sur A', 'codigo' => 'SUR-A']);

    crearUsuarioReporte(31, 'coord31@example.com', $zonaNorte->id, $deptoNorte->id);
    crearUsuarioReporte(32, 'coord32@example.com', $zonaSur->id, $deptoSur->id);
    crearUsuarioReporte(21, 'lider21@example.com', $zonaNorte->id, $deptoNorte->id, 31);
    crearUsuarioReporte(22, 'lider22@example.com', $zonaSur->id, $deptoSur->id, 32);

    $cierre = CierreCampana::query()->create(['nombre' => 'Cierre R1', 'codigo' => '2026-R1', 'estado' => 'cerrado']);

    LiquidacionCierre::query()->create([
        'cierre_campana_id' => $cierre->id,
        'lider_id' => 21,
        'coordinadora_id' => 31,
        'saldo_a_cobrar' => 10000,
        'saldo_a_pagar' => 4500,
        'deuda_arrastrada' => 1200,
        'balance_neto' => 4300,
        'estado' => 'auditada',
    ]);

    LiquidacionCierre::query()->create([
        'cierre_campana_id' => $cierre->id,
        'lider_id' => 22,
        'coordinadora_id' => 32,
        'saldo_a_cobrar' => 19000,
        'saldo_a_pagar' => 9000,
        'deuda_arrastrada' => 400,
        'balance_neto' => 9600,
        'estado' => 'auditada',
    ]);

    $servicio = app(ReporteriaFinancieraService::class);

    $filtradoZona = $servicio->resumenPorLider(['zona_id' => $zonaNorte->id]);
    expect($filtradoZona)->toHaveCount(1)
        ->and((int) $filtradoZona->first()->lider_id)->toBe(21);

    $filtradoDepto = $servicio->resumenPorLider(['departamento_id' => $deptoSur->id]);
    expect($filtradoDepto)->toHaveCount(1)
        ->and((int) $filtradoDepto->first()->lider_id)->toBe(22);
});

it('T18 genera comparativas entre cierres y conciliación por líder/coordinadora', function () {
    $zona = Zona::query()->create(['nombre' => 'Centro', 'codigo' => 'CENTRO']);
    $depto = Departamento::query()->create(['zona_id' => $zona->id, 'nombre' => 'Centro 1', 'codigo' => 'CENTRO-1']);

    crearUsuarioReporte(41, 'coord41@example.com', $zona->id, $depto->id);
    crearUsuarioReporte(42, 'coord42@example.com', $zona->id, $depto->id);
    crearUsuarioReporte(51, 'lider51@example.com', $zona->id, $depto->id, 41);
    crearUsuarioReporte(52, 'lider52@example.com', $zona->id, $depto->id, 42);

    $cierreA = CierreCampana::query()->create(['nombre' => 'Cierre A', 'codigo' => '2026-A', 'estado' => 'cerrado']);
    $cierreB = CierreCampana::query()->create(['nombre' => 'Cierre B', 'codigo' => '2026-B', 'estado' => 'cerrado']);

    LiquidacionCierre::insert([
        [
            'cierre_campana_id' => $cierreA->id,
            'lider_id' => 51,
            'coordinadora_id' => 41,
            'saldo_a_cobrar' => 12000,
            'saldo_a_pagar' => 3000,
            'deuda_arrastrada' => 1000,
            'balance_neto' => 8000,
            'estado' => 'auditada',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'cierre_campana_id' => $cierreB->id,
            'lider_id' => 51,
            'coordinadora_id' => 41,
            'saldo_a_cobrar' => 15000,
            'saldo_a_pagar' => 5000,
            'deuda_arrastrada' => 0,
            'balance_neto' => 10000,
            'estado' => 'auditada',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'cierre_campana_id' => $cierreA->id,
            'lider_id' => 52,
            'coordinadora_id' => 42,
            'saldo_a_cobrar' => 9000,
            'saldo_a_pagar' => 6500,
            'deuda_arrastrada' => 500,
            'balance_neto' => 2000,
            'estado' => 'auditada',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    $servicio = app(ReporteriaFinancieraService::class);

    $porCierre = $servicio->resumenPorCierre()->keyBy('cierre_campana_id');
    expect((float) $porCierre[$cierreA->id]->balance_total)->toBe(10000.0)
        ->and((float) $porCierre[$cierreB->id]->balance_total)->toBe(10000.0);

    $porCoordinadora = $servicio->resumenPorCoordinadora()->keyBy('coordinadora_id');
    expect((float) $porCoordinadora[41]->actividad_total)->toBe(27000.0)
        ->and((float) $porCoordinadora[41]->balance_total)->toBe(18000.0)
        ->and((float) $porCoordinadora[42]->balance_total)->toBe(2000.0);

    $timeline = $servicio->timelineIndividual(51);
    expect($timeline->pluck('cierre_campana_id')->all())->toBe([$cierreA->id, $cierreB->id]);
});
