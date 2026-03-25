<?php

use App\Models\CierreCampana;
use App\Models\CierreCampanaHistorialEstado;
use App\Models\User;
use App\Services\CierreCampanaStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registra bitácora completa de transición y evita duplicar evento en estado repetido', function () {
    $usuario = User::factory()->create();

    $cierre = CierreCampana::query()->create([
        'nombre' => 'Campaña trazabilidad',
        'codigo' => 'CAMP-TRAZA-01',
        'numero_cierre' => 1,
        'fecha_inicio' => '2026-02-01',
        'fecha_cierre' => '2026-02-21',
        'fecha_liquidacion' => '2026-02-28',
        'estado' => CierreCampana::ESTADO_PLANIFICADO,
    ]);

    $servicio = app(CierreCampanaStateMachine::class);

    $servicio->registrarEstadoInicial($cierre, $usuario, 'Alta por fixture');
    $servicio->transicionar($cierre, CierreCampana::ESTADO_ABIERTO, $usuario, 'Inicio operativo');

    expect(fn () => $servicio->transicionar($cierre->refresh(), CierreCampana::ESTADO_ABIERTO, $usuario, 'Reintento'))
        ->toThrow(RuntimeException::class, 'ya se encuentra en el estado solicitado');

    $servicio->transicionar($cierre->refresh(), CierreCampana::ESTADO_LIQUIDACION, $usuario, 'Corte de cobranza');
    $servicio->transicionar($cierre->refresh(), CierreCampana::ESTADO_CERRADO, $usuario, 'Cierre contable');

    $historial = CierreCampanaHistorialEstado::query()
        ->where('cierre_campana_id', $cierre->id)
        ->orderBy('id')
        ->get();

    expect($historial)->toHaveCount(4)
        ->and($historial->last()->motivo)->toBe('Cierre contable')
        ->and($historial->last()->datos['origen'])->toBe('state_machine')
        ->and($cierre->refresh()->datos['cerrada_por'])->toBe($usuario->id);
});
