<?php

use App\Models\CierreCampana;
use App\Models\CierreCampanaHistorialEstado;
use App\Models\User;
use App\Services\CierreCampanaStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registra historial al crear una campaña y al cerrar en transición válida', function () {
    $usuario = User::factory()->create();

    $cierre = CierreCampana::query()->create([
        'nombre' => 'Campaña 2026-01',
        'codigo' => 'CAMP-2026-01',
        'numero_cierre' => 1,
        'fecha_inicio' => now()->startOfMonth()->toDateString(),
        'fecha_cierre' => now()->endOfMonth()->toDateString(),
        'fecha_liquidacion' => now()->addMonth()->startOfMonth()->toDateString(),
        'estado' => CierreCampana::ESTADO_PLANIFICADO,
    ]);

    $servicio = app(CierreCampanaStateMachine::class);

    $servicio->registrarEstadoInicial($cierre, $usuario, 'Alta manual');
    $servicio->transicionar($cierre, CierreCampana::ESTADO_ABIERTO, $usuario, 'Inicio de ventas');
    $servicio->transicionar($cierre->refresh(), CierreCampana::ESTADO_LIQUIDACION, $usuario, 'Corte administrativo');
    $servicio->transicionar($cierre->refresh(), CierreCampana::ESTADO_CERRADO, $usuario, 'Cierre definitivo');

    expect($cierre->refresh()->estado)->toBe(CierreCampana::ESTADO_CERRADO);

    expect(CierreCampanaHistorialEstado::query()->where('cierre_campana_id', $cierre->id)->count())->toBe(4);

    $ultimo = CierreCampanaHistorialEstado::query()
        ->where('cierre_campana_id', $cierre->id)
        ->latest('id')
        ->firstOrFail();

    expect($ultimo->estado_anterior)->toBe(CierreCampana::ESTADO_LIQUIDACION)
        ->and($ultimo->estado_nuevo)->toBe(CierreCampana::ESTADO_CERRADO)
        ->and($ultimo->usuario_id)->toBe($usuario->id)
        ->and($ultimo->motivo)->toBe('Cierre definitivo');
});

it('bloquea cierre inválido fuera de la transición permitida', function () {
    $usuario = User::factory()->create();

    $cierre = CierreCampana::query()->create([
        'nombre' => 'Campaña inválida',
        'codigo' => 'CAMP-2026-INV',
        'numero_cierre' => 1,
        'fecha_inicio' => now()->startOfMonth()->toDateString(),
        'fecha_cierre' => now()->endOfMonth()->toDateString(),
        'estado' => CierreCampana::ESTADO_PLANIFICADO,
    ]);

    $servicio = app(CierreCampanaStateMachine::class);

    expect(fn () => $servicio->transicionar($cierre, CierreCampana::ESTADO_CERRADO, $usuario, 'Intento directo'))
        ->toThrow(RuntimeException::class, 'Transición no permitida');

    expect($cierre->refresh()->estado)->toBe(CierreCampana::ESTADO_PLANIFICADO)
        ->and(CierreCampanaHistorialEstado::query()->where('cierre_campana_id', $cierre->id)->count())->toBe(0);
});
